@extends('layouts.app')
@section('title', 'Employee Orders')
@section('employee', 'active')


<?php
$employeeDetail = getEmployeeDetail($employeeId);
?>
@section('content')

    <style>
        @media print {
            .h {
                background: #000 !important;
                print-color-adjust: exact;
            }

            th {
                color: #000;
            }

            .main-footer {
                display: none
            }
        }
    </style>

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
                    <a onclick="window.print();" class="btn btn-success">Print </a>
                    <a href="{{ route('employee.order', $employeeId) }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>


    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <h5 style="text-align: center;font-weight:bold">PAYMENT HISTORY</h5>
                <div class="card-body">
                    <table cellpadding="3" cellspacing='3' border="0" width="100%">
                        <thead style="background: #000;color:#ffffff">
                            <tr>
                                <th style="border:1px solid #000;text-align:center">Date</th>
                                <th style="border:1px solid #000;text-align:center">Pament Method</th>
                                <th style="border:1px solid #000;text-align:center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            @if ($employeePaymentHistory->isNotEmpty())
                                @foreach ($employeePaymentHistory as $item)
                                    <tr>
                                        <td style="border:1px solid #000;text-align:center">
                                            {{ date('d-m-Y h:i A', strtotime($item->created_at)) }}
                                        </td>
                                        <td style="border:1px solid #000;text-align:center">{{ $item->payment_method }}</td>
                                        <td style="border:1px solid #000;text-align:center">₹{{ $item->amount }}</td>
                                    </tr>
                                    <?php $total += $item->amount; ?>
                                @endforeach
                            @else
                                <tr>
                                    <td></td>
                                    <td style="text-align:center">Data not found.</td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot style="background: #000;color:#ffffff">
                            <tr>
                                <th style="border:1px solid #000;text-align:center"></th>
                                <th style="border:1px solid #000;text-align:center"><strong>Paid</strong></th>
                                <th style="border:1px solid #000;text-align:center">
                                    <strong>₹{{ $employeeTotalPayment }}</strong>
                                </th>
                            </tr>
                            {{-- <tr>
                                <th style="border:1px solid #000;text-align:center"></th>
                                <th style="border:1px solid #000;text-align:center"><strong>Remaining</strong></th>
                                <th style="border:1px solid #000;text-align:center">
                                    <strong>₹{{ $totalAmount - $employeeTotalPayment }}</strong>
                                </th>
                            </tr> --}}
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
