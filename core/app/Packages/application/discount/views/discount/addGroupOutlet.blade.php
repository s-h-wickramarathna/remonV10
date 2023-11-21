@extends('layouts.master') @section('title','Free Issue Groups assign to Outlet')
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
        <li class="active">Discount Group assign to Outlet</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Discount Group assign to Outlet</strong>
                </div>
                <div class="panel-body">
                    <form discountGroup="form" class="form-horizontal form-validation" method="post"
                          id="discountGroupOutlet_form" name="discountGroupOutlet_form">
                        {!!Form::token()!!}
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Discount Groups</label>

                            <div class="col-sm-9">
                                {!! Form::select('free_issue_group',$discountGroups, null,['class'=>'chosen', 'id'=>'free_issue_group','style'=>'width:100%;','required']) !!}
                            </div>
                        </div>
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
                                        <div id="loc1" class="form-group" hidden="true">
                                            <label class="col-sm-3 control-label required">Area</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="raw_count" name="raw_count" value="1">
                                            <input type="hidden" id="main_raw_count" name="main_raw_count" value="1">
                                            <table id="grn_tbl" name="grn_tbl" border="0" class="table">
                                                <tbody id="grn_detail">
                                                <tr id="selected_raw_1">
                                                    <td style="text-align:center;width: 70%"><label
                                                                class="control-label">Outlets</label>
                                                        @if($errors->has('pr_name_1[]'))
                                                            {!! Form::select('pr_name_1[]',[], null,['class'=>'error', 'multiple','id'=>'pr_name_1','style'=>'width:100%;','required']) !!}
                                                            <label id="label-error" class="error"
                                                                   for="label">{{$errors->first('pr_name_1[]')}}</label>
                                                        @else
                                                            {!! Form::select('pr_name_1[]',[], Input::old('pr_name_1[]'),['multiple','id'=>'pr_name_1','style'=>'width:100%;','required']) !!}
                                                        @endif
                                                    </td>
                                                    <td style="text-align:center;width: 50%">
                                                        <div class="row" style="margin-top: 60px">
                                                            <a href='#' id='select-all'
                                                               style="font-size: 16px;color: green">Select
                                                                All</a>
                                                        </div>
                                                        <div class="row">
                                                            <a href='#' id='deselect-all'
                                                               style="font-size: 16px;color: red">Deselect
                                                                All</a>
                                                        </div>
                                                    </td>
                                                    <td style="text-align:center"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="pull-right">
                                            <div >
                                                <div class="pull-left">
                                                    <button type="button" onclick="confirmAlert();"
                                                            class="btn btn-primary"><i
                                                                class="fa fa-floppy-o" style="padding-right: 14px;"></i>
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#pr_name_1').multiSelect();

            for (var i = 1; i < $('.locations').length; i++) {
                var otherDrops = $('.locations').eq(i);
                otherDrops.empty();
                otherDrops.append("<option value='0'>All " + otherDrops.prop('name').capitalizeFirstLetter() + "</option>");
                otherDrops.trigger("chosen:updated");
            }

            $('#select-all').click(function () {
                $('#pr_name_1').multiSelect('select_all');
                return false;
            });
            $('#deselect-all').click(function () {
                $('#pr_name_1').multiSelect('deselect_all');
                return false;
            });

            $('#free_issue_group').change(function () {
                $('#region').val(0);
                $('#region').trigger('chosen:updated');
                $('#region').trigger('change');

                $('#area').val(0);
                $('#area').trigger('chosen:updated');
                $('#area').trigger('change');

                $('#territory').val(0);
                $('#territory').trigger('chosen:updated');
                $('#territory').trigger('change');

                $('#route').val(0);
                $('#route').trigger('chosen:updated');
                $('#route').trigger('change');

                $('#pr_name_1').empty();
            });

            $('.locations').change(function () {
                var free_issue_group = $('#free_issue_group').val();
                if (free_issue_group != 0) {
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
                    $.get("{{ url('discount/outletAssign/json/getLocation')}}",
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

                                var modelMulti = $('#pr_name_1');
                                console.log(data.dataOutlet);
                                modelMulti.empty();
                                $.each(data.dataOutlet, function (index, element) {
                                    modelMulti.append("<option value='" + element.id + "'>" + element.name + "</option>");
                                });
                                modelMulti.multiSelect('refresh');

                                $('.panel').removeClass('panel-refreshing');
                            });
                } else {
                    alert("Please Select Free Issue Group First");
                    location.reload();
                }
            });

        });

        function confirmAlert() {
            var free_issue_group = $('#free_issue_group').val();
            var pr1 = $('#pr_name_1').val();

            if (free_issue_group == 0) {
                alert('Please Select a Discount Group..');
            } else if (pr1 == null) {
                alert('Please Select Outlet..');
            } else {
                var a = confirm("Are you sure..?");
                if (a) {
                    document.discountGroupOutlet_form.submit();
                }
            }
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

        String.prototype.capitalizeFirstLetter = function () {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }

    </script>
@stop