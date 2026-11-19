@extends('layouts.app')
@section('title', 'Payment History - ' . $employee->name)
@section('contractor', 'active')

@section('content')
    @include('partials.payment-history', [
        'routePrefix' => 'contractor',
        'roleLabel' => 'Thekedar',
    ])
@endsection
