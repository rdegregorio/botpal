<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
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
            'text' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'g-recaptcha-response' => ['required', 'captcha'],
            'time' => [
                'required',
                'string',
                static function ($attribute, $value, $fail) {
                    if($value !== md5('time-delay')) {
                        $fail('You sent the form too fast.');
                    }
                },
            ],
        ];
    }
}
