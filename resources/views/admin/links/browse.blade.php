@extends('admin.layout')

@section('head')
	<title>{{ __($menu_item->title) }}</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}">
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
@endsection

@section('scripts')
	<script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(".select2").select2();

        var table = $('#browse_table').DataTable({
        	"order":[],
        });

        $("#locale").on("change", function () {
            table.columns(2).search(this.value).draw();
        });
        $("#group").on( "change", function () {
            table.columns(3).search(this.value).draw();
        });
        $("#parent").on( "change", function () {
            table.columns(4).search(this.value).draw();
        });
    </script>
@endsection

@section('content')

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">{{ __($menu_item->title) }}</h4>
                <div class="ml-auto text-right">
                    @can('add')
                        <a href="{{ route($route_name.'.add') }}" class="btn btn-info">{{ __('Add New') }}</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        @if (session('success')) <div class="alert alert-success" role="alert">{{ session('success') }}</div> @endif
        
        <div class="row my-1">               
            <div class="col-sm-3">
                <select class="select2 form-control custom-select" id="locale" style="width: 100%; height:36px;">
                    <option value="">{{ __('All languages') }}</option>
                    @foreach ($languages as $language)
                        <option value="{{ __($language) }}">{{ __($language) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <select class="select2 form-control custom-select" id="group" style="width: 100%; height:36px;">
                    <option value="">{{ __('All groups') }}</option>
                    @foreach ($lgs as $lg)
                        <option value="{{ __($lg) }}">{{ __($lg) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <select class="select2 form-control custom-select" id="parent" style="width: 100%; height:36px;">
                    <option value="">{{ __('All parents') }}</option>
                    <option value="{{ __('n/a') }}">{{ __('n/a') }}</option>
                    @foreach ($links as $link)
                        <option value="{{ $link->title }}">{{ $link->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><!-- Table title here --></h5>
                        <div class="table-responsive">
                            <table id="browse_table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Code') }}</th>
                                        <th>{{ __('Language') }}</th>
                                        <th>{{ __('Group') }}</th>
                                        <th>{{ __('Parent Title') }}</th>
                                        <th>{{ __('Visibility') }}</th>
                                        <th>{{ __('Rank') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@foreach ($links as $link)
                                    <tr>
                                        <td>
                                            <a href="{{ $link->url }}" target="_blank">{{ $link->title }}</a>
                                        </td>
                                        <td>{{ $link->code }}</td>
                                        <td>{{ __($languages[$link->locale]) }}</td>
                                        <td>{{ __($lgs[$link->group]) }}</td>
                                        <td>{{ $link->parent->title ?? __('n/a') }}</td>
                                        <td>{{ __($vms[$link->visibility]) }}</td>
                                        <td>{{ $link->rank ?? '0' }}</td>
                                        <td>
                                            @canany(['edit', 'delete'])
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                    <div class="dropdown-menu">
                                                        @can('edit')
                                                            <a class="dropdown-item" href="{{ route($route_name.'.edit', ['id' => $link->id]) }}">{{ __('Edit') }}</a>
                                                        @endcan
                                                        @can('delete')
                                                            <a class="dropdown-item" href="{{ route($route_name.'.delete', ['id' => $link->id]) }}">{{ __('Delete') }}</a>
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