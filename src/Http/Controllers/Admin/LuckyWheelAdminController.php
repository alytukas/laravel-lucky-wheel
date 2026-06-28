<?php

namespace Gwebas\LuckyWheel\Http\Controllers\Admin;

use Gwebas\LuckyWheel\Models\LuckyWheelPrize;
use Gwebas\LuckyWheel\Models\LuckyWheelSetting;
use Gwebas\LuckyWheel\Models\LuckyWheelSpin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LuckyWheelAdminController extends Controller
{
    public function index(): View
    {
        $settings = LuckyWheelSetting::current();
        $prizes = LuckyWheelPrize::orderBy('sort_order')->orderBy('id')->get();
        $recentSpins = LuckyWheelSpin::with('prize')->orderByDesc('id')->limit(20)->get();

        return view('lucky-wheel::admin.index', compact('settings', 'prizes', 'recentSpins'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_enabled' => 'nullable|boolean',
            'theme' => 'required|string|in:default,christmas,summer',
            'email_policy' => 'required|string|in:none,before_spin,after_win',
            'cooldown_hours' => 'required|integer|min:0',
            'auto_open_seconds' => 'required|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $validated['is_enabled'] = $request->has('is_enabled');

        $settings = LuckyWheelSetting::current();
        $settings->update($validated);

        return redirect()->back()->with('success', __('Lucky wheel settings successfully updated!'));
    }

    public function storePrize(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:percentage,fixed,free_shipping,no_prize',
            'value' => 'nullable|numeric|min:0',
            'probability_weight' => 'required|integer|min:0',
            'bg_color' => 'required|string|max:20',
            'text_color' => 'required|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['active'] = true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        LuckyWheelPrize::create($validated);

        return redirect()->back()->with('success', __('Prize successfully added!'));
    }

    public function destroyPrize(LuckyWheelPrize $prize): RedirectResponse
    {
        $prize->delete();

        return redirect()->back()->with('success', __('Prize successfully removed!'));
    }
}
