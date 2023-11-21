@extends('layouts.master') @section('title','Product List')
@section('css')
    <style type="text/css">
        .switch.switch-sm {
            width: 30px;
            height: 16px;
        }

        .switch.switch-sm span i::before {
            width: 16px;
            height: 16px;
        }

        .btn-success:hover, .btn-success:focus, .btn-success.focus, .btn-success:active, .btn-success.active, .open > .dropdown-toggle.btn-success {
            color: white;
            background-color: #3b3e81;
            border-color: #3b3e81;
        }

        .btn-success {
            color: white;
            background-color: #1E2444;
            border-color: #1E2444;
        }

        .btn-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            background-color: #3b3e81;
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
            color: #1975D1;
        }

        .datatable a.blue:hover {
            color: #003366;
        }

        b, strong {
            font-weight: bold;
        }

    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Configuration</a>
        </li>
        <li class="active">Make Rep Log out</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <div class="row">
                        <div class="col-xs-6"><strong>Make Rep Log out</strong></div>
                        <div class="col-xs-6 text-right">

                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table id="print_table" name="print_table"
                           class="table table-bordered bordered table-striped table-condensed datatable">
                        <thead>
                        <tr>
                            <th class="text-center" width="4%">#</th>
                            <th class="text-center" style="font-weight:normal;">Rep Name</th>
                            <th class="text-center" style="font-weight:normal;">User Name</th>
                            <th class="text-center" style="font-weight:normal;">Distributor</th>
                            <th class="text-center" width="6%" style="font-weight:normal;">Status</th>
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
            table = generateTable('.datatable', '{{url('configuration/repActive/json/list')}}', [], []);
            table.on('draw.dt', function () {
                $("[data-toggle=tooltip]").tooltip();

                $('.datatable .menu-activate').change(function () {
                    if ($(this).prop('checked') == true) {
                        var y = confirm("Are you sure 1...?");
                        if (y) {
                            ajaxRequest('{{url('configuration/repActive/status')}}', {
                                'id': $(this).val(),
                                'status': 0
                            }, 'post', successFunc);
                        } else {
                            table.ajax.reload();
                        }
                    } else {
                        var y = confirm("Are you sure...?");
                        if (y) {
                            ajaxRequest('{{url('configuration/repActive/status')}}', {
                                'id': $(this).val(),
                                'status': 1
                            }, 'post', successFunc);
                        } else {
                            table.ajax.reload();
                        }
                    }
                });
            });
        });

        function successFunc(data) {
            table.ajax.reload();
        }

        function printTable() {
            $('#print_table').print();
        }
    </script>
@stop
