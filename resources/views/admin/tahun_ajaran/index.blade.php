@extends('layouts.app')
@section('title', 'Daftar Tahun Ajaran')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Daftar Tahun Ajaran</h5>
    <a href="{{ route('admin.tahun-ajaran.create') }}" wire:navigate class="btn btn-add btn-sm"><i class="fas fa-plus me-1"></i>Tambah Tahun Ajaran</a>
</div>

<livewire:admin.tahun-ajaran-table />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


