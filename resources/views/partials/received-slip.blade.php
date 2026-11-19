{{--
    Shared printable "received pieces" slip for one order (Employee / Contractor).
    Expects: $employee, $order, $items (ReceivedItem rows), $routePrefix, $roleLabel
--}}
@php
    $authUser = Auth::guard('web')->user();
    $companyTitle = !empty($authUser->company_name) ? $authUser->company_name : companyName($authUser->company_id);

    $particular = !empty($order->item_id) ? getItemName($order->item_id) : $order->particular;
    $size = sizeName($order->size);
    $issued = (int) $order->qty;
    $received = (int) $items->sum('qty');
    $pending = $issued - $received;
    $donePct = $issued > 0 ? min(100, round(($received / $issued) * 100)) : 0;
    $orderDate = date('d-m-Y', strtotime(!empty($order->date) ? $order->date : $order->created_at));

    $money = fn($v) => '₹' . number_format((float) $v, 2);
@endphp

@include('partials.statement-styles')

<style>
    .st-order {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .st-order > div {
        padding: 12px 16px;
        border-right: 1px solid #e6e9f0;
    }

    .st-order > div:last-child {
        border-right: 0;
    }

    .st-order .st-v {
        font-weight: 700;
        font-size: 15px;
    }

    .st-status {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        border-radius: 999px;
        padding: 3px 10px;
        margin-top: 8px;
    }

    .st-status--done {
        background: #d1fae5;
        color: #047857;
    }

    .st-status--pending {
        background: #fef3c7;
        color: #92400e;
    }

    .st-bar {
        height: 8px;
        border-radius: 999px;
        background: #eef2f7;
        overflow: hidden;
        margin: -12px 0 24px;
    }

    .st-bar span {
        display: block;
        height: 100%;
        background: linear-gradient(90deg, #34d399, #059669);
    }

    @media (max-width: 767.98px) {
        .st-order {
            grid-template-columns: 1fr 1fr;
        }

        .st-order > div:nth-child(2) {
            border-right: 0;
        }

        .st-order > div:nth-child(-n+2) {
            border-bottom: 1px solid #e6e9f0;
        }
    }
</style>

<!-- Toolbar (screen only) -->
<section class="content-header no-print">
    <div class="container-fluid">
        <div class="st-toolbar mb-2">
            <div>
                <h1 class="m-0">Received Pieces Slip</h1>
                <p class="text-muted mb-0">{{ $employee->name }} · {{ $particular }} · Order #{{ $order->id }}</p>
            </div>
            <form onsubmit="return false">
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
                        <h2>Received Slip</h2>
                        <div class="st-meta">Order: <b>#{{ $order->id }}</b></div>
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
                        <div class="st-label">Status</div>
                        @if ($pending <= 0)
                            <span class="st-status st-status--done">Completed</span>
                        @else
                            <span class="st-status st-status--pending">{{ $pending }} pcs pending</span>
                        @endif
                    </div>
                </div>

                <!-- Order details -->
                <div class="st-order">
                    <div>
                        <div class="st-label">Particular</div>
                        <div class="st-v">{{ $particular ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="st-label">Size</div>
                        <div class="st-v">{{ $size ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="st-label">Order Date</div>
                        <div class="st-v">{{ $orderDate }}</div>
                    </div>
                    <div>
                        <div class="st-label">Rate</div>
                        <div class="st-v">₹{{ $order->rate }}</div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="st-summary">
                    <div class="st-box">
                        <div class="st-label">Issued Pieces</div>
                        <div class="st-val">{{ number_format($issued) }}</div>
                    </div>
                    <div class="st-box st-box--due">
                        <div class="st-label">Received Pieces</div>
                        <div class="st-val">{{ number_format($received) }}</div>
                    </div>
                    <div class="st-box {{ $pending > 0 ? 'st-box--neg' : '' }}">
                        <div class="st-label">Pending Pieces</div>
                        <div class="st-val">{{ number_format(max(0, $pending)) }}</div>
                    </div>
                </div>
                <div class="st-bar"><span style="width: {{ $donePct }}%"></span></div>

                <!-- Received entries -->
                <div class="st-section">
                    <h3>Received Piece History</h3>
                    <span>{{ $donePct }}% received · Order value {{ $money($order->total_amount) }}</span>
                </div>
                <div class="st-table-wrap">
                    <table class="st-table">
                        <thead>
                            <tr>
                                <th class="c" style="width:60px">#</th>
                                <th>Date</th>
                                <th>Particular</th>
                                <th class="c">Size</th>
                                <th class="num">Received</th>
                                <th class="num">Cumulative</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $cumulative = 0; @endphp
                            @forelse ($items as $i => $item)
                                @php $cumulative += $item->qty; @endphp
                                <tr>
                                    <td class="c">{{ $i + 1 }}</td>
                                    <td style="white-space:nowrap">
                                        {{ date('d-m-Y', strtotime(!empty($item->date) ? $item->date : $item->created_at)) }}
                                    </td>
                                    <td>{{ $particular }}</td>
                                    <td class="c">{{ $size }}</td>
                                    <td class="num st-credit"><strong>{{ $item->qty }}</strong></td>
                                    <td class="num">{{ $cumulative }} / {{ $issued }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="st-empty">No pieces received for this order yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($items->isNotEmpty())
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="num">Total Received</td>
                                    <td class="num st-grand">{{ number_format($received) }}</td>
                                    <td class="num">{{ $donePct }}%</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                <!-- Signatures -->
                <div class="st-sign">
                    <div>{{ $roleLabel }} Signature</div>
                    <div>Received By</div>
                </div>

                <div class="st-foot">
                    This is a computer-generated slip from {{ $companyTitle }}.
                </div>
            </div>
        </div>
    </div>
</section>
