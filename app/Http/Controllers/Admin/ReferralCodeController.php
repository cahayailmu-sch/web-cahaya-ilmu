<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use App\Support\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ReferralCodeController extends Controller
{
    /**
     * Create a new referral code (event or sibling) from the SPMB settings page.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureCanManage();

        $type = (string) $request->input('type', ReferralCode::TYPE_SIBLING_ALUMNI);

        if (! array_key_exists($type, ReferralCode::TYPES)) {
            $type = ReferralCode::TYPE_SIBLING_ALUMNI;
        }

        $request->merge([
            'code' => ReferralCode::normalize((string) $request->input('code')),
            'type' => $type,
            'discount_amount' => $request->filled('discount_amount')
                ? $request->input('discount_amount')
                : ReferralCode::TYPES[$type]['discount_amount'],
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        $validated = $request->validateWithBag('referralCreate', [
            'code' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9-]+$/',
                Rule::unique('referral_codes', 'code'),
            ],
            'type' => ['required', Rule::in(array_keys(ReferralCode::TYPES))],
            'discount_amount' => ['required', 'integer', 'min:0', 'max:100000000'],
            'max_uses' => ['required', 'integer', 'min:1', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'code.required' => 'Kode referral wajib diisi.',
            'code.regex' => 'Kode referral hanya boleh berisi huruf, angka, dan tanda hubung.',
            'code.unique' => 'Kode referral tersebut sudah terdaftar.',
            'type.required' => 'Jenis kode referral wajib dipilih.',
            'type.in' => 'Jenis kode referral tidak valid.',
            'discount_amount.required' => 'Nominal potongan wajib diisi.',
            'discount_amount.integer' => 'Nominal potongan harus berupa angka.',
            'discount_amount.min' => 'Nominal potongan tidak boleh negatif.',
            'max_uses.required' => 'Batas pemakaian wajib diisi.',
            'max_uses.integer' => 'Batas pemakaian harus berupa angka.',
            'max_uses.min' => 'Batas pemakaian minimal 1 kali.',
            'max_uses.max' => 'Batas pemakaian maksimal 1.000 kali.',
            'notes.max' => 'Catatan internal terlalu panjang.',
        ]);

        $referral = ReferralCode::create([
            'code' => $validated['code'],
            'type' => $validated['type'],
            'discount_amount' => (int) $validated['discount_amount'],
            'max_uses' => (int) $validated['max_uses'],
            'used_count' => 0,
            'is_active' => (bool) $validated['is_active'],
            'notes' => $validated['notes'] ?? null,
        ]);

        ActivityLogger::log(
            'create',
            'Pengaturan SPMB',
            "Menambahkan kode referral {$referral->code} ({$referral->typeLabel()}, potongan {$referral->discountDisplay()}, maksimal {$referral->max_uses} pemakaian).",
            ['after' => $referral->only(['code', 'type', 'discount_amount', 'max_uses', 'notes', 'is_active'])],
        );

        return back()->with('success', "Kode referral {$referral->code} berhasil ditambahkan.");
    }

    /**
     * Generate a unique sibling referral code for the admin form.
     */
    public function generate(): JsonResponse
    {
        $this->ensureCanManage();

        do {
            $code = 'ALUMNI26-'.Str::upper(Str::random(4));
        } while (ReferralCode::query()->where('code', $code)->exists());

        return response()->json(['code' => $code]);
    }

    /**
     * Activate or deactivate a sibling referral code.
     */
    public function toggle(ReferralCode $referralCode): RedirectResponse
    {
        $this->ensureCanManage();

        if ($referralCode->type !== ReferralCode::TYPE_SIBLING_ALUMNI) {
            return back()->with('error', 'Kode Event Muslim Vibes tidak dapat diubah statusnya.');
        }

        $referralCode->update(['is_active' => ! $referralCode->is_active]);

        ActivityLogger::log(
            'update',
            'Pengaturan SPMB',
            "Mengubah status kode referral {$referralCode->code} menjadi ".($referralCode->is_active ? 'aktif' : 'nonaktif').'.',
            ['after' => ['code' => $referralCode->code, 'is_active' => $referralCode->is_active]],
        );

        return back()->with('success', "Status kode {$referralCode->code} berhasil diperbarui.");
    }

    /**
     * Delete a referral code (event or sibling).
     */
    public function destroy(ReferralCode $referralCode): RedirectResponse
    {
        $this->ensureCanManage();

        $snapshot = $referralCode->only([
            'code',
            'type',
            'discount_amount',
            'max_uses',
            'used_count',
            'notes',
            'is_active',
        ]);

        $typeLabel = $referralCode->typeLabel();
        $discountDisplay = $referralCode->discountDisplay();

        $referralCode->delete();

        ActivityLogger::log(
            'delete',
            'Pengaturan SPMB',
            "Menghapus kode referral {$snapshot['code']} ({$typeLabel}, potongan {$discountDisplay}, terpakai {$snapshot['used_count']} kali).",
            ['before' => $snapshot],
        );

        return back()->with('success', "Kode referral {$snapshot['code']} berhasil dihapus.");
    }

    /**
     * Update an existing referral code (event or sibling) from the admin panel.
     */
    public function update(Request $request, ReferralCode $referralCode): RedirectResponse
    {
        $this->ensureCanManage();

        $request->merge([
            'code' => ReferralCode::normalize((string) $request->input('code')),
        ]);

        $before = $referralCode->only([
            'code',
            'type',
            'discount_amount',
            'max_uses',
            'used_count',
            'notes',
            'is_active',
        ]);

        $validated = $request->validateWithBag('referralEdit', [
            'code' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9-]+$/',
                Rule::unique('referral_codes', 'code')->ignore($referralCode->getKey()),
            ],
            'type' => ['required', Rule::in(array_keys(ReferralCode::TYPES))],
            'discount_amount' => ['required', 'integer', 'min:0', 'max:100000000'],
            'max_uses' => ['required', 'integer', 'min:'.max(1, $referralCode->used_count), 'max:1000'],
            'notes' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'code.required' => 'Kode referral wajib diisi.',
            'code.regex' => 'Kode referral hanya boleh berisi huruf, angka, dan tanda hubung.',
            'code.unique' => 'Kode referral tersebut sudah terdaftar.',
            'type.required' => 'Jenis kode referral wajib dipilih.',
            'type.in' => 'Jenis kode referral tidak valid.',
            'discount_amount.required' => 'Nominal potongan wajib diisi.',
            'discount_amount.integer' => 'Nominal potongan harus berupa angka.',
            'discount_amount.min' => 'Nominal potongan tidak boleh negatif.',
            'max_uses.required' => 'Batas pemakaian wajib diisi.',
            'max_uses.integer' => 'Batas pemakaian harus berupa angka.',
            'max_uses.min' => 'Batas pemakaian tidak boleh lebih kecil dari jumlah pemakaian saat ini ('.$referralCode->used_count.' kali).',
            'max_uses.max' => 'Batas pemakaian maksimal 1.000 kali.',
            'notes.max' => 'Catatan internal terlalu panjang.',
        ]);

        $referralCode->update([
            'code' => $validated['code'],
            'type' => $validated['type'],
            'discount_amount' => (int) $validated['discount_amount'],
            'max_uses' => (int) $validated['max_uses'],
            'notes' => $validated['notes'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $after = $referralCode->only([
            'code',
            'type',
            'discount_amount',
            'max_uses',
            'used_count',
            'notes',
            'is_active',
        ]);

        ActivityLogger::log(
            'update',
            'Pengaturan SPMB',
            "Memperbarui kode referral {$before['code']} menjadi {$after['code']} ({$referralCode->typeLabel()}, potongan {$referralCode->discountDisplay()}, maksimal {$after['max_uses']} pemakaian).",
            ['before' => $before, 'after' => $after],
        );

        return back()->with('success', "Kode referral {$referralCode->code} berhasil diperbarui.");
    }

    /**
     * Reset a code's usage counter so it becomes available again.
     */
    public function reset(ReferralCode $referralCode): RedirectResponse
    {
        $this->ensureCanManage();

        $before = $referralCode->only(['code', 'used_count', 'used_by_registration_id', 'is_active']);

        $referralCode->update([
            'used_count' => 0,
            'used_by_registration_id' => null,
            'is_active' => true,
        ]);

        ActivityLogger::log(
            'reset',
            'Pengaturan SPMB',
            "Mereset status pemakaian kode referral {$referralCode->code} dari {$before['used_count']} pemakaian menjadi 0 sehingga kembali tersedia.",
            [
                'before' => $before,
                'after' => $referralCode->only(['code', 'used_count', 'used_by_registration_id', 'is_active']),
            ],
        );

        return back()->with('success', "Status pemakaian kode {$referralCode->code} berhasil direset. Kode kembali tersedia.");
    }

    /**
     * Reset every Event Muslim Vibes code back to zero usage.
     */
    public function resetEventCodes(): RedirectResponse
    {
        $this->ensureCanManage();

        $eventCodes = ReferralCode::query()->eventFree()->orderBy('code')->get();

        $before = $eventCodes
            ->map(fn (ReferralCode $code): array => $code->only([
                'code',
                'used_count',
                'used_by_registration_id',
                'is_active',
            ]))
            ->all();

        ReferralCode::query()->eventFree()->update([
            'used_count' => 0,
            'used_by_registration_id' => null,
            'is_active' => true,
        ]);

        ActivityLogger::log(
            'reset',
            'Pengaturan SPMB',
            'Mereset status pemakaian seluruh kode Event Muslim Vibes ('.$eventCodes->count().' kode) agar kembali tersedia.',
            ['before' => $before],
        );

        return back()->with('success', 'Seluruh kode Event Muslim Vibes berhasil direset dan kembali tersedia.');
    }

    /**
     * Ensure only superadmins and TK admins can manage referral codes.
     */
    private function ensureCanManage(): void
    {
        $user = auth()->user();

        if (! $user?->isSuperAdmin() && strtolower((string) $user?->getLevel()) !== 'tk') {
            abort(403, 'Hanya Superadmin dan Admin TK yang dapat mengelola kode referral KB-TK.');
        }
    }
}
