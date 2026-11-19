@extends('layouts.app')
@section('title', 'Statement - ' . $employee->name)
@section('employee', 'active')

@section('content')
    @include('partials.worker-statement', [
        'routePrefix' => 'employee',
        'roleLabel' => 'Karigar',
    ])
@endsection
