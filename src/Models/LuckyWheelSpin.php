<?php

namespace Gwebas\LuckyWheel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LuckyWheelSpin extends Model
{
    protected $table = 'lucky_wheel_spins';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function prize(): BelongsTo
    {
        return $this->belongsTo(LuckyWheelPrize::class, 'prize_id');
    }
}
