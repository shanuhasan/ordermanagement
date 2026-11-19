{{--
    Shared printable payment history for Employees (karigar) and Contractors (thekedar).
    Expects: $employee, $employeePaymentHistory, $routePrefix ('employee' | 'contractor'), $roleLabel
    Optional: $showReference (bool) – adds the Reference column (payment_name), used by Masters.
--}}
@php
    $authUser = Auth::guard('web')->user();
    $companyTitle = !empty($authUser->company_name) ? $authUser->company_name : companyName($authUser->company_id);
    $selectedYear = request('year') ?: date('Y');

    $payments = $employeePaymentHistory;
    $totalPaid = $payments->sum('amount');
    $byMethod = $payments->groupBy(fn($p) => $p->payment_method ?: 'Other')->map->sum('amount');
    $lastPayment = $payments->first();
    $showReference = $showReference ?? false;
    $cols = $showReference ? 5 : 4;
    $payDate = fn($p) => date('d-m-Y', strtotime(!empty($p->date) ? $p->date : $p->created_at));

    $money = fn($v) => '₹' . number_format((float) $v, 2);
@endphp

@include('partials.statement-styles')

<style>
    .st-summary--4 {
        grid-template-columns: repeat(4, 1fr);
    }

    @media (max-width: 991.98px) {
        .st-summary--4 {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<!-- Toolbar (screen only) -->
<section class="content-header no-print">
    <div class="container-fluid">
        <div class="st-toolbar mb-2">
            <div>
                <h1 class="m-0">Payment History</h1>
                <p class="text-muted mb-0">{{ $employee->name }} · {{ $selectedYear }}</p>
            </div>

            <form action="" method="get">
                <div>
                    <label for="year" class="mb-1">Year</label>
                    <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                        @foreach (\App\Models\Year::getYear() as $val)
                            <option value="{{ $val->name }}" {{ $selectedYear == $val->name ? 'selected' : '' }}>
                                {{ $val->name }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route($routePrefix . '.order', $employee->guid) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
                <a href="{{ route($routePrefix . '.order.amount', $employee->guid) }}" class="btn btn-warning">
                    <i class="fas fa-plus mr-1"></i> Add Payment
                </a>
                <button type="button" class="btn btn-primary" onclick="window.print();">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            </form>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="st-sheet">
            <div class="st-band"></div>
            <div class="st-pad">

                <!-- Header -->
                <div class="st-head">
                    <div class="st-brand">
                        <div class="st-mark">{{ strtoupper(mb_substr($companyTitle ?: 'J', 0, 1)) }}</div>
                        <div>
                            <div class="st-company">{{ $companyTitle }}</div>
                            <div class="st-muted">Order Management</div>
                        </div>
                    </div>
                    <div class="st-title">
                        <h2>Payments</h2>
                        <div class="st-meta">Period: <b>Jan – Dec {{ $selectedYear }}</b></div>
                        <div class="st-meta">Generated: <b>{{ date('d M Y, h:i A') }}</b></div>
                    </div>
                </div>

                <!-- Worker -->
                <div class="st-party">
                    <div>
                        <div class="st-label">{{ $roleLabel }}</div>
                        <div class="st-party-name">{{ $employee->name }}</div>
                        @if (!empty($employee->code))
                            <div class="st-muted">Code: {{ $employee->code }}</div>
                        @endif
                        @if (!empty($employee->phone))
                            <div class="st-muted">Mobile: {{ $employee->phone }}</div>
                        @endif
                    </div>
                    <div style="text-align:right">
                        <div class="st-label">Last Payment</div>
                        <div class="st-party-name">
                            {{ $lastPayment ? $payDate($lastPayment) : '—' }}
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="st-summary st-summary--4">
                    <div class="st-box st-box--due">
                        <div class="st-label">Total Paid</div>
                        <div class="st-val">{{ $money($totalPaid) }}</div>
                    </div>
                    <div class="st-box">
                        <div class="st-label">Cash</div>
                        <div class="st-val">{{ $money($byMethod['Cash'] ?? 0) }}</div>
                    </div>
                    <div class="st-box st-box--paid">
                        <div class="st-label">Online</div>
                        <div class="st-val">{{ $money($byMethod['Online'] ?? 0) }}</div>
                    </div>
                    <div class="st-box">
                        <div class="st-label">Payments</div>
                        <div class="st-val">{{ $payments->count() }}</div>
                    </div>
                </div>

                <!-- Payments -->
                <div class="st-section">
                    <h3>Payment History</h3>
                    <span>Newest first</span>
                </div>
                <div class="st-table-wrap">
                    <table class="st-table">
                        <thead>
                            <tr>
                                <th class="c" style="width:60px">#</th>
                                <th>Date</th>
                                <th>Payment Method</th>
                                @if ($showReference)
                                    <th>Reference</th>
                                @endif
                                <th class="num">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $i => $item)
                                <tr>
                                    <td class="c">{{ $i + 1 }}</td>
                                    <td style="white-space:nowrap">{{ $payDate($item) }}</td>
                                    <td>
                                        <span class="st-pay-tag" style="margin-left:0">{{ $item->payment_method ?: 'Payment' }}</span>
                                    </td>
                                    @if ($showReference)
                                        <td>{{ $item->payment_name ?: '—' }}</td>
                                    @endif
                                    <td class="num st-credit"><strong>{{ $money($item->amount) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $cols }}" class="st-empty">No payments for {{ $selectedYear }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($payments->isNotEmpty())
                            <tfoot>
                                <tr>
                                    <td colspan="{{ $cols - 1 }}" class="num">Total Paid</td>
                                    <td class="num st-grand">{{ $money($totalPaid) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                <!-- Signatures -->
                <div class="st-sign">
                    <div>{{ $roleLabel }} Signature</div>
                    <div>Authorised Signatory</div>
                </div>

                <div class="st-foot">
                    This is a computer-generated record from {{ $companyTitle }}.
                </div>
            </div>
        </div>
    </div>
</section>
