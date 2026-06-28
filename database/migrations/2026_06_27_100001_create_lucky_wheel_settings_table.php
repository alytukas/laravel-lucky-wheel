<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lucky_wheel_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(true);
            $table->string('theme')->default('default');
            $table->string('email_policy')->default('none'); // 'none', 'before_spin', 'after_win'
            $table->integer('cooldown_hours')->default(24);
            $table->integer('auto_open_seconds')->default(5);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lucky_wheel_settings');
    }
};
