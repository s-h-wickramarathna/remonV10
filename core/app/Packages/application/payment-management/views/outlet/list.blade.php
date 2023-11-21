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


</style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Payment Management</a>
        </li>
        <li class="active">Customer List</li>
    </ol>

    <section>
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <div class="row" id="filter" hidden>
                        <div class="col-xs-2"><strong>Customer List</strong></div>
                        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                <h6><strong>Marketeer</strong></h6>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <select name="distributors" class="form-control" required="required" id="dis_id">
                                    <option value="0">--ALL--</option>
                                    @foreach($distributors as $distributor)
                                        <option value="{{$distributor->id}}">
                                        {{$distributor->first_name ? $distributor->first_name : ''}}
                                        {{$distributor->last_name ? $distributor->last_name : ''}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  

                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                            <button type="button" class="btn btn-success btn-find pull-right" style="margin-right: 10px">Find</button>
                        </div>            
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered bordered table-striped table-condensed datatable">
                        <thead>
                        <tr>
                            <th rowspan="2" class="text-center" width="4%">#</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;" width="20%">Name(code)</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;" width="10%">Inoivces</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;" width="20%">Tel/Fax</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;" width="20%">Email/Address</th>
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


            $('.datatable').dataTable().fnDestroy();
            $('.datatable').DataTable({
                    "columnDefs": [
                        { "orderable": false, "targets": [] },
                        {"className":"text-center vertical-align-middle", "targets":[2]},
                        {"className":"text-right vertical-align-middle", "targets":[]}
                    ],
                "ajax": {
                    "url" : "{{url('payment/json/getOutlets')}}",
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
