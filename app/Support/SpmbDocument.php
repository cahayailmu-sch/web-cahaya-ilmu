<?php

namespace App\Support;

use App\Models\PpdbRegistration;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

/**
 * Builds the printable "Formulir Pendaftaran Lengkap" and
 * "Surat Pernyataan Kesanggupan Biaya" document for a registration.
 */
class SpmbDocument
{
    /**
     * Render the browser-printable (A4) document.
     */
    public static function view(PpdbRegistration $registration, ?string $backUrl = null, ?string $pdfUrl = null): View
    {
        return view('spmb.document', self::data($registration, false) + [
            'backUrl' => $backUrl,
            'pdfUrl' => $pdfUrl,
        ]);
    }

    /**
     * Download the document as an A4 PDF file.
     */
    public static function download(PpdbRegistration $registration): Response
    {
        return Pdf::loadView('spmb.document', self::data($registration, true) + [
            'backUrl' => null,
            'pdfUrl' => null,
        ])
            ->setPaper('a4', 'portrait')
            ->download(self::filename($registration));
    }

    /**
     * Build the PDF file name for a registration.
     */
    public static function filename(PpdbRegistration $registration): string
    {
        return 'Formulir-SPMB-'.$registration->registration_number.'-'.Str::slug($registration->full_name).'.pdf';
    }

    /**
     * Build the shared view data for the document.
     *
     * @return array<string, mixed>
     */
    private static function data(PpdbRegistration $registration, bool $isPdf): array
    {
        $settings = SchoolSetting::pluck('value', 'key');
        $logoPath = public_path('assets/images/logo-utama.png');

        return [
            'registration' => $registration,
            'sections' => SpmbForm::fullFormSections($registration->level),
            'feeSections' => SpmbForm::feeSections($registration->level),
            'agreements' => SpmbForm::agreements($registration->level),
            'fees' => $registration->feeOptions(),
            'pickupColumns' => config('spmb.pickup_columns', []),
            'tahunAjaran' => $settings['tahun_ajaran'] ?? (now()->year.'/'.(now()->year + 1)),
            'foundationName' => $settings['foundation_name'] ?? 'Yayasan Ath-Thoifah Al-Manshuroh Balikpapan',
            'schoolAddress' => $settings['alamat_sekolah'] ?? 'Balikpapan, Kalimantan Timur',
            'isPdf' => $isPdf,
            'logoSrc' => $isPdf
                ? (is_file($logoPath) ? 'data:image/png;base64,'.base64_encode((string) file_get_contents($logoPath)) : null)
                : asset('assets/images/logo-utama.png'),
        ];
    }
}
