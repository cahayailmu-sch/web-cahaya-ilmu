<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Post;
use App\Models\PpdbRegistration;
use App\Models\ReferralCode;
use App\Models\SchoolAgenda;
use App\Models\SchoolSetting;
use App\Services\ImageCompressor;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class FrontController extends Controller
{
    /**
     * Minimum age in months for the SD level (6 years 3 months), measured on 1 July.
     */
    private const MINIMUM_SD_AGE_MONTHS = 75;

    /**
     * Show the public home page.
     */
    public function home(): View
    {
        $publicData = $this->publicViewData();
        $settings = $publicData['settings'];

        return view('home', [
            'stats' => [
                'students' => (int) ($settings['jumlah_siswa'] ?? 0),
                'teachers' => (int) ($settings['jumlah_guru'] ?? 0),
                'registrations' => PpdbRegistration::count(),
                'galleries' => Gallery::count(),
            ],
            'posts' => Post::query()
                ->where('is_published', true)
                ->latest()
                ->take(3)
                ->get(),
            'galleries' => Gallery::query()
                ->latest()
                ->take(8)
                ->get(),
            'agendas' => SchoolAgenda::query()
                ->where('is_published', true)
                ->orderBy('event_date')
                ->orderByDesc('id')
                ->take(4)
                ->get(),
        ] + $publicData);
    }

    /**
     * Show the info school catalog with category, level, and search filters.
     */
    public function infoIndex(Request $request): View
    {
        $categories = ['Berita', 'Pengumuman', 'Prestasi'];
        $levels = ['Umum', 'TK', 'SD', 'SMP', 'SMA'];

        $activeCategory = $request->string('kategori')->toString();
        $requestedLevel = $request->string('jenjang')->toString();
        $activeLevel = collect($levels)->first(
            fn (string $level) => strcasecmp($level, $requestedLevel) === 0
        ) ?? '';
        $search = trim($request->string('q')->toString());

        if (! in_array($activeCategory, $categories, true)) {
            $activeCategory = '';
        }

        $posts = Post::query()
            ->where('is_published', true)
            ->when($activeCategory !== '', fn ($query) => $query->where('category', $activeCategory))
            ->when($activeLevel !== '', fn ($query) => $query->where('level', $activeLevel))
            ->when($search !== '', fn ($query) => $query->where('title', 'like', '%'.$search.'%'))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $agendas = SchoolAgenda::query()
            ->where('is_published', true)
            ->when($activeLevel !== '', fn ($query) => $query->where('level', $activeLevel))
            ->orderBy('event_date')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        return view('info.index', $this->publicViewData() + [
            'posts' => $posts,
            'agendas' => $agendas,
            'categories' => $categories,
            'levels' => $levels,
            'activeCategory' => $activeCategory,
            'activeLevel' => $activeLevel,
            'search' => $search,
            'instagramFeeds' => $this->instagramFeeds(),
        ]);
    }

    /**
     * Show a single published info school article.
     */
    public function infoShow(string $slug): View
    {
        $post = Post::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPosts = Post::query()
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->where('category', $post->category)
                    ->orWhere('level', $post->level);
            })
            ->latest()
            ->take(3)
            ->get();

        if ($relatedPosts->isEmpty()) {
            $relatedPosts = Post::query()
                ->where('is_published', true)
                ->where('id', '!=', $post->id)
                ->latest()
                ->take(3)
                ->get();
        }

        $shareUrl = url()->current();

        return view('info.show', $this->publicViewData() + [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'shareUrl' => $shareUrl,
            'whatsappShareUrl' => 'https://wa.me/?text='.urlencode($post->title.' — '.$shareUrl),
        ]);
    }

    /**
     * Show the school gallery catalog with level and category filters.
     */
    public function galleryIndex(Request $request): View
    {
        $levels = ['Umum', 'TK', 'SD', 'SMP', 'SMA'];
        $requestedLevel = $request->string('jenjang')->toString();
        $activeLevel = collect($levels)->first(
            fn (string $level) => strcasecmp($level, $requestedLevel) === 0
        ) ?? '';

        $categories = Gallery::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $activeCategory = $request->string('kategori')->toString();

        if (! $categories->contains($activeCategory)) {
            $activeCategory = '';
        }

        $galleries = Gallery::query()
            ->when($activeLevel !== '', fn ($query) => $query->where('level', $activeLevel))
            ->when($activeCategory !== '', fn ($query) => $query->where('category', $activeCategory))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('galeri.index', $this->publicViewData() + [
            'galleries' => $galleries,
            'categories' => $categories,
            'levels' => $levels,
            'activeLevel' => $activeLevel,
            'activeCategory' => $activeCategory,
        ]);
    }

    /**
     * Show the complete profile of a school level.
     */
    public function jenjangShow(string $slug): View
    {
        $profile = config('jenjang.'.$slug);

        abort_unless(is_array($profile), 404);

        $level = $profile['short'];
        $publicData = $this->publicViewData();
        $profile['hero_image'] = SchoolSetting::jenjangHeroImage($slug, $publicData['settings']);

        return view('jenjang.show', $publicData + [
            'profile' => $profile,
            'galleries' => Gallery::query()
                ->where('level', $level)
                ->latest()
                ->take(8)
                ->get(),
            'posts' => Post::query()
                ->where('is_published', true)
                ->where('level', $level)
                ->latest()
                ->take(3)
                ->get(),
            'agendas' => SchoolAgenda::query()
                ->where('is_published', true)
                ->whereIn('level', [$level, 'Umum'])
                ->orderBy('event_date')
                ->orderByDesc('id')
                ->take(4)
                ->get(),
            'instagramFeedPosts' => $this->instagramFeedPosts($level),
            'instagramPostLinks' => $this->instagramPostLinks($publicData['settings'], $level),
            'spmbTk' => config('spmb.tk'),
        ]);
    }

    /**
     * Show the complete foundation profile.
     */
    public function about(): View
    {
        return view('about', $this->publicViewData());
    }

    /**
     * Show the detailed flagship programs.
     */
    public function programs(): View
    {
        return view('programs', $this->publicViewData());
    }

    /**
     * Show the public SPMB registration page.
     */
    public function ppdb(): View
    {
        $publicData = $this->publicViewData();
        $settings = $publicData['settings'];
        $levels = SchoolSetting::ppdbLevels($settings);

        $levelsJs = collect($levels)->map(fn (array $level) => [
            'is_open' => $level['is_open'],
            'bank' => $level['bank'],
            'account_number' => $level['account_number'],
            'account_name' => $level['account_name'],
            'fee' => $level['fee'],
            'fee_display' => $level['fee_display'],
        ])->all();

        $targetYear = $this->targetSchoolYear($settings);
        $sdCutoff = $this->sdCutoffBirthDate($settings);

        return view('ppdb', [
            'ppdbLevels' => $levels,
            'ppdbLevelsJs' => $levelsJs,
            'ppdbQuotas' => SchoolSetting::ppdbQuotaSummary($settings),
            'ppdbOpen' => collect($levels)->contains('is_open', true),
            'minimumSdAgeMonths' => self::MINIMUM_SD_AGE_MONTHS,
            'schoolYearStartDate' => $this->schoolYearStartDate($settings)->toDateString(),
            'targetSchoolYear' => $targetYear,
            'sdCutoffBirthDate' => $sdCutoff->toDateString(),
            'spmbTk' => config('spmb.tk'),
        ] + $publicData);
    }

    /**
     * Check an SPMB registration status by registration number on the public page.
     */
    public function ppdbStatus(Request $request): View
    {
        $publicData = $this->publicViewData();
        $rawQuery = $request->string('nomor')->toString() ?: $request->string('registration_number')->toString();
        $registrationNumber = strtoupper(trim($rawQuery));

        $registration = $registrationNumber !== ''
            ? PpdbRegistration::query()
                ->where('registration_number', $registrationNumber)
                ->first()
            : null;

        return view('ppdb-status', $publicData + [
            'registrationNumber' => $registrationNumber,
            'registration' => $registration,
            'ppdbLevels' => SchoolSetting::ppdbLevels($publicData['settings']),
        ]);
    }

    /**
     * Show the printable SPMB registration proof card.
     */
    public function ppdbCard(string $registration_number): View
    {
        $registration = PpdbRegistration::query()
            ->where('registration_number', strtoupper(trim($registration_number)))
            ->firstOrFail();

        $publicData = $this->publicViewData();

        return view('ppdb-card', $publicData + [
            'registration' => $registration,
            'ppdbLevels' => SchoolSetting::ppdbLevels($publicData['settings']),
        ]);
    }

    /**
     * Store a new SPMB registration from the public form.
     */
    public function storePpdb(Request $request, ImageCompressor $imageCompressor): RedirectResponse
    {
        $settings = $this->settings();
        $levels = SchoolSetting::ppdbLevels($settings);

        $request->merge([
            'gender' => $this->normalizeGender($request->input('gender')),
            'level' => strtoupper(trim((string) $request->input('level'))),
            'sub_level' => $this->normalizeSubLevel($request->input('sub_level')),
            'referral_code' => ReferralCode::normalize((string) $request->input('referral_code')),
        ]);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'level' => ['required', Rule::in(array_keys($levels))],
            'sub_level' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => $request->input('level') === 'TK'),
                Rule::in(array_keys((array) config('spmb.tk.groups', []))),
            ],
            'promo_tk' => ['nullable', 'string', Rule::in(array_keys((array) config('spmb.tk.promos', [])))],
            'referral_code' => ['nullable', 'string', 'max:50'],
            'referral_alumni_names' => ['nullable', 'string', 'max:500'],
            'previous_school' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => in_array($request->input('level'), ['SMP', 'SMA'], true)),
            ],
            'parent_name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9+()\-\s]{9,20}$/'],
            'address' => ['nullable', 'string', 'max:1000'],
            'payment_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:6144'],
            'persetujuan' => ['accepted'],
        ], [
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin tidak valid. Pilih Laki-laki atau Perempuan.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.before' => 'Tanggal lahir tidak boleh tanggal yang akan datang.',
            'sub_level.required' => 'Kelompok KB, TK A, atau TK B wajib dipilih untuk pendaftaran KB-TK.',
            'sub_level.in' => 'Kelompok KB, TK A, atau TK B yang dipilih tidak valid.',
            'promo_tk.in' => 'Promo yang dipilih tidak valid.',
            'referral_code.max' => 'Kode referral terlalu panjang.',
            'referral_alumni_names.max' => 'Keterangan kakak alumni terlalu panjang.',
            'previous_school.required' => 'Asal sekolah wajib diisi untuk jenjang SMP dan SMA.',
            'payment_proof.mimes' => 'Bukti pembayaran harus berupa file JPG, PNG, atau PDF.',
            'payment_proof.max' => 'Ukuran bukti pembayaran maksimal 6 MB.',
            'persetujuan.accepted' => 'Anda harus menyetujui penggunaan data untuk melanjutkan.',
        ]);

        $level = $validated['level'];

        if (! ($levels[$level]['is_open'] ?? false)) {
            throw ValidationException::withMessages([
                'level' => "Pendaftaran jenjang {$level} sedang ditutup. Silakan pilih jenjang lain yang masih dibuka.",
            ]);
        }

        if ($level === 'SMA' && $validated['gender'] !== 'P') {
            throw ValidationException::withMessages([
                'gender' => 'Maaf, pendaftaran jenjang SMA Islam Cahaya Ilmu dikhususkan untuk banaat (putri / perempuan).',
            ]);
        }

        if ($level === 'TK') {
            $groupKey = (string) ($validated['sub_level'] ?? '');
            $groupQuota = SchoolSetting::ppdbTkGroupQuotaSummary($settings)[$groupKey] ?? null;

            if ($groupQuota && $groupQuota['is_full']) {
                throw ValidationException::withMessages([
                    'sub_level' => "Mohon maaf, kuota pendaftaran SPMB untuk kelompok {$groupQuota['label']} saat ini sudah PENUH ({$groupQuota['used']}/{$groupQuota['quota']}). Silakan pilih kelompok lain atau hubungi panitia SPMB melalui WhatsApp untuk informasi daftar tunggu (Waiting List).",
                ]);
            }
        } else {
            $genderQuota = SchoolSetting::ppdbQuotaSummary($settings, [$level])[$level][$validated['gender'] === 'L' ? 'male' : 'female'] ?? null;

            if ($genderQuota && $genderQuota['is_full']) {
                $genderLabel = $validated['gender'] === 'L' ? 'Laki-laki' : 'Perempuan';

                throw ValidationException::withMessages([
                    'gender' => "Mohon maaf, kuota pendaftaran SPMB untuk jenjang {$level} - {$genderLabel} saat ini sudah PENUH ({$genderQuota['used']}/{$genderQuota['quota']}). Silakan hubungi panitia SPMB melalui WhatsApp untuk informasi daftar tunggu (Waiting List).",
                ]);
            }
        }

        $sdCutoffDate = $this->sdCutoffBirthDate($settings);
        $birthDate = Carbon::parse($validated['birth_date'])->startOfDay();

        if ($level === 'SD' && $birthDate->gt($sdCutoffDate)) {
            throw ValidationException::withMessages([
                'birth_date' => 'Maaf, batas usia minimal masuk SD adalah kelahiran maksimal 31 Maret (6 tahun 3 bulan per 1 Juli). Kelahiran 1 April ke atas belum dapat mendaftar.',
            ]);
        }

        $tkConfig = (array) config('spmb.tk', []);
        $subLevel = $level === 'TK' ? (string) ($validated['sub_level'] ?? '') : null;
        $promoKey = $level === 'TK' ? (string) ($validated['promo_tk'] ?? '') : '';
        $promo = $promoKey !== '' ? ($tkConfig['promos'][$promoKey] ?? null) : null;
        $referralKey = (string) ($validated['referral_code'] ?? '');

        if ($level !== 'TK' && $referralKey !== '') {
            throw ValidationException::withMessages([
                'referral_code' => 'Kode referral / voucher promo hanya berlaku untuk pendaftaran KB-TK.',
            ]);
        }

        $referral = null;
        $referralNote = null;
        $isFreeReferral = false;

        if ($level === 'TK' && $referralKey !== '') {
            $referral = ReferralCode::findByCode($referralKey);

            if (! $referral instanceof ReferralCode || ! $referral->isAvailable()) {
                throw ValidationException::withMessages([
                    'referral_code' => '❌ Kode referral tidak valid atau kuota kode ini sudah terpakai.',
                ]);
            }

            if ($referral->type === ReferralCode::TYPE_SIBLING_ALUMNI) {
                $alumniNames = trim((string) ($validated['referral_alumni_names'] ?? ''));

                if ($alumniNames === '') {
                    throw ValidationException::withMessages([
                        'referral_alumni_names' => 'Nama 2 kakak alumni KB-TK Islam Cahaya Ilmu wajib diisi untuk kode referral kakak alumni.',
                    ]);
                }

                $referralNote = 'Kakak Alumni: '.$alumniNames;
            } else {
                $referralNote = $referral->descriptionLabel();
            }

            $referralKey = $referral->code;
            $isFreeReferral = $referral->type === ReferralCode::TYPE_EVENT_FREE_REG;

            // A referral code supersedes the legacy manual promo selection.
            $promoKey = '';
            $promo = null;
        }

        if ($level === 'TK' && $subLevel !== '') {
            $group = $tkConfig['groups'][$subLevel] ?? null;

            if ($group && $birthDate->gt(Carbon::parse($group['cutoff'])->endOfDay())) {
                $targetYear = $this->targetSchoolYear($settings);

                throw ValidationException::withMessages([
                    'birth_date' => "Maaf, kelompok {$group['label']} memerlukan usia minimal {$group['min_age_label']} pada Juni {$targetYear}. Ananda yang lahir setelah {$group['cutoff_label']} belum memenuhi syarat kelompok ini. Silakan pilih kelompok yang sesuai.",
                ]);
            }
        }

        $fee = (int) ($levels[$level]['fee'] ?? 0);
        $discountAmount = 0;

        if ($referral instanceof ReferralCode) {
            $discountAmount = (int) $referral->discount_amount;

            if ($isFreeReferral) {
                $fee = 0;
            }
        } elseif ($promo !== null) {
            $promoLimit = $promo['limit'] ?? null;

            if ($promoLimit !== null && $this->tkPromoUsage($promoKey) >= (int) $promoLimit) {
                throw ValidationException::withMessages([
                    'promo_tk' => "Kuota {$promo['label']} sudah terpenuhi. Silakan mendaftar tanpa promo atau hubungi WhatsApp Admin KB-TK.",
                ]);
            }

            $discountAmount = (int) ($promo['discount_amount'] ?? 0);

            if (! empty($promo['waives_registration_fee'])) {
                $fee = 0;
            }
        }

        $storedReferralCode = $referral?->code ?? ($promo !== null ? $promoKey : null);
        $storedReferralNote = $referral !== null
            ? $referralNote
            : ($promo !== null ? (string) ($promo['label'] ?? '') : null);

        $paymentProofPath = null;

        if ($isFreeReferral) {
            // Free event codes do not require a transfer proof; store an
            // auditable marker so admins know why no file was uploaded.
            $paymentProofPath = 'FREE - KODE REFERRAL: '.($referral?->code ?? $referralKey);
        } else {
            if ($fee > 0 && ! $request->hasFile('payment_proof')) {
                throw ValidationException::withMessages([
                    'payment_proof' => 'Bukti pembayaran pendaftaran wajib diunggah.',
                ]);
            }

            if ($request->hasFile('payment_proof')) {
                $paymentProof = $request->file('payment_proof');
                $isImage = str_starts_with((string) $paymentProof->getMimeType(), 'image/');

                if (! $isImage) {
                    $paymentProofPath = $paymentProof->store('ppdb/payments', 'public');
                } else {
                    try {
                        $paymentProofPath = $imageCompressor->storeUploaded(
                            $paymentProof,
                            'payment-proofs',
                            ImageCompressor::PAYMENT_PROOF_MAX_DIMENSION,
                        );
                    } catch (Throwable $exception) {
                        report($exception);

                        try {
                            $paymentProofPath = $paymentProof->store('ppdb/payments', 'public');
                        } catch (Throwable $storageException) {
                            report($storageException);

                            throw ValidationException::withMessages([
                                'payment_proof' => 'Bukti pembayaran gagal diunggah. Silakan coba lagi dengan file lain.',
                            ]);
                        }
                    }
                }

                if (! is_string($paymentProofPath) || $paymentProofPath === '') {
                    throw ValidationException::withMessages([
                        'payment_proof' => 'Bukti pembayaran gagal diunggah. Silakan coba lagi dengan file lain.',
                    ]);
                }
            }
        }

        $registration = DB::transaction(function () use (
            $referral,
            $validated,
            $level,
            $subLevel,
            $paymentProofPath,
            $storedReferralCode,
            $discountAmount,
            $fee,
            $storedReferralNote,
        ): PpdbRegistration {
            if ($referral instanceof ReferralCode) {
                // Lock the code again so two concurrent submissions cannot
                // redeem the same single-use code.
                $referral = ReferralCode::query()
                    ->whereKey($referral->getKey())
                    ->lockForUpdate()
                    ->first();

                if (! $referral instanceof ReferralCode || ! $referral->isAvailable()) {
                    throw ValidationException::withMessages([
                        'referral_code' => '❌ Kode referral tidak valid atau kuota kode ini sudah terpakai.',
                    ]);
                }
            }

            $registration = PpdbRegistration::create([
                'registration_number' => PpdbRegistration::generateRegistrationNumber(),
                'full_name' => $validated['full_name'],
                'gender' => $validated['gender'],
                'birth_place' => $validated['birth_place'],
                'birth_date' => $validated['birth_date'],
                'level' => $level,
                'sub_level' => $subLevel !== '' ? $subLevel : null,
                'previous_school' => $validated['previous_school'] ?? null,
                'parent_name' => $validated['parent_name'],
                'whatsapp' => $validated['whatsapp'],
                'address' => $validated['address'] ?? '',
                'payment_proof' => $paymentProofPath,
                'referral_code' => $storedReferralCode,
                'discount_amount' => $discountAmount,
                'final_fee' => $fee,
                'referral_note' => $storedReferralNote,
                'status' => PpdbRegistration::STATUS_PENDING,
            ]);

            if ($referral instanceof ReferralCode) {
                $referral->markUsed($registration);
            }

            return $registration;
        });

        ActivityLogger::log(
            'create',
            'SPMB',
            "Pendaftaran SPMB baru (Tahap 1): {$registration->full_name} ({$registration->level}) - No. {$registration->registration_number}",
            [
                'after' => [
                    'registration_number' => $registration->registration_number,
                    'registration_code' => $registration->registration_code,
                    'full_name' => $registration->full_name,
                    'level' => $registration->level,
                    'status' => $registration->status,
                    'referral_code' => $registration->referral_code,
                    'discount_amount' => $registration->discount_amount,
                ],
            ],
        );

        $whatsappNumbers = $this->whatsappNumbers($settings);
        $adminNumber = $whatsappNumbers[$registration->level] ?? '';

        $whatsappUrl = $adminNumber !== ''
            ? 'https://wa.me/'.$adminNumber.'?text='.urlencode($this->confirmationMessage($registration))
            : null;

        return redirect()
            ->route('ppdb.status', ['nomor' => $registration->registration_number])
            ->with('ppdb_success', [
                'registration_number' => $registration->registration_number,
                'registration_code' => $registration->registration_code,
                'full_name' => $registration->full_name,
                'level' => $registration->level,
                'level_label' => $registration->levelLabel(),
                'sub_level' => $registration->sub_level,
                'sub_level_label' => $registration->subLevelLabel(),
                'referral_code' => $registration->referral_code,
                'promo_label' => $registration->promoLabel(),
                'discount_amount' => $registration->discount_amount,
                'final_fee' => $registration->final_fee,
                'final_fee_display' => 'Rp '.number_format($registration->final_fee, 0, ',', '.'),
                'whatsapp_url' => $whatsappUrl,
            ]);
    }

    /**
     * Normalize the KB-TK group input to the configured group key.
     */
    private function normalizeSubLevel(mixed $value): string
    {
        $raw = trim((string) $value);

        if ($raw === '') {
            return '';
        }

        $normalized = strtoupper((string) preg_replace('/\s+/', ' ', $raw));

        foreach (array_keys((array) config('spmb.tk.groups', [])) as $groupKey) {
            if (strtoupper((string) $groupKey) === $normalized) {
                return (string) $groupKey;
            }
        }

        return $raw;
    }

    /**
     * Normalize the many possible gender inputs to the stored "L" / "P" values.
     */
    private function normalizeGender(mixed $value): string
    {
        $gender = strtolower(trim((string) $value));

        return match ($gender) {
            'l', 'laki-laki', 'laki laki' => 'L',
            'p', 'perempuan' => 'P',
            default => strtoupper(trim((string) $value)),
        };
    }

    /**
     * Get the active or target school year (first year of academic year, e.g. 2027 from '2027/2028').
     *
     * @param  Collection<string, string|null>|null  $settings
     */
    private function targetSchoolYear(?Collection $settings = null): int
    {
        $settings ??= $this->settings();
        $tahunAjaran = (string) ($settings['tahun_ajaran'] ?? '');

        if (preg_match('/^(\d{4})/', $tahunAjaran, $matches)) {
            return (int) $matches[1];
        }

        return now()->month >= 7 ? now()->year + 1 : now()->year;
    }

    /**
     * Get the start date of the target school year (1 July).
     *
     * @param  Collection<string, string|null>|null  $settings
     */
    private function schoolYearStartDate(?Collection $settings = null): Carbon
    {
        return Carbon::create($this->targetSchoolYear($settings), 7, 1)->startOfDay();
    }

    /**
     * Get the latest allowed birth date for elementary school (SD) entry (31 March of 6 years prior).
     *
     * @param  Collection<string, string|null>|null  $settings
     */
    private function sdCutoffBirthDate(?Collection $settings = null): Carbon
    {
        return Carbon::create($this->targetSchoolYear($settings) - 6, 3, 31)->endOfDay();
    }

    /**
     * Build the shared data required by every public page.
     *
     * @return array<string, mixed>
     */
    public function publicViewData(): array
    {
        $settings = $this->settings();
        $whatsappNumbers = $this->whatsappNumbers($settings);

        return [
            'settings' => $settings,
            'contacts' => $this->contactData($settings, $whatsappNumbers),
            'youtube' => $this->youtubeData($settings),
            'foundationName' => (string) ($settings['foundation_name'] ?? config('sekolah.foundation_name')),
            'motto' => (string) ($settings['motto'] ?? config('sekolah.motto')),
            'visi' => config('sekolah.visi'),
            'misi' => config('sekolah.misi'),
            'jenjangHeroes' => [
                'tk' => SchoolSetting::jenjangHeroImage('tk', $settings),
                'sd' => SchoolSetting::jenjangHeroImage('sd', $settings),
                'smp' => SchoolSetting::jenjangHeroImage('smp', $settings),
                'sma' => SchoolSetting::jenjangHeroImage('sma', $settings),
            ],
        ] + $this->whatsappData($whatsappNumbers);
    }

    /**
     * Build the latest Instagram-ready posts for every school level.
     *
     * @return array<string, Collection<int, Post>>
     */
    private function instagramFeeds(): array
    {
        $levels = ['TK', 'SD', 'SMP', 'SMA'];

        $posts = Post::query()
            ->where('is_published', true)
            ->whereIn('level', $levels)
            ->latest()
            ->take(80)
            ->get()
            ->filter(fn (Post $post) => $this->hasInstagramContent($post));

        $feeds = [];

        foreach ($levels as $level) {
            $feeds[$level] = $posts
                ->where('level', $level)
                ->take(3)
                ->values();
        }

        return $feeds;
    }

    /**
     * Build the latest Instagram-ready posts for one school level.
     *
     * @return Collection<int, Post>
     */
    private function instagramFeedPosts(string $level, int $limit = 4): Collection
    {
        return Post::query()
            ->where('is_published', true)
            ->where('level', $level)
            ->latest()
            ->take(30)
            ->get()
            ->filter(fn (Post $post) => $this->hasInstagramContent($post))
            ->take($limit)
            ->values();
    }

    /**
     * Determine whether a post has slide images or an Instagram link.
     */
    private function hasInstagramContent(Post $post): bool
    {
        return $post->imageUrls() !== [] || $post->hasInstagramPost();
    }

    /**
     * Get the configured Instagram post links for one school level.
     *
     * @return list<string>
     */
    private function instagramPostLinks(Collection $settings, string $level): array
    {
        $key = strtolower($level);
        $links = [];

        for ($index = 1; $index <= 3; $index++) {
            $url = trim((string) ($settings["instagram_post_{$key}_{$index}"] ?? ''));

            if ($url !== '') {
                $links[] = $url;
            }
        }

        return $links;
    }

    /**
     * Build the official contact and Instagram details for every school level.
     *
     * @param  array{TK: string, SD: string, SMP: string, SMA: string, UMUM: string}  $numbers
     * @return array<string, array<string, string>>
     */
    private function contactData(Collection $settings, array $numbers): array
    {
        $contacts = [];

        foreach (['TK', 'SD', 'SMP', 'SMA'] as $level) {
            $key = strtolower($level);
            $number = $numbers[$level] ?? '';
            $username = ltrim(trim((string) ($settings["instagram_username_{$key}"] ?? '')), '@');
            $instagramUrl = trim((string) ($settings["instagram_url_{$key}"] ?? ''));
            $youtubeUrl = trim((string) ($settings["youtube_{$key}"] ?? ''));

            if ($instagramUrl === '' && $username !== '') {
                $instagramUrl = 'https://www.instagram.com/'.$username;
            }

            $contacts[$level] = [
                'level' => $level,
                'level_label' => $level === 'TK' ? 'KB-TK' : $level,
                'name' => $level === 'TK' ? 'KB-TK Islam Cahaya Ilmu' : "{$level} Islam Cahaya Ilmu",
                'logo' => "/assets/images/logo-{$key}.png",
                'wa_number' => $number,
                'wa_display' => $number !== '' ? $this->formatWhatsapp($number) : '-',
                'wa_url' => $number !== '' ? 'https://wa.me/'.$number : '',
                'instagram_username' => $username !== '' ? '@'.$username : '',
                'instagram_url' => $instagramUrl,
                'youtube_url' => $youtubeUrl,
                'youtube_handle' => $this->youtubeHandle($youtubeUrl),
            ];
        }

        return $contacts;
    }

    /**
     * Extract the YouTube handle from a channel URL.
     */
    private function youtubeHandle(string $url): string
    {
        if (preg_match('~youtube\.com/@([A-Za-z0-9._-]+)~i', $url, $matches) === 1) {
            return '@'.$matches[1];
        }

        return '';
    }

    /**
     * Build the official YouTube channel details.
     *
     * @return array{name: string, url: string}
     */
    private function youtubeData(Collection $settings): array
    {
        return [
            'name' => trim((string) ($settings['youtube_channel_name'] ?? '')),
            'url' => trim((string) ($settings['youtube_url'] ?? '')),
        ];
    }

    /**
     * Load all school settings keyed by name.
     */
    private function settings(): Collection
    {
        return SchoolSetting::pluck('value', 'key');
    }

    /**
     * Normalize the configured WhatsApp numbers per level.
     *
     * @return array{TK: string, SD: string, SMP: string, SMA: string, UMUM: string}
     */
    private function whatsappNumbers(Collection $settings): array
    {
        $numbers = [];

        foreach (['TK', 'SD', 'SMP', 'SMA'] as $level) {
            $numbers[$level] = $this->normalizeWhatsapp($settings['wa_admin_'.strtolower($level)] ?? '');
        }

        $numbers['UMUM'] = $this->normalizeWhatsapp((string) ($settings['wa_pusat'] ?? ''))
            ?: ($numbers['SD'] ?: ($numbers['TK'] ?: ($numbers['SMP'] ?: $numbers['SMA'])));

        return $numbers;
    }

    /**
     * Build the shared WhatsApp data for the public views.
     *
     * @param  array{TK: string, SD: string, SMP: string, SMA: string, UMUM: string}  $numbers
     * @return array<string, mixed>
     */
    private function whatsappData(array $numbers): array
    {
        $general = $numbers['UMUM'];

        return [
            'whatsappNumbers' => $numbers,
            'generalWhatsapp' => $general,
            'generalWhatsappDisplay' => $general !== '' ? $this->formatWhatsapp($general) : '-',
        ];
    }

    /**
     * Convert a local phone number into the international WhatsApp format.
     */
    private function normalizeWhatsapp(?string $number): string
    {
        $digits = preg_replace('/\D+/', '', (string) $number) ?? '';

        return str_starts_with($digits, '0')
            ? '62'.substr($digits, 1)
            : $digits;
    }

    /**
     * Format a WhatsApp number for display, e.g. 0812-5550-1234.
     */
    private function formatWhatsapp(string $number): string
    {
        if (str_starts_with($number, '62')) {
            $number = '0'.substr($number, 2);
        }

        return trim(chunk_split($number, 4, '-'), '-');
    }

    /**
     * Build the WhatsApp confirmation message for a registration.
     */
    private function confirmationMessage(PpdbRegistration $registration): string
    {
        $lines = [
            "Assalamu'alaikum warahmatullahi wabarakatuh,",
            '',
            'Panitia SPMB Cahaya Ilmu Islamic School Balikpapan,',
            'saya telah mengisi formulir pendaftaran online dengan data berikut:',
            '',
            "*Nomor Pendaftaran:* {$registration->registration_number}",
            "*Nama Lengkap:* {$registration->full_name}",
            "*Pilihan Jenjang:* {$registration->levelLabel()}",
        ];

        if (filled($registration->subLevelLabel())) {
            $lines[] = "*Kelompok:* {$registration->subLevelLabel()}";
        }

        $lines = array_merge($lines, [
            "*Nama Orang Tua / Wali:* {$registration->parent_name}",
            "*Nomor WhatsApp:* {$registration->whatsapp}",
            '*Biaya Pendaftaran:* Rp '.number_format($registration->final_fee, 0, ',', '.'),
        ]);

        if (filled($registration->promoLabel())) {
            $lines[] = "*Promo:* {$registration->promoLabel()}";
        }

        $lines = array_merge($lines, [
            '',
            'Mohon informasi mengenai langkah selanjutnya. Terima kasih.',
            '',
            "Wassalamu'alaikum warahmatullahi wabarakatuh.",
        ]);

        return implode("\n", $lines);
    }

    /**
     * Count how many registrations already claimed a KB-TK promo.
     */
    private function tkPromoUsage(string $promoKey): int
    {
        return PpdbRegistration::query()
            ->where('level', 'TK')
            ->where('referral_code', $promoKey)
            ->count();
    }
}
