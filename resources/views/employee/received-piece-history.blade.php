@extends('layouts.app')
@section('title', 'Received Pieces - ' . $employee->name)
@section('employee', 'active')

@section('content')
    @include('partials.received-history', [
        'routePrefix' => 'employee',
        'roleLabel' => 'Karigar',
    ])
@endsection
