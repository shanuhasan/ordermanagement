@extends('layouts.app')
@section('title', 'Advance Amount')
@section('contractor', 'active')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            @include('message')
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h3>
                        {{ $employee->name }}
                        ({{ !empty($employee->code) ? 'Code:- ' . $employee->code . ',' : '' }}
                        {{ !empty($employee->phone) ? 'Mobile:- ' . $employee->phone : ' ' }})
                        Advance Amount
                    </h3> --}}
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('contractor.order.payment.history', $employee->guid) }}" class="btn btn-info">Payment
                        History</a>
                    <a href="{{ route('contractor.order', $employee->guid) }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body text-center" style="background-color: green; color:#fff">
                    <h5>
                        {{ $employee->name }}
                        ({{ !empty($employee->code) ? 'Code:- ' . $employee->code . ',' : '' }}
                        {{ !empty($employee->phone) ? 'Mobile:- ' . $employee->phone : ' ' }})
                    </h5>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <form action="" method="get">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
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
                        <button type="submit" class="btn btn-success">Filter</button>
                        <a href="{{ route('contractor.order', $employee->guid) }}" class="btn btn-danger">Reset</a>
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
            <div class="row">
                <div class="col-md-8">
                    <form action="{{ route('contractor.order.payment') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $employee->id }}" name="employee_id" id="employee_id">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="rate">Advance Amount</label>
                                            <input type="text" name="amount"
                                                class="form-control only-number @error('amount') is-invalid	@enderror"
                                                placeholder="Advance Amount">
                                            @error('amount')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="date">Date<span style="color: red">*</span></label>
                                            <input type="date" name="date" id="date" class="form-control"
                                                placeholder="Date" value="{{ date('Y-m-d') }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="rate">Payment Method</label>
                                            <select name="payment_method" id="payment_method" class="form-control">
                                                @foreach (paymentMethod() as $key => $item)
                                                    <option value="{{ $key }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('contractor.order', $employee->guid) }}" class="btn btn-info">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="card">
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap table-bordered">
                                        <tbody>
                                            <tr style="font-size:24px">
                                                <td><strong>Total Amount</strong></td>
                                                <td align="right"><strong>₹{{ $totalAmount }}</strong></td>
                                            </tr>
                                            <tr style="background-color: green; color:#fff;font-size:24px">
                                                <td><strong>Paid Amount</strong></td>
                                                <td align="right">
                                                    <strong>₹{{ $employeeTotalPayment }}</strong>
                                                </td>
                                            </tr>
                                            <tr style="background-color: red; color:#fff;font-size:24px">
                                                <td><strong>Remaining Amount</strong></td>
                                                <td align="right">
                                                    <strong>₹{{ $totalAmount - $employeeTotalPayment }}</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->

    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 style="font-weight:bold">PAYMENT HISTORY</h5>
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
                                            {{ date('d-m-Y', strtotime($item->created_at)) }}
                                        </td>
                                        <td style="border:1px solid #000;text-align:center">{{ $item->payment_method }}
                                        </td>
                                        <td style="border:1px solid #000;text-align:center">₹{{ $item->amount }}</td>
                                    </tr>
                                    <?php $total += $item->amount; ?>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" style="border:1px solid #000;text-align:center">Data not found.
                                    </td>
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

@section('script')

@endsection
