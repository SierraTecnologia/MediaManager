<?php

namespace MediaManager\Http\Requests;

class PlaylistRequest extends BaseFormRequest
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
            'token' => 'required',
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
        //    'origin.required' => 'Origin is required!',
        //    'user_token.required' => 'User Token is required!',
           'token.required' => 'Token is required!',
        //    'total.required' => 'Total price is required!',
        //    'endotera_type_id.required' => 'Tipo de Pagamento é Obrigatório!',
        //    'endotera_type_id.integer' => 'Tipo de Pagamento precisa ser um inteiro!'
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
        // $params['collaborator_info'] = $params['collaborator'];
        // unset($params['collaborator']);
        // if (!isset($params['money_id']) || empty($params['money_id'])) {
        //     $params['money_id'] = 1;
        // }
            return $params;
        // return GroupRequest::filterParamsForExternal($params);
    }
}
