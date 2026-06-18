@php
    // View: resources/views/guru/perkembangan/edit.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Edit Laporan Perkembangan')
@section('content')
<h5>Edit Perkembangan</h5>
<livewire:guru.perkembangan-form :perkembangan="$perkembangan" />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush

