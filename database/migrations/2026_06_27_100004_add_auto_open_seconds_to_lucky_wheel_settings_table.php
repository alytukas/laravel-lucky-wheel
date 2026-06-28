<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lucky_wheel_settings') && !Schema::hasColumn('lucky_wheel_settings', 'auto_open_seconds')) {
            Schema::table('lucky_wheel_settings', function (Blueprint $table) {
                $table->integer('auto_open_seconds')->default(5)->after('cooldown_hours');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lucky_wheel_settings') && Schema::hasColumn('lucky_wheel_settings', 'auto_open_seconds')) {
            Schema::table('lucky_wheel_settings', function (Blueprint $table) {
                $table->dropColumn('auto_open_seconds');
            });
        }
    }
};
