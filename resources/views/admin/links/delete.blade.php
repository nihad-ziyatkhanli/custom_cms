@extends('admin.layout')

@section('head')
    <title>{{ __($menu_item->title) }} - {{ __('Delete') }}</title>
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
                <h4 class="page-title">{{ __($menu_item->title) }}</h4>
                <div class="ml-auto text-right">
                    @can('browse')
                        <a href="{{ route($route_name) }}" class="btn btn-info">{{ __('Back to Main') }}</a>
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
                        <h4 class="card-title">{{ __('Delete') }}</h4>
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">{{ __('Are you sure you want to delete this record?') }}</h4>
                            <hr>
                            <div class="mb-0">
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('Language') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ __($languages[$link->locale]) }}</div>
                                </div>
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('Group') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ __($lgs[$link->group]) }}</div>
                                </div>
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('Visibility') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ ucfirst(__($vms[$link->visibility])) }}</div>
                                </div>
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('Title') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ $link->title }}</div>
                                </div>
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('Code') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ $link->code }}</div>
                                </div>
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('Icon') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ $link->icon }}</div>
                                </div>
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('URL') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ $link->url }}</div>
                                </div>
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('Parent') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ $link->parent->title ?? '' }}</div>
                                </div>
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('Children') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">
                                        @foreach ($link->children as $child)
                                            <div>{{ $child->title }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.delete', ['id' => $link->id]) }}">
                        @csrf
                        <div class="border-top">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary">{{ __('Delete') }}</button>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="cascade" name="cascade" value="1" checked>
                                            <label class="custom-control-label" for="cascade">{{ __('Delete all children too') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection