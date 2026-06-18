@php
    // View: resources/views/admin/perkembangan/index.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Perkembangan Siswa')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 perkembangan-page-heading">
    <h5>
        <span class="page-title-desktop">Data Laporan Perkembangan</span>
        <span class="page-title-mobile">Data Perkembangan</span>
    </h5>
</div>

<livewire:admin.perkembangan-table />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
