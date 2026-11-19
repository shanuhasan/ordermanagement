@extends('layouts.app')

@section('title', 'Dashboard')
@section('dashboard', 'active')
@section('content')
    @php
        $remaining = $totalAmount - $paidAmount;
        $completePct = $totalPcs > 0 ? min(100, round(($totalComplete / $totalPcs) * 100)) : 0;
        $paidPct = $totalAmount > 0 ? min(100, round(($paidAmount / $totalAmount) * 100)) : 0;
    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="pm-page-title mb-2">
                <div>
                    <h1 class="m-0">Welcome back, {{ Auth::user()->name }}</h1>
                    <p>Here's how production and payments are tracking for {{ $year }}.</p>
                </div>
                <div>
                    <a href="{{ route('employee.index') }}" class="btn btn-secondary">
                        <i class="fas fa-user-friends mr-1"></i> Employees
                    </a>
                    <a href="{{ route('party.index') }}" class="btn btn-primary">
                        <i class="fas fa-handshake mr-1"></i> Parties
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="pm-section-label">Production · {{ $year }}</div>
            <div class="row">
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('employee.index') }}" class="pm-stat pm-stat--slate">
                        <div class="pm-stat-icon"><i class="fas fa-users"></i></div>
                        <div class="pm-stat-label">Total Employees</div>
                        <div class="pm-stat-value">{{ number_format($totalEmployee) }}</div>
                        <div class="pm-stat-foot">View all <i class="fas fa-arrow-right ml-1"></i></div>
                    </a>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="pm-stat pm-stat--blue">
                        <div class="pm-stat-icon"><i class="fas fa-layer-group"></i></div>
                        <div class="pm-stat-label">Total Pieces</div>
                        <div class="pm-stat-value">{{ number_format($totalPcs) }}</div>
                        <div class="pm-stat-foot">Issued this year</div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="pm-stat pm-stat--green">
                        <div class="pm-stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="pm-stat-label">Completed Pieces</div>
                        <div class="pm-stat-value">{{ number_format($totalComplete) }}</div>
                        <div class="pm-progress"><span style="width: {{ $completePct }}%"></span></div>
                        <div class="pm-stat-foot">{{ $completePct }}% of total received</div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="pm-stat pm-stat--amber">
                        <div class="pm-stat-icon"><i class="fas fa-hourglass-half"></i></div>
                        <div class="pm-stat-label">Pending Pieces</div>
                        <div class="pm-stat-value">{{ number_format($totalPending) }}</div>
                        <div class="pm-stat-foot">Still with workers</div>
                    </div>
                </div>
            </div>

            <div class="pm-section-label">Payments · {{ $year }}</div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="pm-stat pm-stat--hero">
                        <div class="pm-stat-icon"><i class="fas fa-rupee-sign"></i></div>
                        <div class="pm-stat-label">Total Amount</div>
                        <div class="pm-stat-value">₹{{ number_format($totalAmount, 2) }}</div>
                        <div class="pm-progress"><span style="width: {{ $paidPct }}%"></span></div>
                        <div class="pm-stat-foot">{{ $paidPct }}% paid so far</div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="pm-stat pm-stat--green">
                        <div class="pm-stat-icon"><i class="fas fa-wallet"></i></div>
                        <div class="pm-stat-label">Paid Amount</div>
                        <div class="pm-stat-value">₹{{ number_format($paidAmount, 2) }}</div>
                        <div class="pm-stat-foot">Advances &amp; payments made</div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="pm-stat pm-stat--rose">
                        <div class="pm-stat-icon"><i class="fas fa-file-invoice"></i></div>
                        <div class="pm-stat-label">Remaining Amount</div>
                        <div class="pm-stat-value">₹{{ number_format($remaining, 2) }}</div>
                        <div class="pm-stat-foot">Balance still payable</div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
