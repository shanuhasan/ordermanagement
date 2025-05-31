@extends('layouts.app')
@section('title', 'Contractor / Thekedar')
@section('contractor', 'active')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Contractor / Thekedar</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('contractor.create') }}" class="btn btn-primary">Add Contractor</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <form action="" method="get">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Name"
                                        value="{{ Request::get('name') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="code">Code</label>
                                    <input type="text" name="code" class="form-control" placeholder="Code"
                                        value="{{ Request::get('code') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" class="form-control only-number"
                                        placeholder="Phone" maxlength="10" value="{{ Request::get('phone') }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{ route('contractor.index') }}" class="btn btn-danger">Reset</a>
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
            @include('message')
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th style="text-align:center">#</th>
                                <th style="text-align:center">Name</th>
                                <th style="text-align:center">Code</th>
                                <th style="text-align:center">Phone</th>
                                <th style="text-align:center">Status</th>
                                <th style="text-align:center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($employees->isNotEmpty())
                                <?php $i = 1; ?>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td style="text-align:center">{{ $i++ }}</td>
                                        <td style="text-align:center">
                                            <a href="{{ route('contractor.order', $employee->guid) }}"><strong>{{ $employee->name }}</strong>
                                            </a>
                                        </td>
                                        <td style="text-align:center">
                                            <a href="{{ route('contractor.order', $employee->guid) }}"><strong>{{ $employee->code }}</strong>
                                            </a>
                                        </td>
                                        <td style="text-align:center">
                                            <a href="{{ route('contractor.order', $employee->guid) }}"><strong>{{ $employee->phone }}</strong>
                                            </a>
                                        </td>
                                        <td style="text-align:center">
                                            @if ($employee->status == 1)
                                                <svg class="text-success-500 h-6 w-6 text-success"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            @endif

                                        </td>
                                        <td style="text-align:center">
                                            <a href="{{ route('contractor.edit', $employee->guid) }}">
                                                <svg class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="javascript:void()" onclick="deleteEmployee('{{ $employee->guid }}')"
                                                class="text-danger w-4 h-4 mr-1">
                                                <svg wire:loading.remove.delay="" wire:target=""
                                                    class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path ath fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Record Not Found</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $employees->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('script')
    <script>
        function deleteEmployee(id) {
            var url = "{{ route('contractor.delete', 'ID') }}";
            var newUrl = url.replace('ID', id);

            if (confirm('Are you sure want to delete')) {
                $.ajax({
                    url: newUrl,
                    type: 'get',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response['status']) {
                            window.location.href = "{{ route('contractor.index') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
