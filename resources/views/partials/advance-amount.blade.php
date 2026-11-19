{{--
    Shared "Advance Amount" page for Employees, Contractors and Masters.
    Expects: $employee, $totalAmount, $employeeTotalPayment, $employeePaymentHistory,
             $routePrefix ('employee' | 'contractor' | 'master'), $roleLabel
    Optional: $showReference (bool) – Online reference name field + column (Masters).
--}}
@php
    $selectedYear = request('year') ?: date('Y');
    $showReference = $showReference ?? false;

    $paid = (float) $employeeTotalPayment;
    $total = (float) $totalAmount;
    $remaining = $total - $paid;
    $paidPct = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;

    $payments = $employeePaymentHistory->sortByDesc(fn($p) => $p->date ?? $p->created_at)->values();
    $byMethod = $payments->groupBy(fn($p) => $p->payment_method ?: 'Other')->map->sum('amount');
    $payDate = fn($p) => date('d-m-Y', strtotime(!empty($p->date) ? $p->date : $p->created_at));
    $cols = $showReference ? 5 : 4;

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
                        {{ $roleLabel }}
                        @if (!empty($employee->code))
                            · Code {{ $employee->code }}
                        @endif
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
                <a href="{{ route($routePrefix . '.order', $employee->guid) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
                <a href="{{ route($routePrefix . '.order.payment.history', $employee->guid) }}" class="btn btn-info">
                    <i class="fas fa-history mr-1"></i> Payment History
                </a>
            </form>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @include('message')

        <div class="row">
            <!-- Record payment -->
            <div class="col-lg-7">
                <form action="{{ route($routePrefix . '.order.payment') }}" method="post" class="aa-card">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <div class="aa-card-head">
                        <h3><i class="fas fa-hand-holding-usd mr-2 text-success"></i>Record Advance / Payment</h3>
                    </div>

                    <div class="aa-card-body">
                        <div class="row">
                            <div class="col-md-7 mb-3">
                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                <div class="aa-amount">
                                    <span>₹</span>
                                    <input type="text" name="amount" id="amount" value="{{ old('amount') }}"
                                        class="form-control only-number @error('amount') is-invalid @enderror"
                                        placeholder="0.00" autocomplete="off">
                                </div>
                                @error('amount')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                                <div class="aa-chips">
                                    @foreach ([500, 1000, 2000, 5000] as $quick)
                                        <button type="button" class="aa-chip" data-amount="{{ $quick }}">₹{{ number_format($quick) }}</button>
                                    @endforeach
                                    @if ($remaining > 0)
                                        <button type="button" class="aa-chip" data-amount="{{ round($remaining, 2) }}">
                                            Full balance {{ $money($remaining) }}
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

                            @if ($showReference)
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
                            @endif
                        </div>

                        <div class="d-flex flex-wrap" style="gap:8px">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-check mr-1"></i> Save Payment
                            </button>
                            <a href="{{ route($routePrefix . '.order', $employee->guid) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Balance -->
            <div class="col-lg-5">
                <div class="aa-balance">
                    <div class="st-label">{{ $remaining < 0 ? 'Overpaid' : 'Remaining Amount' }} · {{ $selectedYear }}</div>
                    <div class="aa-balance-big {{ $remaining < 0 ? 'is-over' : '' }}">{{ $money(abs($remaining)) }}</div>
                    <div class="pm-progress"><span style="width: {{ $paidPct }}%"></span></div>
                    <div class="small" style="color:#94a3b8">{{ $paidPct }}% of work amount paid</div>

                    <div class="aa-lines">
                        <div class="aa-line">
                            <span>Total Work Amount</span>
                            <b>{{ $money($total) }}</b>
                        </div>
                        <div class="aa-line">
                            <span>Paid Amount</span>
                            <b style="color:#6ee7b7">{{ $money($paid) }}</b>
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

        <!-- Payments -->
        <div class="aa-card">
            <div class="aa-card-head">
                <h3><i class="fas fa-receipt mr-2 text-muted"></i>Payments in {{ $selectedYear }}</h3>
                <span class="text-muted small">{{ $payments->count() }} {{ Str::plural('payment', $payments->count()) }} · newest first</span>
            </div>
            <div class="st-table-wrap">
                <table class="st-table">
                    <thead>
                        <tr>
                            <th class="c" style="width:60px;border-radius:0">#</th>
                            <th>Date</th>
                            <th>Payment Method</th>
                            @if ($showReference)
                                <th>Reference</th>
                            @endif
                            <th class="num" style="border-radius:0">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $i => $item)
                            <tr>
                                <td class="c">{{ $i + 1 }}</td>
                                <td style="white-space:nowrap">{{ $payDate($item) }}</td>
                                <td><span class="st-pay-tag" style="margin-left:0">{{ $item->payment_method ?: 'Payment' }}</span></td>
                                @if ($showReference)
                                    <td>{{ $item->payment_name ?: '—' }}</td>
                                @endif
                                <td class="num st-credit"><strong>{{ $money($item->amount) }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $cols }}" class="st-empty">No payments recorded in {{ $selectedYear }} yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($payments->isNotEmpty())
                        <tfoot>
                            <tr>
                                <td colspan="{{ $cols - 1 }}" class="num">Total Paid</td>
                                <td class="num st-grand">{{ $money($paid) }}</td>
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
        var input = document.getElementById('amount');
        input.value = chip.getAttribute('data-amount');
        input.focus();
    });

    @if ($showReference)
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
    @endif
</script>
