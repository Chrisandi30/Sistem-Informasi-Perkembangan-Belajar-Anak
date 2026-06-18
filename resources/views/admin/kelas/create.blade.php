@php
    // View: resources/views/admin/kelas/create.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Tambah Kelas')
@section('content')
<h5>Tambah Kelas</h5>
<livewire:admin.kelas-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


