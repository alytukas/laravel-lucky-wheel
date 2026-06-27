<?php

namespace Gwebas\LuckyWheel\Models;

use Illuminate\Database\Eloquent\Model;

class LuckyWheelPrize extends Model
{
    protected $table = 'lucky_wheel_prizes';

    protected $guarded = [];

    protected $casts = [
        'value' => 'float',
        'probability_weight' => 'integer',
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
