<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PaidUserRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (\Auth::user()->getCurrentActiveSubscription()?->isFree()) {
            $fail('The :attribute is not available for free subscriptions.');
        }
    }
}
