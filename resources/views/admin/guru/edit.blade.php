@extends('layouts.app')
@section('title', 'Edit Data Guru')
@section('content')
<h5>Edit Guru</h5>
<livewire:admin.guru-form :guru="$guru" />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


