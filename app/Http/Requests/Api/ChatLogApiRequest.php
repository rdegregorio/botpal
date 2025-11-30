<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ChatLogApiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => 'required',
            'chatConfig' => 'required|uuid',
            'chatUuid' => 'nullable|uuid|required_with:messageUuid',
            'messageUuid' => 'nullable|uuid|required_with:chatUuid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
