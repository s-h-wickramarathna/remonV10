@extends('layouts.master') @section('title','Add Discount Group')
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
            <a href="javascript:;"> Discount Group</a>
        </li>
        <li class="active">Add Discount Group</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Add Discount Group</strong>
                </div>
                <div class="panel-body">
                    <form discountGroup="form" class="form-horizontal form-validation" method="post"
                          id="freeIssueGroup_form" name="freeIssueGroup_form">
                        {!!Form::token()!!}
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Discount Rule</label>

                            <div class="col-sm-3">
                                {!! Form::select('rule',$rule, Input::old('rule'),['class'=>'chosen error','style'=>'width:500px;','required','data-placeholder'=>'Choose Rule','id'=>'rule']) !!}
                            </div>

                            <label class="col-sm-2 required">Group Name</label>

                            <div class="col-sm-3">
                                <input type="text" autocomplete="off" onkeyup="check_rule();"
                                       class="form-control @if($errors->has('type')) error @endif" name="group_name"
                                       id="group_name" placeholder="Group Name" required
                                       value="{{Input::old('group_name')}}">
                                @if($errors->has('group_name'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('group_name')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="raw_count" name="raw_count" value="1">
                            <input type="hidden" id="main_raw_count" name="main_raw_count" value="1">
                            <input type="hidden" id="dis_rule_type" name="dis_rule_type">
                            <table id="grn_tbl" name="grn_tbl" border="0" class="table">
                                <tbody id="grn_detail">
                                <tr id="selected_raw_1">
                                    {{--<td style="text-align:center">--}}
                                    {{--<a class="btn btn-success" id="add_raw_1" autocomplete="off" name="add_raw_1"--}}
                                    {{--style="height:30px" onclick="createField();"><i class="fa fa-plus"></i></a>--}}
                                    {{--</td>--}}
                                    <td style="text-align:center;width: 50%"><label class="control-label">Group
                                            Detail</label>
                                        {!! Form::select('pr_cat_in_group',$product_category, Input::old('pr_cat_in_group'),['class'=>'chosen error','style'=>'width:500px;','required','data-placeholder'=>'Choose Product','id'=>'pr_cat_in_group','name'=>'pr_cat_in_group']) !!}
                                        @if($errors->has('pr_name_1[]'))
                                            {!! Form::select('pr_name_1[]',$product_list, null,['class'=>'error', 'multiple','id'=>'pr_name_1','style'=>'width:50%;','required']) !!}
                                            <label id="label-error" class="error"
                                                   for="label">{{$errors->first('pr_name_1[]')}}</label>
                                        @else
                                            {!! Form::select('pr_name_1[]',$product_list, Input::old('pr_name_1[]'),['multiple','id'=>'pr_name_1','style'=>'width:100%;','required']) !!}
                                        @endif
                                    </td>
                                    <td style="text-align:center;width: 50%">
                                        <div class="row" style="margin-top: 120px">
                                            <a href='#' id='select-all-inGroup'
                                               style="font-size: 16px;color: green">Select
                                                All</a>
                                        </div>
                                        <div class="row">
                                            <a href='#' id='deselect-all-inGroup'
                                               style="font-size: 16px;color: red">Deselect
                                                All</a>
                                        </div>
                                    </td>
                                    <td style="text-align:center"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="pull-left">
                            <div class="col-sm-6 col-md-offset-11">
                                <div class="pull-left">
                                    <button type="button" onclick="confirmAlert();" class="btn btn-primary"><i
                                                class="fa fa-floppy-o" style="padding-right: 14px;"></i> Save
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
        var product = <?php echo json_encode($product_list) ?>;
        $(document).ready(function () {
            $('#pr_name_1').multiSelect();
            $('#ruleType_cmb').chosen();
            $('.form-validation').validate();
            $('#raw_count').val(1);
            $('#main_raw_count').val(1);
            var x = 0;
            var ruleType;

            $('#select_all').click(function () {
                alert($('#pr_name_1').val());
                $('#pr_name_1 option').prop('selected', true);
            });

            $('#select-all-inGroup').click(function () {
                $('#pr_name_1').multiSelect('select_all');
                return false;
            });
            $('#deselect-all-inGroup').click(function () {
                $('#pr_name_1').multiSelect('deselect_all');
                return false;
            });
            $('#pr_cat_in_group').change(function () {
                $.get("{{ url('freeissue/group/json/getProducts')}}",
                        {catId: $(this).val()},
                        function (data) {
                            var model = $('#pr_name_1');
                            model.empty();
                            console.log(data);
                            $.each(data.data, function (index, element) {
                                model.append("<option value='" + element.id + "'>" + element.name + "</option>");
                            });
                            model.multiSelect('refresh');
                        });
            });
            $('#rule').change(function () {
                $.get("{{ url('discount/group/json/getRuleType')}}",
                        {rule: $(this).val()},
                        function (data) {
                            ruleType = data.data[0]['rule_type'];
                            $('#dis_rule_type').val(ruleType);
                        });
            });
        });


        function confirmAlert() {
            var rule = $('#rule').val();
            var group_name = $('#group_name').val();
            var pr1 = $('#pr_name_1').val();
            var item_count = $('#main_raw_count').val();
            var rule_type = $('#dis_rule_type').val();
            if (rule == 0) {
                alert('Please Select Rule..');
                //$('#rule').css('border-color', 'red');
                $('#rule_chosen').css({border: 'red'});
            } else if (group_name == '') {
                $('#group_name').css({border: '1px solid red'});
            } else if (pr1 == null) {
                if (rule_type == 'invoice qty' || rule_type == 'invoice total') {
                    document.freeIssueGroup_form.submit();
                } else {
                    alert('Please Select In Group Product');
                }
            } else {
                document.freeIssueGroup_form.submit();
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
    </script>
@stop