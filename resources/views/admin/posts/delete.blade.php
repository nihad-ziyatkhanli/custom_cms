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
                                @foreach ($post->translations as $pt)
                                    <div class="row my-1">
                                        <div class="col-sm-3 text-right pr-3 py-1">{{ __('Title') }} ({{ __($languages[$pt->locale]) }})</div>
                                        <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ $pt->title }}</div>
                                    </div>
                                @endforeach
                                <div class="row my-1">
                                    <div class="col-sm-3 text-right pr-3 py-1">{{ __('Category') }}</div>
                                    <div class="col-sm-9 pl-3 py-1 border border-secondary rounded">{{ __($post->category->title) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.delete', ['id' => $post->id]) }}">
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