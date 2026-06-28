<?php

namespace Gwebas\LuckyWheel\Models;

use Illuminate\Database\Eloquent\Model;

class LuckyWheelSetting extends Model
{
    protected $table = 'lucky_wheel_settings';

    protected $guarded = [];

    protected $casts = [
        'is_enabled' => 'boolean',
        'cooldown_hours' => 'integer',
        'auto_open_seconds' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'is_enabled' => true,
                'theme' => 'default',
                'email_policy' => 'none',
                'cooldown_hours' => 24,
                'auto_open_seconds' => 5,
            ]
        );
    }
}
