<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('card_brand')->nullable();
            $table->string('card_last_four', 4)->nullable();
        });

        Schema::table('subscriptions', function ($table) {
            $table->string('stripe_plan');

            $table->tinyInteger('type');
            $table->unsignedInteger('requests_count')->default(0);
            $table->unsignedInteger('custom_available_requests')->nullable();
            $table->unsignedInteger('custom_price')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('trial_requests_count')->nullable();
            $table->boolean('trial_activated')->nullable();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
