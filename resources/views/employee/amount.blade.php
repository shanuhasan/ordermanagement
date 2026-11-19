@extends('layouts.app')
@section('title', 'Advance Amount - ' . $employee->name)
@section('employee', 'active')

@section('content')
    @include('partials.advance-amount', [
        'routePrefix' => 'employee',
        'roleLabel' => 'Karigar',
    ])
@endsection
