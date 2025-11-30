<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatLogsTable extends Migration
{
    public function up(): void
    {
        Schema::create('chat_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('chat_config_id')->constrained('chat_configs')->cascadeOnDelete();
            $table->uuid('chat_uuid')->index();
            $table->uuid('message_uuid');
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
            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('completion_tokens')->default(0);
            $table->unsignedInteger('total_tokens')->storedAs('prompt_tokens + completion_tokens');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_logs');
    }
}

;

