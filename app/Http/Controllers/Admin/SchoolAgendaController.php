<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolAgenda;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SchoolAgendaController extends Controller
{
    /**
     * Display the school agenda listing and creation form.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $userLevel = $user->getLevel();

        $levels = $isSuperAdmin ? ['Umum', 'TK', 'SD', 'SMP', 'SMA'] : array_values(array_filter([$userLevel]));
        $activeLevel = $isSuperAdmin ? $request->string('level')->toString() : (string) $userLevel;
        $isLevelFilterActive = in_array($activeLevel, $levels, true);

        $baseScopeQuery = SchoolAgenda::query()
            ->when(! $isSuperAdmin, fn ($query) => $query->where('level', $userLevel));

        $agendas = (clone $baseScopeQuery)
            ->when($isSuperAdmin && $isLevelFilterActive, fn ($query) => $query->where('level', $activeLevel))
            ->orderBy('event_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $levelCounts = (clone $baseScopeQuery)
            ->selectRaw('level, count(*) as total')
            ->groupBy('level')
            ->pluck('total', 'level');

        return view('admin.agendas.index', [
            'agendas' => $agendas,
            'levels' => $levels,
            'activeLevel' => $isLevelFilterActive ? $activeLevel : null,
            'levelCounts' => $levelCounts,
            'totalAgendas' => (clone $baseScopeQuery)->count(),
        ]);
    }

    /**
     * Store a new school agenda item.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAgenda($request);
        $validated['is_published'] = $request->boolean('is_published', true);

        $agenda = SchoolAgenda::create($validated);

        ActivityLogger::log(
            'create',
            'Info Sekolah',
            "Menambahkan agenda sekolah baru: {$agenda->title} ({$agenda->level})",
            [
                'after' => [
                    'title' => $agenda->title,
                    'level' => $agenda->level,
                    'event_date' => $agenda->event_date->toDateString(),
                ],
            ],
        );

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda kegiatan sekolah berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the given school agenda item.
     */
    public function edit(SchoolAgenda $agenda): View
    {
        $this->authorizeAgendaAccess($agenda);

        $user = auth()->user();

        return view('admin.agendas.edit', [
            'agenda' => $agenda,
            'levels' => $user->isSuperAdmin() ? ['Umum', 'TK', 'SD', 'SMP', 'SMA'] : array_values(array_filter([$user->getLevel()])),
        ]);
    }

    /**
     * Update the given school agenda item.
     */
    public function update(Request $request, SchoolAgenda $agenda): RedirectResponse
    {
        $this->authorizeAgendaAccess($agenda);

        $before = [
            'title' => $agenda->title,
            'level' => $agenda->level,
            'event_date' => $agenda->event_date->toDateString(),
        ];

        $validated = $this->validateAgenda($request);
        $validated['is_published'] = $request->boolean('is_published');

        $agenda->update($validated);

        ActivityLogger::log(
            'update',
            'Info Sekolah',
            "Memperbarui agenda sekolah: {$agenda->title} ({$agenda->level})",
            [
                'before' => $before,
                'after' => [
                    'title' => $agenda->title,
                    'level' => $agenda->level,
                    'event_date' => $agenda->event_date->toDateString(),
                ],
            ],
        );

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda kegiatan sekolah berhasil diperbarui.');
    }

    /**
     * Delete the given school agenda item.
     */
    public function destroy(SchoolAgenda $agenda): RedirectResponse
    {
        $this->authorizeAgendaAccess($agenda);

        $snapshot = [
            'title' => $agenda->title,
            'level' => $agenda->level,
            'event_date' => $agenda->event_date->toDateString(),
        ];

        $agenda->delete();

        ActivityLogger::log(
            'delete',
            'Info Sekolah',
            "Menghapus agenda sekolah: {$snapshot['title']} ({$snapshot['level']})",
            ['before' => $snapshot],
        );

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda kegiatan sekolah berhasil dihapus.');
    }

    /**
     * Ensure level-scoped admins can only access agendas belonging to their level.
     */
    private function authorizeAgendaAccess(SchoolAgenda $agenda): void
    {
        $user = auth()->user();

        if (! $user->isSuperAdmin() && $agenda->level !== $user->getLevel()) {
            abort(403, 'Anda tidak memiliki akses ke agenda jenjang ini.');
        }
    }

    /**
     * Validate the school agenda request.
     *
     * @return array<string, mixed>
     */
    private function validateAgenda(Request $request): array
    {
        $user = auth()->user();

        if (! $user->isSuperAdmin()) {
            $request->merge(['level' => $user->getLevel()]);
        }

        $allowedLevels = $user->isSuperAdmin()
            ? ['Umum', 'TK', 'SD', 'SMP', 'SMA']
            : array_values(array_filter([$user->getLevel()]));

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'level' => ['required', Rule::in($allowedLevels)],
            'event_date' => ['required', 'date'],
            'time_label' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
