@php
    // View: resources/views/admin/guru/create.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Tambah Guru')
@section('content')
<h5>Tambah Guru</h5>
<livewire:admin.guru-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


