<?php

namespace MediaManager\Http\Requests;

use MediaManager\Http\Rules\ReCaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ReCaptchaRequest.
 *
 * @package MediaManager\Http\Requests
 */
class ReCaptchaRequest extends FormRequest
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
     * @psalm-return array{g_recaptcha_response?: array{0: string, 1: mixed}}
     */
    public function rules(): array
    {
        $rules = [];

        $reCaptchaRule = $this->container->make(ReCaptchaRule::class);
        if ($reCaptchaRule->isEnabled()) {
            $rules['g_recaptcha_response'] = ['required', $reCaptchaRule];
        }

        return $rules;
    }
}
