<?php

namespace MediaManager\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use League\Flysystem\Plugin\ListWith;
use MediaManager\Events\MediaFileAdded;
use MediaManager\Services\MediaService;

class MediaController extends Controller
{
    /**
     * @var string 
     */
    private $mediaService;

    // public static $title = 'Medias';
    // public static $description = 'Medias';
    public static $icon = 'fas fa-fw fa-video text-red';

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index(Request $request)
    {
        // Check permission
        // $this->authorize('browse_media');

        return $this->populateView('media-manager::media.index');
    }

    public function vlc(Request $request)
    {
        // Check permission
        // $this->authorize('browse_media');

        return $this->populateView('media-manager::media.vlc'); //, compact($videos));
    }

    public function files(Request $request)
    {
        // Check permission
        // $this->authorize('browse_media');

        return response()->json($this->mediaService->files($request->folder, $request->details));
    }

    public function new_folder(Request $request)
    {
        // Check permission
        // $this->authorize('browse_media');

        return response()->json(
            $this->mediaService->new_folder(
                $request->new_folder
            )
        );
    }

    public function delete(Request $request)
    {
        // Check permission
        // $this->authorize('browse_media');

        return response()->json(
            $this->mediaService->delete(
                $request->path, $request->get('files')
            )
        );
    }

    public function move(Request $request)
    {
        // Check permission
        // $this->authorize('browse_media');

        return response()->json(
            $this->mediaService->move(
                $request->path, $request->destination, $request->get('files')
            )
        );
    }

    public function rename(Request $request)
    {
        // Check permission
        // $this->authorize('browse_media');

        return response()->json(
            $this->mediaService->rename(
                $request->folder_location, $request->filename, $request->new_filename
            )
        );
    }

    public function upload(Request $request)
    {
        // Check permission
        // $this->authorize('browse_media');

        return response()->json(
            $this->mediaService->upload(
                $request->file,
                $request->get('details'),
                $request->upload_path,
                $request->get('filename')
            )
        );
    }

    public function crop(Request $request)
    {
        // Check permission
        // $this->authorize('browse_media');

        return response()->json(
            $this->mediaService->crop(
                $request->get('createMode'),
                $request->get('x'),
                $request->get('y'),
                $request->get('height'),
                $request->get('width'),
                $request->upload_path,
                $request->originImageName,
            )
        );

    }

    private function addWatermarkToImage($image, $options)
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
}
