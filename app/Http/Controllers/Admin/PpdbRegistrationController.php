<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistration;
use App\Models\ReferralCode;
use App\Models\SchoolSetting;
use App\Support\ActivityLogger;
use App\Support\SpmbDocument;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PpdbRegistrationController extends Controller
{
    /**
     * Display the SPMB registrations with level, status, and keyword filters.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $userLevel = $user->getLevel();

        $levels = $isSuperAdmin ? ['TK', 'SD', 'SMP', 'SMA'] : array_values(array_filter([$userLevel]));
        $statusLabels = PpdbRegistration::statusLabels();
        $statuses = array_keys($statusLabels);

        $activeLevel = $isSuperAdmin ? $request->string('level')->toString() : (string) $userLevel;
        $isLevelFilterActive = in_array($activeLevel, $levels, true);

        $rawStatus = $request->string('status')->toString();
        $activeStatus = match ($rawStatus) {
            'Baru' => PpdbRegistration::STATUS_PENDING,
            'Diverifikasi' => PpdbRegistration::STATUS_VERIFIED,
            'Diterima' => PpdbRegistration::STATUS_ACCEPTED,
            'Ditolak' => PpdbRegistration::STATUS_REJECTED,
            default => $rawStatus,
        };
        $isStatusFilterActive = in_array($activeStatus, $statuses, true);

        $search = trim($request->string('q')->toString());

        $registrations = $this->filteredQuery($request)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $baseScopeQuery = PpdbRegistration::query()
            ->when(! $isSuperAdmin, fn (Builder $query) => $query->where('level', $userLevel));

        $levelCounts = (clone $baseScopeQuery)
            ->selectRaw('level, count(*) as total')
            ->groupBy('level')
            ->pluck('total', 'level');

        $statusCounts = (clone $baseScopeQuery)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $quotaLevels = [];

        foreach ($levels as $level) {
            $quotaLevels[strtolower($level)] = $level;
        }

        $settings = SchoolSetting::pluck('value', 'key');
        $quotaSummary = SchoolSetting::ppdbQuotaSummary($settings, $levels);

        return view('admin.ppdb.index', [
            'registrations' => $registrations,
            'levels' => $levels,
            'statuses' => $statuses,
            'statusLabels' => $statusLabels,
            'activeLevel' => $isLevelFilterActive ? $activeLevel : null,
            'activeStatus' => $isStatusFilterActive ? $activeStatus : null,
            'search' => $search,
            'levelCounts' => $levelCounts,
            'statusCounts' => $statusCounts,
            'totalRegistrations' => (clone $baseScopeQuery)->count(),
            'quotaSummary' => $quotaSummary,
            'quotaLevels' => $quotaLevels,
            'settings' => $settings,
        ]);
    }

    /**
     * Show the detailed view of an SPMB registration.
     */
    public function show(PpdbRegistration $ppdbRegistration): View
    {
        $this->authorizeRegistrationAccess($ppdbRegistration);

        return view('admin.ppdb.show', [
            'registration' => $ppdbRegistration,
            'whatsappGroupUrl' => $ppdbRegistration->whatsappGroupUrl(),
            'feeOptions' => $ppdbRegistration->feeOptions(),
        ]);
    }

    /**
     * Display a printable HTML view of the full SPMB registration document.
     */
    public function print(PpdbRegistration $ppdbRegistration): View
    {
        $this->authorizeRegistrationAccess($ppdbRegistration);

        ActivityLogger::log(
            'print',
            'SPMB',
            "Mencetak dokumen formulir lengkap SPMB {$ppdbRegistration->full_name} ({$ppdbRegistration->level}) - No. {$ppdbRegistration->registration_number}",
        );

        return SpmbDocument::view(
            $ppdbRegistration,
            route('admin.ppdb.index', ['level' => $ppdbRegistration->level]),
            route('admin.ppdb.pdf', $ppdbRegistration),
        );
    }

    /**
     * Download the official A4 PDF of the registration and fee agreement document.
     */
    public function pdf(PpdbRegistration $ppdbRegistration): Response
    {
        $this->authorizeRegistrationAccess($ppdbRegistration);

        ActivityLogger::log(
            'print',
            'SPMB',
            "Mengunduh PDF formulir lengkap SPMB {$ppdbRegistration->full_name} ({$ppdbRegistration->level}) - No. {$ppdbRegistration->registration_number}",
        );

        return SpmbDocument::download($ppdbRegistration);
    }

    /**
     * Export the filtered SPMB registrations as an Excel-compatible UTF-8 CSV file.
     */
    public function export(Request $request): StreamedResponse
    {
        $registrations = $this->filteredQuery($request)->latest()->get();
        $filename = 'data-spmb-cahaya-ilmu-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($registrations): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            // Write UTF-8 BOM so Microsoft Excel opens the CSV with proper encoding.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No. Pendaftaran',
                'Kode Akses Tahap 2',
                'Nama Lengkap',
                'Jenis Kelamin',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Usia (Tahun)',
                'Jenjang',
                'Asal Sekolah',
                'Nama Orang Tua / Wali',
                'WhatsApp',
                'Alamat',
                'Biaya Pendaftaran (Rp)',
                'Kode Referral',
                'Diskon (Rp)',
                'Status',
                'Tanggal Diverifikasi',
                'Tanggal Selesai Tahap 2',
                'Tanggal Daftar',
            ]);

            foreach ($registrations as $registration) {
                fputcsv($handle, [
                    $registration->registration_number,
                    $registration->registration_code,
                    $registration->full_name,
                    $registration->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                    $registration->birth_place ?? '-',
                    $registration->birth_date ? $registration->birth_date->format('Y-m-d') : '-',
                    $registration->age() ?? '-',
                    $registration->level,
                    $registration->previous_school ?? '-',
                    $registration->parent_name,
                    $registration->whatsapp,
                    $registration->address,
                    $registration->final_fee,
                    $registration->referral_code ?? '-',
                    $registration->discount_amount,
                    $registration->statusLabel(),
                    $registration->verified_at?->format('Y-m-d H:i') ?? '-',
                    $registration->completed_at?->format('Y-m-d H:i') ?? '-',
                    $registration->created_at?->format('Y-m-d H:i') ?? '-',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Update the status of a registration.
     */
    public function updateStatus(Request $request, PpdbRegistration $ppdbRegistration): RedirectResponse
    {
        $this->authorizeRegistrationAccess($ppdbRegistration);

        $allowedStatuses = [
            PpdbRegistration::STATUS_PENDING,
            PpdbRegistration::STATUS_VERIFIED,
            PpdbRegistration::STATUS_COMPLETED,
            PpdbRegistration::STATUS_ACCEPTED,
            PpdbRegistration::STATUS_REJECTED,
            'Baru',
            'Diverifikasi',
            'Diterima',
            'Ditolak',
        ];

        $validated = $request->validate([
            'status' => ['required', Rule::in($allowedStatuses)],
        ]);

        $newStatus = $validated['status'];
        $previousStatus = $ppdbRegistration->status;
        $updates = ['status' => $newStatus];

        if (in_array($newStatus, [PpdbRegistration::STATUS_VERIFIED, 'Diverifikasi'], true) && $ppdbRegistration->verified_at === null) {
            $updates['verified_at'] = now();
        }

        if ($newStatus === PpdbRegistration::STATUS_COMPLETED && $ppdbRegistration->completed_at === null) {
            $updates['completed_at'] = now();
        }

        $isRejecting = in_array($newStatus, [PpdbRegistration::STATUS_REJECTED, 'Ditolak'], true)
            && ! in_array($previousStatus, [PpdbRegistration::STATUS_REJECTED, 'Ditolak'], true);

        $releasedReferralCode = DB::transaction(function () use ($ppdbRegistration, $updates, $isRejecting): ?string {
            $ppdbRegistration->update($updates);

            return $isRejecting ? $this->releaseReferralQuota($ppdbRegistration) : null;
        });

        ActivityLogger::log(
            'verify',
            'SPMB',
            "Mengubah status pendaftaran SPMB {$ppdbRegistration->full_name} ({$ppdbRegistration->level}) menjadi {$ppdbRegistration->status}",
            [
                'before' => ['status' => $previousStatus],
                'after' => array_filter([
                    'status' => $ppdbRegistration->status,
                    'referral_released' => $releasedReferralCode,
                ], fn (mixed $value): bool => $value !== null),
            ],
        );

        return back()->with('success', "Status pendaftaran {$ppdbRegistration->full_name} berhasil diperbarui menjadi {$ppdbRegistration->status}.");
    }

    /**
     * Delete a registration.
     */
    public function destroy(PpdbRegistration $ppdbRegistration): RedirectResponse
    {
        $this->authorizeRegistrationAccess($ppdbRegistration);

        $snapshot = [
            'registration_number' => $ppdbRegistration->registration_number,
            'full_name' => $ppdbRegistration->full_name,
            'level' => $ppdbRegistration->level,
            'status' => $ppdbRegistration->status,
        ];

        $wasRejected = in_array($ppdbRegistration->status, [PpdbRegistration::STATUS_REJECTED, 'Ditolak'], true);

        $releasedReferralCode = DB::transaction(function () use ($ppdbRegistration, $wasRejected): ?string {
            $released = $wasRejected ? null : $this->releaseReferralQuota($ppdbRegistration);

            $ppdbRegistration->delete();

            return $released;
        });

        ActivityLogger::log(
            'delete',
            'SPMB',
            "Menghapus data pendaftar SPMB {$snapshot['full_name']} ({$snapshot['level']}) - No. {$snapshot['registration_number']}",
            [
                'before' => array_filter([
                    ...$snapshot,
                    'referral_released' => $releasedReferralCode,
                ], fn (mixed $value): bool => $value !== null),
            ],
        );

        return back()->with('success', 'Data pendaftar berhasil dihapus.');
    }

    /**
     * Release one referral quota when a registration is rejected or deleted.
     */
    private function releaseReferralQuota(PpdbRegistration $registration): ?string
    {
        $code = ReferralCode::normalize((string) $registration->referral_code);

        if ($code === '') {
            return null;
        }

        return DB::transaction(function () use ($registration, $code): ?string {
            $referral = ReferralCode::query()
                ->where('code', $code)
                ->lockForUpdate()
                ->first();

            if (! $referral instanceof ReferralCode) {
                return null;
            }

            $updates = [];

            if ($referral->used_count > 0) {
                $updates['used_count'] = $referral->used_count - 1;
            }

            if ((int) $referral->used_by_registration_id === (int) $registration->getKey()) {
                $updates['used_by_registration_id'] = null;
            }

            if ($updates === []) {
                return null;
            }

            $referral->update($updates);

            return $referral->code;
        });
    }

    /**
     * Ensure level-scoped admins can only modify registrations from their own level.
     */
    private function authorizeRegistrationAccess(PpdbRegistration $ppdbRegistration): void
    {
        $user = auth()->user();

        if (! $user->isSuperAdmin() && $ppdbRegistration->level !== $user->getLevel()) {
            abort(403, 'Anda tidak memiliki akses ke pendaftar jenjang ini.');
        }
    }

    /**
     * Build the filtered Eloquent query for SPMB registrations.
     *
     * @return Builder<PpdbRegistration>
     */
    private function filteredQuery(Request $request): Builder
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $userLevel = $user->getLevel();

        $levels = ['TK', 'SD', 'SMP', 'SMA'];
        $statuses = array_keys(PpdbRegistration::statusLabels());

        $activeLevel = $request->string('level')->toString();
        $isLevelFilterActive = in_array($activeLevel, $levels, true);

        $rawStatus = $request->string('status')->toString();
        $activeStatus = match ($rawStatus) {
            'Baru' => PpdbRegistration::STATUS_PENDING,
            'Diverifikasi' => PpdbRegistration::STATUS_VERIFIED,
            'Diterima' => PpdbRegistration::STATUS_ACCEPTED,
            'Ditolak' => PpdbRegistration::STATUS_REJECTED,
            default => $rawStatus,
        };
        $isStatusFilterActive = in_array($activeStatus, $statuses, true);

        $search = trim($request->string('q')->toString());

        return PpdbRegistration::query()
            ->when(! $isSuperAdmin, fn (Builder $query) => $query->where('level', $userLevel))
            ->when($isSuperAdmin && $isLevelFilterActive, fn (Builder $query) => $query->where('level', $activeLevel))
            ->when($isStatusFilterActive, function (Builder $query) use ($activeStatus, $rawStatus): void {
                $query->where(function (Builder $subQuery) use ($activeStatus, $rawStatus): void {
                    $subQuery->where('status', $activeStatus);
                    if ($rawStatus !== '' && $rawStatus !== $activeStatus) {
                        $subQuery->orWhere('status', $rawStatus);
                    }
                });
            })
            ->when($search !== '', function (Builder $query) use ($search): void {
                $like = '%'.$search.'%';

                $query->where(function (Builder $subQuery) use ($like): void {
                    $subQuery->where('registration_number', 'like', $like)
                        ->orWhere('registration_code', 'like', $like)
                        ->orWhere('full_name', 'like', $like)
                        ->orWhere('parent_name', 'like', $like)
                        ->orWhere('whatsapp', 'like', $like)
                        ->orWhere('previous_school', 'like', $like);
                });
            });
    }
}
