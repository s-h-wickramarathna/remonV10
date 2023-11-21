@extends('layouts.master') @section('title','Menu List')
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
        <li class="active">Receipt List</li>
    </ol>

    <section>
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <div class="row">
                        <div class="col-xs-4"><strong>Receipt List</strong></div>
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6  text-right">
                                <h6>Customer</h6>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <select name="outlet" id="outlet"  class="form-control chosen" required="required">
                                        <option value="0">-ALL-</option>
                                     @foreach($outlets as $outlet)
                                        <option value="{{$outlet->id}}">{{$outlet->f_name .' '. $outlet->l_name}}</option>
                                     @endforeach
                                </select>    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered bordered table-striped table-condensed datatable">
                        <thead>
                        <tr>
                            <th rowspan="2" class="text-center" width="4%">#</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Customer Name</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Receipt No</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Receipt Date</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal">Amount</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Payment Method</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Receipt By</th>
                            <th colspan="2" class="text-center" width="4%" style="font-weight:normal;">Action</th>
                        </tr>
                        <tr style="display: none;">
                            <th style="display: none;" width="2%"></th>
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
            changeOutlet();
            var outlet = $('select[name="outlet"]');
            outlet.change(function (e) {
                $('.panel').addClass('panel_refreshing');
                changeOutlet();
            });

            function changeOutlet() {
                var outlet = $('select[name="outlet"]');
                $('.panel').addClass('panel-refreshing');

                $('.datatable').dataTable().fnDestroy();
                $('.datatable').DataTable({
                    "columnDefs": [
                        { "orderable": false, "targets": [] },
                        {"className":"text-center vertical-align-middle", "targets":[5,6]},
                        {"className":"text-right vertical-align-middle", "targets":[4]}
                    ],
                    "ajax": {
                        "url" : "{{url('payment/json/getRecipts')}}",
                        "type": "GET",
                        "data" : {
                            "outlet_id" : outlet.val()
                        }

                    },

                    "processing": true,
                    "serverSide": true,
                    'order': [1, 'asc'],
                    "pageLength": 25,
                    "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]]
                });
                $('.panel').removeClass('panel-refreshing');
            }


        });

        
    </script>
@stop
