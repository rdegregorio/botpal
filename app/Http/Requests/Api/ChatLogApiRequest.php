<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ChatLogApiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => 'required|string',
            'chatConfig' => 'required|uuid',
            'chatUuid' => 'nullable|uuid',
            'messageUuid' => 'nullable|uuid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
