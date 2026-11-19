@extends('layouts.app')
@section('title', 'Received Pieces - ' . $employee->name)
@section('contractor', 'active')

@section('content')
    @include('partials.received-history', [
        'routePrefix' => 'contractor',
        'roleLabel' => 'Thekedar',
    ])
@endsection
