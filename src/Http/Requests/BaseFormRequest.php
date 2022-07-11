<?php
namespace MediaManager\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Waavi\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Validation\ValidationException;

abstract class BaseFormRequest extends FormRequest
{
    // use ApiResponse, SanitizesInput; @todo PEsquisar sobre apiResponse
    use SanitizesInput;
    /**
     * For more sanitizer rule check https://github.com/Waavi/Sanitizer
     */
    public function validateResolved()
    {
        {
            $this->sanitize();
            parent::validateResolved();
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize();
      
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(
                [
                    'success' => false,
                    'message' => $this->returnOneError($errors)
                    // 'errors' => $errors
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }

    /**
     * Retorn only First error
     */
    private function returnOneError($errors)
    {
        if (!empty($errors)){
            foreach($errors as $error) {
                return $error[0];
            }
        }
        return '';
    }
}