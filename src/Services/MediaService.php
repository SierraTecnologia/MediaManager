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

    public function __construct()
    {
        $this->mimeTypes = include base_path('config'.DIRECTORY_SEPARATOR.'mime.php');
        $this->filesystem = \Illuminate\Support\Facades\Config::get('rica.storage.disk', env('FILESYSTEM_DRIVER', 'public'));
    }

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

    public function processFilePath($path): void
    {

    }

    /**
     * @return (array|mixed|string)[][]
     *
     * @psalm-return array<int, array{name: mixed, filename?: mixed, type: 'file'|'folder'|mixed, path: mixed, relative_path: mixed, size?: mixed, last_modified: ''|mixed, thumbnails?: list<mixed>, items?: ''}>
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
     * @return (array|bool|null|string)[]
     *
     * @psalm-return array{success: bool, error: array|null|string}
     */
    public function new_folder($new_folder): array
    {
        // Check permission
        // $this->authorize('browse_media');

        $new_folder = $new_folder;
        $success = false;
        $error = '';

        if (Storage::disk($this->filesystem)->exists($new_folder)) {
            $error = __('media.folder_exists_already');
        } elseif (Storage::disk($this->filesystem)->makeDirectory($new_folder)) {
            $success = true;
        } else {
            $error = __('media.error_creating_dir');
        }

        return compact('success', 'error');
    }

    /**
     * @return (array|bool|null|string)[]
     *
     * @psalm-return array{success: bool, error: array|null|string}
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

    /**
     * @return (bool|string)[]
     *
     * @psalm-return array{success: bool, error: string}
     */
    public function move($path, $destination, $files = []): array
    {
        // Check permission
        // $this->authorize('browse_media');
        $path = str_replace('//', '/', Str::finish($path, '/'));
        $dest = str_replace('//', '/', Str::finish($destination, '/'));
        if (strpos($dest, '/../') !== false) {
            $dest = substr($path, 0, -1);
            $dest = substr($dest, 0, strripos($dest, '/') + 1);
        }
        $dest = str_replace('//', '/', Str::finish($dest, '/'));

        $success = true;
        $error = '';

        foreach ($files as $file) {
            $old_path = $path.$file['name'];
            $new_path = $dest.$file['name'];

            try {
                Storage::disk($this->filesystem)->move($old_path, $new_path);
            } catch (\Exception $ex) {
                $success = false;
                $error = $ex->getMessage();

                return compact('success', 'error');
            }
            // @todo
            if ($file = File::where('url', $old_path)->first()) {
                $file->url = $new_path;
                $file->save();
            }
        }

        return compact('success', 'error');
    }

    /**
     * @return (array|bool|null|string)[]
     *
     * @psalm-return array{success: bool, error: array|false|null|string}
     */
    public function rename($folder_location, $filename, $new_filename): array
    {
        $success = false;
        $error = false;

        if (is_array($folderLocation)) {
            $folderLocation = rtrim(implode('/', $folderLocation), '/');
        }

        $location = "{$this->directory}/{$folderLocation}";

        if (!Storage::disk($this->filesystem)->exists("{$location}/{$newFilename}")) {
            if (Storage::disk($this->filesystem)->move("{$location}/{$filename}", "{$location}/{$newFilename}")) {
                $success = true;
            } else {
                $error = __('media.error_moving');
            }
        } else {
            $error = __('media.error_may_exist');
        }

        return compact('success', 'error');
    }

    /**
     * @return (array|bool|null|string)[]
     *
     * @psalm-return array{success: bool, message: array|null|string, path: null|string}
     */
    public function upload($file, $details, $upload_path, $filename = false): array
    {
        // Check permission
        // $this->authorize('browse_media');

        $extension = $file->getClientOriginalExtension();
        $name = Str::replaceLast('.'.$extension, '', $file->getClientOriginalName());
        $details = json_decode($details ?? '{}');
        $absolute_path = Storage::disk($this->filesystem)->path($upload_path);

        try {
            $realPath = Storage::disk($this->filesystem)->getDriver()->getAdapter()->getPathPrefix();

            $allowedMimeTypes = \Illuminate\Support\Facades\Config::get('media-library.media.allowed_mimetypes', '*');
            if ($allowedMimeTypes != '*' && (is_array($allowedMimeTypes) && !in_array($file->getMimeType(), $allowedMimeTypes))) {
                throw new Exception(__('generic.mimetype_not_allowed'));
            }

            if (!$filename || $filename == 'null') {
                while (Storage::disk($this->filesystem)->exists(Str::finish($upload_path, '/').$name.'.'.$extension, $this->filesystem)) {
                    $name = get_file_name($name);
                }
            } else {
                $name = str_replace('{uid}', Auth::user()->getKey(), $filename);
                if (Str::contains($name, '{date:')) {
                    $name = preg_replace_callback(
                        '/\{date:([^\/\}]*)\}/', function ($date) {
                            return \Carbon\Carbon::now()->format($date[1]);
                        }, $name
                    );
                }
                if (Str::contains($name, '{random:')) {
                    $name = preg_replace_callback(
                        '/\{random:([0-9]+)\}/', function ($random) {
                            return Str::random($random[1]);
                        }, $name
                    );
                }
            }

            $file = $file->storeAs($upload_path, $name.'.'.$extension, $this->filesystem);

            $imageMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp',
                'image/svg+xml',
            ];
            if (in_array($file->getMimeType(), $imageMimeTypes)) {
                $image = Image::make($realPath.$file);

                if ($file->getClientOriginalExtension() == 'gif') {
                    copy($file->getRealPath(), $realPath.$file);
                } else {
                    $image = $image->orientate();
                    // Generate thumbnails
                    if (property_exists($details, 'thumbnails') && is_array($details->thumbnails)) {
                        foreach ($details->thumbnails as $thumbnail_data) {
                            $type = $thumbnail_data->type ?? 'fit';
                            $thumbnail = Image::make(clone $image);
                            if ($type == 'fit') {
                                $thumbnail = $thumbnail->fit(
                                    $thumbnail_data->width,
                                    ($thumbnail_data->height ?? null),
                                    function ($constraint) {
                                        $constraint->aspectRatio();
                                    }, ($thumbnail_data->position ?? 'center')
                                );
                            } elseif ($type == 'crop') {
                                $thumbnail = $thumbnail->crop(
                                    $thumbnail_data->width,
                                    $thumbnail_data->height,
                                    ($thumbnail_data->x ?? null),
                                    ($thumbnail_data->y ?? null)
                                );
                            } elseif ($type == 'resize') {
                                $thumbnail = $thumbnail->resize(
                                    $thumbnail_data->width,
                                    ($thumbnail_data->height ?? null),
                                    function ($constraint) use ($thumbnail_data) {
                                        $constraint->aspectRatio();
                                        if (!($thumbnail_data->upsize ?? true)) {
                                            $constraint->upsize();
                                        }
                                    }
                                );
                            }
                            if (property_exists($details, 'watermark') 
                                && property_exists($details->watermark, 'source') 
                                && property_exists($thumbnail_data, 'watermark') 
                                && $thumbnail_data->watermark
                            ) {
                                $thumbnail = $this->addWatermarkToImage($thumbnail, $details->watermark);
                            }
                            $thumbnail->save($realPath.$upload_path.$name.'-'.($thumbnail_data->name ?? 'thumbnail').'.'.$extension, ($details->quality ?? 90));
                        }
                    }
                    // Add watermark to image
                    if (property_exists($details, 'watermark') && property_exists($details->watermark, 'source')) {
                        $image = $this->addWatermarkToImage($image, $details->watermark);
                    }
                    $image->save($realPath.$file, ($details->quality ?? 90));
                }
            }

            $success = true;
            $message = __('media.success_uploaded_file');
            $path = preg_replace('/^public\//', '', $file);

            event(new MediaFileAdded($path));
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
            $path = '';
        }

        $this->updateMediaEloquent();

        return compact('success', 'message', 'path');
    }

    /**
     * @return (array|bool|null|string)[]
     *
     * @psalm-return array{success: bool, message: array|null|string}
     */
    public function crop($createMode, $x, $y, $height, $width, $upload_path, $originImageName): array
    {

        $realPath = Storage::disk($this->filesystem)->getDriver()->getAdapter()->getPathPrefix();
        $originImagePath = $realPath.$upload_path.'/'.$originImageName;

        try {
            if ($createMode) {
                // create a new image with the cpopped data
                $fileNameParts = explode('.', $originImageName);
                array_splice($fileNameParts, count($fileNameParts) - 1, 0, 'cropped_'.time());
                $newImageName = implode('.', $fileNameParts);
                $destImagePath = $realPath.$upload_path.'/'.$newImageName;
            } else {
                // override the original image
                $destImagePath = $originImagePath;
            }

            Image::make($originImagePath)->crop($width, $height, $x, $y)->save($destImagePath);

            $success = true;
            $message = __('media.success_crop_image');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return compact('success', 'message');
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
