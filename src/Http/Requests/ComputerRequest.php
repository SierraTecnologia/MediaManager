<?php

namespace MediaManager\Http\Requests;

use MediaManager\Util\Filter;
use Carbon\Carbon;

class ComputerRequest extends BaseFormRequest
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
            'token' => 'required', // Company Token
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
            'token' => 'trim|capitalize|escape'
        ];
    }

    public static function filterParams($params)
    {
        unset($params['token']);
        unset($params['user_token']);

        return self::filterParamsForExternal($params);
    }

    public static function filterParamsForExternal($params)
    {
        if (isset($params['holder_birth_date']) && !empty($params['holder_birth_date'])) {
            $params['birth_date'] = Carbon::createFromFormat('d/m/Y', $params['holder_birth_date']);
        }
        if (isset($params['holder_phone_number']) && !empty($params['holder_phone_number'])) {
            $phone = \Validate\Phone::break($params['holder_phone_number']);
            $params['phone_country'] = $phone['country'];
            $params['phone_area_code'] = $phone['region'];
            $params['phone'] = $phone['number'];
        }


        return $params;
    }
}
