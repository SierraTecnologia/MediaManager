<?php

namespace MediaManager\Http\Requests;

class PlaylistFindRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'tid' => 'required',
            // 'token' => 'required'
        ];
    }

    /**
    * Custom message for validation
    *
    * @return array
    */
    public function messages()
    {
       return [
           'tid.required' => 'Id do pedido é obrigatório!',
           'token.required' => 'Company Token is required!'
       ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            
        ];
    }
}
