{{--
    Shared received-piece history for Employees (karigar) and Contractors (thekedar).
    Expects: $employee, $items (ReceivedItem rows, newest first), $orders (Order keyed by id),
             $routePrefix ('employee' | 'contractor'), $roleLabel
--}}
@php
    $authUser = Auth::guard('web')->user();
    $companyTitle = !empty($authUser->company_name) ? $authUser->company_name : companyName($authUser->company_id);
    $selectedYear = request('year') ?: date('Y');

    // Resolve particular / size once per row from the preloaded orders.
    $rows = $items->map(function ($item) use ($orders) {
        $order = $orders[$item->order_id] ?? null;
        return (object) [
            'item' => $item,
            'date' => date('d-m-Y', strtotime(!empty($item->date) ? $item->date : $item->created_at)),
            'particular' => $order ? (!empty($order->item_id) ? getItemName($order->item_id) : $order->particular) : '—',
            'size' => $order ? sizeName($order->size) : '',
        ];
    });

    $totalReceived = $items->sum('qty');
    $byParticular = $rows
        ->groupBy(fn($r) => $r->particular . '|' . $r->size)
        ->map(fn($g) => (object) [
            'particular' => $g->first()->particular,
            'size' => $g->first()->size,
            'qty' => $g->sum(fn($r) => $r->item->qty),
            'entries' => $g->count(),
        ])
        ->sortByDesc('qty')
        ->values();
@endphp

@include('partials.statement-styles')

<!-- Toolbar (screen only) -->
<section class="content-header no-print">
    <div class="container-fluid">
        <div class="st-toolbar mb-2">
            <div>
                <h1 class="m-0">Received Piece History</h1>
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
        <div class="no-print" style="max-width:1000px;margin:0 auto">
            @include('message')
        </div>

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
                        <h2>Received Pieces</h2>
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
                        <div class="st-label">Last Received</div>
                        <div class="st-party-name">{{ $rows->first()->date ?? '—' }}</div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="st-summary">
                    <div class="st-box st-box--due">
                        <div class="st-label">Total Pieces Received</div>
                        <div class="st-val">{{ number_format($totalReceived) }}</div>
                    </div>
                    <div class="st-box">
                        <div class="st-label">Entries</div>
                        <div class="st-val">{{ $items->count() }}</div>
                    </div>
                    <div class="st-box st-box--paid">
                        <div class="st-label">Orders</div>
                        <div class="st-val">{{ $items->pluck('order_id')->unique()->count() }}</div>
                    </div>
                </div>

                @if ($byParticular->count() > 1)
                    <!-- By particular -->
                    <div class="st-section">
                        <h3>By Particular</h3>
                        <span>{{ $byParticular->count() }} items</span>
                    </div>
                    <div class="st-table-wrap">
                        <table class="st-table">
                            <thead>
                                <tr>
                                    <th>Particular</th>
                                    <th class="c">Size</th>
                                    <th class="num">Entries</th>
                                    <th class="num">Pieces</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($byParticular as $g)
                                    <tr>
                                        <td>{{ $g->particular }}</td>
                                        <td class="c">{{ $g->size }}</td>
                                        <td class="num">{{ $g->entries }}</td>
                                        <td class="num st-credit"><strong>{{ number_format($g->qty) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Entries -->
                <div class="st-section">
                    <h3>All Entries</h3>
                    <span>Newest first</span>
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
                                <th class="c no-print" style="width:60px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $i => $r)
                                <tr>
                                    <td class="c">{{ $i + 1 }}</td>
                                    <td style="white-space:nowrap">{{ $r->date }}</td>
                                    <td>{{ $r->particular }}</td>
                                    <td class="c">{{ $r->size }}</td>
                                    <td class="num st-credit"><strong>{{ $r->item->qty }}</strong></td>
                                    <td class="c no-print">
                                        <a href="javascript:void(0)" onclick="deleteReceived('{{ $r->item->id }}')"
                                            class="text-danger" title="Delete entry">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="st-empty">No pieces received in {{ $selectedYear }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($items->isNotEmpty())
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="num">Total Received</td>
                                    <td class="num st-grand">{{ number_format($totalReceived) }}</td>
                                    <td class="no-print"></td>
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
                    This is a computer-generated record from {{ $companyTitle }}.
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function deleteReceived(id) {
        var url = "{{ route($routePrefix . '.received.pcs.delete', 'ID') }}".replace('ID', id);

        if (!confirm('Are you sure you want to delete this entry?')) {
            return;
        }

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response['status']) {
                    window.location.reload();
                }
            }
        });
    }
</script>
