@include('app')
@extends('layouts.master')
@section('title','List Account')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/ui-grid.css')}}">
    <style type="text/css">
        .panel.panel-bordered {
            border: 1px solid #ccc;
        }

        .btn-primary {
            color: white;
            background-color: #1E2444;
            border-color: #1E2444;
        }

        .chosen-container {
            font-family: 'FontAwesome', 'Open Sans', sans-serif;
        }
    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Account Management</a>
        </li>
        <li class="active">List Account</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <div class="row">
                        <div class="col-xs-6"><strong>Account List</strong></div>
                        <div class="col-xs-6 text-right">
                            @if($permissions)
                                <button class="btn btn-success" onclick="window.location.href='{{url('account/add')}}'">
                                    <i
                                            class="fa fa-plus" style="width: 28px;padding-right: 16px;"></i>Add
                                </button>

                            @endif
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table id="print_table" name="print_table"
                           class="table table-bordered bordered table-striped table-condensed datatable">
                        <thead>
                        <tr>
                            <th rowspan="2" class="text-center" width="4%">#</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Account No</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Bank</th>
                            <th rowspan="2" class="text-center" style="font-weight:normal;">Branch</th>
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
            table = generateTable('.datatable', '{{url('account/json/list')}}', [], []);

            table.on('draw.dt', function () {
                $("[data-toggle=tooltip]").tooltip();

                $('.datatable .menu-activate').change(function () {
                    if ($(this).prop('checked') == true) {
                        ajaxRequest('{{url('account/status')}}', {
                            'id': $(this).val(),
                            'status': 1
                        }, 'post', successFunc);
                    } else {
                        ajaxRequest('{{url('account/status')}}', {
                            'id': $(this).val(),
                            'status': 0
                        }, 'post', successFunc);
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

        function ExportExcel() {
            window.location.href = '{{url('product/exceltolist')}}';
        }
    </script>
@stop

