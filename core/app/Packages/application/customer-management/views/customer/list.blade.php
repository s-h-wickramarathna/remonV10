@extends('layouts.master') @section('title','Customer List')
@section('css')
    <style type="text/css">
        .switch.switch-sm {
            width: 30px;
            height: 16px;
        }

        b, strong {
            font-weight: bold;
        }

        .switch.switch-sm span i::before {
            width: 16px;
            height: 16px;
        }

        .btn-success:hover, .btn-success:focus, .btn-success.focus, .btn-success:active, .btn-success.active, .open > .dropdown-toggle.btn-success {
            color: white;
            background-color: #F8AE2A;
            border-color: #F8AE2A;
        }

        .btn-success {
            color: white;
            background-color: #F8AE2A;
            border-color: #F8AE2A;
        }

        .btn-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            background-color: #F8AE2A;
            -moz-opacity: 0;
            -khtml-opacity: 0;
            -webkit-opacity: 0;
            opacity: 0;
            -ms-filter: progid:DXImageTransform.Microsoft.Alpha(opacity=0 *100);
            filter: alpha(opacity=0 *100);
            -webkit-transform: scale3d(0.7, 1, 1);
            -moz-transform: scale3d(0.7, 1, 1);
            -o-transform: scale3d(0.7, 1, 1);
            -ms-transform: scale3d(0.7, 1, 1);
            transform: scale3d(0.7, 1, 1);
            -webkit-transition: transform 0.4s, opacity 0.4s;
            -moz-transition: transform 0.4s, opacity 0.4s;
            -o-transition: transform 0.4s, opacity 0.4s;
            transition: transform 0.4s, opacity 0.4s;
            -webkit-animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            -moz-animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            -o-animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
        }

        .switch :checked + span {
            border-color: #689A07;
            -webkit-box-shadow: #689A07 0px 0px 0px 21px inset;
            -moz-box-shadow: #2ecc71 0px 0px 0px 21px inset;
            box-shadow: #689A07 0px 0px 0px 21px inset;
            -webkit-transition: border 300ms, box-shadow 300ms, background-color 1.2s;
            -moz-transition: border 300ms, box-shadow 300ms, background-color 1.2s;
            -o-transition: border 300ms, box-shadow 300ms, background-color 1.2s;
            transition: border 300ms, box-shadow 300ms, background-color 1.2s;
            background-color: #689A07;

        }

        .datatable a.blue {
            color: #fff;
        }

        .datatable a.blue:hover {
            color: #fff;
        }

    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Customer Management</a>
        </li>
        <li class="active">Customer List</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <div class="row">
                        <div class="col-xs-6"><strong>Customer List</strong></div>
                        <div class="col-xs-6 text-right">
                            @if($permissions)
                                <button class="btn btn-success"
                                        onclick="window.location.href='{{url('customer/add')}}'"><i class="fa fa-plus"
                                                                                                    style="width: 28px;"></i>Add
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table id="print_table" name="print_table"
                           class="table table-bordered bordered table-striped table-condensed datatable">
                        <thead style="background: rgba(204, 204, 204, 0.21);">
                        <tr>
                            <th rowspan="2" class="text-center" width="4%">#</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Name</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Address / E-mail</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Mobile</th>
                            {{--  <th rowspan="2" class="text-center" style="font-weight:normal;">Telephone</th>--}}
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Marketeer</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Credit Limit</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Credit Period</th>
                            <th rowspan="2" class="text-center" width="6%" style="font-weight:normal;">Status</th>
                            <th colspan="1" class="text-center" width="4%" style="font-weight:normal;">Action</th>
                        </tr>
                        <tr style="display: none;">
                            <th style="display: none;" width="2%"></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">

       var id = 0;
        var table = '';
        $(document).ready(function () {

            getCustomers();




            $('.datatable').dataTable().on('draw.dt', function () {
                $("[data-toggle=tooltip]").tooltip();

                $('.datatable .menu-activate').change(function () {
                    console.log('call_1');
                    if ($(this).prop('checked') == true) {
                        ajaxRequest('{{url('customer/status')}}', {
                            'id': $(this).val(),
                            'status': 1
                        }, 'post', successFunc);
                    } else {
                        ajaxRequest('{{url('customer/status')}}', {
                            'id': $(this).val(),
                            'status': 0
                        }, 'post', successFunc);
                    }
                });
                $('.datatable .credit-activate').change(function () {
                    console.log('call_2');
                    if ($(this).prop('checked') == true) {
                        ajaxRequest('{{url('customer/credit-status')}}', {
                            'id': $(this).val(),
                            'status': 1
                        }, 'post', successFunc);
                    } else {
                        ajaxRequest('{{url('customer/credit-status')}}', {
                            'id': $(this).val(),
                            'status': 0
                        }, 'post', successFunc);
                    }
                });
            });
        });

        function successFunc(data) {

        }


        function getCustomers() {
            $('.datatable').dataTable().fnDestroy();
            $('.datatable').DataTable({
                "columnDefs": [
                    { "orderable": false, "targets": [] },
                    {"className":"text-center vertical-align-middle", "targets":[2]},
                    {"className":"text-right vertical-align-middle", "targets":[]}
                ],
                "ajax": {
                    "url" : "{{url('customer/json/list')}}",
                    "type": "GET"

                },
                "processing": true,
                "serverSide": true,
                'order': [1, 'asc'],
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]]
            });
        }




        function printTable() {
            $('#print_table').print();
        }
    </script>
@stop
