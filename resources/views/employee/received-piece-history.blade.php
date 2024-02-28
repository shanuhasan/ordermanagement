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

            .main-footer,
            .years {
                display: none
            }
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>{{ $employeeDetail->name }}
                        ({{ !empty($employeeDetail->code) ? 'Code:- ' . $employeeDetail->code . ',' : '' }}
                        {{ !empty($employeeDetail->phone) ? 'Mobile:- ' . $employeeDetail->phone : '' }})</h2>

                </div>
                <div class="col-sm-6 text-right">
                    <a class="btn btn-success" onclick="window.print();">Print</a>
                    <a href="{{ route('employee.order', $employeeId) }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <section class="content years">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <form action="" method="get">
                    <div class="card-body">
                        <div class="row">
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
                                <button type="submit" class="btn btn-success">Filter</button>
                                <a href="{{ route('employee.order.receivedPiece', $employeeId) }}"
                                    class="btn btn-danger">Reset</a>
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
            <div class="card">
                <h5 style="text-align: center;font-weight:bold">RECEIVED PIECE HISTORY
                    ({{ strtoupper($employeeDetail->name) }})</h5>
                <div class="card-body">
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
                            @if ($items->isNotEmpty())
                                @foreach ($items as $item)
                                    <tr style="border:1px solid #000">
                                        <td style="border:1px solid #000;text-align:center"></td>
                                        <td style="border:1px solid #000;text-align:center">
                                            {{ date('d-m-Y', strtotime($item->created_at)) }}
                                        </td>
                                        <td style="border:1px solid #000;text-align:center">
                                            {{-- {{ getOrder($item->order_id)->particular }} --}}
                                            {{ !empty(getOrder($item->order_id)->item_id) ? getItemName(getOrder($item->order_id)->item_id) : getOrder($item->order_id)->particular }}
                                        </td>
                                        <td style="border:1px solid #000;text-align:center">
                                            {{ sizeName(getOrder($item->order_id)->size) }}
                                        </td>
                                        <td style="border:1px solid #000;text-align:center">{{ $item->qty }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" style="text-align:center">Data not found.</td>
                                </tr>
                            @endif


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
