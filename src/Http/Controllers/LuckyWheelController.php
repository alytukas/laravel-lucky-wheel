<?php

namespace Gwebas\LuckyWheel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LuckyWheelController extends Controller
{
    public function spin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'nullable|email|max:255',
            'fingerprint' => 'nullable|string|max:255',
        ]);

        $service = app('lucky-wheel');

        $result = $service->spin(
            auth()->id(),
            $request->ip(),
            $validated['fingerprint'] ?? null,
            $validated['email'] ?? null
        );

        $response = response()->json($result, $result['success'] ? 200 : 422);

        if ($result['success']) {
            $cookieName = config('lucky-wheel.cookie_name', 'lucky_wheel_spun');
            $cookieDays = (int) config('lucky-wheel.cookie_days', 30);
            $response->cookie($cookieName, 'true', $cookieDays * 1440);
        }

        return $response;
    }

    public function claim(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'spin_id' => 'required|integer',
            'email' => 'required|email|max:255',
        ]);

        $service = app('lucky-wheel');
        $result = $service->claimEmail((int) $validated['spin_id'], $validated['email']);

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
