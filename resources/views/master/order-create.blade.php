@extends('layouts.app')
@section('title', 'New Order - ' . $employee->name)
@section('master', 'active')

@section('content')
    @include('partials.order-create', [
        'routePrefix' => 'master',
        'roleLabel' => 'Master',
        'withPrintingName' => true,
    ])
@endsection
