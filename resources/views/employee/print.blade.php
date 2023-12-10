@extends('layouts.app')
@section('title', 'Print')
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
                    <h1>Print</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h2>{{ $employeeDetail->name }} ({{ $employeeDetail->phone }})</h2>
                    <table cellpadding="3" cellspacing='3' border="0" width="100%">
                        <thead style="background: #000;color:#ffffff">
                            <tr>
                                <th style="border:1px solid #000;text-align:center">#</th>
                                <th style="border:1px solid #000;text-align:center">Date</th>
                                <th style="border:1px solid #000;text-align:center">Particular</th>
                                <th style="border:1px solid #000;text-align:center">Pices</th>
                                <th style="border:1px solid #000;text-align:center">Rate</th>
                                <th style="border:1px solid #000;text-align:center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0;
                            $i = 1; ?>
                            @foreach ($orders as $item)
                                <tr style="border:1px solid #000">
                                    <td style="border:1px solid #000;text-align:center">{{ $i++ }}</td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                    <td style="border:1px solid #000;text-align:center">{{ $item->particular }}
                                    <td style="border:1px solid #000;text-align:center">{{ $item->qty }}
                                    </td>
                                    <td style="border:1px solid #000;text-align:center">₹{{ $item->rate }}</td>
                                    <td style="border:1px solid #000;text-align:center">₹{{ $item->total_amount }}
                                    </td>
                                </tr>
                                <?php
                                $total += $item->total_amount;
                                ?>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="border:1px solid #000">
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">Total</td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">₹{{ $total }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="card-body">
                    <h6 style="text-align: center">Payment History</h6>
                    <table cellpadding="3" cellspacing='3' border="0" width="100%">
                        <thead style="background: #000;color:#ffffff">
                            <tr>
                                <th style="border:1px solid #000;text-align:center">#</th>
                                <th style="border:1px solid #000;text-align:center">Date</th>
                                <th style="border:1px solid #000;text-align:center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total1 = 0;
                            $i = 1; ?>
                            @if ($paymentHistory->isNotEmpty())

                                @foreach ($paymentHistory as $item)
                                    <tr style="border:1px solid #000">
                                        <td style="border:1px solid #000;text-align:center">{{ $i++ }}</td>
                                        <td style="border:1px solid #000;text-align:center">
                                            {{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                        <td
                                            style="border:1px
                                        solid #000;text-align:center">
                                            ₹{{ $item->amount }}
                                        </td>
                                        </td>
                                    </tr>
                                    <?php
                                    $total1 += $item->amount;
                                    ?>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">No Advance Amount</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr style="border:1px solid #000">
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">Total Amount</td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">
                                    ₹{{ $total1 }}
                                </td>
                            </tr>
                            <tr style="border:1px solid #000">
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">Remaining Amount
                                </td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">
                                    ₹{{ $total - $total1 }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <button class="no-print btn btn-primary mt-2" onclick="window.print();">Print</button>
                </div>

            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
