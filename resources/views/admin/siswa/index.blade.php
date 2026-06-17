@extends('layouts.app')
@section('title', 'Daftar Siswa')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Daftar Siswa</h5>
    <a href="{{ route('admin.siswa.create') }}" wire:navigate class="btn btn-add btn-sm"><i class="fas fa-plus me-1"></i>Tambah Siswa</a>
</div>

<livewire:admin.siswa-table/>
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush