@php
    // View: resources/views/admin/siswa/create.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Tambah Siswa')
@section('content')
<h5>Tambah Siswa</h5>
<livewire:admin.siswa-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


