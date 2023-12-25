@extends('admin.layouts.app')
@section('title', 'Edit')
@section('employee', 'active')
<?php
$employeeDetail = getEmployeeDetail($employeeId);
?>
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit ({{ $employeeDetail->name }} , {{ $employeeDetail->phone }})</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.employee.order', $employeeId) }}" class="btn btn-primary">Back</a>
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
                <input type="hidden" value="{{ $employeeId }}" name="employee_id">
                <input type="text" name="company" value="{{ $employeeDetail->company_id }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="particular">Particular</label>
                                    <input type="text" name="particular" id="particular" class="form-control"
                                        placeholder="Particular" value="{{ $order->particular }}">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qty">Pieces</label>
                                    <input type="text" name="qty" id="qty" class="form-control"
                                        placeholder="Pieces" value="{{ $order->qty }}">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rate">Rate</label>
                                    <input type="text" name="rate" id="rate" class="form-control"
                                        placeholder="Rate" value="{{ $order->rate }}">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rate">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        @foreach (itemStatus() as $key => $item)
                                            <option value="{{ $key }}"
                                                {{ $order->status == $key ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.employee.order', $employeeId) }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
                url: "{{ route('admin.employee.order.update', $order->id) }}",
                type: 'post',
                data: elements.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $('button[type=submit]').prop('disabled', false);
                    if (response['status'] == true) {

                        window.location.href = "{{ route('admin.employee.order', $employeeId) }}";
                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                    } else {

                        if (response['notFound'] == true) {
                            window.location.href = "{{ route('admin.employee.order', $employeeId) }}";
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
