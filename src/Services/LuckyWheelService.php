<?php

namespace Gwebas\LuckyWheel\Services;

use App\Enums\DiscountTypeEnum;
use App\Models\DiscountCode;
use Gwebas\LuckyWheel\Models\LuckyWheelPrize;
use Gwebas\LuckyWheel\Models\LuckyWheelSetting;
use Gwebas\LuckyWheel\Models\LuckyWheelSpin;
use Illuminate\Support\Str;

class LuckyWheelService
{
    public function getSettings(): LuckyWheelSetting
    {
        return LuckyWheelSetting::current();
    }

    public function getActivePrizes()
    {
        return LuckyWheelPrize::where('active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function canShowWheel(): bool
    {
        $settings = $this->getSettings();

        if (! $settings->is_enabled) {
            return false;
        }

        if ($settings->starts_at && now()->isBefore($settings->starts_at)) {
            return false;
        }

        if ($settings->ends_at && now()->isAfter($settings->ends_at)) {
            return false;
        }

        if ($this->getActivePrizes()->isEmpty()) {
            return false;
        }

        return true;
    }

    public function canSpin(?int $userId = null, ?string $ip = null, ?string $fingerprint = null, ?string $email = null): bool
    {
        if (! $this->canShowWheel()) {
            return false;
        }

        $settings = $this->getSettings();
        $cooldown = $settings->cooldown_hours;

        if ($cooldown <= 0) {
            return true;
        }

        $cutoff = now()->subHours($cooldown);

        return ! LuckyWheelSpin::where('created_at', '>=', $cutoff)
            ->where(function ($query) use ($userId, $ip, $fingerprint, $email) {
                $hasCondition = false;

                if ($userId) {
                    $query->orWhere('user_id', $userId);
                    $hasCondition = true;
                }
                if ($email) {
                    $query->orWhere('email', $email);
                    $hasCondition = true;
                }
                if ($ip) {
                    $query->orWhere('ip_address', $ip);
                    $hasCondition = true;
                }
                if ($fingerprint) {
                    $query->orWhere('fingerprint', $fingerprint);
                    $hasCondition = true;
                }

                if (! $hasCondition) {
                    $query->whereRaw('1 = 0');
                }
            })
            ->exists();
    }

    public function spin(?int $userId = null, ?string $ip = null, ?string $fingerprint = null, ?string $email = null): array
    {
        if (! $this->canSpin($userId, $ip, $fingerprint, $email)) {
            return [
                'success' => false,
                'message' => __('You have already spun the wheel. Please try again later.'),
            ];
        }

        $settings = $this->getSettings();
        if ($settings->email_policy === 'before_spin' && empty($email)) {
            return [
                'success' => false,
                'message' => __('Please enter your email address before spinning the wheel.'),
            ];
        }

        $prizes = $this->getActivePrizes();
        if ($prizes->isEmpty()) {
            return [
                'success' => false,
                'message' => __('There are currently no active prizes.'),
            ];
        }

        // Calculate total probability weight
        $totalWeight = $prizes->sum('probability_weight');
        if ($totalWeight <= 0) {
            $totalWeight = $prizes->count();
        }

        $randomWeight = mt_rand(1, (int) $totalWeight);
        $currentWeight = 0;
        $winningPrize = null;
        $winningIndex = 0;

        foreach ($prizes as $index => $prize) {
            $currentWeight += $prize->probability_weight > 0 ? $prize->probability_weight : 1;
            if ($randomWeight <= $currentWeight) {
                $winningPrize = $prize;
                $winningIndex = $index;
                break;
            }
        }

        if (! $winningPrize) {
            $winningPrize = $prizes->first();
            $winningIndex = 0;
        }

        $requiresEmailAfter = ($settings->email_policy === 'after_win' && empty($email));

        // Generate promo code if applicable
        $promoCode = null;
        if (in_array($winningPrize->type, ['percentage', 'fixed']) && $winningPrize->value > 0) {
            $promoCode = 'WHEEL-'.strtoupper(Str::random(6));

            if (class_exists(DiscountCode::class) && class_exists(DiscountTypeEnum::class)) {
                DiscountCode::create([
                    'code' => $promoCode,
                    'type' => $winningPrize->type === 'fixed' ? DiscountTypeEnum::Fixed : DiscountTypeEnum::Percentage,
                    'value' => $winningPrize->value,
                    'usage_limit' => 1,
                    'user_id' => $userId,
                    'assigned_email' => $email,
                    'active' => true,
                ]);
            }
        } elseif ($winningPrize->type === 'free_shipping') {
            $promoCode = 'FREE-SHIP-'.strtoupper(Str::random(4));
        }

        // Record spin
        $spin = LuckyWheelSpin::create([
            'user_id' => $userId,
            'email' => $email,
            'ip_address' => $ip,
            'fingerprint' => $fingerprint,
            'prize_id' => $winningPrize->id,
            'promo_code' => $promoCode,
        ]);

        $msg = $promoCode ? __('Congratulations on winning! Your discount code: :code', ['code' => $promoCode]) : __('Congratulations on winning: :title!', ['title' => $winningPrize->title]);

        return [
            'success' => true,
            'spin_id' => $spin->id,
            'prize' => [
                'id' => $winningPrize->id,
                'title' => $winningPrize->title,
                'type' => $winningPrize->type,
                'value' => $winningPrize->value,
                'bg_color' => $winningPrize->bg_color,
                'text_color' => $winningPrize->text_color,
            ],
            'prize_index' => $winningIndex,
            'total_prizes' => $prizes->count(),
            'promo_code' => $requiresEmailAfter ? null : $promoCode,
            'requires_email' => $requiresEmailAfter,
            'email_policy' => $settings->email_policy,
            'message' => $requiresEmailAfter ? __('Please enter your email to reveal your discount code.') : $msg,
        ];
    }

    public function claimEmail(int $spinId, string $email): array
    {
        $spin = LuckyWheelSpin::with('prize')->find($spinId);
        if (! $spin) {
            return ['success' => false, 'message' => __('Spin record not found.')];
        }

        $spin->update(['email' => $email]);

        if ($spin->promo_code && class_exists(DiscountCode::class)) {
            DiscountCode::where('code', $spin->promo_code)->update(['assigned_email' => $email]);
        }

        $msg = $spin->promo_code ? __('Congratulations on winning! Your discount code: :code', ['code' => $spin->promo_code]) : __('Congratulations on winning: :title!', ['title' => $spin->prize->title ?? '']);

        return [
            'success' => true,
            'promo_code' => $spin->promo_code,
            'message' => $msg,
        ];
    }
}
