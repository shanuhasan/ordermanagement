@extends('layouts.app')
@section('title', 'Credit Amount')
@section('party', 'active')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            @include('message')
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>
                        Credit Amount
                    </h3>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('party.order', $employee->guid) }}" class="btn btn-primary">Back</a>
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
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('party.order.payment') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $employee->id }}" name="party_id" id="employee_id">
                        <input type="hidden" value="{{ $employee->guid }}" name="pguid">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="rate">Credit Amount</label>
                                            <input type="text" name="amount"
                                                class="form-control only-number @error('amount') is-invalid	@enderror"
                                                placeholder="Credit Amount">
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
                                <a href="{{ route('party.order', $employee->guid) }}" class="btn btn-info">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('script')

@endsection
