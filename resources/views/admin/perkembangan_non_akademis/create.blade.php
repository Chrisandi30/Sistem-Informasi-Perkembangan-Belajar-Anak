@php
    // View: resources/views/admin/perkembangan_non_akademis/create.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Tambah Perkembangan Non Akademis')
@section('content')
<h5>Tambah Aspek Non Akademis</h5>
<livewire:admin.perkembangan-non-akademis-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
