@extends('layouts.app')
@section('title', 'Received Slip - ' . $employee->name)
@section('contractor', 'active')

@section('content')
    @include('partials.received-slip', [
        'routePrefix' => 'contractor',
        'roleLabel' => 'Thekedar',
    ])
@endsection
