@extends('layouts.app')
@section('title', 'New Order - ' . $employee->name)
@section('party', 'active')

@section('content')
    @include('partials.order-create', [
        'routePrefix' => 'party',
        'roleLabel' => 'Party',
        'ownerField' => 'party_id',
    ])
@endsection
