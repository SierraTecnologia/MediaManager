<?php

namespace MediaManager\Http\Requests;

use Auth;
use Gate;
use MediaManager\Models\Imagen as Image;
use Illuminate\Foundation\Http\FormRequest;

class ImagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (\Illuminate\Support\Facades\Config::get('app.env') !== 'testing') {
            return Gate::allows('cms', Auth::user());
        }

        return true;
    }
}
