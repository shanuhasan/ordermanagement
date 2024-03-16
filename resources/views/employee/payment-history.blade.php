@extends('layouts.app')
@section('title', 'Payment History')
@section('employee', 'active')

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
                        {{ $employee->name }}
                        ({{ !empty($employee->code) ? 'Code:- ' . $employee->code . ',' : '' }}
                        {{ !empty($employee->phone) ? 'Mobile:- ' . $employee->phone : ' ' }})
                    </h3>
                </div>
                <div class="col-sm-6 text-right">
                    <a onclick="window.print();" class="btn btn-success">Print </a>
                    <a href="{{ route('employee.order', $employee->guid) }}" class="btn btn-primary">Back</a>
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
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Filter</button>
                        <a href="{{ route('employee.order.payment.history', $employee->guid) }}"
                            class="btn btn-danger">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </section>


    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 style="font-weight:bold">PAYMENT HISTORY
                        ({{ strtoupper($employee->name) }})
                    </h5>
                    <table cellpadding="3" cellspacing='3' border="0" width="100%">
                        <thead style="background: #000;color:#ffffff">
                            <tr>
                                <th style="border:1px solid #000;text-align:center">#</th>
                                <th style="border:1px solid #000;text-align:center">Date</th>
                                <th style="border:1px solid #000;text-align:center">Pament Method</th>
                                <th style="border:1px solid #000;text-align:center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0;
                            $i = 1; ?>
                            @if ($employeePaymentHistory->isNotEmpty())
                                @foreach ($employeePaymentHistory as $item)
                                    <tr>
                                        <td style="border:1px solid #000;text-align:center">{{ $i++ }}</td>
                                        <td style="border:1px solid #000;text-align:center">
                                            {{ date('d-m-Y h:i A', strtotime($item->created_at)) }}
                                        </td>
                                        <td style="border:1px solid #000;text-align:center">{{ $item->payment_method }}
                                        </td>
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
                        <tfoot>
                            <tr>
                                <th style="border:1px solid #000;text-align:center"></th>
                                <th style="border:1px solid #000;text-align:center"></th>
                                <th style="border:1px solid #000;text-align:center"><strong>Total Paid</strong></th>
                                <th style="border:1px solid #000;text-align:center">
                                    <strong>₹{{ $total }}</strong>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection
