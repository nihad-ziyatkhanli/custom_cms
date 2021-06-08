@extends('admin.layout')

@section('head')
    <title>{{ __($menu_item->title) }} - {{ __('Edit') }}</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(".select2").select2();

        var parent = false;
        @if ($link->parent)
            parent = {
                id: {{ $link->parent_id }},
                title: "{{ $link->parent->title }}",
            };
        @endif

        function loadParents() {
            if ($("#locale").val() == "{{ $link->locale }}" && $("#group").val() == "{{ $link->group }}" && parent)
                $("#parent_id").append(new Option(parent.title, parent.id, true, true));
            else
                $("#parent_id option").not('[value=""]').remove();

            $("#parent_id").select2({
                ajax: {
                    url: "{{ route($route_name).'/ajax/parents' }}",
                    delay: 500,
                    dataType: "json",
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            locale: $("#locale").val(),
                            group: $("#group").val(),
                            id: {{ $link->id }},
                        }

                        return query;
                    },
                },
            });
        }
        $("#locale, #group").on('change', loadParents);
        loadParents();
    </script>
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
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.edit', ['id' => $link->id]) }}">
                        @csrf
                        <div class="card-body">
                            <h4 class="card-title">{{ __('Edit') }}</h4>
                            <div class="form-group row">
                                <label for="locale" class="col-sm-3 text-right control-label col-form-label">{{ __('Language') }}</label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control custom-select" id="locale" name="locale" style="width: 100%; height:36px;">
                                        @foreach ($languages as $key => $language)
                                            <option value="{{ $key }}" @if ($key == $link->locale) selected @endif>{{ __($language) }}</option>
                                        @endforeach
                                    </select>
                                    @error('locale') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="group" class="col-sm-3 text-right control-label col-form-label">{{ __('Group') }}</label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control custom-select" id="group" name="group" style="width: 100%; height:36px;">
                                        @foreach ($lgs as $key => $lg)
                                            <option value="{{ $key }}" @if ($key == $link->group) selected @endif>{{ __($lg) }}</option>
                                        @endforeach
                                    </select>
                                    @error('group') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="visibility" class="col-sm-3 text-right control-label col-form-label">{{ __('Visibility') }}</label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control custom-select" id="visibility" name="visibility" style="width: 100%; height:36px;">
                                        @foreach ($vms as $key => $vm)
                                            <option value="{{ $key }}" @if ($key == $link->visibility) selected @endif>{{ ucfirst(__($vm)) }}</option>
                                        @endforeach
                                    </select>
                                    @error('visibility') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-sm-3 text-right control-label col-form-label">{{ __('Title') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ $link->title }}" placeholder="">
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="code" class="col-sm-3 text-right control-label col-form-label">{{ __('Code') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ $link->code }}" placeholder="">
                                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="icon" class="col-sm-3 text-right control-label col-form-label">{{ __('Icon') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ $link->icon }}" placeholder="">
                                    @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="parent_id" class="col-sm-3 text-right control-label col-form-label">{{ __('Parent') }}</label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control custom-select" id="parent_id" name="parent_id" style="width: 100%; height:36px;">
                                        <option value="">{{ __('Select') }}</option>
                                    </select>
                                    @error('parent_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="url" class="col-sm-3 text-right control-label col-form-label">{{ __('URL') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ $link->url }}" placeholder="">
                                    @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="rank" class="col-sm-3 text-right control-label col-form-label">{{ __('Rank') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('rank') is-invalid @enderror" id="rank" name="rank" value="{{ $link->rank }}" placeholder="">
                                    @error('rank') <div class="invalid-feedback">{{ $message }}</div> @enderror
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