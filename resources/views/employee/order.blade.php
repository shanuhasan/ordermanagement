@extends('layouts.app')
@section('title', 'Employee Orders')
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
                    <h1>{{ $employeeDetail->name }} ({{ $employeeDetail->phone }})
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('employee.order.create', $employeeId) }}" class="btn btn-primary">Add</a>
                    <a href="{{ route('employee.order.print', $employeeId) }}" class="btn btn-success">Print</a>
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
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-title">
                            {{-- <a href="{{ route('orders.index') }}" class="btn btn-danger">Reset</a> --}}
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input type="text" value="{{ Request::get('keyword') }}" name="keyword"
                                    class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Particular</th>
                                <th>Size</th>
                                <th>Pieces</th>
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
                                                href="{{ route('employee.order.edit', ['employeeId' => $employeeId, 'orderId' => $order->id]) }}">
                                                {{ date('d-m-Y H:i A', strtotime($order->created_at)) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('employee.order.edit', ['employeeId' => $employeeId, 'orderId' => $order->id]) }}">
                                                {{ $order->particular }}
                                            </a>
                                        </td>
                                        <td>{{ $order->size }}</td>
                                        <td>{{ $order->qty }}</td>
                                        <td>₹{{ $order->rate }}</td>
                                        <td>
                                            <a
                                                href="{{ route('employee.order.edit', ['employeeId' => $employeeId, 'orderId' => $order->id]) }}">
                                                ₹{{ $order->total_amount }}
                                            </a>
                                        </td>
                                        <td
                                            style="{{ $order->status == 0 ? 'background:red;color:#fff;font-weight:bold;' : 'background:green;color:#fff;font-weight:bold;' }}">
                                            @if ($order->status == 0)
                                                Pending
                                            @else
                                                Complete
                                            @endif
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('employee.order.edit', ['employeeId' => $employeeId, 'orderId' => $order->id]) }}">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a
                                                href="{{ route('employee.order.singleprint', ['employeeId' => $employeeId, 'orderId' => $order->id]) }}">
                                                <i class="fa fa-print" aria-hidden="true"></i>
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
                    {{ $orders->links('pagination::bootstrap-5') }}
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
                    <div class="col-md-6">
                        <form action="{{ route('employee.order.payment') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ $employeeId }}" name="employee_id" id="employee_id">
                            <div class="card">
                                <h5 style="text-align: center;font-weight:bold;background:gray;">ADVANCE</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
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
                                                <p>Total Amount :- <strong>₹{{ $totalAmount }}</strong></p>
                                                <p>Paid Amount :- <strong>₹{{ $employeeTotalPayment }}</strong></p>
                                                <p>Remaining Amount :-
                                                    <strong>₹{{ $totalAmount - $employeeTotalPayment }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <section class="content">
                            <!-- Default box -->
                            <div class="container-fluid">
                                <div class="card">
                                    <h5 style="text-align: center;font-weight:bold;background:gray;">PAID AMOUNT</h5>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-hover text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $total = 0; ?>
                                                @if ($employeePaymentHistory->isNotEmpty())
                                                    @foreach ($employeePaymentHistory as $item)
                                                        <tr>
                                                            <td>{{ date('d-m-Y H:i A', strtotime($item->created_at)) }}
                                                            </td>
                                                            <td>₹{{ $item->amount }}</td>
                                                        </tr>
                                                        <?php $total += $item->amount; ?>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5" align="center">Data not found.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr style="background: green;color:#fff;">
                                                    <td><strong>Paid</strong></td>
                                                    <td><strong>₹{{ $total }}</strong></td>
                                                </tr>
                                                <tr style="background: red;color:#fff;">
                                                    <td><strong>Remaining</strong></td>
                                                    <td><strong>₹{{ $totalAmount - $employeeTotalPayment }}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    {{-- <div class="card-footer clearfix">
                                        {{ $employeePaymentHistory->links('pagination::bootstrap-5') }}
                                    </div> --}}
                                </div>
                            </div>
                            <!-- /.card -->
                        </section>
                    </div>
                </div>

            </div>
            <!-- /.card -->
        </section>
    @endif



@endsection


@section('script')
@endsection
