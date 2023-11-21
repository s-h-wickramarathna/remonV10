@extends('layouts.master') @section('title','Customer Report')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('/assets/ag_grid/themes/theme-material.css')}}">
    <style type="text/css">


        .btn-grid-sm {
            background: none;
            color: #3C3C3C;
            border: none;
            font-weight: 800;
            font-size: 15px;
            padding: 5px;
        }

        .btn-grid-sm:hover {
            color: red;
        }

        .grid-align {
            text-align: center;
        }

        .gray {
            color: #4A4A4A !important;
        }

        .disabled {
            color: #ddd !important;
        }

    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Reports</a>
        </li>
        <li class="active">Customer Report</li>
    </ol>

    <section>
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <form role="form" method="get" action="{{url('reports/customer/download')}}">
                        <div class="row">
                            <div class="col-xs-6"><strong>Customer Report</strong></div>
                            <div class="col-md-6 ">
                                <div class="col-sm-8 pull-right">
                                    <select name="marketeer" class="form-control chosen" required="required"
                                            id="dis_id">
                                        @foreach($reps as $rep)
                                            <option value="{{$rep->id}}">
                                                {{$rep->first_name ? $rep->first_name : ''}}
                                                {{$rep->last_name ? $rep->last_name : ''}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-sm-2 pull-right"><h6>Marketeer</h6></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1 pull-right" style="margin-right: 20px;">
                                <button type="submit" value="1" name="submit" class="btn btn-info">PDF <i class="fa fa-download"></i>
                                </button>
                            </div>
                            <div class="col-xs-1 pull-right" style="margin-right: 20px;">
                                <button type="submit" value="2" name="submit" class="btn btn-warning">Excel <i class="fa fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <table id="print_table" name="print_table"
                           class="table table-bordered bordered table-striped table-condensed datatable">
                        <thead style="background: rgba(204, 204, 204, 0.21);">
                        <tr>
                            <th class="text-center" width="4%">#</th>
                            <th class="text-center" style="font-weight:normal;">Name</th>
                            <th class="text-center" style="font-weight:normal;">Address / E-mail</th>
                            <th class="text-center" width="10%" style="font-weight:normal;">Mobile</th>
                            {{--  <th rowspan="2" class="text-center" style="font-weight:normal;">Telephone</th>--}}
                            <th class="text-center" style="font-weight:normal;">Marketeer</th>
                            <th class="text-center" width="10%" style="font-weight:normal;">Credit Limit</th>
                            <th class="text-center" width="10%" style="font-weight:normal;">Credit Period</th>
                        </tr>

                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>

@stop
@section('js')

    <script src="{{url('/assets/angular/angular/angular.min.js')}}"></script>
    <script src="{{url('/assets/ag_grid/ag-grid.js')}}"></script>
    <script src="{{url('/assets/ag_grid/grid/grid.js')}}"></script>


    <script type="text/javascript">
        /* 
         * Craft by mac
         * Sometimes you have to go with the trend but it aint the choice it is the choise
         *
         */
        $(document).ready(function () {

            $('.datatable').dataTable().fnDestroy();
            $('.panel').addClass('panel-refreshing');
            var dis_id = $('#dis_id').val();

            $.ajax({
                url: '{{url('reports/customer/json/getOutlets')}}',
                data: {dis_id: dis_id},
                method: 'GET',
                success: successFunc
            });

            $('#dis_id').on('change', function () {
                var dis_id = $(this).val();
                $('.panel').addClass('panel-refreshing');

                $.ajax({
                    url: '{{url('reports/customer/json/getOutlets')}}',
                    data: {dis_id: dis_id},
                    method: 'GET',
                    success: successFunc
                });
            });

            function successFunc(response) {
                console.log(response);
                $('.panel').removeClass('panel-refreshing');
                $('.datatable').dataTable().fnDestroy();

                $('.datatable').DataTable({
                    "columnDefs": [
                        {"orderable": false, "targets": []},
                        {"className": "text-center vertical-align-middle", "targets": [5, 6]},
                        {"className": "text-right vertical-align-middle", "targets": []}
                    ],
                    'data': response.data,
                    "pageLength": 25,
                    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
                });
            }
        });


    </script>
@stop
