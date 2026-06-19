@php
    // View: resources/views/kepala_sekolah/perkembangan/show.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Detail Laporan Perkembangan')

@section('content')
    <div class="review-detail-page">
    <h5 class="mb-3 review-detail-title">Detail Laporan Perkembangan</h5>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden review-detail-card">
        <div class="card-body p-4 p-lg-5 review-detail-body">
            @php
                $detailGroups = $perkembangan->groupedDetailsByCategory();
            @endphp
            @php
                $badge = match ($perkembangan->status) {
                    'disetujui' => 'bg-success',
                    'revisi' => 'bg-danger',
                    default => 'bg-warning text-dark',
                };
                $label = match ($perkembangan->status) {
                    'disetujui' => 'Disetujui',
                    'revisi' => 'Revisi',
                    default => 'Menunggu',
                };
            @endphp

            <section class="mb-5 review-info-section">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 review-info-heading">
                    <div style="font-size: 18px; font-weight: 800; color:#1d2533;">Informasi Laporan</div>
                    <span class="badge {{ $badge }}">{{ $label }}</span>
                </div>

                @if($perkembangan->validated_at)
                    <div class="mb-3 text small" style="color:#1d2533;">
                        Validasi: {{ $perkembangan->validated_at->format('d/m/Y H:i') }} ({{ $perkembangan->validator?->name ?? '-' }})
                    </div>
                @endif

                @if($perkembangan->status === 'revisi' && $perkembangan->catatan_validasi)
                    <div class="mb-3 rounded-4 p-3 review-revision-note" style="background:#fff8f8;">
                        <div class="fw-bold mb-1" style="color:#1d2533;">Catatan Revisi</div>
                        <div style="white-space: pre-line; overflow-wrap:anywhere; text-align: justify; color:#1d2533;">{{ $perkembangan->catatan_validasi }}</div>
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
                <a href="{{ route('kepala-sekolah.review.index') }}" class="btn btn-cancel no-cancel-confirm">Kembali</a>

                @if($perkembangan->status !== 'disetujui')
                    <form method="post" action="{{ route('kepala-sekolah.review.approve', $perkembangan) }}">
                        @csrf
                        <button class="btn btn-save" type="submit">Setujui</button>
                    </form>
                @endif
            </div>

            @if($perkembangan->status !== 'disetujui')
                <details class="mt-4 border-top pt-4" style="border-color:#edf1f6 !important;">
                    <summary class="cursor-pointer select-none" style="font-size: 16px; font-weight: 800; color:#1d2533;">
                        Minta Revisi
                    </summary>
                    <div class="mt-3">
                        <form method="post" action="{{ route('kepala-sekolah.review.reject', $perkembangan) }}">
                            @csrf
                            <label class="form-label fw-bold">Catatan Revisi</label>
                            <textarea name="catatan_validasi" class="form-control rounded-4" rows="3" placeholder="Tulis catatan revisi...">{{ old('catatan_validasi', $perkembangan->catatan_validasi) }}</textarea>
                            <div class="mt-3 d-flex gap-2 justify-content-end">
                                <button class="btn btn-danger" type="submit">Kirim Revisi</button>
                            </div>
                        </form>
                    </div>
                </details>
            @endif
        </div>
    </div>
    </div>
@endsection