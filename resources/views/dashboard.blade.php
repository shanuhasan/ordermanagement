@extends('layouts.app')

@section('title', 'Dashboard')
@section('dashboard', 'active')
@section('content')

    <style>
        .inner {
            text-align: center;
        }
    </style>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box card">
                        <div class="inner">
                            <h3>{{ $totalEmployee }}</h3>
                            <h4><strong>Total Employee</strong></h4>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('employee.index') }}" class="small-box-footer text-dark">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box card">
                        <div class="inner">
                            <h3>{{ number_format($totalPcs) }}</h3>
                            <h4><strong>Total Piece</strong></h4>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="javascript:void(0);" class="small-box-footer text-dark">&nbsp</a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box card">
                        <div class="inner">
                            <h3>{{ number_format($totalComplete) }}</h3>
                            <h4><strong>Complete Piece</strong></h4>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="javascript:void(0);" class="small-box-footer text-dark">&nbsp</a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box card">
                        <div class="inner">
                            <h3>{{ number_format($totalPending) }}</h3>
                            <h4><strong>Pending Piece</strong></h4>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="javascript:void(0);" class="small-box-footer text-dark">&nbsp</a>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box card">
                        <div class="inner" style="background:rgb(255, 147, 6);color:#fff">
                            <h3>₹{{ number_format($totalAmount) }}</h3>
                            <h4><strong>Total Amount</strong></h4>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="javascript:void(0);" class="small-box-footer text-dark">&nbsp;</a>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box card">
                        <div class="inner" style="background:green;color:#fff">
                            <h3>₹{{ number_format($paidAmount) }}</h3>
                            <h4><strong>Paid Amount</strong></h4>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="javascript:void(0);" class="small-box-footer text-dark">&nbsp;</a>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box card">
                        <div class="inner" style="background:red;color:#fff">
                            <h3>₹{{ number_format($totalAmount - $paidAmount) }}</h3>
                            <h4><strong>Remaining Amount</strong></h4>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="javascript:void(0);" class="small-box-footer text-dark">&nbsp;</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->

@endsection

@section('script')
    <script>
        console.log('test');
    </script>
@endsection
