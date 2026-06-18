@php
    // View: resources/views/admin/akun/create.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Tambah Akun')
@section('content')
<h5>Tambah Akun</h5>
<livewire:admin.akun-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush