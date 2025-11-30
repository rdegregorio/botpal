<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('chat_configs', function (Blueprint $table) {
            $table->string('assistant_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('chat_configs', function (Blueprint $table) {
            $table->dropColumn('assistant_id');
        });
    }
};
