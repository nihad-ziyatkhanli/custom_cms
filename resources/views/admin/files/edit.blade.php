@extends('admin.layout')

@section('head')
    <title>{{ __($menu_item->title) }} - {{ __('Edit') }}</title>
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
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.edit', ['id' => $file->id]) }}">
                        @csrf
                        <div class="card-body">
                            <h4 class="card-title">{{ __('Edit') }}</h4>
                            <div class="form-group row">
                                <label for="title" class="col-sm-3 text-right control-label col-form-label">{{ __('Title') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ $file->title }}" placeholder="">
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="caption" class="col-sm-3 text-right control-label col-form-label">{{ __('Caption') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('caption') is-invalid @enderror" id="caption" name="caption" value="{{ $file->caption }}" placeholder="">
                                    @error('caption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-3 text-right control-label col-form-label">{{ __('Description') }}</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="">{{ $file->description }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">{{ __('Edit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection