<?php

namespace MediaManager\Http\Requests;

use MediaManager\Http\Rules\ReCaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateAuthRequest.
 *
 * @package MediaManager\Http\Requests
 */
class CreateAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return (mixed|string)[][]
     *
     * @psalm-return array{email: array{0: string, 1: string, 2: string}, password: array{0: string, 1: string}, g_recaptcha_response?: array{0: string, 1: mixed}}
     */
    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];

        $reCaptchaRule = $this->container->make(ReCaptchaRule::class);
        if ($reCaptchaRule->isEnabled()) {
            $rules['g_recaptcha_response'] = ['required', $reCaptchaRule];
        }

        return $rules;
    }
}
