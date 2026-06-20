@php
    // View: resources/views/admin/akun/edit.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Edit Akun')
@section('content')
<h5>Edit Akun</h5>
<livewire:admin.akun-form :akun="$akun" />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
