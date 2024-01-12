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
                    <h2>{{ $employeeDetail->name }}
                        ({{ !empty($employeeDetail->code) ? 'Code:- ' . $employeeDetail->code . ',' : '' }}
                        {{ !empty($employeeDetail->phone) ? 'Mobile:- ' . $employeeDetail->phone : '' }})</h2>
                    <table cellpadding="3" cellspacing='3' border="0" width="100%">
                        <thead style="background: #000;color:#ffffff">
                            <tr>
                                <th style="border:1px solid #000;text-align:center">#</th>
                                <th style="border:1px solid #000;text-align:center">Date</th>
                                <th style="border:1px solid #000;text-align:center">Particular</th>
                                <th style="border:1px solid #000;text-align:center">Pieces</th>
                                <th style="border:1px solid #000;text-align:center">Rate</th>
                                <th style="border:1px solid #000;text-align:center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border:1px solid #000">
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center">
                                    {{ !empty($item->date) ? date('d-m-Y', strtotime($item->date)) : date('d-m-Y h:i A', strtotime($item->created_at)) }}
                                </td>
                                <td style="border:1px solid #000;text-align:center">{{ $order->particular }}
                                <td style="border:1px solid #000;text-align:center">{{ $order->qty }}
                                </td>
                                <td style="border:1px solid #000;text-align:center">₹{{ $order->rate }}</td>
                                <td style="border:1px solid #000;text-align:center">₹{{ $order->total_amount }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="no-print btn btn-primary mt-2" onclick="window.print();">Print</button>
                    <a href="{{ route('employee.order', $employeeId) }}" class="no-print btn btn-info mt-2">Back</a>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
