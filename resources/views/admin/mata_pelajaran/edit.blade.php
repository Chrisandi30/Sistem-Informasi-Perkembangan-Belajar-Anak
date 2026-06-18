@php
    // View: resources/views/admin/mata_pelajaran/edit.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Edit Mata Pelajaran')
@section('content')
<h5>Edit Mata Pelajaran</h5>
<livewire:admin.mata-pelajaran-form :mata-pelajaran="$mataPelajaran" />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


