@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@php
    $cardThemes = [
        'accent-purple' => ['line' => '#0008ff', 'soft' => '#eef0ff', 'icon' => '#0008ff'],
        'accent-blue' => ['line' => '#749aec', 'soft' => '#edf4ff', 'icon' => '#749aec'],
        'accent-green' => ['line' => '#22c55e', 'soft' => '#ebfbf1', 'icon' => '#16a34a'],
        'accent-orange' => ['line' => '#ffb800', 'soft' => '#fff8e7', 'icon' => '#f4b400'],
        'accent-red' => ['line' => '#ef4444', 'soft' => '#fff1f1', 'icon' => '#ef4444'],
        'accent-violet' => ['line' => '#7c3aed', 'soft' => '#f3e8ff', 'icon' => '#7c3aed'],
        'accent-gray' => ['line' => '#9ca3af', 'soft' => '#f3f4f6', 'icon' => '#6b7280'],
    ];
@endphp

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }
    .dashboard-stat {
        position: relative;
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 20px;
        padding: 22px 20px;
        min-height: 148px;
        box-shadow: 0 10px 24px rgba(31, 44, 74, 0.05);
        overflow: hidden;
    }
    .dashboard-stat::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: var(--card-line, #5b5ff6);
        border-radius: 20px 0 0 20px;
    }
    .dashboard-stat-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .dashboard-stat-label {
        font-size: 12px;
        font-weight: 800;
        color: #1f2937;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 10px;
    }
    .dashboard-stat-value {
        font-size: 31px;
        line-height: 1.1;
        font-weight: 800;
        color: #13233e;
        margin-bottom: 8px;
    }
    .dashboard-stat-note {
        font-size: 11px;
        font-weight: 600;
        color: var(--card-line, #5b5ff6);
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .dashboard-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 999px;
        background: var(--card-soft, #eef0ff);
        color: var(--card-icon, #5b5ff6);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .dashboard-stat-value.compact {
        font-size: 25px;
        line-height: 1.15;
        margin-bottom: 13px;
        white-space: nowrap;
    }
    .panel {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 24px rgba(31, 44, 74, 0.05);
    }
    .panel-title { margin: 0 0 12px; font-size: 19px; font-weight: 800; color: #1f2937; }
    .kelas-list { margin: 0; padding: 0; list-style: none; }
    .kelas-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid #edf0f5;
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 10px;
        font-weight: 600;
        background: #fff;
        color: #1f2937;
    }
    .kelas-item:last-child { margin-bottom: 0; }
    .kelas-badge {
        background: #f2ecff;
        color: #7f1d1d;
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 999px;
        font-weight: 700;
    }
    .dashboard-sections {
        display: grid;
        grid-template-columns: 1fr;
        gap: 18px;
    }
    .dashboard-section-title {
        margin: 0 0 10px;
        font-size: 15px;
        font-weight: 800;
        color: #1f2937;
    }
    .status-stack {
        display: grid;
        gap: 10px;
    }
    .status-stack .kelas-item { margin-bottom: 0; }
    @media (max-width: 1000px) {
        .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
        .dashboard-stat { padding: 20px; }
        .dashboard-stat-value { font-size: 27px; }
        .dashboard-stat-icon { width: 54px; height: 54px; font-size: 22px; }
        .panel { padding: 16px; }
        .kelas-item {
            align-items: flex-start;
            gap: 12px;
            padding: 12px;
        }
        .kelas-item > span:first-child {
            min-width: 0;
            flex: 1 1 auto;
        }
        .kelas-badge {
            flex: 0 0 auto;
            max-width: 55%;
            white-space: normal;
            text-align: center;
            line-height: 1.35;
            border-radius: 12px;
        }
        .kelas-badge-status {
            flex: 0 1 55%;
            width: 55%;
            max-width: 55%;
        }
    }
</style>

@if(auth()->user()->role === 'admin')

    <div class="stats-grid">
        @foreach($adminStats as $card)
            @php($theme = $cardThemes[$card['accent']] ?? $cardThemes['accent-purple'])
            <div class="dashboard-stat" style="--card-line: {{ $theme['line'] }}; --card-soft: {{ $theme['soft'] }}; --card-icon: {{ $theme['icon'] }};">
                <div class="dashboard-stat-head">
                    <div>
                        <div class="dashboard-stat-label">{{ $card['label'] }}</div>
                        <div class="dashboard-stat-value {{ !is_numeric($card['value']) && strlen((string) $card['value']) > 8 ? 'compact' : '' }}">{{ $card['value'] }}</div>
                        <div class="dashboard-stat-note">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                            <span>{{ $card['note'] }}</span>
                        </div>
                    </div>
                    <div class="dashboard-stat-icon">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <h3 class="panel-title">INFORMASI KELAS</h3><br>
        <div class="dashboard-sections">
            <div>
                <h4 class="dashboard-section-title">Jumlah Siswa per Kelas</h4>
                <ul class="kelas-list">
                    @foreach($statistik as $item)
                        <li class="kelas-item">
                            <span>{{ $item->nama_kelas }}</span>
                            <span class="kelas-badge">{{ $item->siswas_count }} siswa</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="dashboard-section-title">Jumlah Laporan per Kelas Bulan Ini</h4>
                <ul class="kelas-list">
                    @foreach($statusLaporanPerKelas as $item)
                        <li class="kelas-item">
                            <span>{{ $item->nama_kelas }}</span>
                            <span class="kelas-badge">{{ $item->laporan_bulan_ini_count }} laporan</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@elseif(auth()->user()->role === 'guru')
    <div class="stats-grid">
        @foreach($guruStats as $card)
            @php($theme = $cardThemes[$card['accent']] ?? $cardThemes['accent-purple'])
            <div class="dashboard-stat" style="--card-line: {{ $theme['line'] }}; --card-soft: {{ $theme['soft'] }}; --card-icon: {{ $theme['icon'] }};">
                <div class="dashboard-stat-head">
                    <div>
                        <div class="dashboard-stat-label">{{ $card['label'] }}</div>
                        <div class="dashboard-stat-value">{{ $card['value'] }}</div>
                        <div class="dashboard-stat-note">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ $card['note'] }}</span>
                        </div>
                    </div>
                    <div class="dashboard-stat-icon">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <h3 class="panel-title">INFORMASI KELAS</h3>
        <ul class="kelas-list">
            <li class="kelas-item">
                <span>Kelas yang diampu</span>
                <span class="kelas-badge">{{ $guru?->kelas?->nama_kelas ?? '-' }}</span>
            </li>
            <li class="kelas-item">
                <span>Jumlah siswa aktif</span>
                <span class="kelas-badge">{{ $jumlahSiswa }} siswa</span>
            </li>
            <li class="kelas-item">
                <span>Status laporan bulan ini</span>
                <span class="kelas-badge kelas-badge-status">
                    {{ $statusLaporanGuruBulanIni['menunggu'] }} menunggu, {{ $statusLaporanGuruBulanIni['disetujui'] }} disetujui, {{ $statusLaporanGuruBulanIni['revisi'] }} revisi
                </span>
            </li>
        </ul>


    </div>
@elseif(auth()->user()->role === 'kepala_sekolah')
    <div class="stats-grid">
        @foreach($kepsekStats as $card)
            @php($theme = $cardThemes[$card['accent']] ?? $cardThemes['accent-purple'])
            <div class="dashboard-stat" style="--card-line: {{ $theme['line'] }}; --card-soft: {{ $theme['soft'] }}; --card-icon: {{ $theme['icon'] }};">
                <div class="dashboard-stat-head">
                    <div>
                        <div class="dashboard-stat-label">{{ $card['label'] }}</div>
                        <div class="dashboard-stat-value {{ !is_numeric($card['value']) && strlen((string) $card['value']) > 8 ? 'compact' : '' }}">{{ $card['value'] }}</div>
                        <div class="dashboard-stat-note">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                            <span>{{ $card['note'] }}</span>
                        </div>
                    </div>
                    <div class="dashboard-stat-icon">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <h3 class="panel-title">INFORMASI LAPORAN KELAS</h3>
        <ul class="kelas-list">
            @foreach($statusValidasiPerKelas as $item)
                <li class="kelas-item">
                    <span>{{ $item->nama_kelas }}</span>
                    <span class="kelas-badge">
                        {{ $item->menunggu_count }} menunggu, {{ $item->disetujui_count }} disetujui, {{ $item->revisi_count }} revisi
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
@endif
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
