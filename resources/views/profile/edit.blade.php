@extends('layouts.app')
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
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('message')
            <form method="post" action="{{ route('profile.update') }}">
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
                                    <label for="company_name">Company Name</label>
                                    <input type="text" name="company_name" id="company_name"
                                        class="form-control @error('company_name') is-invalid	@enderror text-to-upper"
                                        placeholder="Company Name" value="{{ $user->company_name }}">
                                    @error('company_name')
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
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </section>

@endsection
