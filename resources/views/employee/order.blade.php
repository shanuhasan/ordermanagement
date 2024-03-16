@extends('layouts.app')
@section('title', 'Employee Orders')
@section('employee', 'active')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ $employee->name }}
                        ({{ !empty($employee->code) ? 'Code:- ' . $employee->code . ',' : '' }}
                        {{ !empty($employee->phone) ? 'Mobile:- ' . $employee->phone : ' ' }})
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('employee.order.receivedPiece', $employee->guid) }}" class="btn btn-success">Received
                        Piece
                        History</a>
                    <a href="{{ route('employee.order.payment.history', $employee->guid) }}" class="btn btn-info">Payment
                        History</a>
                    <a href="{{ route('employee.order.print', $employee->guid) }}" class="btn btn-success">Print</a>
                    <a href="{{ route('employee.order.create', $employee->guid) }}" class="btn btn-primary">Add</a>
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
                                    <label for="size">Size</label>
                                    <select name="size" id="size" class="form-control">
                                        <option value="">Select Size</option>
                                        @foreach (App\Models\Size::list() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ Request::get('size') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success">Filter</button>
                                <a href="{{ route('employee.order', $employee->guid) }}" class="btn btn-danger">Reset</a>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select Status</option>
                                        @foreach (itemStatus() as $key => $val)
                                            <option value="{{ $key }}"
                                                {{ Request::get('status') == $key ? 'selected' : '' }}>
                                                {{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="year">Year</label>
                                    <select name="year" id="year" class="form-control">
                                        <option value="">Select Year</option>
                                        @foreach (years() as $key => $val)
                                            <option value="{{ $key }}"
                                                {{ Request::get('year') == $key ? 'selected' : '' }}>
                                                {{ $val }}</option>
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
                                <th>Particular/Item</th>
                                <th>Size</th>
                                <th>Total Piece</th>
                                <th>Received Piece</th>
                                <th>Pending Piece</th>
                                <th>Rate</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($orders->isNotEmpty())
                                <?php $i = 1; ?>
                                @foreach ($orders as $order)
                                    <?php
                                    $advAmt = 0;
                                    $orderDetail = \App\Models\OrderItem::where('order_id', $order->id)->get();
                                    if (!empty($orderDetail)) {
                                        foreach ($orderDetail as $k => $vl) {
                                            $advAmt += $vl->amount;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            <a
                                                href="{{ route('employee.order.edit', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                {{ !empty($order->date) ? date('d-m-Y', strtotime($order->date)) : date('d-m-Y h:i A', strtotime($order->created_at)) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('employee.order.edit', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                {{ !empty($order->item_id) ? getItemName($order->item_id) : $order->particular }}
                                            </a>
                                        </td>
                                        <td>{{ sizeName($order->size) }}</td>
                                        <td>{{ $order->qty }}</td>
                                        <td>{{ receivedItems($order->id) }}</td>
                                        <td>{{ $order->qty - receivedItems($order->id) }}</td>
                                        <td>₹{{ $order->rate }}</td>
                                        <td>
                                            <a
                                                href="{{ route('employee.order.edit', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                ₹{{ $order->total_amount }}
                                            </a>
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
                                            {{-- <a
                                                href="{{ route('employee.order.edit', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </a> --}}
                                            <a
                                                href="{{ route('employee.order.singleprint', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                <i class="fa fa-print" aria-hidden="true"></i>
                                            </a>
                                            {{-- <a
                                                href="{{ route('employee.order.view', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a> --}}
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
                    {!! $orders->appends(request()->input())->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->

    @if ($orders->isNotEmpty())
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <form action="{{ route('employee.order.payment') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ $employee->id }}" name="employee_id" id="employee_id">
                            <div class="card">
                                <h5 style="text-align: center;font-weight:bold;background:gray;">ADVANCE AMOUNT</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="rate">Advance Amount</label>
                                                <input type="text" name="amount"
                                                    class="form-control only-number @error('amount') is-invalid	@enderror"
                                                    placeholder="Advance Amount">
                                                @error('amount')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="rate">Payment Method</label>
                                                <select name="payment_method" id="payment_method" class="form-control">
                                                    @foreach (paymentMethod() as $key => $item)
                                                        <option value="{{ $key }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <section class="content">
                            <div class="container-fluid">
                                <div class="card">
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-hover text-nowrap table-bordered">
                                            <tbody>
                                                <tr style="font-size:24px">
                                                    <td><strong>Total Amount</strong></td>
                                                    <td align="right"><strong>₹{{ $totalAmount }}</strong></td>
                                                </tr>
                                                <tr style="background-color: green; color:#fff;font-size:24px">
                                                    <td><strong>Paid Amount</strong></td>
                                                    <td align="right">
                                                        <strong>₹{{ $employeeTotalPayment }}</strong>
                                                    </td>
                                                </tr>
                                                <tr style="background-color: red; color:#fff;font-size:24px">
                                                    <td><strong>Remaining Amount</strong></td>
                                                    <td align="right">
                                                        <strong>₹{{ $totalAmount - $employeeTotalPayment }}</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

            </div>
            <!-- /.card -->
        </section>
    @endif
@endsection
