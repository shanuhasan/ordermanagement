@extends('layouts.app')
@section('title', 'Payment History - ' . $employee->name)
@section('employee', 'active')

@section('content')
    @include('partials.payment-history', [
        'routePrefix' => 'employee',
        'roleLabel' => 'Karigar',
    ])
@endsection
