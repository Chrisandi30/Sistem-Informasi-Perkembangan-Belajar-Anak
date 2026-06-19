@php
    // View: resources/views/admin/siswa/show.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Detail Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Detail Siswa</h5>
</div>

<div class="card card-body form-shell bg-white p-4 p-lg-5">
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->nama ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">NIS</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->nis ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">NISN</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->nisn ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Tempat Lahir</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->tempat_lahir ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Tanggal Lahir</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->tanggal_lahir?->format('Y-m-d') ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Jenis Kelamin</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->jenis_kelamin_label }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Agama</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->agama ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Kelas</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->kelas->nama_kelas ?? '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Tahun Ajaran</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->tahunAjaran?->tahun_ajaran ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Nama Ayah</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->nama_ayah ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Nama Ibu</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->nama_ibu ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Nama Wali</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->nama_wali ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Nomor Telepon Orang Tua/Wali</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->nomor_telepon ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Alamat</label>
            <textarea class="form-control bg-light" rows="2" readonly>{{ $siswa->alamat ?: '-' }}</textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label">Username Akun</label>
            <input type="text" class="form-control bg-light" value="{{ $siswa->user?->username ?: '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label">Password Akun</label>
            <input type="password" class="form-control pointer-events-none select-none bg-slate-100 text-slate-600" value="password" readonly tabindex="-1">
        </div>
        <div class="col-md-4">
            <label class="form-label">Pas Foto Siswa</label>
            <div class="form-control bg-light d-flex align-items-center justify-content-center" style="min-height: 180px;">
                @if($siswa->pas_foto_url)
                    <img src="{{ $siswa->pas_foto_url }}" alt="Pas foto {{ $siswa->nama }}" style="width: 120px; height: 150px; object-fit: cover; border-radius: 14px; border: 1px solid #d8e3ef;">
                @else
                    <div class="text-center text-muted">
                        <div class="mb-2" style="font-size: 40px;"><i class="fas fa-image"></i></div>
                        <div>Belum ada pas foto</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex flex-wrap justify-content-end gap-3">
        <a href="{{ $returnTo }}" class="btn btn-cancel no-cancel-confirm">
            Kembali
        </a>
        <a href="{{ route('admin.siswa.edit', ['siswa' => $siswa, 'return_to' => $returnTo]) }}" wire:navigate class="btn btn-save">
            Edit
        </a>
    </div>
</div>
@endsection

