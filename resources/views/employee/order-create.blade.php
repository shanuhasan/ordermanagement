@extends('layouts.app')
@section('title', 'New Order - ' . $employee->name)
@section('employee', 'active')

@section('content')
    @include('partials.order-create', [
        'routePrefix' => 'employee',
        'roleLabel' => 'Karigar',
        'withStatus' => true,
    ])
@endsection
