@extends('layouts.app')
@section('title', 'Tambah Guru')
@section('content')
<h5>Tambah Guru</h5>
<livewire:admin.guru-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


