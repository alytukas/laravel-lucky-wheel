<?php

namespace Gwebas\LuckyWheel\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Wheel extends Component
{
    public function render(): View|string
    {
        $service = app('lucky-wheel');

        if (! $service->canShowWheel()) {
            return '';
        }

        $cookieName = config('lucky-wheel.cookie_name', 'lucky_wheel_spun');
        if (request()->hasCookie($cookieName) && $service->getSettings()->cooldown_hours > 0) {
            return '';
        }

        $settings = $service->getSettings();
        $prizes = $service->getActivePrizes();

        return view('lucky-wheel::components.wheel', compact('settings', 'prizes'));
    }
}
