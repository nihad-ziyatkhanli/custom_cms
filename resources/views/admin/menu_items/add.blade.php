@extends('admin.layout')

@section('head')
    <title>{{ $menu_item->title }} - Add</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(".select2").select2();
    </script>
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
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.add') }}">
                        @csrf
                        <div class="card-body">
                            <h4 class="card-title">Add New</h4>
                            <div class="form-group row">
                                <label for="title" class="col-sm-3 text-right control-label col-form-label">Title</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Title Here">
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="code" class="col-sm-3 text-right control-label col-form-label">Code</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="Code Here">
                                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="icon" class="col-sm-3 text-right control-label col-form-label">Icon</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon') }}" placeholder="Icon Here">
                                    @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="parent_id" class="col-sm-3 text-right control-label col-form-label">Parent</label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control custom-select" id="parent_id" name="parent_id" style="width: 100%; height:36px;">
                                        <option value="">Select</option>
                                        @foreach ($parents as $parent)
                                            <option value="{{ $parent->id }}" @if( $parent->id == old('parent_id') ) selected @endif>{{ $parent->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="url" class="col-sm-3 text-right control-label col-form-label">URL</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}" placeholder="URL Here">
                                    @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="rank" class="col-sm-3 text-right control-label col-form-label">Rank</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('rank') is-invalid @enderror" id="rank" name="rank" value="{{ old('rank') }}" placeholder="Rank Here">
                                    @error('rank') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
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