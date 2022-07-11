<?php

namespace MediaManager\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use League\Flysystem\Plugin\ListWith;
use MediaManager\Events\MediaFileAdded;

class MediaController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function vlc(Request $request)
    {
        // Check permission
        // $this->authorize('browse_media');

        return view('media-manager::media.vlc'); //, compact($videos));
    }
}
