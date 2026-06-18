@php
    // View: resources/views/admin/kelas/edit.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Edit Kelas')
@section('content')
<h5>Edit Kelas</h5>
<livewire:admin.kelas-form :kelas="$kelas" />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


