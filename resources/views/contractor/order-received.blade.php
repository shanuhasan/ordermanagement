@extends('layouts.app')
@section('title', 'Order Received')
@section('contractor', 'active')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('contractor.order', $employee->guid) }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body text-center" style="background-color: green; color:#fff">
                    <h5>
                        {{ $employee->name }}
                        ({{ !empty($employee->code) ? 'Code:- ' . $employee->code . ',' : '' }}
                        {{ !empty($employee->phone) ? 'Mobile:- ' . $employee->phone : ' ' }})
                        Received Piece
                    </h5>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" id="employeeForm" method="post">
                @csrf
                <input type="hidden" value="{{ $employee->id }}" name="employee_id">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="date">Date<span style="color: red">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        placeholder="Date" value="{{ date('Y-m-d') }}">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="received_qty">Received Piece (<span style="color: red">{{ $pendingItem }}
                                            Pcs. Pending</span>)</label>
                                    <input type="text" name="received_qty" id="received_qty"
                                        class="form-control only-number" placeholder="Enter Received Piece">
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('contractor.order', $employee->guid) }}" class="btn btn-info">Cancel</a>
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
                url: "{{ route('contractor.order.received.store', $order->id) }}",
                type: 'post',
                data: elements.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $('button[type=submit]').prop('disabled', false);
                    if (response['status'] == true) {

                        window.location.href = "{{ route('contractor.order', $employee->guid) }}";
                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                    } else {

                        if (response['notFound'] == true) {
                            window.location.href = "{{ route('contractor.order', $employee->guid) }}";
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
