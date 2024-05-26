@extends('layouts.app')
@section('title', 'Change Password')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Change Password</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('dashboard') }}" class="btn btn-info">Home</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('message')
            <form action="{{ route('profile.changePasswordProcess') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="old_password">Old Password</label>
                                    <input type="password" name="old_password"
                                        class="form-control @error('old_password') is-invalid	@enderror"
                                        placeholder="Old Password">
                                    @error('old_password')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="new_password">New Password</label>
                                    <input type="password" name="new_password"
                                        class="form-control @error('new_password') is-invalid	@enderror"
                                        placeholder="New Password">
                                    @error('new_password')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" name="confirm_password"
                                        class="form-control @error('confirm_password') is-invalid	@enderror"
                                        placeholder="Confirm Password">
                                    @error('confirm_password')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-info">Cancel</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- /.card -->
    </section>
    <!-- /.content -->



@endsection
