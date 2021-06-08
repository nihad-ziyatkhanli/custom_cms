@extends('admin.layout')

@section('head')
    <title>{{ __($menu_item->title) }}</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}">
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script>
        // DataTable
        $('#browse_table thead th:eq(0)').each(function () {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="'+title+'">');
        });

        var table = $('#browse_table').DataTable({
            "order": [],
            "pagingType": "full_numbers",
            "serverSide": true,
            "ajax": {
                "url": "{{ route($route_name).'/ajax/browse' }}",
                "data": function ( d ) {
                    if($('#show_all').is(':checked'))
                        d.show_all = 1;
                },
            },
            "columns":[
                {
                    "data": "title",
                    "render": function (value) {
                        var str = "";
                        if (value) {
                            str += '<a href="'+value['url']+'" target="_blank">';
                            str += value['text'];
                            str += '</a>';
                        }
                        return str;
                    }
                },
                {
                    "data": "category",
                    "orderable": false,
                },
                {
                    "data": "user",
                    "orderable": false,
                },
                {
                    "data": "visibility",
                },
                {
                    "data": "file_url",
                    "searchable": false,
                    "orderable": false,
                    "render": function (value) {
                        var str = "";
                        if (value) {
                            str += '<div class="float-left mx-2">';
                            str += '<img src="'+value+'" width="60" height="60">';
                            str += '</div>';
                        }
                        return str;
                    }
                },
                {
                    "data": "created_at",
                },
                {
                    "data": "operations",
                    "searchable": false,
                    "orderable": false,
                    "render": function (value) {
                        var str = "";
                        if(value['edit'] || value['delete'])
                        {
                            str += '<div class="btn-group">';
                            str += '<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>';
                            str += '<div class="dropdown-menu">';
                                
                            if(value['edit'])
                                str += '<a class="dropdown-item" href="'+value['edit']+'">{{ __('Edit') }}</a>';
                                                                  
                            if(value['delete'])                                
                               str += '<a class="dropdown-item" href="'+value['delete']+'">{{ __('Delete') }}</a>';
                                                                    
                            str += '</div>';
                            str += '</div>';
                        }
                        return str;
                    }
                },
            ]
        });

        function delay(callback, ms) {
            var timer = 0;
            return function () {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        $('.dataTables_filter input').unbind().bind('input', (delay(function (e) {
            table.search(this.value).draw();
            return;
        }, 400))); 

        table.columns().eq( 0 ).each( function (colIdx) {
            if (colIdx != 0) return; //Do not add event handlers for these columns

            $( 'input', table.column( colIdx ).header() ).on( 'keyup change', delay(function () {
                table.column( colIdx ).search( this.value ).draw();
            }, 400));
        });

        $('#browse_table thead th').on("click", 'input', function (event) {
            event.stopPropagation();
        });

        $("#show_all").on("change", delay(function () {
            table.ajax.reload();
        }, 400));
    </script>
@endsection

@section('content')

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">{{ __($menu_item->title) }}</h4>
                <div class="ml-auto text-right">
                    @can('add')
                        <a href="{{ route($route_name.'.add') }}" class="btn btn-info">{{ __('Add New') }}</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        @if (session('success')) <div class="alert alert-success" role="alert">{{ session('success') }}</div> @endif
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="custom-control custom-checkbox mx-3 mb-3">
                                    <input type="checkbox" class="custom-control-input" id="show_all" name="show_all" checked>
                                    <label class="custom-control-label" for="show_all">{{ __('Show posts from all authors') }}</label>
                                </div>
                            </div>
                        </div>
                        <h5 class="card-title"><!-- Table title here --></h5>
                        <div class="table-responsive">
                            <table id="browse_table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Author') }}</th>
                                        <th>{{ __('Visibility') }}</th>
                                        <th>{{ __('Featured Image') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection