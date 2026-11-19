@extends('layouts.app')
@section('title', 'Advance Amount - ' . $employee->name)
@section('contractor', 'active')

@section('content')
    @include('partials.advance-amount', [
        'routePrefix' => 'contractor',
        'roleLabel' => 'Thekedar',
    ])
@endsection
