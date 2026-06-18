@extends('layouts.app')
@section('title', 'Detail Laporan Perkembangan')
@section('content')
<h5 class="review-detail-title">Detail Laporan Perkembangan</h5>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-4 p-lg-5">
        @php
            $detailGroups = $perkembangan->groupedDetailsByCategory();
        @endphp
        <section class="mb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div style="font-size: 18px; font-weight: 800; color:#24334d;">Informasi Laporan</div>
                @php
                    $statusLabel = match ($perkembangan->status) {
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                        default => 'Menunggu',
                    };
                    $statusBadge = match ($perkembangan->status) {
                        'disetujui' => 'bg-success',
                        'revisi' => 'bg-danger',
                        default => 'bg-warning text-dark',
                    };
                @endphp
                <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
            </div>

            @if($perkembangan->validated_at)
                <div class="mb-3 text small" style="color:#1d2533;">
                    Validasi: {{ $perkembangan->validated_at->format('d/m/Y H:i') }} ({{ $perkembangan->validator?->name ?? '-' }})
                </div>
            @endif

            @if($perkembangan->status === 'revisi' && $perkembangan->catatan_validasi)
                <div class="mb-3 rounded-4 p-3" style="background:#fff8f8;">
                    <div class="fw-bold mb-1" style="color:#7a1b1b;">Catatan Revisi</div>
                    <div class="text-muted" style="white-space: pre-line; overflow-wrap:anywhere; text-align: justify;">{{ $perkembangan->catatan_validasi }}</div>
                </div>
            @endif

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="rounded-4 px-4 py-3 h-100" style="background:#f8fbff;">
                        <div class="text small mb-1 font-bold text-[#1d2533]">Siswa</div>
                        <div style="font-weight:800; color:#1d2533; font-size:17px;">{{ $perkembangan->siswa?->nama ?? '-' }}</div>
                        <div class="text-muted small mb-1 font-bold text-[#1d2533]">{{ $perkembangan->siswa?->kelas?->nama_kelas ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="rounded-4 px-4 py-3 h-100" style="background:#f8fbff;">
                        <div class="text small mb-1 font-bold text-[#1d2533]">Periode</div>
                        <div style="font-weight:800; color:#1d2533; font-size:17px;">{{ $monthOptions[$perkembangan->bulan] ?? $perkembangan->bulan }}/{{ $perkembangan->tahun }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="rounded-4 px-4 py-3 h-100" style="background:#f8fbff;">
                        <div class="text small mb-1 font-bold text-[#1d2533]">Guru</div>
                        <div style="font-weight:800; color:#1d2533; font-size:17px;">{{ $perkembangan->guru?->nama ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </section>

        @foreach($detailGroups as $kategori => $details)
            <section class="mb-4 @if(! $loop->first) border-top pt-5 mt-5 @endif" style="border-color:#edf1f6 !important;">
                <div class="mb-3" style="font-size: 18px; font-weight: 800; color:#1d2533;">{{ $kategori }}</div>
                @foreach($details as $detail)
                    @php
                        $halBerkembang = trim((string) ($detail->hal_berkembang ?? ''));
                        $perluDiperhatikan = trim((string) ($detail->perlu_diperhatikan ?? ''));
                        $detailColumnClass = $halBerkembang !== '' && $perluDiperhatikan !== '' ? 'col-md-6' : 'col-md-12';
                    @endphp
                    <div class="mb-4 @if(! $loop->first) border-top pt-4 @endif" style="border-color:#edf1f6 !important;">
                        <div class="mb-3" style="font-size:17px; font-weight:800; color:#1d2533;">{{ $detail->nama_aspek }}</div>
                        <div class="row g-3">
                            @if($halBerkembang !== '')
                                <div class="{{ $detailColumnClass }}">
                                    <div class="p-3 rounded-4" style="background:#f8fbff;">
                                        <div class="fw-semibold mb-2">Hal yang Sudah Berkembang</div>
                                        <div style="white-space: pre-line; overflow-wrap:anywhere; text-align: justify;">{{ $halBerkembang }}</div>
                                    </div>
                                </div>
                            @endif
                            @if($perluDiperhatikan !== '')
                                <div class="{{ $detailColumnClass }}">
                                    <div class="p-3 rounded-4" style="background:#fff8f8;">
                                        <div class="fw-semibold mb-2">Hal yang Perlu Diperhatikan</div>
                                        <div style="white-space: pre-line; overflow-wrap:anywhere; text-align: justify;">{{ $perluDiperhatikan }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </section>
        @endforeach

    @if($perkembangan->catatan_pengembangan)
            <section class="mb-2 border-top pt-5 mt-5" style="border-color:#edf1f6 !important;">
                <div class="mb-3" style="font-size: 18px; font-weight: 800; color:#1d2533;">Catatan</div>
                <div class="rounded-4 p-3" style="background:#fbfdff; white-space: pre-line; overflow-wrap:anywhere; text-align: justify;">
                    {{ $perkembangan->catatan_pengembangan }}
                </div>
            </section>
    @endif

        <div class="mt-6 flex flex-wrap justify-end gap-3">
            <a href="{{ route('guru.perkembangan.index') }}" class="btn btn-cancel no-cancel-confirm">Kembali</a>
            <a href="{{ route('guru.perkembangan.edit', $perkembangan) }}" wire:navigate class="btn btn-save">Edit Laporan</a>
        </div>
    </div>
</div>
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
