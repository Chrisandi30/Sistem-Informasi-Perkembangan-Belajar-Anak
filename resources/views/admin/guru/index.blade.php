@extends('layouts.app')
@section('title', 'Daftar Guru')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Daftar Guru</h5>
    <a href="{{ route('admin.guru.create') }}" wire:navigate class="btn btn-add btn-sm"><i class="fas fa-plus me-1"></i>Tambah Guru</a>
</div>

<livewire:admin.guru-table />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
