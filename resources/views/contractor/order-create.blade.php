@extends('layouts.app')
@section('title', 'New Order - ' . $employee->name)
@section('contractor', 'active')

@section('content')
    @include('partials.order-create', [
        'routePrefix' => 'contractor',
        'roleLabel' => 'Thekedar',
        'withStatus' => true,
    ])
@endsection
