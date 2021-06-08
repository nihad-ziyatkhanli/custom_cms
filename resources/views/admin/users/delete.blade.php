@extends('admin.layout')

@section('head')
    <title>{{ $menu_item->title }} - Delete</title>
@endsection

@section('stylesheets')
<!-- -->
@endsection

@section('scripts')
<!-- -->
@endsection

@section('content')

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">{{ $menu_item->title }}</h4>
                <div class="ml-auto text-right">
                    @can('browse')
                        <a href="{{ route($route_name) }}" class="btn btn-info">Back to Main</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        @if (session('fail')) <div class="alert alert-danger" role="alert">{{ session('fail') }}</div> @endif

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Delete</h4>
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Are you sure you want to delete this record?</h4>
                            <p>If this item has associated records, they will also be deleted.</p>
                            <hr>
                            <div class="mb-0">
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">Name</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ $user->name }}</div>
                                </div>
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">Email</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ $user->email }}</div>
                                </div>
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">Role</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ $user->role->title ?? 'User' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.delete', ['id' => $user->id]) }}">
                        @csrf
                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection