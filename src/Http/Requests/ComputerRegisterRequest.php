<?php

namespace MediaManager\Http\Requests;

use Illuminate\Support\Facades\Hash;

class ComputerRegisterRequest extends BaseFormRequest
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
            // 'token' => 'required|max:255', //|unique:collaborator
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
        //    'token.required' => 'Token é obrigatório!'
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

    public static function filterParams($params)
    {
        // unset($params['token']);

        // if (isset($params['nome']) && !empty($params['nome'])) {
        //     $params['name'] = $params['nome'];
        // }

        // if (isset($params['user_token']) && !empty($params['user_token'])) {
        //     $params['token'] = $params['user_token'];
        // }

        // $params['name'] = \Validate\Name::toDatabase($params['name']);
        
        // $params['password'] = Hash::make(\Illuminate\Support\Str::random(8));
        return $params;
    }
}
