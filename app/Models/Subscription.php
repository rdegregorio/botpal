<?php

namespace App\Models;

use Laravel\Cashier\Subscription as SubscriptionAlias;

class Subscription extends SubscriptionAlias
{
    public const PLAN_BASIC = 1;
    public const PLAN_PREMIUM = 2;
    public const PLAN_BUSINESS = 3;
    public const PLAN_FREE = 4;

    public const PLAN_NAMES = [
        self::PLAN_FREE => 'Free',
        self::PLAN_BASIC => 'Basic',
        self::PLAN_PREMIUM => 'Premium',
        self::PLAN_BUSINESS => 'Business',
    ];

    public const TRIAL_PERIOD_DAYS = 14;
    public const TRIAL_PERIOD_REQUESTS = 100;

    protected static array $limits = [
        self::PLAN_FREE => 1000,
        self::PLAN_BASIC => 5000,
        self::PLAN_PREMIUM => 10000,
    ];

    /*
     * It's using for displaying and not for real deals
     * Prices in cents
     */
    protected static $prices = [
        self::PLAN_BASIC => 499,
        self::PLAN_PREMIUM => 999,
        self::PLAN_BUSINESS => 29999,
    ];

    protected static $names = [
        self::PLAN_FREE => 'Free',
        self::PLAN_BASIC => 'Basic',
        self::PLAN_PREMIUM => 'Premium',
        self::PLAN_BUSINESS => 'Business',
    ];

    /*
     * Used only for stripe
     */
    protected static $planNames = [
        self::PLAN_BASIC => 'price_1OYYVFIANWL0jjnHs0z0rWSv',
        self::PLAN_PREMIUM => 'price_1OYYWeIANWL0jjnH8zqh8u1R',
    ];

    protected $dates = [
        'expires_at',
        'trial_ends_at',
        'ends_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'type' => 'integer',
        'custom_available_requests' => 'integer',
        'trial_requests_count' => 'integer',
        'trial_activated' => 'boolean',
        'expires_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $guarded = ['id'];

    public static function getLimitByType($type): ?int
    {
        if ($type === null) {
            return null;
        }
        return \Arr::get(static::$limits, $type);
    }

    public static function getPriceByType($type): ?float
    {
        if ($type === null) {
            return 0;
        }
        return \Arr::get(static::$prices, $type, 0) / 100;
    }

    public static function getPlanNameByType($type): ?string
    {
        if ($type === null) {
            return null;
        }
        return \Arr::get(static::$planNames, $type);
    }

    public static function getPlanTypeByName(string $planName): ?string
    {
        return array_search($planName, static::$planNames, true);
    }

    public static function getNameByType($type): ?string
    {
        if ($type === null) {
            return null;
        }
        return \Arr::get(static::$names, $type, 'Archived plan');
    }

    public static function getAutoPayableTypes(): array
    {
        return array_keys(self::$prices);
    }

    public static function getAutoPayablePlans(): array
    {
        return self::$planNames;
    }

    public function scopeForUser($q, $user_id)
    {
        return $q->where(compact('user_id'));
    }

    public function isAutoPayable(): bool
    {
        return in_array($this->type, [
            self::PLAN_BASIC,
            self::PLAN_PREMIUM,
        ], true);
    }

    public function isBasic(): bool
    {
        return $this->type === self::PLAN_BASIC;
    }

    public function isBusiness(): bool
    {
        return $this->type === self::PLAN_BUSINESS;
    }

    public function isFree(): bool
    {
        return $this->type === self::PLAN_FREE;
    }

    public function getLeftRequestsForCurrentPeriodAttribute()
    {
        if ($this->onTrial()) {
            return $this->getAvailableTrialRequests() - $this->trial_requests_count;
        }

        if ($this->isBusiness()) {
            return $this->custom_available_requests - $this->requests_count;
        }

        return self::getLimitByType($this->type) - $this->requests_count;
    }

    public function getDefaultLimitAttribute()
    {
        if ($this->onTrial()) {
            return $this->getAvailableTrialRequests();
        }

        if ($this->isBusiness()) {
            return $this->custom_available_requests;
        }

        return self::getLimitByType($this->type);
    }

    public function getName()
    {
        return self::getNameByType($this->type);
    }

    public function getPrice()
    {
        if ($this->isAutoPayable()) {
            return self::getPriceByType($this->type);
        }

        if ($this->isBusiness()) {
            return $this->custom_price / 100;
        }

        return '0.00';
    }

    public function getPriceAttribute()
    {
        $price = $this->getPrice();
        return $price === '0.00' ? 0 : $price;
    }

    public function cancelInDatabase(): bool
    {
        $date = now()->subDay();
        return $this->update([
            'ends_at' => $date,
            'canceled_at' => $date,
            'trial_ends_at' => (clone $date)->subSeconds(3),
        ]);
    }

    public function getCurrentPeriodActiveDaysAttribute(): ?int
    {
        $startDate = $this->expires_at->clone()->subMonth()->startOfDay();

        return $startDate->diffInDays(now()->startOfDay());
    }

    public function getAvgDailyUsageAttribute(): int
    {
        $currentPeriodActiveDays = $this->current_period_active_days ?: 1;

        if($currentPeriodActiveDays > 30) {
            $currentPeriodActiveDays = 30;
        }

        $requestsCount = $this->onTrial() ? $this->trial_requests_count : $this->requests_count;

        return (int) ($requestsCount / $currentPeriodActiveDays);
    }

    public function getAvailableTrialRequests(): int
    {
        return $this->custom_available_trial_requests ?? self::TRIAL_PERIOD_REQUESTS;
    }
}
