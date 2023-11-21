@extends('layouts.master') @section('title','Customer List')
@section('css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/ag_grid/themes/theme-material.css')}}">
<style type="text/css">


    .btn-grid-sm{
        background: none;
        color: #3C3C3C;
        border:none;
        font-weight: 800;
        font-size: 15px;
        padding: 5px;
    }

    .btn-grid-sm:hover{
        color: red;
    }

    .grid-align {
        text-align: center;
    }

    .gray{
        color: #4A4A4A !important;
    }

    .disabled{
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
            <a href="javascript:;">Invoice Management</a>
        </li>
        <li class="active">Customer List</li>
    </ol>

    <section>
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <div class="row">
                        <div class="col-xs-6"><strong>Customer List</strong></div>
                        
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered bordered table-striped table-condensed datatable">
                        <thead>
                        <tr>
                            <th rowspan="2" class="text-center" width="4%">#</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;" width="20%">Name(code)</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;" width="15%">Tel/Fax</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;" width="20%">Email/Address</th>
                            <th rowspan="2" class="text-right" style="font-weight:normal;" width="15%">Outstanding(Rs.)</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;" width="10%">Credit</th>
                            <th class="text-center" width="4%" style="font-weight:normal;">Action</th>
                        </tr>
                        <tr style="display: none;">
                            <th style="display: none;" width="2%"></th>
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
           // table = generateTable('.datatable', '{{url('invoice/json/getOutlets')}}', [], [], [4]);

            $('.datatable').dataTable().on('draw.dt', function () {
                $("[data-toggle=tooltip]").tooltip();
            });


            $('.datatable').dataTable().fnDestroy();

            $('.datatable').DataTable({
                "columnDefs": [
                    { "orderable": false, "targets": [] },
                    {"className":"text-center vertical-align-middle", "targets":[2]},
                    {"className":"text-right vertical-align-middle", "targets":[]}
                ],
                "ajax": {
                    "url" : "{{url('invoice/json/getOutlets')}}",
                    "type": "GET"

                },
                "processing": true,
                "serverSide": true,
                'order': [1, 'asc'],
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]]
            });


        });

        
    </script>
@stop
