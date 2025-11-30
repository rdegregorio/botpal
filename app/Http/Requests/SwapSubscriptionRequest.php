<?php

namespace App\Http\Requests;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SwapSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = $this->user();

        return (bool)$user->getCurrentActiveSubscription();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'planType' => [
                'required',
                'integer',
                Rule::in(Subscription::PLAN_BASIC, Subscription::PLAN_PREMIUM),
            ],
        ];
    }
}
