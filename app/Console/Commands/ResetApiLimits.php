<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\Subscriptions\SubscriptionService;
use Illuminate\Console\Command;

class ResetApiLimits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-limits:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset limits every month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $expiredSubscriptions = Subscription::whereNull('ends_at')->where('expires_at', '<', now())->get();

        foreach ($expiredSubscriptions as $s) {
            SubscriptionService::refreshExpiresPeriod($s);
        }
    }
}
