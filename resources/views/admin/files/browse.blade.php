@extends('admin.layout')

@section('head')
	<title>{{ __($menu_item->title) }}</title>
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
                <h4 class="page-title">{{ __($menu_item->title) }}</h4>
                <div class="ml-auto text-right">
                    @can('add')
                        <a href="{{ route($route_name.'.add') }}" class="btn btn-info">{{ __('Upload New') }}</a>
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
                                        <th>{{ __('File') }}</th>
                                        <th>{{ __('URL') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@foreach ($files as $file)
                                    <tr>
                                        <td>
                                            <div class="float-left mx-2">
                                                @if (in_array($file->mime_type, config('custom.image_mime_types')))
                                                    <img src="{{ Storage::url($file->path) }}" width="60" height="60">
                                                @else
                                                    <img src="{{ asset('assets/images/document.png') }}" width="60">
                                                @endif
                                            </div>
                                            <div class="float-left m-1">
                                                <h6>{{ $file->title ?? __('Untitled') }}</h6>
                                                <span>{{ pathinfo($file->path, PATHINFO_BASENAME) }}</span>
                                            </div>
                                        </td>
                                        <td>{{ Storage::url($file->path) }}</td>
                                        <td>{{ $file->user->name }}</td>
                                        <td>{{ isset($file->created_at) ? $file->created_at->diffForHumans() : __('n/a') }}</td>
                                        <td>
                                            @canany(['edit', 'delete'], $file)
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                    <div class="dropdown-menu">
                                                        @can('edit', $file)
                                                            <a class="dropdown-item" href="{{ route($route_name.'.edit', ['id' => $file->id]) }}">{{ __('Edit') }}</a>
                                                        @endcan
                                                        @can('delete', $file)
                                                            <a class="dropdown-item" href="{{ route($route_name.'.delete', ['id' => $file->id]) }}">{{ __('Delete') }}</a>
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