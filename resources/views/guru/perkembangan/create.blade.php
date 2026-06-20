@php
    // View: resources/views/guru/perkembangan/create.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Input Laporan Perkembangan')
@section('content')
<h5>Form Input Perkembangan </h5>
<livewire:guru.perkembangan-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
