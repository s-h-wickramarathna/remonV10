@extends('layouts.master') @section('title','Discount Group List')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css">
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
            background-color: #F8AE2A;
            border-color: #F8AE2A;
        }

        .btn-success {
            color: white;
            background-color: #F8AE2A;
            border-color: #F8AE2A;
        }

        b, strong {
            font-weight: bold;
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
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Discount Management</a>
        </li>
        <li>
            <a href="javascript:;">Discount Group</a>
        </li>
        <li class="active">Discount Group List</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <div class="row">
                        <div class="col-xs-6"><strong>Discount Group List</strong></div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="col-md-1">
                            <label class="control-label required">Status</label>
                        </div>
                        <div class="col-md-2 left">
                            <select id="status_cmb" name="status_cmb" class="chosen">
                                <option value="0">All</option>
                                <option value="1">Active</option>
                                <option value="2">Deactive</option>
                            </select>
                        </div>
                        <div class="col-md-3 right">
                            <button type="button" class="btn btn-info" id="btn-find"><i class="fa fa-floppy-o"></i> Find
                            </button>
                            <button class="btn btn-success"
                                    onclick="window.location.href='{{url('discount/group/add')}}'"><i
                                        class="fa fa-plus" style="width: 28px;"></i>Add
                            </button>
                        </div>
                    </div>

                    <table style="margin-top: 0px" hidden="true" id="pr_id"
                           class="table table-bordered bordered table-striped table-condensed datatable">
                        <thead>
                        <tr style="background: rgba(204, 204, 204, 0.21);">
                            <th rowspan="2" class="text-center" width="30%" style="font-weight:normal;">Group Name</th>
                            <th rowspan="2" class="text-center" width="30%" style="font-weight:normal;">Rule Name</th>
                            <th colspan="1" class="text-center" width="10%" style="font-weight:normal;">View Group</th>
                            <th rowspan="2" class="text-center" width="10%" style="font-weight:normal;">View Rule</th>
                            <th rowspan="2" class="text-center" width="10%" style="font-weight:normal;">Status</th>
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


    <div class="modal fade" id="detail_div" name="detail_div">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">In Group Details</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered bordered table-striped table-condensed datatableingroup_details"
                           id="datatableingroup_details">
                        <thead>
                        <tr>
                            <th class="text-center" width="60%" style="font-weight:normal;">Product</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rule_detail_div" name="rule_detail_div">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Rule Details</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered bordered table-striped table-condensed datatablerule_details"
                           id="datatablerule_details">
                        <thead>
                        <tr>
                            <th class="text-center" width="50%" style="font-weight:normal;">Value</th>
                            <th class="text-center" width="50%" style="font-weight:normal;">Discount</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


@stop
@section('js')
    <script src="{{asset('assets/sammy_new/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('assets/vendor/print/printThis.js')}}"></script>
    <script src="{{asset('assets/vendor/print/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/vendor/print/dataTables.buttons.min.js')}}"></script>
    <script type="text/javascript">
        var id = 0;
        var table = '';
        $(document).ready(function () {
            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
            });

            $('#pr_id').on('draw.dt', function () {
                $("[data-toggle=tooltip]").tooltip();

                $('.menu-activate').change(function () {
                    if ($(this).prop('checked') == true) {
                        alert('You Can not active Decativated Groups');
                        $(this).prop('checked', false);
                    } else {
                        if (confirm("Are you sure...?")) {
                            ajaxRequest('{{url('discount/group/status')}}', {
                                'id': $(this).val(),
                                'status': 0
                            }, 'post', successFunc);
                        } else {
                            $(this).prop('checked', false);
                        }
                    }
                });
            });

            $('#btn-find').click(function () {
                $('.datatable').show();
                $('.datatable').dataTable().fnDestroy();
                var status = $("#status_cmb").val();
                $('.datatable').dataTable({
                    "columnDefs": [
                        {"orderable": false, "targets": []},
                        {"className": "text-center vertical-align-middle", "targets": []},
                        {"className": "text-right vertical-align-middle", "targets": []}
                    ],
                    'ajax': '{{url('discount/group/json/list')}}?status=' + status,
                    "pageLength": 25,
                    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    "dom": 'Bfrtip',
                    "buttons": [
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                    ],
                });

            });

            $('.datatable').on('click', '.btn-groupDetail', function () {
                var group_id = $(this).data('group');

                $('#datatableingroup_details').dataTable().fnDestroy();
                $('#datatableoutgroup_details').dataTable().fnDestroy();
                generateTable('#datatableingroup_details', '{{url('discount/group/json/listDetailGroup')}}?group_id=' + group_id, [], []);
                $('#detail_div').modal('show');
                //$('#detail_div').show();
            });

            $('.datatable').on('click', '.btn-ruleDetail', function () {
                var rule_id = $(this).data('rule');

                $('#datatablerule_details').dataTable().fnDestroy();
                generateTable('#datatablerule_details', '{{url('discount/rule/json/listDetailRule')}}?rule_id=' + rule_id, [], []);
                $('#rule_detail_div').modal('show');
                //$('#detail_div').show();
            });

        });

        function successFunc(data) {
            table.ajax.reload();
        }
    </script>
@stop
