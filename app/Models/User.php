<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use App\Services\Subscriptions\CustomBillable;
use App\Services\Subscriptions\SubscriptionService;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, CustomBillable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'open_ai_token',
        'open_ai_model',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'array'
    ];
    private $currentActiveSubscription = false;

    public function chatConfigs(): HasMany
    {
        return $this->hasMany(ChatConfig::class);
    }

    public function chatConfigLatest(): HasOne
    {
        return $this->hasOne(ChatConfig::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->id, [1, 6]);
    }

    public function getCurrentActiveSubscription(): ?Subscription
    {
        if ($this->currentActiveSubscription !== false) {
            return $this->currentActiveSubscription;
        }

        $subscriptions = SubscriptionService::getUserActiveSubscriptions($this);

        if ($subscriptions->isEmpty()) {
            return $this->currentActiveSubscription = null;
        }

        $subscriptionWithLimits = $subscriptions->filter(function ($s) use ($subscriptions) {
            /**
             * @var Subscription $s
             */
            return $s->left_requests_for_current_period > 0;
        })->first();

        if ($subscriptionWithLimits) {
            return $this->currentActiveSubscription = $subscriptionWithLimits;
        }

        return $this->currentActiveSubscription = $subscriptions->first();
    }

    public function getSettingsValue(string $key, $default = null)
    {
        return Arr::get($this->settings, $key, $default);
    }

    public function setSettingsValue(string $key, $value, bool $save = true): void
    {
        $settings = $this->settings;
        Arr::set($settings, $key, $value);
        $this->settings = $settings;

        if ($save) {
            $this->save();
        }
    }

    public function unsetSettingsValue(array $keys, bool $save = true): void
    {
        $settings = $this->settings;
        Arr::forget($settings, $keys);
        $this->settings = $settings;

        if ($save) {
            $this->save();
        }
    }

    public function getAvailableTrialDaysToSubscribe(): int
    {
        $days = Subscription::TRIAL_PERIOD_DAYS;

        if($this->trial_ends_at) {
            $days = now()->diffInDays($this->trial_ends_at, false);
        }

        return max($days, 0);
    }
}
