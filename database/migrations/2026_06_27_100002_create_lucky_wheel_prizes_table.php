<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lucky_wheel_prizes', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g. "10% Nuolaida"
            $table->string('type')->default('percentage'); // 'percentage', 'fixed', 'free_shipping', 'no_prize'
            $table->decimal('value', 10, 2)->nullable();
            $table->unsignedInteger('probability_weight')->default(10);
            $table->string('bg_color')->default('#FF5733');
            $table->string('text_color')->default('#FFFFFF');
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lucky_wheel_prizes');
    }
};
