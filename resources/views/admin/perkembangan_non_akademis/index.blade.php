@php
    // View: resources/views/admin/perkembangan_non_akademis/index.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Perkembangan Non Akademis')
@section('content')
<div class="d-flex justify-content-between mb-3"><h5>Daftar Aspek Non Akademis</h5><a href="{{ route('admin.perkembangan-non-akademis.create') }}" wire:navigate class="btn btn-add btn-sm"><i class="fas fa-plus me-1"></i>Tambah Aspek</a></div>

<livewire:admin.perkembangan-non-akademis-table />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
