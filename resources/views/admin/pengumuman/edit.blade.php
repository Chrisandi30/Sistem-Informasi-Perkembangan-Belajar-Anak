@php
    // View: resources/views/admin/pengumuman/edit.blade.php
@endphp
@extends('layouts.app')
@section('title', 'Edit Pengumuman')
@section('content')
<h5>Edit Pengumuman</h5>
<livewire:admin.pengumuman-form :pengumuman="$pengumuman" />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush