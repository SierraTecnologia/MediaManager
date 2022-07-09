<?php

namespace MediaManager\Http\Requests;

use Carbon\Carbon;
use MediaManager\Util\Filter;

class GroupRequest extends BaseFormRequest
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
            'name' => 'required|max:255',
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
        //    'cpf.required' => 'Cpf is required!',
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
            'name' => 'trim|capitalize|escape'
        ];
    }

    public static function filterParams($params)
    {

        return self::filterParamsForExternal($params);
    }

    public static function filterParamsForExternal($params)
    {


        return $params;
    }
}
