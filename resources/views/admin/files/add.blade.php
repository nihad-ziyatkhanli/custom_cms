@extends('admin.layout')

@section('head')
    <title>{{ __($menu_item->title) }} - {{ __('Add') }}</title>
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
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.add') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <h4 class="card-title">{{ __('Upload New') }}</h4>
                            <div class="form-group row">
                                <div class="col">
                                    <input type="file" class="form-control-file" id="file" name="file">
                                    @error('file') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection