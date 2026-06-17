@extends('layouts.app')
@section('title', 'Pengumuman')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Pengumuman Sekolah</h5>
    <a href="{{ route('admin.pengumuman.create') }}" wire:navigate class="btn btn-add btn-sm"><i class="fas fa-plus me-1"></i>Tambah Pengumuman</a>
</div>

<livewire:admin.pengumuman-table />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush