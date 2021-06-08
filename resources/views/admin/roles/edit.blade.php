@extends('admin.layout')

@section('head')
    <title>{{ $menu_item->title }} - Edit</title>
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
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.edit', ['id' => $role->id]) }}">
                        @csrf
                        <div class="card-body">
                            <h4 class="card-title">Edit</h4>
                            <div class="form-group row">
                                <label for="title" class="col-sm-3 text-right control-label col-form-label">Title</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ $role->title }}" placeholder="Title Here">
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="code" class="col-sm-3 text-right control-label col-form-label">Code</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ $role->code }}" placeholder="Code Here">
                                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="rank" class="col-sm-3 text-right control-label col-form-label">Rank</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('rank') is-invalid @enderror" id="rank" name="rank" value="{{ $role->rank }}" placeholder="Rank Here">
                                    @error('rank') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-right control-label col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="status1" name="status" value="1" {{ $role->status === 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status1">Enabled</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="status0" name="status" value="0" {{ $role->status === 0 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status0">Disabled</label>
                                    </div>
                                    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
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