@extends('layouts.app')
@section('title', 'Statement - ' . $employee->name)
@section('contractor', 'active')

@section('content')
    @include('partials.worker-statement', [
        'routePrefix' => 'contractor',
        'roleLabel' => 'Thekedar',
    ])
@endsection
