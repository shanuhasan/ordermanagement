{{--
    Shared printable statement for Employees (karigar) and Contractors (thekedar).
    Expects: $employee, $orders, $paymentHistory, $routePrefix ('employee' | 'contractor'), $roleLabel
--}}
@php
    $authUser = Auth::guard('web')->user();
    $companyTitle = !empty($authUser->company_name) ? $authUser->company_name : companyName($authUser->company_id);
    $selectedYear = request('year') ?: date('Y');

    $workTotal = $orders->sum('total_amount');
    $workPcs = $orders->sum('qty');
    $paidTotal = $paymentHistory->sum('amount');
    $remaining = $workTotal - $paidTotal;

    $money = fn($v) => '₹' . number_format((float) $v, 2);
@endphp

@include('partials.statement-styles')

<!-- Toolbar (screen only) -->
<section class="content-header no-print">
    <div class="container-fluid">
        <div class="st-toolbar mb-2">
            <div>
                <h1 class="m-0">Work &amp; Payment Statement</h1>
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
                        <h2>Statement</h2>
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
                        @if (!empty($employee->address))
                            <div class="st-muted" style="max-width:360px">{{ $employee->address }}</div>
                        @endif
                    </div>
                    <div style="text-align:right">
                        <div class="st-label">Total Pieces</div>
                        <div class="st-party-name">{{ number_format($workPcs) }}</div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="st-summary">
                    <div class="st-box">
                        <div class="st-label">Total Work Amount</div>
                        <div class="st-val">{{ $money($workTotal) }}</div>
                    </div>
                    <div class="st-box st-box--paid">
                        <div class="st-label">Paid / Advance</div>
                        <div class="st-val">{{ $money($paidTotal) }}</div>
                    </div>
                    <div class="st-box {{ $remaining < 0 ? 'st-box--neg' : 'st-box--due' }}">
                        <div class="st-label">{{ $remaining < 0 ? 'Overpaid' : 'Remaining Amount' }}</div>
                        <div class="st-val">{{ $money(abs($remaining)) }}</div>
                    </div>
                </div>

                <!-- Work -->
                <div class="st-section">
                    <h3>Work History</h3>
                    <span>{{ $orders->count() }} {{ Str::plural('entry', $orders->count()) }}</span>
                </div>
                <div class="st-table-wrap">
                    <table class="st-table">
                        <thead>
                            <tr>
                                <th class="c">#</th>
                                <th>Date</th>
                                <th>Particular</th>
                                <th class="c">Size</th>
                                <th class="num">Pcs</th>
                                <th class="num">Rate</th>
                                <th class="num">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $i => $item)
                                <tr>
                                    <td class="c">{{ $i + 1 }}</td>
                                    <td style="white-space:nowrap">
                                        {{ date('d-m-Y', strtotime(!empty($item->date) ? $item->date : $item->created_at)) }}
                                    </td>
                                    <td>{{ !empty($item->item_id) ? getItemName($item->item_id) : $item->particular }}</td>
                                    <td class="c">{{ sizeName($item->size) }}</td>
                                    <td class="num">{{ $item->qty }}</td>
                                    <td class="num">₹{{ $item->rate }}</td>
                                    <td class="num">{{ $money($item->total_amount) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="st-empty">No work entries for {{ $selectedYear }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($orders->isNotEmpty())
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="num">Total</td>
                                    <td class="num">{{ number_format($workPcs) }}</td>
                                    <td></td>
                                    <td class="num st-grand">{{ $money($workTotal) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                <!-- Payments -->
                <div class="st-section">
                    <h3>Payment History</h3>
                    <span>{{ $paymentHistory->count() }} {{ Str::plural('payment', $paymentHistory->count()) }}</span>
                </div>
                <div class="st-table-wrap">
                    <table class="st-table">
                        <thead>
                            <tr>
                                <th class="c" style="width:60px">#</th>
                                <th>Date</th>
                                <th>Payment Method</th>
                                <th class="num">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paymentHistory as $i => $item)
                                <tr>
                                    <td class="c">{{ $i + 1 }}</td>
                                    <td style="white-space:nowrap">{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <span class="st-pay-tag" style="margin-left:0">{{ $item->payment_method ?: 'Payment' }}</span>
                                    </td>
                                    <td class="num st-credit">{{ $money($item->amount) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="st-empty">No advance / payment for {{ $selectedYear }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="num">Total Paid</td>
                                <td class="num st-credit">{{ $money($paidTotal) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="num" style="border-top:0;padding-top:4px">
                                    {{ $remaining < 0 ? 'Overpaid' : 'Remaining Amount' }}
                                </td>
                                <td class="num st-grand" style="border-top:0;padding-top:4px;{{ $remaining < 0 ? 'color:#e11d48' : '' }}">
                                    {{ $money(abs($remaining)) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Signatures -->
                <div class="st-sign">
                    <div>{{ $roleLabel }} Signature</div>
                    <div>Authorised Signatory</div>
                </div>

                <div class="st-foot">
                    This is a computer-generated statement from {{ $companyTitle }}.
                </div>
            </div>
        </div>
    </div>
</section>
