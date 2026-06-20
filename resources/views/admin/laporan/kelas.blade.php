@extends('layouts.app')
@section('title', 'Laporan Data Kelas')
@section('content')
<style>
    .content-inner .laporan-kelas-table th:last-child,
    .content-inner .laporan-kelas-table td:last-child {
        width: 190px !important;
        min-width: 190px;
        white-space: nowrap;
    }

    .content-inner .laporan-kelas-actions {
        justify-content: flex-start;
        flex-wrap: nowrap;
    }
</style>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Laporan Data Kelas</h5>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            {{-- Tabel untuk menampilkan daftar data sistem. --}}
            <table class="table align-middle mb-0 laporan-kelas-table">
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Guru</th>
                        <th class="text-start">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $k)
                        <tr>
                            <td class="fw-bold">{{ $k->nama_kelas }}</td>
                            <td>{{ $k->siswas_count }} siswa</td>
                            <td>{{ $k->gurus->pluck('nama')->join(', ') ?: '-' }}</td>
                            <td class="text-start">
                                <div class="d-flex align-items-center gap-2 laporan-kelas-actions">
                                    <a href="{{ route('admin.laporan.kelas.export-pdf', ['kelas_id' => $k->id]) }}" class="btn btn-outline-danger btn-sm fw-bold pdf-download">PDF</a>
                                    <a href="{{ route('admin.laporan.kelas.print', ['kelas_id' => $k->id]) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm pdf-download" aria-label="Cetak {{ $k->nama_kelas }}" title="Cetak {{ $k->nama_kelas }}">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
