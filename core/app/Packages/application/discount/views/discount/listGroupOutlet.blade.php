@extends('layouts.master') @section('title','List of Outlets Discount Groups')
@section('css')
    <style type="text/css">
        .panel.panel-bordered {
            border: 1px solid #ccc;
        }

        .btn-primary {
            color: white;
            background-color: #F8AE2A;
            border-color: #F8AE2A;
        }

        .chosen-container {
            font-family: 'FontAwesome', 'Open Sans', sans-serif;
        }

        .spacing-table {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0px;
            border-collapse: separate;
            border-spacing: 0px; /* this is the ultimate fix */
        }

        .spacing-table th {
            text-align: left;
            padding: 0px 0px;
        }

        .spacing-table td {
            border-width: 3px 0;
            border-style: solid;
            color: white;
            padding: 5px 5px;
        }

        .spacing-table td:first-child {
            border-left-width: 1px;
            border-radius: 1px 0 0 1px;
        }

        .spacing-table td:last-child {
            border-right-width: 1px;
            border-radius: 0 1px 1px 0;
        }

        b strong {
            font-weight: bold;
        }

        .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
            background-color: rgba(238, 238, 238, 0.64);
            opacity: 1;
        }

        .btn-success {
            color: white;
            background-color: #689A07;
            border-color: #689A07;
        }

        .btn-success:hover {
            color: white;
            background-color: #F8AE2A !important;
            border-color: #F8AE2A !important;
        }

        .btn-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            background-color: #314608;
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

        .form-group {
            margin-bottom: 0px !important;
            margin-top: 15px;
        }

        .btn-primary {
            color: white;
            background-color: #C51C6A;
            border-color: #C51C6A;
        }

        .chosen-container {
            font-family: 'FontAwesome', 'Open Sans', sans-serif;
        }

        .spacing-table {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0px;
            border-collapse: separate;
            border-spacing: 0px; /* this is the ultimate fix */
        }

        .spacing-table th {
            text-align: left;
            padding: 0px 0px;
        }

        .spacing-table td {
            border-width: 3px 0;
            border-style: solid;
            color: white;
            padding: 5px 5px;
        }

        .spacing-table td:first-child {
            border-left-width: 1px;
            border-radius: 1px 0 0 1px;
        }

        .spacing-table td:last-child {
            border-right-width: 1px;
            border-radius: 0 1px 1px 0;
        }

        b, strong {
            font-weight: bold;
        }

        .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
            background-color: rgba(238, 238, 238, 0.64);
            opacity: 1;
        }

        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 6px 15px;
            border-color: #e4e4e4;
        }
    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;"> Discount Management</a>
        </li>
        <li>
            <a href="javascript:;"> Discount to Outlet</a>
        </li>
        <li class="active">List of Outlet's Discount Group</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>List of Outlet's Discount Group</strong>
                </div>
                <div class="panel-body">
                    <form discountGroup="form" class="form-horizontal form-validation" method="post"
                          id="freeIssueGroupOutlet_form" name="freeIssueGroupOutlet_form">
                        {!!Form::token()!!}
                        <?php $j = 1 ?>
                        @foreach($location_category as $key => $value)
                            @if($j==count($location_category))
                                <div class="form-group" style="display: none;">
                                    @else
                                        <div class="form-group">
                                            @endif
                                            <label class="col-sm-3 control-label required">{{ $key }} </label>

                                            <div class="col-sm-9">
                                                {!! Form::select(strtolower($key),$value, null,['class'=>'chosen locations', 'id'=>strtolower($key),'style'=>'width:100%;','required']) !!}
                                            </div>
                                        </div>
                                        <?php $j++ ?>
                                        @endforeach
                                        <div class="col-sm-6 col-md-offset-11" style="margin-top: 20px">
                                            <div class="pull-left">
                                                <button type="button" onclick="confirmAlert();"
                                                        class="btn btn-primary"><i
                                                            class="fa fa-floppy-o" style="padding-right: 14px;"></i>
                                                    Find
                                                </button>
                                            </div>
                                        </div>
                                        <table style="margin-top: 0px" hidden="true" id="pr_id"
                                               class="table table-bordered bordered table-striped table-condensed datatable">
                                            <thead>
                                            <tr style="background: rgba(204, 204, 204, 0.21);">
                                                <th class="text-center" width="5%"
                                                    style="font-weight:normal;">#
                                                </th>
                                                <th class="text-center" width="50%"
                                                    style="font-weight:normal;">Outlet Name
                                                </th>
                                                <th class="text-center" width="10%"
                                                    style="font-weight:normal;">View Group
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detail_div" name="detail_div">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Group Details</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered bordered table-striped table-condensed datatablegroup_details"
                           id="datatablegroup_details">
                        <thead>
                        <tr>
                            <th class="text-center" width="80%" style="font-weight:normal;">Group</th>
                            <th class="text-center" width="50%" style="font-weight:normal;">Status</th>
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
    <script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script type="text/javascript">
        var id = 0;
        var table = '';
        $(document).ready(function () {
            $('#pr_name_1').multiSelect();

            for (var i = 1; i < $('.locations').length; i++) {
                var otherDrops = $('.locations').eq(i);
                otherDrops.empty();
                otherDrops.append("<option value='0'>All " + otherDrops.prop('name').capitalizeFirstLetter() + "</option>");
                otherDrops.trigger("chosen:updated");
            }

            $('.locations').change(function () {

                var group = $('#free_issue_group').val();
                var index = $('.locations').index(this);
                var locType = $(this).prop('name');
                var value = $(this).val();
                var nextDropDown = $('.locations').eq(index + 1);
                var prevDropDown = $('.locations').eq(index - 1);

                for (var i = index + 1; i < $('.locations').length; i++) {
                    var otherDrops = $('.locations').eq(i);
                    otherDrops.empty();
                    otherDrops.append("<option value='0'>All " + otherDrops.prop('name').capitalizeFirstLetter() + "</option>");
                    otherDrops.trigger("chosen:updated");
                }

                if (locType == 'outlet') {
                    nextDropDown = $('.locations').eq(index);
                }
                $('.panel').addClass('panel-refreshing');
                $.get("{{ url('freeissue/outletAssign/json/getLocation')}}",
                        {
                            locType: locType,
                            location: value,
                            prevLocationType: prevDropDown.prop('name'),
                            prevLocationId: prevDropDown.val(),
                            group: group
                        },
                        function (data) {
                            if (value > 0) {
                                var model = nextDropDown;
                                model.empty();
                                model.append("<option value='0'>All " + nextDropDown.prop('name').capitalizeFirstLetter() + "</option>");
                                $.each(data.data, function (index, element) {
                                    model.append("<option value='" + element.id + "'>" + element.name + "</option>");
                                });
                                model.trigger("chosen:updated");

                                for (var i = index + 2; i < $('.locations').length; i++) {
                                    var otherDrops = $('.locations').eq(i);
                                    otherDrops.empty();
                                    otherDrops.append("<option value='0'>All " + otherDrops.prop('name').capitalizeFirstLetter() + "</option>");
                                    otherDrops.trigger("chosen:updated");
                                }
                            }

//                                var modelMulti = $('#pr_name_1');
//                                console.log(data.dataOutlet);
//                                modelMulti.empty();
//                                $.each(data.dataOutlet, function (index, element) {
//                                    modelMulti.append("<option value='" + element.id + "'>" + element.name + "</option>");
//                                });
//                                modelMulti.multiSelect('refresh');

                            $('.panel').removeClass('panel-refreshing');
                        });


            });

            $('.datatable').on('click', '.btn-groupDetail', function () {
                var outlet_id = $(this).data('outlet');
                $('#datatablegroup_details').dataTable().fnDestroy();
                generateTable('#datatablegroup_details', '{{url('discount/outletAssign/json/listDetailGroupOutlet')}}?outlet_id=' + outlet_id, [], []);
                $('#detail_div').modal('show');
            });

            $('#datatablegroup_details').on('draw.dt', function () {
                $("[data-toggle=tooltip]").tooltip();

                $('.menu-activate').change(function () {
                    if ($(this).prop('checked') == true) {
                        var y = confirm("Are you sure...?");
                        if (y) {
                            ajaxRequest('{{url('discount/outletAssign/status')}}', {
                                'id': $(this).val(),
                                'status': 1
                            }, 'post', successFunc);
                        } else {
                            $(this).prop('checked', false);
                        }
                    } else {
                        var y = confirm("Are you sure...?");
                        if (y) {
                            ajaxRequest('{{url('discount/outletAssign/status')}}', {
                                'id': $(this).val(),
                                'status': 0
                            }, 'post', successFunc);
                        } else {
                            $(this).prop('checked', false);
                        }
                    }
                });
            });

        });

        function confirmAlert() {
            var region = $('#region').val();
            var area = $('#area').val();
            var territory = $('#territory').val();
            var route = $('#route').val();
            $('.datatable').show();
            $('.datatable').dataTable().fnDestroy();
            var status = $("#status_cmb").val();
            $('.datatable').dataTable({
                "columnDefs": [
                    {"orderable": false, "targets": []},
                    {"className": "text-center vertical-align-middle", "targets": []},
                    {"className": "text-right vertical-align-middle", "targets": []}
                ],
                'ajax': '{{url('discount/outletAssign/json/list')}}?region=' + region + '&area=' + area + '&territory=' + territory + '&route=' + route,
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
        }

        function get_item_datas(id) {
            var item_count = $('#main_raw_count').val();
            var item_id1 = $('#pr_name_' + id).val();
            for (var i = 1; i < item_count; i++) {

                var item_id = $('#pr_name_' + i).val();

                if (item_id == item_id1) {
                    if (id != 1) {
                        $('#pr_name_' + i).focus();
                        $('#selected_raw_' + id).remove();
                    }
                } else {
                    if ((i != id) || (i == 1)) {
                        $('#pr_name_' + id).val(item_id1);
                        $('#pr_name_' + id).focus();
                    }
                }
            }
        }

        function check_rule() {
            var rule = $('#rule').val();
            if (rule == 0) {
                alert('Please Select Rule..');
                $('#group_name').val('');
                $('#group_name_chosen').css({border: 'red'});
                $('#group_name').focus();
            }
        }

        function successFunc(data) {
            $('#detail_div').ajax.reload();
        }

        String.prototype.capitalizeFirstLetter = function () {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }

    </script>
@stop