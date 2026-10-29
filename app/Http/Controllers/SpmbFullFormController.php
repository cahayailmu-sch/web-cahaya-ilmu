<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistration;
use App\Models\SchoolSetting;
use App\Services\ImageCompressor;
use App\Support\ActivityLogger;
use App\Support\SpmbDocument;
use App\Support\SpmbForm;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class SpmbFullFormController extends Controller
{
    /**
     * Show the stage-two "Formulir Lengkap & Surat Kesanggupan Biaya" page.
     */
    public function show(string $code): View|RedirectResponse
    {
        $registration = $this->findByCode($code);

        if ($registration->hasCompletedFullForm() && ! $registration->canFillFullForm()) {
            return redirect()->route('spmb.full-form.success', $registration->registration_code);
        }

        $publicData = app(FrontController::class)->publicViewData();

        return view('spmb.full-form', $publicData + [
            'registration' => $registration,
            'sections' => SpmbForm::fullFormSections($registration->level),
            'feeSections' => SpmbForm::feeSections($registration->level),
            'agreements' => SpmbForm::agreements($registration->level),
            'fees' => $registration->feeOptions(),
            'spmbTk' => config('spmb.tk'),
            'installmentDeadline' => SchoolSetting::installmentDeadlineLabel(),
            'pickupRows' => (int) config('spmb.pickup_rows', 3),
            'pickupColumns' => config('spmb.pickup_columns', []),
        ]);
    }

    /**
     * Store the stage-two submission and mark the registration as completed.
     */
    public function store(Request $request, string $code, ImageCompressor $imageCompressor): RedirectResponse
    {
        $registration = $this->findByCode($code);

        if (! $registration->canFillFullForm()) {
            return redirect()
                ->route('spmb.full-form', $registration->registration_code)
                ->with('error', 'Formulir Lengkap belum dapat diisi. Pastikan pendaftaran Anda sudah diverifikasi oleh Admin SPMB.');
        }

        $rules = SpmbForm::rules($registration->level);

        if ($registration->level === 'TK' && ! $registration->passportPhotoPath()) {
            $rules['pas_foto_3x4'] = ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];
        }

        $validated = $request->validate(
            $rules,
            [
                'required' => ':attribute wajib diisi.',
                'required_if' => ':attribute wajib diisi jika pembayaran dicicil.',
                'accepted' => ':attribute wajib disetujui.',
                'in' => 'Pilihan :attribute tidak valid.',
                'date' => ':attribute harus berupa tanggal yang valid.',
                'before' => ':attribute harus sebelum hari ini.',
                'integer' => ':attribute harus berupa angka.',
                'max' => ':attribute terlalu panjang.',
                'mimes' => ':attribute harus berupa gambar (JPG, PNG, WebP) atau PDF.',
                'image' => ':attribute harus berupa file gambar yang valid.',
                'pas_foto_3x4.max' => 'Ukuran pas foto maksimal 5 MB.',
                'pas_foto_3x4.mimes' => 'Pas foto harus berformat JPG, JPEG, PNG, atau WebP.',
                'pas_foto_3x4.image' => 'Pas foto harus berupa file gambar yang valid.',
                'pas_foto_3x4.uploaded' => 'Pas foto gagal diunggah. Pastikan ukurannya maksimal 5 MB lalu coba lagi.',
            ],
            SpmbForm::attributes($registration->level),
        );

        // Keep every submitted field (required or optional), not only the
        // validated ones, so nothing the registrant filled in is discarded.
        $formInput = $this->arrayInput($request, 'form');
        $feeInput = $this->arrayInput($request, 'fee');

        $pickups = $this->sanitizePickups($this->arrayInput($request, 'pickup'));
        $uploadedDocs = $this->storeUploadedDocuments($request, $registration, $imageCompressor);
        $passportPhoto = $this->storePassportPhoto($request, $registration, $imageCompressor);
        $agreementInput = $validated['agreements'] ?? [];
        $agreementKeys = ! empty($agreementInput)
            ? array_keys($agreementInput)
            : array_keys(config('spmb.agreements', []));

        $hasAlumniSiblings = null;
        $alumniSiblingNames = null;

        if ($registration->level === 'TK') {
            $hasAlumniSiblings = ($validated['form']['has_alumni_siblings'] ?? $formInput['has_alumni_siblings'] ?? null) === 'YA, ADA';
            $alumniSiblingNames = $hasAlumniSiblings
                ? trim((string) ($validated['form']['alumni_sibling_names'] ?? $formInput['alumni_sibling_names'] ?? ''))
                : null;
        }

        $registration->update([
            'full_form_data' => array_merge(
                $formInput,
                $validated['form'] ?? [],
                ['penjemput' => $pickups],
                $uploadedDocs,
                $passportPhoto !== null ? ['pas_foto_3x4' => $passportPhoto] : [],
            ),
            'fee_agreement_data' => array_merge(
                $feeInput,
                $validated['fee'] ?? [],
                [
                    'agreements' => array_fill_keys($agreementKeys, true),
                    'signed_at' => now()->toDateString(),
                ],
            ),
            'has_alumni_siblings' => $hasAlumniSiblings,
            'alumni_sibling_names' => $alumniSiblingNames,
            'status' => PpdbRegistration::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        ActivityLogger::log(
            'complete',
            'SPMB',
            "Pendaftar {$registration->full_name} ({$registration->level}) - No. {$registration->registration_number} telah mengisi Formulir Lengkap & Surat Kesanggupan Biaya",
            ['after' => ['status' => PpdbRegistration::STATUS_COMPLETED]],
        );

        return redirect()
            ->route('spmb.full-form.success', $registration->registration_code)
            ->with('success', 'Alhamdulillah, Formulir Lengkap & Surat Kesanggupan Biaya berhasil dikirim.');
    }

    /**
     * Show the completion page with the level- and gender-specific WhatsApp group.
     */
    public function success(string $code): View|RedirectResponse
    {
        $registration = $this->findByCode($code);

        if (! $registration->hasCompletedFullForm()) {
            return redirect()->route('spmb.full-form', $registration->registration_code);
        }

        $publicData = app(FrontController::class)->publicViewData();

        return view('spmb.full-form-success', $publicData + [
            'registration' => $registration,
            'whatsappGroupUrl' => $registration->whatsappGroupUrl(),
        ]);
    }

    /**
     * Show the browser-printable copy of the submitted documents.
     */
    public function print(string $code): View|RedirectResponse
    {
        $registration = $this->findByCode($code);

        if (! $registration->hasCompletedFullForm()) {
            return redirect()->route('spmb.full-form', $registration->registration_code);
        }

        return SpmbDocument::view(
            $registration,
            route('spmb.full-form.success', $registration->registration_code),
            route('spmb.full-form.pdf', $registration->registration_code),
        );
    }

    /**
     * Download the submitted documents as a PDF file.
     */
    public function pdf(string $code): Response|RedirectResponse
    {
        $registration = $this->findByCode($code);

        if (! $registration->hasCompletedFullForm()) {
            return redirect()->route('spmb.full-form', $registration->registration_code);
        }

        return SpmbDocument::download($registration);
    }

    /**
     * Find a registration by its secure access code.
     */
    private function findByCode(string $code): PpdbRegistration
    {
        return PpdbRegistration::query()
            ->where('registration_code', $code)
            ->firstOrFail();
    }

    /**
     * Read a nested request array safely, returning an empty array when absent.
     *
     * @return array<string, mixed>
     */
    private function arrayInput(Request $request, string $key): array
    {
        $value = $request->input($key, []);

        return is_array($value) ? $value : [];
    }

    /**
     * Normalize and filter the authorized pickup rows.
     *
     * @param  list<array<string, mixed>>  $rows
     * @return list<array<string, string>>
     */
    private function sanitizePickups(array $rows): array
    {
        return collect($rows)
            ->map(fn (array $row): array => array_map(fn ($value): string => trim((string) $value), $row))
            ->filter(fn (array $row): bool => ($row['nama'] ?? '') !== '')
            ->values()
            ->all();
    }

    /**
     * Store any newly uploaded supporting documents and retain existing ones.
     *
     * @return array<string, string>
     */
    private function storeUploadedDocuments(
        Request $request,
        PpdbRegistration $registration,
        ImageCompressor $imageCompressor,
    ): array {
        $uploadedDocs = [];

        foreach (SpmbForm::DOCUMENT_KEYS as $docKey) {
            if ($request->hasFile($docKey)) {
                $file = $request->file($docKey);
                $isImage = str_starts_with((string) $file->getMimeType(), 'image/');
                $path = null;

                if ($isImage) {
                    try {
                        $path = $imageCompressor->storeUploaded($file, 'ppdb/documents/'.$docKey, ImageCompressor::CONTENT_MAX_DIMENSION);
                    } catch (Throwable $exception) {
                        Log::warning('Gagal mengompresi dokumen SPMB, memakai unggahan asli.', [
                            'document' => $docKey,
                            'registration' => $registration->registration_number,
                            'error' => $exception->getMessage(),
                        ]);
                    }
                }

                if (! $path) {
                    try {
                        $path = $file->store('ppdb/documents/'.$docKey, 'public');
                    } catch (Throwable $exception) {
                        Log::error('Gagal menyimpan dokumen SPMB.', [
                            'document' => $docKey,
                            'registration' => $registration->registration_number,
                            'error' => $exception->getMessage(),
                        ]);
                    }
                }

                if ($path) {
                    $uploadedDocs[$docKey] = $path;
                } elseif (! empty($registration->full_form_data[$docKey])) {
                    $uploadedDocs[$docKey] = (string) $registration->full_form_data[$docKey];
                }
            } elseif (! empty($registration->full_form_data[$docKey])) {
                $uploadedDocs[$docKey] = (string) $registration->full_form_data[$docKey];
            }
        }

        return $uploadedDocs;
    }

    /**
     * Store the optional KB-TK 3x4 passport photo and retain the existing one.
     */
    private function storePassportPhoto(
        Request $request,
        PpdbRegistration $registration,
        ImageCompressor $imageCompressor,
    ): ?string {
        $existing = $registration->passportPhotoPath();

        if (! $request->hasFile('pas_foto_3x4')) {
            return $existing;
        }

        $file = $request->file('pas_foto_3x4');
        $path = null;

        try {
            $path = $imageCompressor->storeUploaded(
                $file,
                'ppdb/photos',
                ImageCompressor::PASSPORT_PHOTO_MAX_DIMENSION,
            );
        } catch (Throwable $exception) {
            Log::warning('Gagal mengompresi pas foto SPMB, memakai unggahan asli.', [
                'registration' => $registration->registration_number,
                'error' => $exception->getMessage(),
            ]);
        }

        if (! $path) {
            try {
                $path = $file->store('ppdb/photos', 'public');
            } catch (Throwable $exception) {
                Log::error('Gagal menyimpan pas foto SPMB.', [
                    'registration' => $registration->registration_number,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return $path ?: $existing;
    }
}
