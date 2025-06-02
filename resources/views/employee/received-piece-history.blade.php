@extends('layouts.app')
@section('title', 'Received Pieces History')
@section('employee', 'active')

@section('content')
    <style>
        @media print {
            .h {
                background: #000 !important;
                print-color-adjust: exact;
            }

            th {
                color: #000;
            }

            .main-footer,
            .years {
                display: none
            }
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h3>
                        {{ $employee->name }}
                        ({{ !empty($employee->code) ? 'Code:- ' . $employee->code . ',' : '' }}
                        {{ !empty($employee->phone) ? 'Mobile:- ' . $employee->phone : '' }})
                    </h3> --}}
                </div>
                <div class="col-sm-6 text-right">
                    <a class="btn btn-success" onclick="window.print();">Print</a>
                    <a href="{{ route('employee.order', $employee->guid) }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body text-center" style="background-color: green; color:#fff">
                    <h5>
                        {{ $employee->name }}
                        ({{ !empty($employee->code) ? 'Code:- ' . $employee->code . ',' : '' }}
                        {{ !empty($employee->phone) ? 'Mobile:- ' . $employee->phone : ' ' }})
                    </h5>
                </div>
            </div>
        </div>
    </section>

    <section class="content years">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <form action="" method="get">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="year">Year</label>
                                    <select name="year" id="year" class="form-control">
                                        <option value="">Select Year</option>
                                        @foreach (\App\Models\Year::getYear() as $val)
                                            <?php
                                            $year = !empty(Request::get('year')) ? Request::get('year') : date('Y');
                                            ?>
                                            <option value="{{ $val->name }}" {{ $year == $val->name ? 'selected' : '' }}>
                                                {{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{ route('employee.order.receivedPiece', $employee->guid) }}"
                            class="btn btn-danger">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 style="font-weight:bold">RECEIVED PIECE HISTORY
                        ({{ strtoupper($employee->name) }})</h5>
                    <table cellpadding="3" cellspacing='3' border="0" width="100%">
                        <thead style="background: #000;color:#ffffff">
                            <tr>
                                <th style="border:1px solid #000;text-align:center">#</th>
                                <th style="border:1px solid #000;text-align:center">Date</th>
                                <th style="border:1px solid #000;text-align:center">Particular</th>
                                <th style="border:1px solid #000;text-align:center">Size</th>
                                <th style="border:1px solid #000;text-align:center">Received Piece</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                                $i = 1;
                            @endphp
                            @if ($items->isNotEmpty())
                                @foreach ($items as $item)
                                    <tr style="border:1px solid #000">
                                        <td style="border:1px solid #000;text-align:center">{{ $i++ }}</td>
                                        <td style="border:1px solid #000;text-align:center">
                                            {{ date('d-m-Y', strtotime($item->created_at)) }}
                                        </td>
                                        <td style="border:1px solid #000;text-align:center">
                                            {{-- {{ getOrder($item->order_id)->particular }} --}}
                                            {{ !empty(getOrder($item->order_id)->item_id) ? getItemName(getOrder($item->order_id)->item_id) : getOrder($item->order_id)->particular }}
                                        </td>
                                        <td style="border:1px solid #000;text-align:center">
                                            {{ sizeName(getOrder($item->order_id)->size) }}
                                        </td>
                                        <td style="border:1px solid #000;text-align:center">{{ $item->qty }}
                                        </td>
                                    </tr>
                                    @php
                                        $total = $total + $item->qty;
                                    @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" style="border:1px solid #000;text-align:center">Data not found.</td>
                                </tr>
                            @endif
                        </tbody>
                        @if ($items->isNotEmpty())
                            <tfoot>
                                <tr>
                                    <th style="border:1px solid #000;text-align:center"></th>
                                    <th style="border:1px solid #000;text-align:center"></th>
                                    <th style="border:1px solid #000;text-align:center"></th>
                                    <th style="border:1px solid #000;text-align:center">Total</th>
                                    <th style="border:1px solid #000;text-align:center">{{ $total }}</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
