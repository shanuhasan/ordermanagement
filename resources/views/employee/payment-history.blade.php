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
                    <h1>
                        {{ $employeeDetail->name }}
                        ({{ !empty($employeeDetail->code) ? 'Code:- ' . $employeeDetail->code . ',' : '' }}
                        {{ !empty($employeeDetail->phone) ? 'Mobile:- ' . $employeeDetail->phone : ' ' }})
                    </h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>


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
                                        <td>{{ date('d-m-Y h:i A', strtotime($item->created_at)) }}
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
                                <td><strong>₹{{ $employeeTotalPayment }}</strong></td>
                            </tr>
                            <tr style="background: red;color:#fff;">
                                <td><strong>Remaining</strong></td>
                                <td><strong>₹{{ $totalAmount - $employeeTotalPayment }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $employeePaymentHistory->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>



@endsection
