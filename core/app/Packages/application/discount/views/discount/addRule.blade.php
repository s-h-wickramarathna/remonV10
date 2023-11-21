@extends('layouts.master') @section('title','Add Discount Rule')
@section('css')
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
            background-color: #314608 !important;
            border-color: #314608 !important;
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
             .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
            border-top:none;border:none;
        }
    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;"> Discount Rule Management</a>
        </li>
        <li>
            <a href="javascript:;"> Discount Rule</a>
        </li>
        <li class="active">Add Discount Rule</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Add Rule</strong>
                </div>
                <div class="panel-body">
                    <form discountRule="form" class="form-horizontal form-validation" method="post"
                          id="discountRule_form" name="discountRule_form">
                        {!!Form::token()!!}
                        <div class="form-group col-sm-12">
                            <label class="col-sm-1 control-label required">Rule Type</label>

                            <div class="col-sm-3">
                                <select id="rule_type" name="rule_type" class="chosen">
                                    <option value="0" {{ Request::old('rule_type') == 0 ? 'selected': '' }}>Select a Rule type</option>
                                    <option value="1" {{ Request::old('rule_type') == 1 ? 'selected': '' }}>Line Qty</option>
                                    <option value="2" {{ Request::old('rule_type') == 2 ? 'selected': '' }}>Line Total</option>
                                    <option value="3" {{ Request::old('rule_type') == 3 ? 'selected': '' }}>Category Qty</option>
                                    <option value="4" {{ Request::old('rule_type') == 4 ? 'selected': '' }}>Category Total</option>
                                    <option value="5" {{ Request::old('rule_type') == 5 ? 'selected': '' }}>Invoice Qty</option>
                                    <option value="6" {{ Request::old('rule_type') == 6 ? 'selected': '' }}>Invoice total</option>
                                </select>
                            </div>

                            <label class="col-sm-1 control-label required">Rule Name</label>

                            <div class="col-sm-3">
                                <input type="text" autocomplete="off"
                                       class="form-control @if($errors->has('type')) error @endif" name="rule_name"
                                       id="rule_name" placeholder="Rule Name" required
                                       value="{{Input::old('rule_name')}}" onkeyup="checkRuleType();">

                                       @if($errors->has('rule_name'))
                                            <label id="label-error" class="error"
                                                   for="label">{{$errors->first('rule_name')}}</label>
                                        @endif
                            </div>
                        </div>
                        <div class="form-group col-sm-12" style="margin-top:40px">
                            <input type="hidden" id="raw_count" name="raw_count" value="1">
                            <input type="hidden" id="main_raw_count" name="main_raw_count" value="1">
                            <table id="grn_tbl" name="grn_tbl" border="0" class="table">
                                <tbody id="grn_detail">
                                <tr id="selected_raw_1">
                                    <td style="text-align:center;width: 6%"></td>
                                    <td style="text-align:center;width: 30%">
                                        <input type="text" placeholder="Invoice Value" autocomplete="off" name="in_qty_1"
                                               id="in_qty_1"
                                               class="form-control @if($errors->has('in_qty_1')) error @endif"
                                               style="width:100%" required value="{{Input::old('in_qty_1')}}"
                                               onkeyup="checkNumInQty(1);">
                                    </td>
                                    <td style="text-align:center;width: 30%">
                                        <input type="text" placeholder="Discount(%)" autocomplete="off"
                                               name="out_qty_1" id="out_qty_1"
                                               class="form-control @if($errors->has('out_qty_1')) error @endif"
                                               style="width:100%" required value="{{Input::old('out_qty_1')}}"
                                               onkeyup="checkNumOutQty(1);">
                                    </td>
                                    <td style="text-align:center;;width: 5%">
                                        <a class="btn btn-success" id="add_raw_1" autocomplete="off" name="add_raw_1" onclick="createField();"
                                           style="height:30px"><i class="fa fa-plus"
                                                                  style=""></i></a>
                                    </td>
                                    <td style="text-align:center;width: 5%"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        
                            <div class="col-sm-1 col-md-offset-9">
                                <div class="pull-right">
                                    <button type="submit" onclick="confirmAlert();" class="btn btn-primary"><i
                                                class="fa fa-floppy-o"
                                                style="padding-right: 14px;"></i>
                                        Save
                                    </button>
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
            $('#ruleType_cmb').chosen();
            $('.form-validation').validate();
            $('#raw_count').val(1);
            $('#main_raw_count').val(1);
        });

        function createField() {
            var raw_count = $('#raw_count').val();
            var main_raw_count = $('#main_raw_count').val();
            raw_count++;
            main_raw_count++;
            $('#grn_detail').append(
                    '<tr id="selected_raw_' + raw_count + '">'
                    + '<td style="text-align:center;width: 10%"></td>'
                    + '<td style="text-align:center;width: 40%"><input type="text" placeholder="Invoice Value" autocomplete="off"  name="in_qty_' + raw_count + '" id="in_qty_' + raw_count + '" class="form-control @if($errors->has("in_qty_'+ raw_count +'")) error @endif" style="width:100%" required value="{{Input::old("in_qty_'+ raw_count +'")}}" onkeyup="checkNumInQty(' + raw_count + ');"></td>'
                    + '<td style="text-align:center;width: 40%"><input type="text" placeholder="Discount(%)"  autocomplete="off" name="out_qty_' + raw_count + '" id="out_qty_' + raw_count + '" class="form-control @if($errors->has("out_qty_'+ raw_count +'")) error @endif" style="width:100%" required value="{{Input::old("out_qty_'+ raw_count +'")}}" onkeyup="checkNumOutQty(' + raw_count + ');"></td>'
                    + '<td style="text-align:center;width: 5%"><a class="btn btn-success" id="add_raw_' + raw_count + '" name="add_raw_' + raw_count + '" style="height:30px" onclick="createField();"  ><i class="fa fa-plus" style=""></i></a></td>'
                    + '<td style="text-align:center;width: 5%"><a class="btn btn-danger" id="remove_raw_' + raw_count + '" name="remove_raw_' + raw_count + '" style="height:30px" onclick="removeRaw(' + raw_count + ');"  ><i class="fa fa-minus" style=""></i></a></td>'
                    + '</tr>'
            );
            $('#raw_count').val(raw_count);
            $('#main_raw_count').val(main_raw_count);
        }
        function removeRaw(id) {
            $('#selected_raw_' + id).remove();
            var raw_count = $('#raw_count').val();
            $('#raw_count').val(raw_count - 1);
        }

        function check_duplicate(id) {
            var value_p = $('#pr_value_' + id).val();
            var row_count = $('#main_raw_count').val();
            for (var i = 1; i >= row_count; i++) {
                if (($('#pr_value_' + i)).val() != '') {
                    if ($('#pr_value_' + i).val() == value_p) {
                        $('#pr_value_' + i).css('border-color', 'red');
                        $('#pr_value_' + i).val('');
                    } else {
                        $('#pr_value_' + i).css('border-color', 'green');
                    }
                }
            }
        }

        function checkNumInQty(id) {
            var qty = $('#in_qty_' + id).val();
            if (!isNaN(parseFloat(qty)) && isFinite(qty)) {
                $('#in_qty_' + id).css('border-color', 'black');
            } else {
                $('#in_qty_' + id).val("");
                $('#in_qty_' + id).css('border-color', 'red');

            }
        }

        function checkNumOutQty(id) {
            var qty = $('#out_qty_' + id).val();
            if (!isNaN(parseFloat(qty)) && isFinite(qty)) {
                $('#out_qty_' + id).css('border-color', 'black');
            } else {
                $('#out_qty_' + id).val("");
                $('#out_qty_' + id).css('border-color', 'red');
            }
        }

        function checkRuleType() {
            var rule_type = $('#rule_type').val();
            if (rule_type == 0) {
                alert("Please Select a Rule Type");
                $('#rule_name').val('');
                $('#rule_type').focus();
            }
        }
    </script>
@stop
