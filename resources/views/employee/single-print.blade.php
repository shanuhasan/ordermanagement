@extends('layouts.app')
@section('title', 'Received Slip - ' . $employee->name)
@section('employee', 'active')

@section('content')
    @include('partials.received-slip', [
        'routePrefix' => 'employee',
        'roleLabel' => 'Karigar',
    ])
@endsection
