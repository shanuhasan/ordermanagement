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
                    <a class="btn btn-primary" href="{{ route('employee.order', $employeeId) }}">Back</a>
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
                                <a href="{{ route('employee.order.print', $employeeId) }}" class="btn btn-danger">Reset</a>
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
                <div class="card-body">
                    <h5 style="font-weight:bold">HISTORY
                        ({{ strtoupper($employeeDetail->name) }})</h5>
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
                            <?php $total = 0;
                            $i = 1; ?>
                            @foreach ($orders as $item)
                                <tr style="border:1px solid #000">
                                    <td style="border:1px solid #000;text-align:center">{{ $i++ }}</td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ !empty($item->date) ? date('d-m-Y', strtotime($item->date)) : date('d-m-Y h:i A', strtotime($item->created_at)) }}
                                    </td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ !empty($item->item_id) ? getItemName($item->item_id) : $item->particular }}
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
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">Total</td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">₹{{ $total }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- payment history --}}
                    <h5 class="mt-2" style="font-weight:bold">PAYMENT HISTORY</h5>
                    <table cellpadding="3" cellspacing='3' border="0" width="100%">
                        <thead style="background: #000;color:#ffffff">
                            <tr>
                                <th style="border:1px solid #000;text-align:center">#</th>
                                <th style="border:1px solid #000;text-align:center">Date</th>
                                <th style="border:1px solid #000;text-align:center">Payment Method</th>
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
                                            {{ date('d-m-Y h:i A', strtotime($item->created_at)) }}</td>
                                        <td style="border:1px solid #000;text-align:center">{{ $item->payment_method }}
                                        </td>
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
                                <tr style="border:1px solid #000">
                                    <td style="border:1px solid #000;text-align:center"></td>
                                    <td style="border:1px solid #000;text-align:center"></td>
                                    <td style="border:1px solid #000;text-align:center">No Advance Amount.</td>
                                    <td style="border:1px solid #000;text-align:center"></td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr style="border:1px solid #000">
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">Total Amount</td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">
                                    ₹{{ $total1 }}
                                </td>
                            </tr>
                            <tr style="border:1px solid #000">
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">Remaining Amount
                                </td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">
                                    ₹{{ $total - $total1 }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="card-body">

                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
