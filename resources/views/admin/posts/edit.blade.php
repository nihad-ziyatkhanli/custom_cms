@extends('admin.layout')

@section('head')
    <title>{{ __($menu_item->title) }} - {{ __('Edit') }}</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <link href="{{ asset('assets/libs/quill/dist/quill.snow.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/quill/dist/quill.min.js') }}"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow'
        });

        $("form").on("submit", function () {
            $("#content").val(JSON.stringify(quill.getContents()));
        });

        function setTranslation () {
            $.ajax({
                url: "{{ route($route_name).'/ajax/translation' }}",
                data: {
                    "id": {{ $post->id }},
                    "locale": $("#locale").val(),
                },
                beforeSend: function () {
                    $('.preloader').empty().css('background-color', 'rgba(0,0,0,0.5)').show();
                },
                success: function (result) {
                    var title, excerpt, content;
                    title = excerpt = content = "";
                    var pt_default = false, disable_del = true;

                    if (result) {
                        pt_default = result.is_default;
                        title = result.title;
                        excerpt = result.excerpt;
                        disable_del = pt_default;
                        try {
                            content = JSON.parse(result.content);
                        }
                        catch (e) { 
                            console.log("Invalid content");
                        }
                    }

                    $('#pt_default').prop('checked', pt_default);
                    $('#pt_default').off('click').on('click', function () {
                        return !pt_default;
                    });
                    $('#pt_delete').prop('checked', false).prop('disabled', disable_del);
                    $("#title").val(title);
                    $("#excerpt").val(excerpt);
                    quill.setContents(content);
                    $('.preloader').hide();
                }
            });
        }
        $("#locale").on("change", setTranslation);
        setTranslation();
    </script>
    <script>
        $(".select2").select2();

        $("#file_id").select2({
            ajax: {
                url: "{{ route($route_name).'/ajax/attachments' }}",
                delay: 500,
                dataType: "json",
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }

                    return query;
                },
            },
            templateResult: formatState,
        });

        function formatState (state) {
            if (!state.id)
                return state.text;

            var container = $('<span><img src="" width="50" height="50" /> <span></span></span>');
            container.find("span").text(state.text);
            container.find("img").attr("src", state.url);
            return container;
        };
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

        @if (session('fail'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('fail') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card">
                    <form class="form-horizontal" method="post" action="{{ route($route_name.'.edit', ['id' => $post->id]) }}">
                        @csrf
                        <div class="card-body">
                            <h4 class="card-title">{{ __('Edit') }}</h4>
                            <h6 class="row">
                                <div class="col-sm-2 text-right">{{__('URL')}}</div>
                                <div  class="col-sm-10">
                                    <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                                </div>
                            </h6>
                            <hr>
                            <div class="form-group row">
                                <label for="locale" class="col-sm-2 text-right control-label col-form-label">{{ __('Language') }}</label>
                                <div class="col-sm-3">
                                    <select class="select2 form-control custom-select" id="locale" name="locale" style="width: 100%; height:36px;">
                                        @foreach ($languages as $key => $language)
                                            <option value="{{ $key }}" @if ($key == $locale) selected @endif>{{ __($language) }}</option>
                                        @endforeach
                                    </select>
                                    @error('locale') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-4">
                                    <div class="custom-control custom-checkbox my-1">
                                        <input type="checkbox" class="custom-control-input" id="pt_default" name="pt_default" value="1">
                                        <label class="custom-control-label" for="pt_default">{{ __('Default Translation') }}</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="custom-control custom-checkbox my-1">
                                        <input type="checkbox" class="custom-control-input" id="pt_delete" name="pt_delete" value="1">
                                        <label class="custom-control-label" for="pt_delete">{{ __('Delete Translation') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 text-right control-label col-form-label">{{ __('Title') }}</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="" placeholder="">
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    @error('slug') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <label for="visibility" class="col-sm-2 text-right control-label col-form-label">{{ __('Visibility') }}</label>
                                <div class="col-sm-2">
                                    <select class="select2 form-control custom-select" id="visibility" name="visibility" style="width: 100%; height:36px;">
                                        @foreach ($vms as $key => $vm)
                                            <option value="{{ $key }}" @if ($key == $post->visibility) selected @endif>{{ ucfirst(__($vm)) }}</option>
                                        @endforeach
                                    </select>
                                    @error('visibility') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="category_id" class="col-sm-2 text-right control-label col-form-label">{{ __('Category') }}</label>
                                <div class="col-sm-4">
                                    <select class="select2 form-control custom-select" id="category_id" name="category_id" style="width: 100%; height:36px;">
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if ($category->id == $post->category_id) selected @endif>{{ __($category->title) }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <label for="file_id" class="col-sm-2 text-right control-label col-form-label">{{ __('Featured Image') }}</label>
                                <div class="col-sm-4">
                                    <select class="form-control custom-select" id="file_id" name="file_id" style="width: 100%; height:36px;">
                                        <option value="">{{ __('Select') }}</option>
                                        @if ($post->file_id)
                                            <option value="{{ $post->file_id }}" selected>{{ pathinfo($post->file->path, PATHINFO_BASENAME) }}</option>
                                        @endif
                                    </select>
                                    @error('file_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="excerpt" class="col-sm-2 text-right control-label col-form-label">{{ __('Excerpt') }}</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="5" placeholder=""></textarea>
                                    @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="editor" class="col-sm-2 text-right control-label col-form-label">{{ __('Content') }}</label>
                                <div class="col-sm-10">
                                    <input type="hidden" id="content" name="content" value="">
                                    <div class="form-control @error('content') is-invalid @enderror" id="editor" style="height: 250px"></div>
                                    @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
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