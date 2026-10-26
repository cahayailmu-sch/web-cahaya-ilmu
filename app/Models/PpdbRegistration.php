<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PpdbRegistration extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_VERIFIED = 'verified';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'registration_number',
        'registration_code',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'level',
        'sub_level',
        'previous_school',
        'parent_name',
        'whatsapp',
        'address',
        'payment_proof',
        'referral_code',
        'discount_amount',
        'final_fee',
        'referral_note',
        'status',
        'full_form_data',
        'fee_agreement_data',
        'verified_at',
        'completed_at',
    ];

    /**
     * The model's default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'final_fee' => 'integer',
            'discount_amount' => 'integer',
            'full_form_data' => 'array',
            'fee_agreement_data' => 'array',
            'verified_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Generate a secure access code for the stage-two form on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (PpdbRegistration $registration): void {
            if (empty($registration->registration_code)) {
                $registration->registration_code = Str::random(40);
            }
        });
    }

    /**
     * Get every supported status with its human-readable label.
     *
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return config('spmb.statuses');
    }

    /**
     * Get the human-readable label of the current status.
     */
    public function statusLabel(): string
    {
        $legacy = [
            'Baru' => 'Menunggu Verifikasi',
            'Diverifikasi' => 'Terverifikasi (Tahap 1)',
            'Diterima' => 'Diterima',
            'Ditolak' => 'Ditolak',
        ];

        return self::statusLabels()[$this->status] ?? $legacy[$this->status] ?? (string) $this->status;
    }

    /**
     * Determine whether the registrant may fill in the stage-two form.
     */
    public function canFillFullForm(): bool
    {
        return in_array($this->status, [self::STATUS_VERIFIED, 'Diverifikasi'], true);
    }

    /**
     * Determine whether the stage-two form has been submitted.
     */
    public function hasCompletedFullForm(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Get the official school name for the registration level.
     */
    public function schoolName(): string
    {
        if ($this->level === 'TK') {
            return (string) config('spmb.tk.name', 'KB-TK Islam Cahaya Ilmu');
        }

        return $this->level.' Islam Cahaya Ilmu';
    }

    /**
     * Get the short level label used on cards and documents.
     */
    public function levelLabel(): string
    {
        return $this->level === 'TK' ? 'KB-TK' : $this->level;
    }

    /**
     * Get the human-readable sub-level (kelompok) label for KB-TK registrants.
     */
    public function subLevelLabel(): ?string
    {
        if (! filled($this->sub_level)) {
            return null;
        }

        $groups = (array) config('spmb.tk.groups', []);

        return (string) ($groups[$this->sub_level]['label'] ?? $this->sub_level);
    }

    /**
     * Get the human-readable promo / referral label applied to the registration.
     */
    public function promoLabel(): ?string
    {
        if (! filled($this->referral_code)) {
            return null;
        }

        if (filled($this->referral_note)) {
            return (string) $this->referral_note;
        }

        $promos = (array) config('spmb.tk.promos', []);

        return (string) ($promos[$this->referral_code]['label'] ?? $this->referral_code);
    }

    /**
     * Determine whether the payment proof is a free-referral marker instead of a file.
     */
    public function paymentProofIsFree(): bool
    {
        return is_string($this->payment_proof) && str_starts_with($this->payment_proof, 'FREE - ');
    }

    /**
     * Get the human-readable gender label.
     */
    public function genderLabel(): string
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get the public URL of the stage-two "Formulir Lengkap".
     */
    public function fullFormUrl(): string
    {
        return route('spmb.full-form', $this->registration_code);
    }

    /**
     * Get the education fee options for the registration level.
     *
     * @return array{uang_masuk: array<int, int>, spp: array<int, int>, konsumsi: int}
     */
    public function feeOptions(): array
    {
        return config("spmb.fees.{$this->level}", config('spmb.fees.SD'));
    }

    /**
     * Get the official WhatsApp group link for the registrant's level and gender.
     *
     * @param  Collection<string, string|null>|null  $settings
     */
    public function whatsappGroupUrl(?Collection $settings = null): ?string
    {
        $key = 'wa_group_'.strtolower($this->level).'_'.($this->gender === 'P' ? 'p' : 'l');
        $url = $settings !== null
            ? ($settings[$key] ?? null)
            : SchoolSetting::where('key', $key)->value('value');

        return filled($url) ? (string) $url : null;
    }

    /**
     * Read a value from the stage-two form data with smart pre-filling from stage 1.
     */
    public function formValue(string $key, mixed $default = null): mixed
    {
        $value = data_get($this->full_form_data, $key, data_get($this->fee_agreement_data, $key));

        if ($value !== null && $value !== '') {
            return $value;
        }

        return match ($key) {
            'nama_panggilan' => Str::before($this->full_name ?? '', ' '),
            'alamat_lengkap' => $this->address,
            'kota' => 'Balikpapan',
            'kota_ttd' => 'Balikpapan',
            'riwayat_sekolah' => $this->previous_school,
            'ayah_nama' => $this->parent_name,
            'ayah_hp' => $this->whatsapp,
            'pj_nama' => $this->parent_name,
            'pj_alamat' => $this->address,
            'pj_hp' => $this->whatsapp,
            'ttd_ayah' => $this->parent_name,
            'agama' => 'Islam',
            'tinggal_bersama' => 'Orang Tua',
            'pilihan_uang_masuk' => '1',
            'cara_bayar' => 'Cash',
            'pilihan_spp' => '1',
            'penilaian_biaya' => 'Sedang',
            'hubungan_ortu' => 'Rukun/Serumah',
            'penanggung_jawab' => 'Ayah',
            'pernah_melanggar' => 'Tidak',
            'hukuman_fisik' => 'Tidak',
            'mengambil_uang' => 'Tidak Pernah',
            'suka_tv' => 'Tidak',
            'tidur_ditemani' => 'Tidak',
            'mandiri_makan' => 'Ya',
            'alergi' => 'Tidak',
            'pengaruh_terbesar' => 'Gadget (HP, Laptop, dll)',
            'sosok_berpengaruh' => 'Ayah',
            'perilaku_diperbaiki' => 'Tidak Ada',
            'riwayat_penyakit' => 'Tidak Ada',
            default => $default,
        };
    }

    /**
     * Get the public URL for an uploaded stage-two document.
     */
    public function documentUrl(string $key): ?string
    {
        return $this->resolveStorageUrl(data_get($this->full_form_data, $key));
    }

    /**
     * Get the stored relative path of the 3x4 passport photo.
     */
    public function passportPhotoPath(): ?string
    {
        $path = data_get($this->full_form_data, 'pas_foto_3x4');

        return is_string($path) && $path !== '' ? $path : null;
    }

    /**
     * Get the public URL for the uploaded 3x4 passport photo.
     */
    public function passportPhotoUrl(): ?string
    {
        return $this->resolveStorageUrl($this->passportPhotoPath());
    }

    /**
     * Get the absolute filesystem path of the passport photo for DomPDF.
     */
    public function passportPhotoAbsolutePath(): ?string
    {
        $path = $this->passportPhotoPath();

        if ($path === null) {
            return null;
        }

        $absolutePath = public_path('storage/'.$path);

        return is_file($absolutePath) ? $absolutePath : null;
    }

    /**
     * Build a WhatsApp chat link for the registrant's number.
     */
    public function whatsappLink(): string
    {
        $number = preg_replace('/\D+/', '', $this->whatsapp) ?? '';

        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        return 'https://wa.me/'.$number;
    }

    /**
     * Get the registrant's age in completed years.
     */
    public function age(): ?int
    {
        return $this->birth_date?->age;
    }

    /**
     * Get the public URL of the uploaded payment proof.
     */
    public function paymentProofUrl(): ?string
    {
        if ($this->paymentProofIsFree()) {
            return null;
        }

        return $this->resolveStorageUrl($this->payment_proof);
    }

    /**
     * Normalize a relative or absolute storage path into a public URL.
     */
    private function resolveStorageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (preg_match('~^https?://(?:127\.0\.0\.1|localhost)(?::\d+)?(/.*)$~i', $path, $matches) === 1) {
            return $matches[1];
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/storage/')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Generate the next unique SPMB registration number for the current year.
     */
    public static function generateRegistrationNumber(): string
    {
        $prefix = 'SPMB-'.now()->year.'-';
        $lastNumber = static::query()
            ->where('registration_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('registration_number');

        $sequence = $lastNumber ? ((int) Str::afterLast($lastNumber, '-')) + 1 : 1;

        do {
            $candidate = $prefix.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
            $sequence++;
        } while (static::query()->where('registration_number', $candidate)->exists());

        return $candidate;
    }
}
