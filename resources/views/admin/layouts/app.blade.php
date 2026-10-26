<!DOCTYPE html>
<html lang="id" class="max-w-[100vw] overflow-x-clip">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') &middot; Admin Panel - Cahaya Ilmu Islamic School</title>

    <link rel="icon" type="image/png" href="/assets/images/logo-utama.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen max-w-[100vw] overflow-x-clip bg-slate-50 font-sans text-slate-800 antialiased">
    <div class="flex min-h-screen">
        <div id="sidebar-backdrop"
             class="fixed inset-0 z-40 hidden bg-slate-900/60 backdrop-blur-sm lg:hidden"
             onclick="toggleSidebar()"></div>

        <aside id="sidebar"
               class="fixed inset-y-0 left-0 z-50 flex h-screen w-72 -translate-x-full flex-col bg-slate-900 transition-transform duration-200 ease-in-out lg:z-40 lg:translate-x-0">
            <div class="flex h-20 shrink-0 items-center gap-3 border-b border-white/10 px-5">
                <div class="grid h-11 w-16 shrink-0 place-items-center overflow-hidden rounded-2xl bg-white p-1.5 shadow-sm">
                    <img src="/assets/images/logo-utama.png" alt="Logo Cahaya Ilmu Islamic School" class="h-full w-full object-contain">
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold leading-4 text-white">Admin Panel - Cahaya Ilmu Islamic School</p>
                    <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-slate-400">Balikpapan</p>
                </div>
                <button type="button"
                        class="rounded-lg p-1.5 text-slate-400 hover:bg-white/10 hover:text-white lg:hidden"
                        onclick="toggleSidebar()">
                    <span class="sr-only">Tutup menu</span>
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="min-h-0 flex-1 space-y-6 overflow-y-auto px-3 py-5">
                <div>
                    <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">Menu Utama</p>
                    <div class="space-y-1">
                        <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            <x-slot:icon>
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                </svg>
                            </x-slot:icon>
                            Ringkasan
                        </x-admin.nav-link>
                    </div>
                </div>

                <div>
                    <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">Manajemen</p>
                    <div class="space-y-1">
                        <x-admin.nav-link :href="route('admin.ppdb.index')" :active="request()->routeIs('admin.ppdb.*')">
                            <x-slot:icon>
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                            </x-slot:icon>
                            Data SPMB
                        </x-admin.nav-link>

                        <x-admin.nav-link :href="route('admin.ppdb-settings.index')" :active="request()->routeIs('admin.ppdb-settings.*')">
                            <x-slot:icon>
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </x-slot:icon>
                            Pengaturan SPMB
                        </x-admin.nav-link>

                        <x-admin.nav-link :href="route('admin.posts.index')" :active="request()->routeIs('admin.posts.*')">
                            <x-slot:icon>
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                                </svg>
                            </x-slot:icon>
                            Info Sekolah
                        </x-admin.nav-link>

                        <x-admin.nav-link :href="route('admin.galleries.index')" :active="request()->routeIs('admin.galleries.*')">
                            <x-slot:icon>
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </x-slot:icon>
                            Galeri Sekolah
                        </x-admin.nav-link>

                        <x-admin.nav-link :href="route('admin.agendas.index')" :active="request()->routeIs('admin.agendas.*')">
                            <x-slot:icon>
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                            </x-slot:icon>
                            Agenda Sekolah
                        </x-admin.nav-link>
                    </div>
                </div>

                <div>
                    <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">Sistem</p>
                    <div class="space-y-1">
                        @if (auth()->user()->isSuperAdmin())
                            <x-admin.nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                <x-slot:icon>
                                    <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                </x-slot:icon>
                                Kelola Admin
                            </x-admin.nav-link>
                        @endif

                        <x-admin.nav-link :href="route('admin.activity-logs.index')" :active="request()->routeIs('admin.activity-logs.*')">
                            <x-slot:icon>
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </x-slot:icon>
                            Riwayat Aktivitas
                        </x-admin.nav-link>

                        <x-admin.nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                            <x-slot:icon>
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </x-slot:icon>
                            Pengaturan Web
                        </x-admin.nav-link>
                    </div>
                </div>
            </nav>

            <div class="shrink-0 border-t border-white/10 p-4">
                <div class="flex items-center gap-3 rounded-xl bg-white/5 p-3">
                    <div class="grid size-9 shrink-0 place-items-center rounded-full bg-gradient-to-br from-rose-500 to-amber-500 text-sm font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}" class="mt-2">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-400 transition hover:bg-white/5 hover:text-white">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        Keluar dari akun
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col lg:pl-72">
            <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur">
                <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                    <button type="button"
                            class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 lg:hidden"
                            onclick="toggleSidebar()">
                        <span class="sr-only">Buka menu</span>
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-900">@yield('title', 'Dashboard')</p>
                        <p class="hidden truncate text-xs text-slate-500 sm:block">@yield('subtitle', 'Panel administrasi Cahaya Ilmu Islamic School')</p>
                    </div>

                    <div class="ml-auto flex shrink-0 items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-700">
                            <span class="size-1.5 rounded-full bg-rose-500"></span>
                            @if (auth()->user()->isSuperAdmin())
                                Login sebagai: Superadmin
                            @else
                                Login sebagai: Admin {{ auth()->user()->getLevel() }}
                            @endif
                        </span>
                        <span class="hidden rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-500 md:inline-flex">
                            {{ now()->format('d M Y') }}
                        </span>
                        <a href="{{ url('/') }}"
                           target="_blank"
                           rel="noopener"
                           class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-medium text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                            <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                            <span class="hidden min-[380px]:inline">Lihat Website</span>
                        </a>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-5 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                        <svg class="mt-0.5 size-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="px-4 pb-6 text-center text-xs text-slate-400 sm:px-6 lg:px-8 lg:text-left">
                &copy; {{ now()->year }} Cahaya Ilmu Islamic School Balikpapan
            </footer>
        </div>
    </div>

    <script>
        function toggleSidebar(forceClose = false) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (!sidebar || !backdrop) return;

            const isClosed = sidebar.classList.contains('-translate-x-full');
            const shouldOpen = forceClose ? false : isClosed;

            sidebar.classList.toggle('-translate-x-full', !shouldOpen);
            backdrop.classList.toggle('hidden', !shouldOpen);
            document.body.classList.toggle('overflow-hidden', shouldOpen);
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                toggleSidebar(true);
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                toggleSidebar(true);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
