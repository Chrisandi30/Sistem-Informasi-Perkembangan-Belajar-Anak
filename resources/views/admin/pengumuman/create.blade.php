@extends('layouts.app')
@section('title', 'Tambah Pengumuman')
@section('content')
<h5>Tambah Pengumuman</h5>
<livewire:admin.pengumuman-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


