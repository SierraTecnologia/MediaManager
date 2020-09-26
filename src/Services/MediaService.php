<?php

namespace MediaManager\Services;

use App;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Log;
use SplFileInfo;
use Crypto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use League\Flysystem\Plugin\ListWith;
use MediaManager\Events\MediaFileAdded;
use App\Models\File;

class MediaService
{
    protected $mimeTypes;

    /**
     * @var string 
     */
    private $filesystem;

    /**
     * @var string 
     */
    private $directory = '';

    public function allFiles($folder = ''): array
    {
        $allFiles = [];
        $tempFiles = $this->files($folder);
        foreach ($tempFiles as $tempFile) {
            if ($tempFile['type']=='folder') {
                $allFiles = array_merge($allFiles, $this->allFiles($tempFile['relative_path']));
            } else {
                $allFiles[] = $tempFile;
            }
        }
        return $allFiles;
    }

    /**
     * @return (array|mixed|string)[][]
     *
     * @psalm-return array<int, array{name: mixed, filename?: mixed, type: mixed|string, path: mixed, relative_path: mixed, size?: mixed, last_modified: mixed|string, thumbnails?: list<mixed>, items?: string}>
     */
    public function files($folder = '', $details = []): array
    {
        // Check permission
        // $this->authorize('browse_media');

        $options = $details ?? [];
        $thumbnail_names = [];
        $thumbnails = [];
        if (!($options->hide_thumbnails ?? false)) {
            $thumbnail_names = array_column(($options['thumbnails'] ?? []), 'name');
        }

        if ($folder == '/') {
            $folder = '';
        }

        $dir = $this->directory.$folder;

        $files = [];
        $storage = Storage::disk($this->filesystem)->addPlugin(new ListWith());
        $storageItems = $storage->listWith(['mimetype'], $dir);

        foreach ($storageItems as $item) {
            if ($item['type'] == 'dir') {
                $files[] = [
                    'name'          => $item['basename'],
                    'type'          => 'folder',
                    'path'          => Storage::disk($this->filesystem)->url($item['path']),
                    'relative_path' => $item['path'],
                    'items'         => '',
                    'last_modified' => '',
                ];
            } else {
                if (empty(pathinfo($item['path'], PATHINFO_FILENAME)) && !\Illuminate\Support\Facades\Config::get('rica.hidden_files')) {
                    continue;
                }
                // Its a thumbnail and thumbnails should be hidden
                if (Str::endsWith($item['filename'], $thumbnail_names)) {
                    $thumbnails[] = $item;
                    continue;
                }
                $files[] = [
                    'name'          => $item['basename'],
                    'filename'      => $item['filename'],
                    'type'          => $item['mimetype'] ?? 'file',
                    'path'          => Storage::disk($this->filesystem)->url($item['path']),
                    'relative_path' => $item['path'],
                    'size'          => $item['size'],
                    'last_modified' => $item['timestamp'],
                    'thumbnails'    => [],
                ];
            }
        }

        foreach ($files as $key => $file) {
            foreach ($thumbnails as $thumbnail) {
                if ($file['type'] != 'folder' && Str::startsWith($thumbnail['filename'], $file['filename'])) {
                    $thumbnail['thumb_name'] = str_replace($file['filename'].'-', '', $thumbnail['filename']);
                    $thumbnail['path'] = Storage::disk($this->filesystem)->url($thumbnail['path']);
                    $files[$key]['thumbnails'][] = $thumbnail;
                }
            }
        }

        return $files;
    }

    /**
     * @return array
     *
     * @psalm-return array<string, mixed>
     */
    public function delete($path, $files): array
    {
        // Check permission
        // $this->authorize('browse_media');

        $path = str_replace('//', '/', Str::finish($path, '/'));
        $success = true;
        $error = '';

        foreach ($files as $file) {
            $file_path = $path.$file['name'];
            if ($file['type'] == 'folder') {
                if (!Storage::disk($this->filesystem)->deleteDirectory($file_path)) {
                    $error = __('media.error_deleting_folder');
                    $success = false;
                }
            } elseif (!Storage::disk($this->filesystem)->delete($file_path)) {
                $error = __('media.error_deleting_file');
                $success = false;
            }
        }
        if ($file = File::where('url', $file_path)->first()) {
            $file->destroy();
        }

        return compact('success', 'error');
    }

    private function addWatermarkToImage(\Intervention\Image\Image $image, $options): \Intervention\Image\Image
    {
        $watermark = Image::make(Storage::disk($this->filesystem)->path($options->source));
        // Resize watermark
        $width = $image->width() * (($options->size ?? 15) / 100);
        $watermark->resize(
            $width, null, function ($constraint) {
                $constraint->aspectRatio();
            }
        );

        return $image->insert(
            $watermark,
            ($options->position ?? 'top-left'),
            ($options->x ?? 0),
            ($options->y ?? 0)
        );
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function updateMediaEloquent(): void
    {
        $files = $this->allFiles();

        foreach ($files as $file) {
            if (!$video = File::where('url', $file['path'])->first()) {
                $video = new File();
                $video->name = $file['name'];
                $video->url = $file['path'];
                $video->path = $file['relative_path'];
                $video->type = $file['type'];
                $video->filename = $file['filename'];
                $video->size = $file['size'];
                $video->last_modified = $file['last_modified'];

                $video->save();
            }
        }
    }
}
