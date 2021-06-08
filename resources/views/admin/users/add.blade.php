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
                                <label for="name" class="col-sm-3 text-right control-label col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Username Here">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 text-right control-label col-form-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email Here">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-2">
                                    <div class="custom-control custom-checkbox my-1">
                                        <input type="checkbox" class="custom-control-input" id="verified" name="verified" value="1" {{ old('verified') === '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="verified">Verified</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-3 text-right control-label col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="" placeholder="Password Here">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="role_id" class="col-sm-3 text-right control-label col-form-label">Role</label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control custom-select" id="role_id" name="role_id" style="width: 100%; height:36px;">
                                        <option value="">Select</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" @if( $role->id == old('role_id') ) selected @endif>{{ $role->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id') <div class="text-danger">{{ $message }}</div> @enderror
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