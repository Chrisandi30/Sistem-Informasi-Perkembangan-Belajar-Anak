@php
    // View: resources/views/admin/tahun_ajaran/create.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Tambah Tahun Ajaran')
@section('content')
<h5>Tambah Tahun Ajaran</h5>
<livewire:admin.tahun-ajaran-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush