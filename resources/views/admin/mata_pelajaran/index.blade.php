@php
    // View: resources/views/admin/mata_pelajaran/index.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Daftar Mata Pelajaran')
@section('content')
<div class="d-flex justify-content-between mb-3"><h5>Daftar Mata Pelajaran</h5><a href="{{ route('admin.mata-pelajaran.create') }}" wire:navigate class="btn btn-add btn-sm"><i class="fas fa-plus me-1"></i>Tambah Mata pelajaran</a></div>

<livewire:admin.mata-pelajaran-table />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


