@extends('admin.layouts.app')
@section('title', 'Profile')
@section('profile', 'active')
@section('content')

    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('message')
            <form method="post" action="{{ route('admin.profile.update') }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name<span style="color: red">*</span></label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid	@enderror" placeholder="Name"
                                        value="{{ $user->name }}">
                                    @error('name')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" readonly disabled name="email" id="email"
                                        class="form-control" placeholder="Email" value="{{ $user->email }}">
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
