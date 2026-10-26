<!DOCTYPE html>
<html lang="id" class="overflow-x-hidden max-w-[100vw]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Panel - Cahaya Ilmu Islamic School Balikpapan - '.config('sekolah.motto'))</title>

    <link rel="icon" type="image/png" href="/assets/images/logo-utama.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden max-w-[100vw] bg-slate-50 font-sans text-slate-800 antialiased">
    <main class="flex min-h-screen items-center justify-center bg-[radial-gradient(ellipse_at_top_right,_rgba(225,29,72,0.08),_transparent_45%),radial-gradient(ellipse_at_bottom_left,_rgba(245,158,11,0.08),_transparent_45%)] p-4 sm:p-6">
        <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-900/5 lg:grid-cols-2">
            <div class="relative hidden flex-col justify-between overflow-hidden bg-slate-900 p-10 text-white lg:flex">
                <div class="pointer-events-none absolute -right-24 -top-24 size-72 rounded-full bg-rose-500/20 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 -left-20 size-80 rounded-full bg-amber-400/10 blur-3xl"></div>

                <div class="relative">
                    <div class="flex items-center gap-3">
                        <div class="grid h-16 w-24 shrink-0 place-items-center overflow-hidden rounded-2xl bg-white p-2 shadow-sm">
                            <img src="/assets/images/logo-utama.png" alt="Logo Cahaya Ilmu Islamic School" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold leading-4 text-white">Admin Panel - Cahaya Ilmu Islamic School</p>
                            <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-amber-300/80">Balikpapan</p>
                        </div>
                    </div>

                    <h1 class="mt-12 text-3xl font-bold leading-tight">
                        Selamat datang<br>kembali.
                    </h1>
                    <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-300/80">
                        Kelola pendaftaran SPMB, info sekolah, galeri, dan informasi sekolah dalam satu tempat.
                    </p>
                    <p class="mt-4 text-sm font-medium italic leading-6 text-amber-300/90">
                        &ldquo;{{ config('sekolah.motto') }}&rdquo;
                    </p>
                </div>

                <div class="relative mt-12 rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs leading-relaxed text-slate-300/70">
                        Cahaya Ilmu Islamic School Balikpapan<br>
                        TK &middot; SD &middot; SMP &middot; SMA
                    </p>
                </div>
            </div>

            <div class="p-6 sm:p-10 lg:p-12">
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <div class="grid h-11 w-16 shrink-0 place-items-center overflow-hidden rounded-xl border border-slate-200 bg-white p-1.5 shadow-sm">
                        <img src="/assets/images/logo-utama.png" alt="Logo Cahaya Ilmu Islamic School" class="h-full w-full object-contain">
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold leading-4 text-slate-900">Admin Panel - Cahaya Ilmu Islamic School</p>
                        <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-slate-400">Balikpapan</p>
                    </div>
                </div>

                @yield('content')
            </div>
        </div>
    </main>

    @stack('scripts')
</body>
</html>
