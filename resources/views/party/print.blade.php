@extends('layouts.app')
@section('title', 'Statement - ' . $employee->name)
@section('party', 'active')

@section('content')
    @php
        $authUser = Auth::guard('web')->user();
        $companyTitle = !empty($authUser->company_name) ? $authUser->company_name : companyName($authUser->company_id);
        $selectedYear = request('year') ?: date('Y');

        // Pre-compute rows, running balance and totals once.
        $rows = [];
        $total = $creditAmount = $runningBalance = 0;
        foreach ($orders as $item) {
            $amount = $item->total_amount ?? 0;
            $credit = $item->credit ?? 0;
            $runningBalance += $amount - $credit;
            $total += $amount;
            $creditAmount += $credit;
            $rows[] = [$item, $runningBalance];
        }

        $money = fn($v) => '₹' . number_format((float) $v, 2);
    @endphp

    @include('partials.statement-styles')

    <!-- Toolbar (screen only) -->
    <section class="content-header no-print">
        <div class="container-fluid">
            <div class="st-toolbar mb-2">
                <div>
                    <h1 class="m-0">Account Statement</h1>
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
                    <a href="{{ route('party.order', $employee->guid) }}" class="btn btn-secondary">
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

                    <!-- Party -->
                    <div class="st-party">
                        <div>
                            <div class="st-label">Statement for</div>
                            <div class="st-party-name">{{ $employee->name }}</div>
                            @if (!empty($employee->phone))
                                <div class="st-muted">Mobile: {{ $employee->phone }}</div>
                            @endif
                            @if (!empty($employee->address))
                                <div class="st-muted" style="max-width:360px">{{ $employee->address }}</div>
                            @endif
                        </div>
                        <div style="text-align:right">
                            <div class="st-label">Entries</div>
                            <div class="st-party-name">{{ count($rows) }}</div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="st-summary">
                        <div class="st-box">
                            <div class="st-label">Total Amount</div>
                            <div class="st-val">{{ $money($total) }}</div>
                        </div>
                        <div class="st-box">
                            <div class="st-label">Credit Received</div>
                            <div class="st-val">{{ $money($creditAmount) }}</div>
                        </div>
                        <div class="st-box st-box--due">
                            <div class="st-label">Balance Due</div>
                            <div class="st-val">{{ $money($runningBalance) }}</div>
                        </div>
                    </div>

                    <!-- Ledger -->
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
                                    <th class="num">Amount</th>
                                    <th class="num">Credit</th>
                                    <th class="num">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $i => [$item, $balance])
                                    @php $isPayment = empty($item->item_id); @endphp
                                    <tr class="{{ $isPayment ? 'st-pay' : '' }}">
                                        <td class="c">{{ $i + 1 }}</td>
                                        <td style="white-space:nowrap">
                                            {{ !empty($item->date) ? date('d-m-Y', strtotime($item->date)) : date('d-m-Y', strtotime($item->created_at)) }}
                                        </td>
                                        <td>
                                            @if ($isPayment)
                                                {{ $item->payment_name }}
                                                <span class="st-pay-tag">{{ $item->payment_method ?: 'Payment' }}</span>
                                            @else
                                                {{ getItemName($item->item_id) }}
                                            @endif
                                        </td>
                                        <td class="c">{{ sizeName($item->size_id) }}</td>
                                        <td class="num">{{ $item->qty }}</td>
                                        <td class="num">{{ !empty($item->rate) ? '₹' . $item->rate : '' }}</td>
                                        <td class="num">{{ !empty($item->total_amount) ? $money($item->total_amount) : '' }}</td>
                                        <td class="num st-credit">{{ !empty($item->credit) ? $money($item->credit) : '' }}</td>
                                        <td class="num"><strong>{{ $money($balance) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="st-empty">No entries for {{ $selectedYear }}.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if (count($rows))
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="num">Total</td>
                                        <td class="num">{{ $money($total) }}</td>
                                        <td class="num st-credit">{{ $money($creditAmount) }}</td>
                                        <td class="num st-grand">{{ $money($runningBalance) }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    <!-- Signatures -->
                    <div class="st-sign">
                        <div>Party Signature</div>
                        <div>Authorised Signatory</div>
                    </div>

                    <div class="st-foot">
                        This is a computer-generated statement from {{ $companyTitle }}.
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
