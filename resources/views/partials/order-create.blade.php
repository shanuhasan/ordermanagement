{{--
    Shared Add / Edit Order form for Employees, Contractors, Masters and Parties.
    Expects: $employee (worker or party), $routePrefix ('employee' | 'contractor' | 'master' | 'party'), $roleLabel
    Optional:
      $ownerField       – hidden id field name ('employee_id' default, 'party_id' for parties)
      $withStatus       – send status=Pending (employees / contractors)
      $withPrintingName – show Printing Name field (masters)
      $order           – existing order: switches to edit mode (pre-filled, posts to .order.update)
      $sizeField       – order column holding the size id ('size' default, 'size_id' for masters / parties)
      $pendingItem     – pending pieces (employees / contractors), shown in the summary when editing
--}}
@php
    $ownerField = $ownerField ?? 'employee_id';
    $withStatus = $withStatus ?? false;
    $withPrintingName = $withPrintingName ?? false;
    $order = $order ?? null;
    $isEdit = !empty($order);
    $sizeField = $sizeField ?? 'size';
    $pendingItem = $pendingItem ?? null;
    $val = fn($key, $default = '') => $isEdit ? ($order->{$key} ?? $default) : $default;
    $submitUrl = $isEdit ? route($routePrefix . '.order.update', $order->id) : route($routePrefix . '.order.store');
    $items = App\Models\Item::list();
    $sizes = App\Models\Size::list();
@endphp

@include('partials.statement-styles')
@include('partials.advance-amount-styles')

<style>
    .oc-step {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 4px 0 14px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: #64748b;
    }

    .oc-step b {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: #ecfdf5;
        color: #047857;
        font-size: 11px;
    }

    .oc-divider {
        height: 1px;
        background: var(--pm-border);
        margin: 8px 0 20px;
    }

    .oc-label-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
    }

    .oc-label-row a {
        font-size: 12px;
        font-weight: 600;
    }

    .oc-unit {
        position: relative;
    }

    .oc-unit > span {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 600;
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .oc-unit > span.pre {
        left: 14px;
    }

    .oc-unit > span.post {
        right: 14px;
    }

    .oc-unit .form-control.has-pre {
        padding-left: 30px;
    }

    .oc-unit .form-control.has-post {
        padding-right: 44px;
    }

    .oc-summary-item {
        font-size: 1.15rem;
        font-weight: 700;
        margin-top: 2px;
        min-height: 1.6rem;
    }

    .oc-summary-item.is-empty {
        color: #64748b;
        font-weight: 500;
    }

    .oc-calc {
        font-size: 13px;
        color: #94a3b8;
        margin-top: 4px;
        font-variant-numeric: tabular-nums;
    }
</style>

<!-- Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="st-toolbar mb-2">
            <div class="aa-head">
                <div class="aa-avatar">{{ strtoupper(mb_substr($employee->name, 0, 1)) }}</div>
                <div>
                    <h1 class="m-0">{{ $isEdit ? 'Edit Order #' . $order->id : 'New Order' }}</h1>
                    <p class="text-muted mb-0">
                        {{ $roleLabel }} · {{ $employee->name }}
                        @if (!empty($employee->code))
                            · Code {{ $employee->code }}
                        @endif
                        @if (!empty($employee->phone))
                            · {{ $employee->phone }}
                        @endif
                    </p>
                </div>
            </div>
            <div>
                <a href="{{ route($routePrefix . '.order', $employee->guid) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="" id="orderForm" method="post" novalidate>
            @csrf
            <input type="hidden" name="{{ $ownerField }}" value="{{ $employee->id }}">
            @if ($withStatus)
                {{-- Keep the current status on edit (the update action saves whatever is sent). --}}
                <input type="hidden" name="status" value="{{ $isEdit ? $order->status : 'Pending' }}">
            @endif

            <div class="row">
                <!-- Form -->
                <div class="col-lg-8">
                    <div class="aa-card">
                        <div class="aa-card-head">
                            <h3><i class="fas fa-clipboard-list mr-2 text-success"></i>Order Details</h3>
                            <span class="text-muted small"><span class="text-danger">*</span> required</span>
                        </div>
                        <div class="aa-card-body">

                            <div class="oc-step"><b>1</b> What</div>
                            <div class="row">
                                <div class="col-md-6 mb-3 oc-field">
                                    <div class="oc-label-row">
                                        <label for="item_id">Particular / Item <span class="text-danger">*</span></label>
                                        <a href="{{ route('item.create') }}"><i class="fas fa-plus"></i> New item</a>
                                    </div>
                                    <select name="item_id" id="item_id" class="form-control">
                                        <option value="">Select item</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}" {{ $val('item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error"></p>
                                </div>

                                <div class="col-md-6 mb-3 oc-field">
                                    <div class="oc-label-row">
                                        <label for="size">Size <span class="text-danger">*</span></label>
                                        <a href="{{ route('size.create') }}"><i class="fas fa-plus"></i> New size</a>
                                    </div>
                                    <select name="size" id="size" class="form-control">
                                        <option value="">Select size</option>
                                        @foreach ($sizes as $item)
                                            <option value="{{ $item->id }}" {{ $val($sizeField) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error"></p>
                                </div>

                                @if ($withPrintingName)
                                    <div class="col-md-12 mb-3 oc-field">
                                        <label for="printing_name">Printing Name</label>
                                        <input type="text" name="printing_name" id="printing_name" class="form-control" value="{{ $val('printing_name') }}"
                                            placeholder="Name to print on the work">
                                        <p class="error"></p>
                                    </div>
                                @endif
                            </div>

                            <div class="oc-divider"></div>

                            <div class="oc-step"><b>2</b> How many &amp; price</div>
                            <div class="row">
                                <div class="col-md-4 mb-3 oc-field">
                                    <label for="qty">Total Pieces <span class="text-danger">*</span></label>
                                    <div class="oc-unit">
                                        <input type="text" name="qty" id="qty" inputmode="numeric"
                                            class="form-control only-number has-post" placeholder="0" value="{{ $val('qty') }}" autocomplete="off">
                                        <span class="post">pcs</span>
                                    </div>
                                    <p class="error"></p>
                                </div>

                                <div class="col-md-4 mb-3 oc-field">
                                    <label for="rate">Rate <span class="text-danger">*</span></label>
                                    <div class="oc-unit">
                                        <span class="pre">₹</span>
                                        <input type="text" name="rate" id="rate" inputmode="decimal"
                                            class="form-control only-number has-pre" placeholder="0.00" value="{{ $val('rate') }}" autocomplete="off">
                                    </div>
                                    <p class="error"></p>
                                </div>

                                <div class="col-md-4 mb-3 oc-field">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="{{ $isEdit ? ($order->date ?: date('Y-m-d', strtotime($order->created_at))) : date('Y-m-d') }}">
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live summary -->
                <div class="col-lg-4">
                    <div class="aa-balance" style="position:sticky;top:76px">
                        <div class="st-label">Order Summary</div>
                        <div class="oc-summary-item is-empty" id="sumItem">Select an item</div>

                        <div class="aa-lines">
                            <div class="aa-line">
                                <span>Size</span>
                                <b id="sumSize">—</b>
                            </div>
                            <div class="aa-line">
                                <span>Pieces</span>
                                <b id="sumQty">0</b>
                            </div>
                            <div class="aa-line">
                                <span>Rate</span>
                                <b id="sumRate">₹0</b>
                            </div>
                            @if ($isEdit && $pendingItem !== null)
                                <div class="aa-line">
                                    <span><i class="aa-dot" style="background:#34d399"></i>Received</span>
                                    <b>{{ number_format($order->qty - $pendingItem) }} pcs</b>
                                </div>
                                <div class="aa-line">
                                    <span><i class="aa-dot" style="background:#fbbf24"></i>Pending</span>
                                    <b style="color:{{ $pendingItem > 0 ? '#fcd34d' : '#6ee7b7' }}">{{ number_format($pendingItem) }} pcs</b>
                                </div>
                            @endif
                        </div>

                        <div class="st-label" style="margin-top:18px">Total Amount</div>
                        <div class="aa-balance-big" id="sumTotal">₹0.00</div>
                        <div class="oc-calc" id="sumCalc">pieces × rate</div>

                        <div class="d-flex flex-column mt-4" style="gap:8px">
                            <button type="submit" class="btn btn-primary btn-block py-2">
                                <i class="fas fa-check mr-1"></i> {{ $isEdit ? 'Save Changes' : 'Create Order' }}
                            </button>
                            <a href="{{ route($routePrefix . '.order', $employee->guid) }}"
                                class="btn btn-block py-2" style="background:rgba(255,255,255,.08);color:#cbd5e1;border:0">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    (function() {
        var form = document.getElementById('orderForm');
        var $ = function(id) {
            return document.getElementById(id);
        };
        var inr = new Intl.NumberFormat('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        function selectedText(sel) {
            return sel.value ? sel.options[sel.selectedIndex].text.trim() : '';
        }

        function refresh() {
            var item = selectedText($('item_id'));
            var size = selectedText($('size'));
            var qty = parseFloat($('qty').value) || 0;
            var rate = parseFloat($('rate').value) || 0;

            $('sumItem').textContent = item || 'Select an item';
            $('sumItem').classList.toggle('is-empty', !item);
            $('sumSize').textContent = size || '—';
            $('sumQty').textContent = qty.toLocaleString('en-IN');
            $('sumRate').textContent = '₹' + (rate || 0); // shown exactly as entered
            $('sumTotal').textContent = '₹' + inr.format(qty * rate);
            $('sumCalc').textContent = qty && rate ? qty.toLocaleString('en-IN') + ' pcs × ₹' + rate : 'pieces × rate';
        }

        ['item_id', 'size', 'qty', 'rate'].forEach(function(id) {
            $(id).addEventListener('input', refresh);
            $(id).addEventListener('change', refresh);
        });
        refresh();

        function clearErrors() {
            form.querySelectorAll('.is-invalid').forEach(function(el) {
                el.classList.remove('is-invalid');
            });
            form.querySelectorAll('p.error').forEach(function(p) {
                p.textContent = '';
            });
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var buttons = form.querySelectorAll('button[type=submit]');
            buttons.forEach(function(b) {
                b.disabled = true;
            });

            jQuery.ajax({
                url: "{{ $submitUrl }}",
                type: 'post',
                data: jQuery(form).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === true) {
                        window.location.href = "{{ route($routePrefix . '.order', $employee->guid) }}";
                        return;
                    }
                    if (response.notFound) {
                        window.location.href = "{{ route($routePrefix . '.order', $employee->guid) }}";
                        return;
                    }
                    buttons.forEach(function(b) {
                        b.disabled = false;
                    });
                    clearErrors();
                    jQuery.each(response.errors || {}, function(key, val) {
                        var input = $(key);
                        if (!input) return;
                        input.classList.add('is-invalid');
                        var p = input.closest('.oc-field').querySelector('p.error');
                        if (p) p.textContent = Array.isArray(val) ? val[0] : val;
                    });
                    var first = form.querySelector('.is-invalid');
                    if (first) first.focus();
                },
                error: function() {
                    buttons.forEach(function(b) {
                        b.disabled = false;
                    });
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    })();
</script>
