@extends('layouts.app')
@section('title', 'Items')
@section('items', 'active')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        All Items
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <form action="" method="get">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="date">Date</label>
                                    <input type="text" autocomplete="Date" name="date"
                                        class="form-control js-filterdatepicker" placeholder="Date"
                                        value="{{ Request::get('date') }}">
                                </div>
                                <button type="submit" class="btn btn-success">Filter</button>
                                <a href="{{ route('items.index') }}" class="btn btn-danger">Reset</a>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="particular">Particular</label>
                                    <input type="text" name="particular" class="form-control" placeholder="Particular"
                                        value="{{ Request::get('particular') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="employee_id">Employee</label>
                                    <select name="employee_id" class="form-control">
                                        <option value="">select employee</option>
                                        @foreach (App\Models\Employee::getEmployee() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ Request::get('employee_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">select</option>
                                        @foreach (itemStatus() as $key => $item)
                                            <option value="{{ $key }}"
                                                {{ Request::get('status') == $key ? 'selected' : '' }}>{{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('message')
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Particular</th>
                                <th>Employee</th>
                                <th>Size</th>
                                <th>Pieces</th>
                                <th>Rate</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>

                            @if ($orders->isNotEmpty())
                                <?php $i = 1; ?>
                                @foreach ($orders as $order)
                                    <?php
                                    $emp = false;
                                    $model = App\Models\Employee::getSingleEmployee($order->employee_id);
                                    
                                    if (empty($model)) {
                                        continue;
                                    }
                                    // echo '<pre>';
                                    // print_r($model);
                                    // die();
                                    ?>
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            {{ date('d-m-Y h:i A', strtotime($order->created_at)) }}
                                        </td>
                                        <td>
                                            {{ !empty($order->item_id) ? getItemName($order->item_id) : $order->particular }}
                                        </td>
                                        <td>{{ getEmployeeDetail($order->employee_id)->name }}</td>
                                        <td>{{ sizeName($order->size) }}</td>
                                        <td>{{ $order->qty }}</td>
                                        <td>₹{{ $order->rate }}</td>
                                        <td>
                                            ₹{{ $order->total_amount }}
                                        </td>
                                        <td
                                            style="{{ $order->status == 'Pending' ? 'background:red;color:#fff;font-weight:bold;' : 'background:green;color:#fff;font-weight:bold;' }}">
                                            @if ($order->status == 'Pending')
                                                Pending
                                            @else
                                                Completed
                                            @endif
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('employee.order.edit', ['employeeId' => $order->employee_id, 'orderId' => $order->id]) }}">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12" align="center">Record Not Found</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $orders->appends(request()->input())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
