@extends('layouts.app')
@section('title', 'Kelola Akun')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Daftar Pengguna</h5>
    <a href="{{ route('admin.akun.create') }}" wire:navigate class="btn btn-add btn-sm">
        <i class="fas fa-plus me-1"></i>Tambah Akun
    </a>
</div>
<livewire:admin.akun-table />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
