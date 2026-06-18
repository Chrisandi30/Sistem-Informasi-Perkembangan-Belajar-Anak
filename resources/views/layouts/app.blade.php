<!doctype html>
<html lang="id" class="h-full">
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
        --bg: #f5f7fb;
        --sidebar: #ffffff;
        --text: #1f2937;
        --muted: #707889;
        --line: #e6e9f0;
        --active-bg: #7f1d1d;
        --active-text: #ffffff;
        --card: #ffffff;
        --card-border: #e7ebf3;
    }

    * { box-sizing: border-box; }

    body.page-loading {
        cursor: progress;
    }

    .app-loading-overlay {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(245, 247, 251, 0.72);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .18s ease, visibility .18s ease;
        z-index: 9999;
    }

    .app-loading-overlay.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .app-loading-box {
        min-width: 220px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 22px 26px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid #e5e7eb;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
    }

    .app-loading-spinner {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 4px solid #e5e7eb;
        border-top-color: #7f1d1d;
        animation: appSpin .85s linear infinite;
    }

    .app-loading-text {
        font-size: 12px;
        font-weight: 700;
        color: #23324c;
        text-align: center;
    }

    @keyframes appSpin {
        to {
            transform: rotate(360deg);
        }
    }

    .nav-link {
        display: block;
        text-decoration: none;
        color: #364154;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 600;
        transition: .15s ease;
    }

    .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 12px;
        color: #7f1d1d;
    }

    .nav-link:hover {
        color: #7f1d1d;
    }

    .nav-link.active {
        background: var(--active-bg);
        color: var(--active-text);
        border-left: 4px solid #641823;
        padding-left: 16px;
    }

    .nav-link.active i,
    .nav-link.active .dropdown-chevron {
        color: #ffffff;
    }

    .sub-link {
        margin-left: 8px;
        font-size: 13px;
        white-space: nowrap;
    }

    .sub-link i {
        width: 16px;
        margin-right: 8px;
    }

    .menu-dropdown {
        margin-bottom: 8px;
    }

    .menu-dropdown summary {
        list-style: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .menu-dropdown summary::-webkit-details-marker {
        display: none;
    }

    .dropdown-chevron {
        font-size: 14px;
        color: #8c92a3;
        transition: transform .28s cubic-bezier(.22, 1, .36, 1), color .18s ease;
    }

    .menu-dropdown[open] .dropdown-chevron {
        transform: rotate(180deg);
    }

    .dropdown-links {
        margin-top: 0;
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transform: translateY(-6px);
        transition: max-height .32s cubic-bezier(.22, 1, .36, 1), opacity .22s ease, transform .22s ease, margin-top .22s ease;
        will-change: max-height, opacity, transform;
    }

    .menu-dropdown[open] .dropdown-links {
        margin-top: 6px;
        opacity: 1;
        transform: translateY(0);
    }

    .content-inner::-webkit-scrollbar {
        display: none;
    }

    .sidebar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .sidebar::-webkit-scrollbar {
        display: none;
    }

    .content-inner h5 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 16px;
        color: #1f2937;
    }

    .sidebar-mobile-close {
        display: none;
    }

    .content-inner .d-flex.justify-content-between.mb-3,
    .content-inner .d-flex.justify-content-between.align-items-center.mb-3 {
        align-items: center;
        margin-bottom: 18px !important;
    }

    .content-inner .btn {
        border-radius: 8px;
        font-weight: 700;
        padding: 9px 16px;
        border-width: 1px;
    }

    .content-inner .btn-sm {
        padding: 8px 14px;
        font-size: 13px;
    }

    .content-inner .btn-primary {
        background: linear-gradient(135deg, #5168ff, #6c63ff);
        border-color: transparent;
    }

    .content-inner .btn-add {
        background: #7a1f2d;
        border-color: #7a1f2d;
        color: #fff;
    }

    .content-inner .btn-add:hover {
        background: #641823;
        border-color: #641823;
        color: #fff;
    }

    .content-inner .btn-save {
        background: #0f8de8;
        border-color: #0f8de8;
        color: #fff;
        box-shadow: none;
    }

    .content-inner .btn-save:hover,
    .content-inner .btn-save:focus,
    .content-inner .btn-save:active {
        background: #0b7bca;
        border-color: #0b7bca;
        color: #fff;
        box-shadow: none;
    }

    .content-inner .btn-cancel {
        background: #d83a43;
        border-color: #d83a43;
        color: #fff;
        box-shadow: none;
    }

    .content-inner .btn-cancel:hover,
    .content-inner .btn-cancel:focus,
    .content-inner .btn-cancel:active {
        background: #bf2d35;
        border-color: #bf2d35;
        color: #fff;
        box-shadow: none;
    }

    .content-inner .btn-warning {
        background: #eef4ff;
        color: #356dff;
        border-color: #dbe7ff;
    }

    .content-inner .btn-danger {
        background: #fff2f2;
        color: #ef4444;
        border-color: #ffd9d9;
    }

    .content-inner .btn-success {
        background: #ebfbef;
        color: #17a34a;
        border-color: #c8f2d2;
    }

    .content-inner .table-responsive {
        background: var(--card);
        border: 1px solid var(--card-border);
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(31, 44, 74, 0.04);
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-x pan-y;
        overscroll-behavior-x: contain;
        cursor: default;
    }

    .content-inner .table-responsive:active {
        cursor: default;
    }

    .content-inner .table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .content-inner .table tr:first-child th {
        border: 0;
        border-bottom: 1px solid #edf1f6;
        background: #ffffff;
        color: #1d2533;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
        padding: 18px 16px;
    }

    .content-inner .table td {
        background: #ffffff;
        border: 0;
        border-bottom: 1px solid #edf1f6;
        font-size: 14px;
        padding: 18px 16px;
        vertical-align: middle;
    }

    .content-inner .table th,
    .content-inner .table td {
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .content-inner .table th:last-child,
    .content-inner .table td:last-child {
        white-space: nowrap;
        width: 116px;
    }

    .content-inner .table td .btn,
    .content-inner .table td .btn-sm {
        width: auto;
    }

    .content-inner .table tr:last-child td {
        border-bottom: 0;
    }

    .content-inner .table form.d-inline {
        display: inline-flex !important;
        margin-left: 6px;
    }

    .content-inner .card,
    .content-inner .panel,
    .content-inner .simple-card {
        border-radius: 18px;
        border: 1px solid var(--card-border);
        box-shadow: 0 6px 18px rgba(31, 44, 74, 0.04);
    }

    .content-inner .card-body,
    .content-inner .card-header {
        border-radius: 20px;
    }

    .content-inner .form-control,
    .content-inner .form-select {
        border-radius: 12px;
        min-height: 46px;
        border-color: #dde4ef;
    }

    .content-inner .pagination {
        margin-top: 18px;
    }

    .content-inner .page-link {
        border-radius: 10px;
        margin-right: 6px;
        border-color: #dde4ef;
        color: #3b4a62;
    }

    .alert {
        border-radius: 14px;
        border: 1px solid transparent;
        padding: 12px 14px;
        margin-bottom: 16px;
        font-size: 14px;
    }

    .alert-success { background: #e8f8ef; border-color: #b9ebcc; color: #1e6b41; }
    .alert-danger { background: #fff0f0; border-color: #f6c7c7; color: #8f2d2d; }
    .error-list { margin: 0; padding-left: 18px; }

</style>
<link rel="stylesheet" href="{{ asset('css/responsive.app.css') }}?v={{ filemtime(public_path('css/responsive.app.css')) }}">
</head>
<body class="m-0 h-full bg-[var(--bg)] text-[var(--text)] [font-family:'Inter',sans-serif]">
<div id="appLoadingOverlay" class="app-loading-overlay" aria-hidden="true">
    <div class="app-loading-box">
        <div class="app-loading-spinner"></div>
        <div id="appLoadingText" class="app-loading-text">Memuat halaman...</div>
    </div>
</div>
@php
    $activeGuard = null;
    $authUser = null;
    $guardCandidates = collect([
        session('active_guard'),
        match (true) {
            request()->is('admin', 'admin/*') => 'admin',
            request()->is('guru', 'guru/*') => 'guru',
            request()->is('kepala-sekolah', 'kepala-sekolah/*') => 'kepala_sekolah',
            request()->is('orang-tua', 'orang-tua/*') => 'orang_tua',
            default => null,
        },
        'admin',
        'guru',
        'kepala_sekolah',
        'orang_tua',
        'web',
    ])->filter()->unique()->all();

    foreach ($guardCandidates as $guardName) {
        if (auth($guardName)->check()) {
            $activeGuard = $guardName;
            $authUser = auth($guardName)->user();
            break;
        }
    }
@endphp
@if($authUser)
@php
    $sidebarRoleLabel = match ($authUser->role) {
        'orang_tua' => 'Orang Tua',
        'guru' => 'Guru',
        'kepala_sekolah' => 'Kepala Sekolah',
        default => ucfirst(str_replace('_', ' ', $authUser->role)),
    };
    $sidebarDisplayName = match ($authUser->role) {
        'guru' => $authUser->guru->nama ?? $authUser->name,
        default => $authUser->name,
    };
    $topbarTitle = match (true) {
        request()->routeIs('dashboard') => 'Dashboard',
        request()->routeIs('admin.kelas.*') => 'Manajemen Kelas',
        request()->routeIs('admin.guru.*') => 'Manajemen Guru',
        request()->routeIs('admin.siswa.*') => 'Manajemen Siswa',
        request()->routeIs('admin.mata-pelajaran.*') => 'Manajemen Mata Pelajaran',
        request()->routeIs('admin.perkembangan-non-akademis.*') => 'Aspek Non Akademis',
        request()->routeIs('admin.tahun-ajaran.*') => 'Tahun Ajaran',
        request()->routeIs('admin.akun.*') => 'Manajemen Pengguna',
        request()->routeIs('admin.pengumuman.*') => 'Pengumuman',
        request()->routeIs('admin.laporan.kelas*') => 'Laporan Kelas',
        request()->routeIs('admin.laporan.perkembangan', 'admin.perkembangan.*') => 'Laporan Perkembangan',
        request()->routeIs('guru.siswa.*') => 'Siswa Kelas Saya',
        request()->routeIs('guru.perkembangan.index') => 'Laporan Perkembangan',
        request()->routeIs('guru.perkembangan.create') => 'Input Laporan',
        request()->routeIs('guru.perkembangan.show') => 'Detail Laporan',
        request()->routeIs('guru.perkembangan.edit') => 'Edit Laporan',
        request()->routeIs('kepala-sekolah.review.*') => 'Review & Persetujuan Laporan',
        request()->routeIs('kepala-sekolah.dashboard') => 'Dashboard',
        request()->routeIs('orang-tua.laporan.*') => 'Laporan Anak',
        request()->routeIs('orang-tua.pengumuman.*') => 'Pengumuman',
        default => 'Sistem Informasi TK',
    };
    $mobileTopbarTitle = match (true) {
        request()->routeIs('kepala-sekolah.review.*') => 'Review & Persetujuan Laporan',
        request()->routeIs('admin.laporan.perkembangan', 'admin.perkembangan.*') => 'Data Perkembangan',
        default => $topbarTitle,
    };
@endphp
<div id="mobileSidebarOverlay" class="mobile-sidebar-overlay"></div>
<div class="app-shell">
    <aside id="appSidebar" class="sidebar flex flex-col overflow-y-auto border-r border-[var(--line)] bg-[var(--sidebar)] px-2.5 py-[22px]">
        <div class="sidebar-mobile-close mb-2 justify-end">
            <button type="button" id="mobileSidebarInnerToggle" aria-label="Tutup menu" class="sidebar-close-btn">
                <i class="fas fa-xmark transition duration-200"></i>
            </button>
        </div>
        <div class="mt-[-23px] mb-4 ml-[-8px] px-2 transition-all duration-300">
            <div class="flex w-full items-center gap-3 py-2">
                <div class="inline-flex h-[70px] w-[70px] shrink-0 items-center justify-center overflow-hidden rounded-full">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo TK Winfield" class="h-full w-full object-contain">
                </div>
                <div class="min-w-0 text-left">
                    <p class="m-0 whitespace-nowrap text-[19px] font-black uppercase leading-[1] tracking-normal text-[#7f1d1d]">TK WINFIELD</p>
                    <p class="m-0 mt-1 whitespace-nowrap text-[13px] font-extrabold leading-[1.1] text-[#24334d]">Y.P. Karya Anugerah</p>
                </div>
            </div>
        </div>

        <nav class="mt-[-15px] flex-1">
                    <a href="{{ route('dashboard') }}" wire:navigate class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-house"></i>Dashboard
                    </a>
            
            @if($authUser->role === 'admin')
                <div class="mx-[10px] my-[18px] mb-2 text-[12px] font-bold uppercase tracking-[0.04em] text-[#8c92a3]">Master Data</div>
                <a href="{{ route('admin.kelas.index') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}"><i class="fas fa-school"></i>Kelas</a>
                <a href="{{ route('admin.guru.index') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}"><i class="fas fa-chalkboard-user"></i>Guru</a>
                <a href="{{ route('admin.siswa.index') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}"><i class="fas fa-user-graduate"></i>Siswa</a>
                <a href="{{ route('admin.mata-pelajaran.index') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.mata-pelajaran.*') ? 'active' : '' }}"><i class="fas fa-book"></i>Mata Pelajaran</a>
                <a href="{{ route('admin.perkembangan-non-akademis.index') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.perkembangan-non-akademis.*') ? 'active' : '' }}"><i class="fas fa-list-check"></i>Aspek Non Akademis</a>
                <a href="{{ route('admin.tahun-ajaran.index') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.tahun-ajaran.*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i>Tahun Ajaran</a>
                <a href="{{ route('admin.pengumuman.index') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}"><i class="fas fa-bell"></i>Pengumuman</a>

                <div class="mx-[10px] my-[18px] mb-2 text-[12px] font-bold uppercase tracking-[0.04em] text-[#8c92a3]">Laporan</div>
                <details class="menu-dropdown" data-menu-key="admin-laporan" @if(request()->routeIs('admin.laporan.*', 'admin.perkembangan.*')) open @endif>
                    <summary class="nav-link {{ request()->routeIs('admin.laporan.*', 'admin.perkembangan.*') ? '' : '' }}">
                        <span><i class="fas fa-folder-open"></i>Laporan</span>
                        <i class="fas fa-chevron-down dropdown-chevron"></i>
                    </summary>
                    <div class="dropdown-links">
                        <a href="{{ route('admin.laporan.kelas') }}" wire:navigate class="nav-link sub-link {{ request()->routeIs('admin.laporan.kelas*') ? 'active' : '' }}"><i class="fas fa-file-lines"></i>Laporan Kelas</a>
                        <a href="{{ route('admin.laporan.perkembangan') }}" wire:navigate class="nav-link sub-link {{ request()->routeIs('admin.perkembangan.*', 'admin.laporan.perkembangan') ? 'active' : '' }}"><i class="fas fa-file-pen"></i>Laporan Perkembangan</a>
                    </div>
                </details>

                <div class="mx-[10px] my-[18px] mb-2 text-[12px] font-bold uppercase tracking-[0.04em] text-[#8c92a3]">USER</div>
                <a href="{{ route('admin.akun.index') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.akun.*') ? 'active' : '' }}"><i class="fas fa-user"></i>Akun Pengguna</a>

            @elseif($authUser->role === 'guru')
                <div class="mx-[10px] my-[18px] mb-2 text-[12px] font-bold uppercase tracking-[0.04em] text-[#8c92a3]">Perkembangan Anak</div>
                <a href="{{ route('guru.siswa.index') }}" wire:navigate class="nav-link {{ request()->routeIs('guru.siswa.*') || request()->routeIs('guru.perkembangan.create') ? 'active' : '' }}"><i class="fas fa-user-graduate"></i>Siswa Kelas Saya</a>
                <a href="{{ route('guru.perkembangan.index') }}" wire:navigate class="nav-link {{ request()->routeIs('guru.perkembangan.index', 'guru.perkembangan.show', 'guru.perkembangan.edit') ? 'active' : '' }}"><i class="fas fa-chart-line"></i>Data Perkembangan</a>

            @elseif($authUser->role === 'kepala_sekolah')
                <div class="mx-[10px] my-[18px] mb-2 text-[12px] font-bold uppercase tracking-[0.04em] text-[#8c92a3]">Persetujuan Laporan</div>
                <a href="{{ route('kepala-sekolah.review.index') }}" wire:navigate class="nav-link {{ request()->routeIs('kepala-sekolah.review.*') ? 'active' : '' }}"><i class="fas fa-circle-check"></i>Review & Persetujuan</a>

            @elseif($authUser->role === 'orang_tua')
                <div class="mx-[10px] my-[18px] mb-2 text-[12px] font-bold uppercase tracking-[0.04em] text-[#8c92a3]">Orang Tua</div>
                <a href="{{ route('orang-tua.laporan.index') }}" wire:navigate class="nav-link {{ request()->routeIs('orang-tua.laporan.*') ? 'active' : '' }}">Laporan Anak</a>
                <a href="{{ route('orang-tua.pengumuman.index') }}" wire:navigate class="nav-link {{ request()->routeIs('orang-tua.pengumuman.*') ? 'active' : '' }}">Pengumuman</a>
            @endif
        </nav>

    </aside>

    <section class="flex min-h-screen flex-col">
        <div class="app-topbar flex h-[72px] shrink-0 items-center justify-between border-b border-[var(--line)] bg-white px-[26px] max-[1000px]:px-[18px]">
            <div class="topbar-leading flex min-w-0 items-center gap-3">
                <button type="button" id="mobileSidebarToggle" class="mobile-sidebar-toggle" aria-label="Buka menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="min-w-0 truncate text-[18px] font-extrabold text-[#1f2937]">
                    <span>{{ $topbarTitle }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(in_array($authUser->role, ['admin', 'guru', 'kepala_sekolah'], true))
                    <div class="topbar-user flex items-center gap-3 pr-1">
                        <div class="text-right">
                            <div class="text-[13px] font-extrabold uppercase tracking-[0.04em] text-[#1f2937]">{{ $sidebarDisplayName }}</div>
                            <div class="mt-0.5 text-center text-[12px] font-bold text-[#7f1d1d]">{{ $sidebarRoleLabel }}</div>
                        </div>
                        <div class="h-10 w-px bg-[#e5d7d8]"></div>
                    </div>
                @endif
                <form action="{{ route('logout') }}" method="post" class="m-0 logout-form">
                    @csrf
                    <input type="hidden" name="guard" value="{{ $activeGuard }}">
                    <button type="submit" class="topbar-logout inline-flex items-center gap-2 rounded-full border border-[#ead1d1] bg-[#fff5f5] px-[14px] py-[9px] text-[14px] font-bold text-[#7f1d1d] transition hover:border-[#7f1d1d] hover:bg-[#7f1d1d] hover:text-white">
                        <i class="fas fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="content-inner flex-1 overflow-visible px-6 pt-3 pb-6 max-[1000px]:p-[18px]">
            @if(session('success') && session('success') !== 'Berhasil keluar dari sistem.')
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </section>
</div>
@else
    @yield('content')
@endif
    @include('partials.scripts.app-global-ui')
    @include('partials.scripts.app-form-guard')
    @include('partials.scripts.app-feedback')
    @include('partials.scripts.app-auth-ui')

    @stack('page-scripts')
    @livewireScripts
    @include('partials.scripts.app-sidebar-state')
</body>
</html>
