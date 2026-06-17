<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TK WINFIELD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireStyles
    <style>
        :root {
            --portal-card: #ffffff;
            --portal-line: #dde6f1;
            --portal-text: #1f2937;
            --portal-muted: #6f7f92;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            /* Use a flat background to avoid visible bands behind large cards. */
            background: #f3f6fb;
            color: var(--portal-text);
        }

        .portal-mobile-menu {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height .35s ease, opacity .25s ease, margin-top .35s ease, padding-top .35s ease, border-color .35s ease;
        }

        .portal-mobile-menu.open {
            max-height: 420px;
            opacity: 1;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .portal-stat-label,
        .portal-stat-value,
        .portal-empty,
        .portal-alert {
            overflow-wrap: anywhere;
            word-break: break-word;
        }



        .portal-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 22px;
        }

        .portal-stat {
            border-radius: 22px;
            padding: 22px;
            border: 1px solid var(--portal-line);
            background: #fff;
            box-shadow: 0 12px 28px rgba(41, 60, 89, 0.06);
        }

        .portal-stat.soft-blue { background: #eaf4ff; }
        .portal-stat.soft-yellow { background: #fff6dc; }
        .portal-stat.soft-green { background: #ebf9ef; }

        .portal-stat-label {
            color: #5d6f84;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .portal-stat-value {
            font-size: 30px;
            font-weight: 800;
            color: #1d2533;
            margin-bottom: 6px;
        }

        .laporan-page-wrap { max-width: 1020px; margin: 0 auto; }
        .portal-pagination .pagination { margin-top: 18px; }
        .portal-pagination svg { width: 18px; height: 18px; }

        @media (max-width: 1100px) {
            .portal-stat-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .laporan-page-wrap {
                width: calc(100% + 24px);
                margin-left: -12px;
                margin-right: -12px;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="m-0 text-[var(--portal-text)]">
    @php
        $portalUser = auth()->user();
        $portalSiswa = $portalUser?->siswa;
    @endphp

    <header class="sticky top-0 z-[1000] bg-gradient-to-br from-[#7f1d1d] to-[#4e1010] text-white shadow-[0_8px_20px_rgba(36,56,79,0.14)]">
        <div class="w-full px-5 py-3">
            <div class="flex items-center justify-between gap-4 lg:hidden">
                <div class="whitespace-nowrap text-[22px] font-extrabold tracking-[0.03em] sm:text-[24px]">TK WINFIELD</div>
                <button type="button" id="portalToggle" aria-label="Buka menu" class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-white/95 text-xl text-[#7f1d1d] shadow-sm transition duration-300 hover:scale-105">
                    <i class="fas fa-bars" id="portalToggleIcon"></i>
                </button>
            </div>

            <div class="hidden items-center gap-5 lg:flex">
                <div class="mr-4 whitespace-nowrap text-[18px] font-extrabold tracking-[0.03em] lg:text-[25px]">TK WINFIELD</div>

                <nav class="flex flex-1 flex-wrap items-center justify-center gap-2 lg:gap-3">
                    <a href="{{ route('orang-tua.profil.index') }}" wire:navigate class="rounded-full px-3 py-2 text-[15px] font-semibold text-white/90 transition hover:bg-white/15 hover:text-white {{ request()->routeIs('orang-tua.profil.*') ? 'bg-white/15 text-white' : '' }}">Profil Siswa</a>
                    <a href="{{ route('orang-tua.laporan.index') }}" wire:navigate class="rounded-full px-3 py-2 text-[15px] font-semibold text-white/90 transition hover:bg-white/15 hover:text-white {{ request()->routeIs('orang-tua.laporan.*') ? 'bg-white/15 text-white' : '' }}">Perkembangan</a>
                    <a href="{{ route('orang-tua.pengumuman.index') }}" wire:navigate class="rounded-full px-3 py-2 text-[15px] font-semibold text-white/90 transition hover:bg-white/15 hover:text-white {{ request()->routeIs('orang-tua.pengumuman.*') ? 'bg-white/15 text-white' : '' }}">Pengumuman</a>
                </nav>

                <div class="ml-auto flex items-center gap-3">
                    <div class="flex min-h-[46px] min-w-[190px] max-w-[240px] items-center justify-center rounded-2xl border border-white/70 bg-white/15 px-4 py-2 text-center text-white">
                        <strong class="block break-words text-center text-[13px] leading-[1.25]">{{ $portalSiswa?->nama ?? $portalUser->username }}</strong>
                    </div>
                    <form action="{{ route('logout') }}" method="post" class="m-0 logout-form">
                        @csrf
                        <input type="hidden" name="guard" value="orang_tua">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-[#ead1d1] bg-white px-[14px] py-[9px] text-[14px] font-bold text-[#7f1d1d] transition duration-200 hover:-translate-y-[1px] hover:shadow-[0_8px_18px_rgba(25,20,20,0.16)] active:translate-y-0">
                            <i class="fas fa-right-from-bracket"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <div id="portalMobileMenu" class="portal-mobile-menu lg:hidden">
                <nav class="flex flex-col gap-2 text-center">
                    <a href="{{ route('orang-tua.profil.index') }}" wire:navigate class="rounded-xl px-4 py-3 text-center text-[14px] font-semibold text-white/90 transition hover:bg-white/15 hover:text-white {{ request()->routeIs('orang-tua.profil.*') ? 'bg-white/15 text-white' : '' }}">Profil Siswa</a>
                    <a href="{{ route('orang-tua.laporan.index') }}" wire:navigate class="rounded-xl px-4 py-3 text-center text-[14px] font-semibold text-white/90 transition hover:bg-white/15 hover:text-white {{ request()->routeIs('orang-tua.laporan.*') ? 'bg-white/15 text-white' : '' }}">Perkembangan</a>
                    <a href="{{ route('orang-tua.pengumuman.index') }}" wire:navigate class="rounded-xl px-4 py-3 text-center text-[14px] font-semibold text-white/90 transition hover:bg-white/15 hover:text-white {{ request()->routeIs('orang-tua.pengumuman.*') ? 'bg-white/15 text-white' : '' }}">Pengumuman</a>
                </nav>

                <div class="mt-3 flex flex-col gap-3 text-center">
                    <div class="flex min-h-[54px] items-center justify-center rounded-2xl border border-white/70 bg-white/15 px-4 py-3 text-center text-white">
                        <strong class="block break-words text-center text-[13px] leading-[1.25]">{{ $portalSiswa?->nama ?? $portalUser->username }}</strong>
                    </div>
                    <form action="{{ route('logout') }}" method="post" class="m-0 logout-form">
                        @csrf
                        <input type="hidden" name="guard" value="orang_tua">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-[#ead1d1] bg-white px-[14px] py-[9px] text-[14px] font-bold text-[#7f1d1d] transition duration-200 hover:-translate-y-[1px] hover:shadow-[0_8px_18px_rgba(25,20,20,0.16)] active:translate-y-0">
                            <i class="fas fa-right-from-bracket"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto mt-7 max-w-[1240px] px-5 pb-4 max-[700px]:px-[14px] max-[700px]:pb-4">
        @if(session('success'))
            <div class="portal-alert-success mb-[18px] break-words rounded-[18px] border border-[#d8e8fa] bg-[#eef6ff] px-[18px] py-4 text-[#1f2937]">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="portal-alert-errors mb-[18px] break-words rounded-[18px] border border-[#d8e8fa] bg-[#eef6ff] px-[18px] py-4 text-[#1f2937]">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
    <script>
        function initPortalMobileMenu() {
            const portalToggle = document.getElementById('portalToggle');
            const portalToggleIcon = document.getElementById('portalToggleIcon');
            const portalMobileMenu = document.getElementById('portalMobileMenu');

            if (!portalToggle || !portalMobileMenu || portalToggle.dataset.menuBound === 'true') {
                return;
            }

            portalToggle.dataset.menuBound = 'true';
            portalToggle.addEventListener('click', function () {
                portalMobileMenu.classList.toggle('open');

                if (portalToggleIcon) {
                    portalToggleIcon.classList.toggle('fa-bars');
                    portalToggleIcon.classList.toggle('fa-xmark');
                }
            });
        }
        document.addEventListener('DOMContentLoaded', initPortalMobileMenu);
        document.addEventListener('livewire:navigated', initPortalMobileMenu);
    </script>
    @livewireScripts
</body>
</html>
