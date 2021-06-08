@extends('admin.layout')

@section('head')
	<title>{{ $menu_item->title }}</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}">
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('scripts')
	<script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script>
        $('#browse_table').DataTable({
        	"order":[],
        });
    </script>
@endsection

@section('content')

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">{{ $menu_item->title }}</h4>
                <div class="ml-auto text-right">
                    @can('add')
                        <a href="{{ route($route_name.'.add') }}" class="btn btn-info">Add New</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        @if (session('success')) <div class="alert alert-success" role="alert">{{ session('success') }}</div> @endif
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><!-- Table title here --></h5>
                        <div class="table-responsive">
                            <table id="browse_table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Code</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->title }}</td>
                                        <td>{{ $category->code }}</td>
                                        <td>
                                            @canany(['edit', 'delete'])
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                    <div class="dropdown-menu">
                                                        @can('edit')
                                                            <a class="dropdown-item" href="{{ route($route_name.'.edit', ['id' => $category->id]) }}">Edit</a>
                                                        @endcan
                                                        @can('delete')
                                                            <a class="dropdown-item" href="{{ route($route_name.'.delete', ['id' => $category->id]) }}">Delete</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            @endcanany
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection