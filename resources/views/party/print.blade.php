@extends('layouts.app')
@section('title', 'Print')
@section('party', 'active')

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
                    <h3>
                        Print
                    </h3>
                </div>
                <div class="col-sm-6 text-right">
                    <a class="btn btn-success" onclick="window.print();">Print</a>
                    <a class="btn btn-primary" href="{{ route('party.order', $employee->guid) }}">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="text-center" style="background-color: rgb(25, 0, 255); color:#fff;border-radius:20px;">
                    <h3>
                        <b>{{ $employee->name }}
                            ({{ !empty($employee->phone) ? 'Mobile:- ' . $employee->phone : ' ' }})</b>
                    </h3>
                </div>
            </div>
        </div>
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
                                        @foreach (\App\Models\Year::getYear() as $val)
                                            <?php
                                            $year = !empty(Request::get('year')) ? Request::get('year') : date('Y');
                                            ?>
                                            <option value="{{ $val->name }}" {{ $year == $val->name ? 'selected' : '' }}>
                                                {{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{ route('party.order.print', $employee->guid) }}" class="btn btn-danger">Reset</a>
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
                        ({{ strtoupper($employee->name) }})</h5>
                    <table cellpadding="3" cellspacing='3' border="0" width="100%">
                        <thead style="background: #000;color:#ffffff">
                            <tr>
                                <th style="border:1px solid #000;text-align:center">#</th>
                                <th style="border:1px solid #000;text-align:center">Date</th>
                                <th style="border:1px solid #000;text-align:center">Particular</th>
                                <th style="border:1px solid #000;text-align:center">Size</th>
                                <th style="border:1px solid #000;text-align:center">Piece</th>
                                <th style="border:1px solid #000;text-align:center">Rate</th>
                                <th style="border:1px solid #000;text-align:center">Amount</th>
                                <th style="border:1px solid #000;text-align:center">Credit</th>
                                <th style="border:1px solid #000;text-align:center">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = $creditAmount = 0;
                            $i = 1; ?>
                            @foreach ($orders as $item)
                                <tr style="border:1px solid #000">
                                    <td style="border:1px solid #000;text-align:center">{{ $i++ }}</td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ !empty($item->date) ? date('d-m-Y', strtotime($item->date)) : date('d-m-Y h:i A', strtotime($item->created_at)) }}
                                    </td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ !empty($item->item_id) ? getItemName($item->item_id) : $item->particular }}</td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ sizeName($item->size_id) }}</td>
                                    <td style="border:1px solid #000;text-align:center">{{ $item->qty }}</td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ !empty($item->rate) ? '₹' . $item->rate : '' }}</td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ !empty($item->total_amount) ? '₹' . $item->total_amount : '' }}</td>
                                    <td style="border:1px solid #000;text-align:center">
                                        {{ !empty($item->credit) ? '₹' . $item->credit : '' }}</td>
                                    {{ !empty($item->balance) ? '₹' . $item->balance : '' }}</td>
                                </tr>
                                <?php
                                $total += $item->total_amount;
                                $creditAmount += $item->credit;
                                ?>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="border:1px solid #000">
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center"></td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">Balance</td>
                                <td style="border:1px solid #000;text-align:center;font-weight:bold">
                                    ₹{{ $total - $creditAmount }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
