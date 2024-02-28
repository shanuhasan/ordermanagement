@extends('layouts.app')
@section('title', 'View')
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
                    <h1>
                        {{ $employeeDetail->name }}
                        ({{ !empty($employeeDetail->code) ? 'Code:- ' . $employeeDetail->code . ',' : '' }}
                        {{ !empty($employeeDetail->phone) ? 'Mobile:- ' . $employeeDetail->phone : ' ' }})
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('employee.order.singleprint', ['employeeId' => $employeeId, 'orderId' => $orderId]) }}"
                        class="btn btn-success">
                        Print
                    </a>
                    <a href="{{ route('employee.order', $employeeId) }}" class="btn btn-primary">Back</a>
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
                                <th>Received Piece</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>

                            @if ($items->isNotEmpty())
                                <?php $i = 1; ?>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ date('d-m-Y', strtotime($item->created_at)) }} </td>
                                        {{-- <td>{{ getOrder($item->order_id)->particular }} </td> --}}
                                        <td> {{ !empty(getOrder($item->order_id)->item_id) ? getItemName(getOrder($item->order_id)->item_id) : getOrder($item->order_id)->particular }}
                                        </td>
                                        <td>{{ sizeName(getOrder($item->order_id)->size) }}</td>
                                        <td>{{ $item->qty }}</td>
                                        {{-- <td> </td> --}}
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
                    {{ $items->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
