@extends('layouts.app')
@section('title', 'Edit Master')
@section('master', 'active')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Master</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('master.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" id="employeeForm" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="name">Name<span style="color: red">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ $employee->name }}"
                                        class="form-control text-to-upper" placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="code">Code</label>
                                    <input type="text" name="code" id="code" value="{{ $employee->code }}"
                                        class="form-control text-to-upper" placeholder="Code">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="phone">Phone<span style="color: red">*</span></label>
                                    <input type="phone" name="phone" id="phone" value="{{ $employee->phone }}"
                                        class="form-control only-number" maxlength="10" placeholder="Phone">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status">Status<span style="color: red">*</span></label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $employee->status == 1 ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ $employee->status == 0 ? 'selected' : '' }} value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('master.index') }}" class="btn btn-info">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('script')
    <script>
        $('#employeeForm').submit(function(e) {
            e.preventDefault();
            var elements = $(this);
            $('button[type=submit]').prop('disabled', true);
            $.ajax({
                url: "{{ route('master.update', $employee->guid) }}",
                type: 'put',
                data: elements.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $('button[type=submit]').prop('disabled', false);
                    if (response['status'] == true) {

                        window.location.href = "{{ route('master.index') }}";
                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                    } else {

                        if (response['notFound'] == true) {
                            window.location.href = "{{ route('master.index') }}";
                        }

                        var errors = response['errors'];

                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                        $.each(errors, function(key, val) {
                            $('#' + key).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(val);
                        });
                    }
                },
                error: function(jqXHR) {
                    console.log('Something went wrong.');
                }
            });
        });
    </script>
@endsection
