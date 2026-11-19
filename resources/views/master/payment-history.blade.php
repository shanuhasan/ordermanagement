@extends('layouts.app')
@section('title', 'Payment History - ' . $employee->name)
@section('master', 'active')

@section('content')
    @include('partials.payment-history', [
        'routePrefix' => 'master',
        'roleLabel' => 'Master',
        'showReference' => true,
    ])
@endsection
