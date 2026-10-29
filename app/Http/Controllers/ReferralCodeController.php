<?php

namespace App\Http\Controllers;

use App\Models\ReferralCode;
use App\Support\ReferralCodeCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferralCodeController extends Controller
{
    /**
     * Validate a KB-TK referral code in real time from the public SPMB form.
     *
     * Accepts both GET (query string, preferred for tunnels) and POST (JSON).
     */
    public function check(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['nullable', 'string', 'max:50'],
        ], [
            'code.string' => 'Kode referral tidak valid.',
            'code.max' => 'Kode referral terlalu panjang.',
        ]);

        $cleanCode = ReferralCode::normalize((string) ($validated['code'] ?? ''));

        if ($cleanCode !== '' && ReferralCode::query()->doesntExist()) {
            ReferralCodeCatalog::ensureEventCodes();
        }

        $referral = $cleanCode === '' ? null : ReferralCode::findByCode($cleanCode);

        if (! $referral instanceof ReferralCode) {
            return $this->invalidResponse(
                $cleanCode,
                'not_found',
                'Kode referral tidak ditemukan. Periksa kembali penulisan kode Anda.',
            );
        }

        $maxUses = (int) ($referral->max_uses ?: 1);
        $usedCount = (int) $referral->used_count;

        if (! (bool) $referral->is_active) {
            return $this->invalidResponse(
                $referral->code,
                'inactive',
                'Kode referral ini sedang dinonaktifkan.',
            );
        }

        if ($usedCount >= $maxUses) {
            return $this->invalidResponse(
                $referral->code,
                'exhausted',
                'Kuota kode referral ini sudah habis terpakai.',
            );
        }

        if ($referral->type !== ReferralCode::TYPE_EVENT_FREE_REG) {
            return $this->invalidResponse(
                $referral->code,
                'inactive',
                'Kode referral ini sudah tidak berlaku. Kode referral kini hanya untuk voucher Event Muslim Vibes.',
            );
        }

        return response()->json([
            'valid' => true,
            'code' => $referral->code,
            'type' => $referral->type,
            'type_label' => $referral->typeLabel(),
            'discount_amount' => $referral->discount_amount,
            'discount_display' => $referral->discountDisplay(),
            'waives_registration_fee' => true,
            'remaining_uses' => max(0, $maxUses - $usedCount),
            'message' => '✅ Kode Event Muslim Vibes Valid! Anda mendapatkan GRATIS Biaya Pendaftaran sebesar Rp 250.000.',
        ]);
    }

    /**
     * Build a JSON response for an unusable referral code with a specific reason.
     */
    private function invalidResponse(string $code, string $reason, string $message): JsonResponse
    {
        return response()->json([
            'valid' => false,
            'code' => $code,
            'reason' => $reason,
            'message' => $message,
        ]);
    }
}
