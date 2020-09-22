<?php

namespace MediaManager\Http\Controllers;

use Illuminate\Filesystem\Filesystem;
use Stalker\Services\MidiaService;

class MidiaController extends BaseController
{
    public function __construct(MidiaService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Provide the File as a Public Midia.
     *
     * @param string $encFileName
     *
     * @return Download
     */
    public function asPublic($encFileName)
    {
        return $this->service->asPublic($encFileName);
    }

    /**
     * Provide the File as a Full Midia.
     *
     * @param string $encFileName
     *
     * @return Download
     */
    public function asFull($encFileName)
    {
        return $this->service->asFull($encFileName);
    }

    /**
     * Provide the File as a Public Preview.
     *
     * @param string $encFileName
     *
     * @return Download
     */
    public function asPreview($encFileName, Filesystem $fileSystem)
    {
        return $this->service->asPreview($encFileName, $fileSystem);
    }

    /**
     * Provide file as download.
     *
     * @param string $encFileName
     * @param string $encRealFileName
     *
     * @return Downlaod
     */
    public function asDownload($encFileName, $encRealFileName)
    {
        return $this->service->asDownload($encFileName, $encRealFileName);
    }

    /**
     * Gets an asset.
     *
     * @param string $encPath
     * @param string $contentType
     *
     * @return Provides the valid
     */
    public function asset($encPath, $contentType, Filesystem $fileSystem)
    {
        return $this->service->asset($encPath, $contentType, $fileSystem);
    }
    // public function show($id)
    // {
    //     $photo_album = PhotoAlbum::find($id);
    //     $photos = Photo::where('photo_album_id', $id)->get();

    //     return view('features.photo.view_album',compact('photos','photo_album'));
    // }
}
