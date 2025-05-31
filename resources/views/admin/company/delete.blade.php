@extends('admin.layouts.app')
@section('title', 'Deleted Company')
@section('company', 'active')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Deleted Company</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.company.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-title">
                            <a href="{{ route('admin.company.index') }}" class="btn btn-danger">Reset</a>
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input type="text" value="{{ Request::get('keyword') }}" name="keyword"
                                    class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($companies->isNotEmpty())
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($companies as $company)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $company->name }}</td>
                                        <td>{{ $company->slug }}</td>
                                        <td>
                                            <a href="javascript:void()" onclick="restoreCompany('{{ $company->guid }}')"
                                                class="text-danger w-4 h-4 mr-1">
                                                Restore
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
                    {{ $companies->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('script')
    <script>
        function restoreCompany(id) {
            var url = "{{ route('admin.company.restore', 'ID') }}";
            var newUrl = url.replace('ID', id);

            if (confirm('Are you sure want to restore')) {
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
                            window.location.href = "{{ route('admin.company.index') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
