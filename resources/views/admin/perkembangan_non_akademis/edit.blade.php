@php
    // View: resources/views/admin/perkembangan_non_akademis/edit.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Edit Perkembangan Non Akademis')
@section('content')
<h5>Edit Aspek Non Akademis</h5>
<livewire:admin.perkembangan-non-akademis-form :perkembanganNonAkademis="$perkembanganNonAkademis" />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
