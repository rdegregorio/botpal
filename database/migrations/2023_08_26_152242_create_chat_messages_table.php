<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_config_id')->constrained('chat_configs')->cascadeOnDelete();
            $table->uuid('message_uuid')->index();
            $table->uuid('chat_uuid');
            $table->text('question');
            $table->text('answer')->nullable();
            $table->boolean('failed')->nullable();
            $table->string('fail_reason')->nullable();
            $table->ipAddress()->nullable();
            $table->char('country_code', 2)->nullable();
            $table->timestamps();
            $table->timestamp('processed_at')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_flagged')->default(false);
            $table->boolean('is_deleted')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_logs');
    }
};

