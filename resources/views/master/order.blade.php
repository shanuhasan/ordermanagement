@extends('layouts.app')
@section('title', 'Master Orders')
@section('master', 'active')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-md-12 text-right">
                    <a href="{{ route('master.order.amount', $employee->guid) }}" class="btn btn-warning">Advance Amount</a>
                    <a href="{{ route('master.order.payment.history', $employee->guid) }}" class="btn btn-info">Payment
                        History</a>
                    <a href="{{ route('master.order.create', $employee->guid) }}" class="btn btn-primary">Add</a>
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
                        <a href="{{ route('master.order', $employee->guid) }}" class="btn btn-danger">Reset</a>
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
                                <th style="text-align:center">Qty</th>
                                <th style="text-align:center">Rate</th>
                                <th style="text-align:center">Total Amount</th>
                                <th style="text-align:center">Printing Name</th>
                                <th style="text-align:center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $totalQty = $total = 0; ?>
                            @if ($orders->isNotEmpty())
                                <?php $i = 1; ?>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td style="text-align:center">{{ $i++ }}</td>
                                        <td style="text-align:center">
                                            {{ !empty($order->date) ? date('d-m-Y', strtotime($order->date)) : date('d-m-Y h:i A', strtotime($order->created_at)) }}
                                        </td>
                                        <td style="text-align:center">
                                            {{ !empty($order->item_id) ? getItemName($order->item_id) : $order->particular }}

                                        </td>
                                        <td style="text-align:center">{{ sizeName($order->size_id) }}</td>
                                        <td style="text-align:center">{{ $order->qty }}</td>
                                        <td style="text-align:center">{{ !empty($order->rate) ? '₹' . $order->rate : '' }}
                                        </td>
                                        <td style="text-align:center">
                                            {{ !empty($order->total_amount) ? '₹' . $order->total_amount : '' }}</td>
                                        <td style="text-align:center">{{ $order->printing_name }}</td>
                                        <td style="text-align:center">
                                            <a
                                                href="{{ route('master.order.edit', ['employeeId' => $employee->guid, 'orderId' => $order->id]) }}">
                                                <svg class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="javascript:void()" onclick="deleteOrder('{{ $order->id }}')"
                                                class="text-danger w-4 h-4 mr-1">
                                                <svg wire:loading.remove.delay="" wire:target=""
                                                    class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path ath fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                    $totalQty += $order->qty;
                                    $total += $order->total_amount;
                                    ?>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12" align="center">Record Not Found</td>
                                </tr>
                            @endif

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center">
                                    <strong>{{ $totalQty }}</strong>
                                </th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center">
                                    <strong>₹{{ $total }}</strong>
                                </th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                            </tr>
                        </tfoot>
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
            var url = "{{ route('master.order.delete', 'ID') }}";
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
