@extends('layouts.app')
@section('title', 'Credit Amount - ' . $employee->name)
@section('party', 'active')

@section('content')
    @php
        $selectedYear = request('year') ?: date('Y');

        $total = (float) $totalAmount;
        $credit = (float) $totalCredit;
        $balance = $total - $credit;
        $paidPct = $total > 0 ? min(100, round(($credit / $total) * 100)) : 0;

        $byMethod = $creditHistory->groupBy(fn($p) => $p->payment_method ?: 'Other')->map->sum('credit');
        $payDate = fn($p) => date('d-m-Y', strtotime(!empty($p->date) ? $p->date : $p->created_at));

        $money = fn($v) => '₹' . number_format((float) $v, 2);
    @endphp

    @include('partials.statement-styles')
    @include('partials.advance-amount-styles')

    <!-- Toolbar -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="st-toolbar mb-2">
                <div class="aa-head">
                    <div class="aa-avatar">{{ strtoupper(mb_substr($employee->name, 0, 1)) }}</div>
                    <div>
                        <h1 class="m-0">{{ $employee->name }}</h1>
                        <p class="text-muted mb-0">
                            Party
                            @if (!empty($employee->phone))
                                · {{ $employee->phone }}
                            @endif
                        </p>
                    </div>
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
                    <a href="{{ route('party.order.print', $employee->guid) }}?year={{ $selectedYear }}" class="btn btn-info">
                        <i class="fas fa-file-invoice mr-1"></i> Statement
                    </a>
                </form>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('message')

            <div class="row">
                <!-- Record credit -->
                <div class="col-lg-7">
                    <form action="{{ route('party.order.payment') }}" method="post" class="aa-card">
                        @csrf
                        <input type="hidden" name="party_id" value="{{ $employee->id }}">
                        <input type="hidden" name="pguid" value="{{ $employee->guid }}">

                        <div class="aa-card-head">
                            <h3><i class="fas fa-hand-holding-usd mr-2 text-success"></i>Record Credit Received</h3>
                        </div>

                        <div class="aa-card-body">
                            <div class="row">
                                <div class="col-md-7 mb-3">
                                    <label for="credit">Credit Amount <span class="text-danger">*</span></label>
                                    <div class="aa-amount">
                                        <span>₹</span>
                                        <input type="text" name="credit" id="credit" value="{{ old('credit') }}"
                                            class="form-control only-number @error('credit') is-invalid @enderror"
                                            placeholder="0.00" autocomplete="off">
                                    </div>
                                    @error('credit')
                                        <p class="error">{{ $message }}</p>
                                    @enderror
                                    <div class="aa-chips">
                                        @foreach ([500, 1000, 5000, 10000] as $quick)
                                            <button type="button" class="aa-chip" data-amount="{{ $quick }}">₹{{ number_format($quick) }}</button>
                                        @endforeach
                                        @if ($balance > 0)
                                            <button type="button" class="aa-chip" data-amount="{{ round($balance, 2) }}">
                                                Full balance {{ $money($balance) }}
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-5 mb-3">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="{{ old('date', date('Y-m-d')) }}">
                                </div>

                                <div class="col-md-7 mb-3">
                                    <label>Payment Method</label>
                                    <div class="aa-methods">
                                        @foreach (paymentMethod() as $key => $label)
                                            <label>
                                                <input type="radio" name="payment_method" value="{{ $key }}"
                                                    {{ old('payment_method', 'Cash') == $key ? 'checked' : '' }}>
                                                <span class="aa-opt">
                                                    <i class="fas {{ $key == 'Online' ? 'fa-mobile-alt' : 'fa-money-bill-wave' }}"></i>
                                                    {{ $label }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-5 mb-3 js-ReferenceName">
                                    <label for="payment_name">Online Reference Name</label>
                                    <input type="text" name="payment_name" id="payment_name"
                                        value="{{ old('payment_name') }}"
                                        class="form-control @error('payment_name') is-invalid @enderror"
                                        placeholder="UPI / bank reference">
                                    @error('payment_name')
                                        <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex flex-wrap" style="gap:8px">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-check mr-1"></i> Save Credit
                                </button>
                                <a href="{{ route('party.order', $employee->guid) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Balance -->
                <div class="col-lg-5">
                    <div class="aa-balance">
                        <div class="st-label">{{ $balance < 0 ? 'Advance Received' : 'Balance Due' }} · {{ $selectedYear }}</div>
                        <div class="aa-balance-big {{ $balance < 0 ? 'is-over' : '' }}">{{ $money(abs($balance)) }}</div>
                        <div class="pm-progress"><span style="width: {{ $paidPct }}%"></span></div>
                        <div class="small" style="color:#94a3b8">{{ $paidPct }}% of order amount received</div>

                        <div class="aa-lines">
                            <div class="aa-line">
                                <span>Total Order Amount</span>
                                <b>{{ $money($total) }}</b>
                            </div>
                            <div class="aa-line">
                                <span>Credit Received</span>
                                <b style="color:#6ee7b7">{{ $money($credit) }}</b>
                            </div>
                            <div class="aa-line">
                                <span><i class="aa-dot" style="background:#34d399"></i>Cash</span>
                                <b>{{ $money($byMethod['Cash'] ?? 0) }}</b>
                            </div>
                            <div class="aa-line">
                                <span><i class="aa-dot" style="background:#38bdf8"></i>Online</span>
                                <b>{{ $money($byMethod['Online'] ?? 0) }}</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Credits -->
            <div class="aa-card">
                <div class="aa-card-head">
                    <h3><i class="fas fa-receipt mr-2 text-muted"></i>Credits in {{ $selectedYear }}</h3>
                    <span class="text-muted small">{{ $creditHistory->count() }} {{ Str::plural('entry', $creditHistory->count()) }} · newest first</span>
                </div>
                <div class="st-table-wrap">
                    <table class="st-table">
                        <thead>
                            <tr>
                                <th class="c" style="width:60px;border-radius:0">#</th>
                                <th>Date</th>
                                <th>Payment Method</th>
                                <th>Reference</th>
                                <th class="num" style="border-radius:0">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($creditHistory as $i => $item)
                                <tr>
                                    <td class="c">{{ $i + 1 }}</td>
                                    <td style="white-space:nowrap">{{ $payDate($item) }}</td>
                                    <td><span class="st-pay-tag" style="margin-left:0">{{ $item->payment_method ?: 'Payment' }}</span></td>
                                    <td>{{ $item->payment_name ?: '—' }}</td>
                                    <td class="num st-credit"><strong>{{ $money($item->credit) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="st-empty">No credit received in {{ $selectedYear }} yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($creditHistory->isNotEmpty())
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="num">Total Credit</td>
                                    <td class="num st-grand">{{ $money($credit) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('click', function(e) {
            var chip = e.target.closest('.aa-chip');
            if (!chip) return;
            var input = document.getElementById('credit');
            input.value = chip.getAttribute('data-amount');
            input.focus();
        });

        (function() {
            var ref = document.querySelector('.js-ReferenceName');
            function toggleRef() {
                var checked = document.querySelector('input[name="payment_method"]:checked');
                ref.style.display = checked && checked.value === 'Online' ? '' : 'none';
            }
            document.querySelectorAll('input[name="payment_method"]').forEach(function(r) {
                r.addEventListener('change', toggleRef);
            });
            toggleRef();
        })();
    </script>
@endsection
