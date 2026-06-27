<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Lucky Wheel General Settings
    |--------------------------------------------------------------------------
    */
    'enabled' => true,

    /*
    | Active Theme ('default', 'christmas', 'summer')
    */
    'theme' => 'default',

    /*
    | Cooldown in hours before a user or IP can spin again.
    */
    'cooldown_hours' => 24,

    /*
    | Email Policy:
    | 'none' - No email required, guest or user can spin immediately.
    | 'before_spin' - Email must be entered before spinning the wheel.
    | 'after_win' - Email must be entered after winning to claim the discount code.
    */
    'email_policy' => 'none',

    /*
    | Schedule (nullable datetime string 'Y-m-d H:i:s')
    */
    'starts_at' => null,
    'ends_at' => null,

    /*
    | Cookie configuration for identifying guest spins
    */
    'cookie_name' => 'lucky_wheel_spun',
    'cookie_days' => 30,

    /*
    | Override package language ('lt', 'en', etc.). If null, app()->getLocale() is used.
    */
    'locale' => null,
];
