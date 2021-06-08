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
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('File') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">
                                        <div class="float-left mx-2">
                                            @if (in_array($file->mime_type, config('custom.image_mime_types')))
                                                <img src="{{ Storage::url($file->path) }}" width="60" height="60">
                                            @else
                                                <img src="{{ asset('assets/images/document.png') }}" width="60">
                                            @endif
                                        </div>
                                        <div class="float-left m-2">
                                            <h6>{{ $file->title ?? __('Untitled') }}</h6>
                                            <span>{{ pathinfo($file->path, PATHINFO_BASENAME) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.delete', ['id' => $file->id]) }}">
                        @csrf
                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">{{ __('Delete') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection