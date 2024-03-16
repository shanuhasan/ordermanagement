@extends('layouts.app')
@section('title', 'Add New')
@section('employee', 'active')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $employee->name }} ({{ $employee->phone }}) Add</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('employee.order', $employee->guid) }}" class="btn btn-primary">Back</a>
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
                <input type="hidden" value="{{ $employee->id }}" name="employee_id">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="item_id">Particular/Items<span style="color: red">*</span><span><a
                                                href="{{ route('item.create') }}"> (Add Item)</a></span></label>
                                    <select name="item_id" id="item_id" class="form-control">
                                        <option value="">Select Item</option>
                                        @foreach (App\Models\Item::list() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="size">Size<span style="color: red">*</span><span><a
                                                href="{{ route('size.create') }}"> (Add Size)</a></span></label>
                                    <select name="size" id="size" class="form-control">
                                        <option value="">Select Size</option>
                                        @foreach (App\Models\Size::list() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="qty">Total Piece<span style="color: red">*</span></label>
                                    <input type="text" name="qty" id="qty" class="form-control only-number"
                                        placeholder="Pieces">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="rate">Rate<span style="color: red">*</span></label>
                                    <input type="text" name="rate" id="rate" class="form-control only-number"
                                        placeholder="Rate">
                                    <p class="error"></p>
                                </div>
                            </div>
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
                                    <label for="rate">Status<span style="color: red">*</span></label>
                                    <select name="status" id="status" class="form-control">
                                        @foreach (itemStatus() as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="received_qty">Received Piece</label>
                                    <input type="text" name="received_qty" id="received_qty"
                                        class="form-control only-number" placeholder="Received Piece">
                                    <p class="error"></p>
                                </div>
                            </div> --}}
                        </div>
                        <button type="submit" class="btn btn-success">Create</button>
                        <a href="{{ route('employee.order', $employee->guid) }}" class="btn btn-info">Cancel</a>
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
                url: "{{ route('employee.order.store') }}",
                type: 'post',
                data: elements.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $('button[type=submit]').prop('disabled', false);
                    if (response['status'] == true) {
                        window.location.href = "{{ route('employee.order', $employee->guid) }}";
                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                    } else {
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
