@extends('admin.layout')

@section('head')
    <title>{{ $menu_item->title }} - Attach</title>
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
        @if ($errors->any()) <div class="alert alert-danger" role="alert">Invalid Request.</div> @endif

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.attach', ['id' => $role->id]) }}">
                        @csrf
                        <div class="card-body">
                            <h4 class="card-title border-bottom">Attach Permissions</h4>
                            @foreach ($menu_items as $item)
                                @if ($item->children->isEmpty())
                                    <div class="row my-4">
                                        <h5 class="col-sm-6 my-auto text-center">{{ $item->title }}</h5>
                                        <div class="col-sm-6 border">
                                            @foreach ($permissions as $permission)
                                                <div class="custom-control custom-checkbox my-1">
                                                    <input type="checkbox" class="custom-control-input" id="{{ $item->id.'_'.$permission->id }}" name="permissions[{{ $item->id }}][]" value="{{ $permission->id }}" {{ $role->rmps->filter(function ($rmp) use ($item, $permission) {
                                                        return $rmp->menu_item_id === $item->id && $rmp->permission_id === $permission->id;
                                                    })->isNotEmpty() ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="{{ $item->id.'_'.$permission->id }}">{{ $permission->title }}</label>
                                                </div>
                                             @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="my-4">
                                        <h5 class="text-right">{{ $item->title }}</h5>
                                        <div class="border col-sm-10 shadow">
                                            @foreach ($item->children as $child)
                                                <div class="row my-4">
                                                    <h5 class="col-sm-6 my-auto text-center">{{ $child->title }}</h5>
                                                    <div class="col-sm-6 border border-right-0">
                                                        @foreach ($permissions as $permission)
                                                            <div class="custom-control custom-checkbox my-1">
                                                                <input type="checkbox" class="custom-control-input" id="{{ $child->id.'_'.$permission->id }}" name="permissions[{{ $child->id }}][]" value="{{ $permission->id }}" {{ $role->rmps->filter(function ($rmp) use ($child, $permission) {
                                                                    return $rmp->menu_item_id === $child->id && $rmp->permission_id === $permission->id;
                                                                })->isNotEmpty() ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="{{ $child->id.'_'.$permission->id }}">{{ $permission->title }}</label>
                                                            </div>
                                                         @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
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