@extends('layouts.app')
@section('title', 'Employee Orders')
@section('employee', 'active')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-md-12 text-right">
                    <a href="{{ route('employee.order.amount', $employee->guid) }}" class="btn btn-warning">Advance Amount</a>
                    <a href="{{ route('employee.order.receivedPiece', $employee->guid) }}" class="btn btn-success">Received
                        Piece
                        History</a>
                    <a href="{{ route('employee.order.payment.history', $employee->guid) }}" class="btn btn-secondary">Payment
                        History</a>
                    <a href="{{ route('employee.order.print', $employee->guid) }}" class="btn btn-info">Print</a>
                    <a href="{{ route('employee.order.create', $employee->guid) }}" class="btn btn-primary">Add</a>
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
                    </h5>
                </div>
            </div>
        </div>
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
                                    <label for="item">Item</label>
                                    <select name="item" id="item" class="form-control">
                                        <option value="">Select Item</option>
                                        @foreach (App\Models\Item::list() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ Request::get('item') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                                        @foreach (\App\Models\Year::getYear() as $val)
                                            <?php
                                            $year = !empty(Request::get('year')) ? Request::get('year') : date('Y');
                                            ?>
                                            <option value="{{ $val->name }}"
                                                {{ $year == $val->name ? 'selected' : '' }}>
                                                {{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{ route('employee.order', $employee->guid) }}" class="btn btn-danger">Reset</a>
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
                                <th style="text-align:center">#</th>
                                <th style="text-align:center">Date</th>
                                <th style="text-align:center">Particular/Item</th>
                                <th style="text-align:center">Size</th>
                                <th style="text-align:center">Total Piece</th>
                                <th style="text-align:center">Received Piece</th>
                                <th style="text-align:center">Pending Piece</th>
                                <th style="text-align:center">Rate</th>
                                <th style="text-align:center">Total Amount</th>
                                {{-- <th style="text-align:center">Status</th> --}}
                                <th style="text-align:center">Action</th>
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
                                        <td style="text-align:center">{{ $i++ }}</td>
                                        <td style="text-align:center">
                                            <a
                                                href="{{ route('employee.order.received', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                {{ !empty($order->date) ? date('d-m-Y', strtotime($order->date)) : date('d-m-Y h:i A', strtotime($order->created_at)) }}
                                            </a>
                                        </td>
                                        <td style="text-align:center">
                                            <a
                                                href="{{ route('employee.order.received', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                {{ !empty($order->item_id) ? getItemName($order->item_id) : $order->particular }}
                                            </a>
                                        </td>
                                        <td style="text-align:center">{{ sizeName($order->size) }}</td>
                                        <td style="text-align:center">{{ $order->qty }}</td>

                                        @php
                                            $style1 = $style2 = 'text-align:center;';
                                            $color1 = $color2 = 'color:#000;';
                                            if ($order->qty == receivedItems($order->id)) {
                                                $style1 =
                                                    'text-align:center;background:green;color:#fff;font-weight:bold;';
                                                $color1 = 'color:#fff;';
                                            }
                                        @endphp

                                        <td style="{{ $style1 }}">
                                            <a style="{{ $color1 }}"
                                                href="{{ route('employee.order.received', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                {{ receivedItems($order->id) }}
                                            </a>
                                        </td>

                                        @php
                                            if ($order->qty - receivedItems($order->id) > 0) {
                                                $style2 =
                                                    'text-align:center;background:red;color:#fff;font-weight:bold;';
                                                $color2 = 'color:#fff;';
                                            }
                                        @endphp

                                        <td style="{{ $style2 }}">
                                            <a style="{{ $color2 }}"
                                                href="{{ route('employee.order.received', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                {{ $order->qty - receivedItems($order->id) }}
                                            </a>
                                        </td>

                                        <td style="text-align:center">₹{{ $order->rate }}</td>
                                        <td style="text-align:center">
                                            <a
                                                href="{{ route('employee.order.received', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                ₹{{ $order->total_amount }}
                                            </a>
                                        </td>
                                        {{-- <td
                                            style="{{ $order->status == 'Pending' ? 'text-align:center;background:red;color:#fff;font-weight:bold;' : 'text-align:center;background:green;color:#fff;font-weight:bold;' }}">
                                            <a style="color: #fff"
                                                href="{{ route('employee.order.received', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                @if ($order->status == 'Pending')
                                                    Pending
                                                @else
                                                    Completed
                                                @endif
                                            </a>
                                        </td> --}}
                                        <td style="text-align:center">
                                            <a
                                                href="{{ route('employee.order.edit', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                <svg class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a
                                                href="{{ route('employee.order.singleprint', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                <i class="fa fa-print" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void()" onclick="deleteOrder('{{ $order->id }}')"
                                                class="text-danger w-4 h-4 mr-1">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
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
                    {!! $orders->appends(request()->input())->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection


@section('script')
    <script>
        function deleteOrder(id) {
            var url = "{{ route('employee.order.delete', 'ID') }}";
            var newUrl = url.replace('ID', id);

            if (confirm('Are you sure want to delete')) {
                $.ajax({
                    url: newUrl,
                    type: 'get',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response['status']) {
                            window.location.reload();
                        }
                    }
                });
            }
        }
    </script>
@endsection
