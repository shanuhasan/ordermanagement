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
                    <h1>Received Piece History</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a class="btn btn-success" onclick="window.print();">Print</a>
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
                                <th style="border:1px solid #000;text-align:center">Size</th>
                                <th style="border:1px solid #000;text-align:center">Received Piece</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr style="border:1px solid #000">
                                    <td style="border:1px solid #000;text-align:center"></td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ date('d-m-Y', strtotime($item->created_at)) }}
                                    </td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ getOrder($item->order_id)->particular }}
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ sizeName(getOrder($item->order_id)->size) }}
                                    </td>
                                    <td style="border:1px solid #000;text-align:center">{{ $item->qty }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
