@extends('layouts.app')
@section('title', 'Daftar Kelas')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Daftar Kelas</h5>
    <a href="{{ route('admin.kelas.create') }}" wire:navigate class="btn btn-add btn-sm"><i class="fas fa-plus me-1"></i>Tambah Kelas</a>
</div>

<livewire:admin.kelas-table />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


