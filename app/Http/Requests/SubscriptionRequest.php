<?php

namespace App\Http\Requests;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionRequest extends FormRequest
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
            'stripeToken' => 'required|string',
            'stripeTokenType' => 'required|string',
            'stripeEmail' => 'required|email',
            'planType' => [
                'required',
                'integer',
                Rule::in(Subscription::PLAN_BASIC, Subscription::PLAN_PREMIUM),
            ],
        ];
    }
}
