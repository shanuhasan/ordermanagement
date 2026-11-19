@extends('layouts.app')
@section('title', 'Advance Amount - ' . $employee->name)
@section('master', 'active')

@section('content')
    @include('partials.advance-amount', [
        'routePrefix' => 'master',
        'roleLabel' => 'Master',
        'showReference' => true,
    ])
@endsection
