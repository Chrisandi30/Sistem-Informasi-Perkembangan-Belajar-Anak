@php
    // View: resources/views/admin/siswa/edit.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Edit Siswa')
@section('content')
<h5>Edit Siswa</h5>
<livewire:admin.siswa-form :siswa="$siswa" />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
