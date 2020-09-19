<?php

namespace MediaManager\Http\Controllers\Admin;

use Request;
use MediaManager\Models\Encoding;

/**
 * Hande encoder progress events
 */
class Encoder extends Controller
{
    /**
     * Get the status of an encode
     *
     * @param  int $id
     * @return Encoding
     */
    public function progress($id)
    {
        return Encoding::findOrFail($id)->forProgress();
    }

    /**
     * Make a simply handler for notify callbacks.  The encoding model will pass
     * the the handling onto whichever provider is registered.
     *
     * @return mixed
     */
    public function notify()
    {
        return Encoding::notify(Request::input());
    }
}
