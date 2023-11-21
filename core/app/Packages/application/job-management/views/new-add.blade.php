@extends('layouts.master') @section('title','Add Job')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('/assets/css/build.css')}}">
    <style type="text/css">

        .panel.panel-bordered {
            border: 1px solid #ccc;
        }

        .btn-primary {
            color: white;
            background-color: #CC1A6C;
            border-color: #CC1A6C;
        }

        .chosen-container {
            font-family: 'FontAwesome', 'Open Sans', sans-serif;
        }

        b, strong {
            font-weight: bold;
        }
    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Job Management</a>
        </li>
        <li class="active">Add Job</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">

                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post"
                          enctype="multipart/form-data">
                        {!!Form::token()!!}
                        <div class="panel panel-bordered">
                            <div class="panel-heading border">
                                <strong>Customer Details</strong>
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-8 col-xs-offset-1">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Date</label>
                                        <label class="col-sm-3 control-label"
                                               style="text-align: left">{{date('Y-m-d H:i:s')}}</label>
                                        <label class="col-sm-3 control-label required">Job No</label>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                       name="job_no" value="{{$job_no}}" readonly>
                                                <span class="input-group-addon"><i
                                                            class="fa fa-venus-mars"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-8 col-xs-offset-1">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label required">Customer Name </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input type="hidden" name="user" value="{{$user}}">
                                            <select name="customer_name" class="chosen" onchange="change_customer()"
                                                    style="width:100%;font-family:'FontAwesome', 'Open Sans', sans-serif">
                                                <option value="0" selected="selected">-- Select Customer --</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->id}}" {{(Input::old("customer_name") == $customer->id ? "selected":"")}}>{{$customer->f_name}} {{$customer->l_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label ">Address </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled
                                                       name="customer_address"
                                                       value="{{Input::old('customer_address')}}"
                                                ><span class="input-group-addon"><i
                                                            class="fa fa-globe"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Contact No </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled
                                                       name="customer_contact"
                                                       value="{{Input::old('customer_contact')}}"
                                                       required><span class="input-group-addon"><i
                                                            class="fa fa-phone-square"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Email </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled
                                                       name="customer_email" value="{{Input::old('customer_email')}}"
                                                       required><span class="input-group-addon"><i
                                                            class="fa fa-envelope-square"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Couple Name</label>
                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                       name="couple_name">
                                                <span class="input-group-addon"><i
                                                            class="fa fa-venus-mars"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label required">Album</label>
                                        <?php $index = 1;?>
                                        @foreach($album_types as $key => $album_type)
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 @if(($index%3) == 0) col-xs-offset-3 @endif">
                                                <input type="radio"
                                                       name="radio_type[]" value="{{$album_type->id}}">
                                                <label for="checkbox">
                                                    {{$album_type->name}}
                                                </label>
                                            </div>
                                            <?php $index += 1;?>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-bordered">
                            <div class="panel-heading border">
                                <strong>Job Specification</strong>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered bordered table-striped table-condensed"
                                       id="orderTable">
                                    <thead>
                                    <tr>
                                        <td>
                                            Album No
                                        </td>
                                        <td align="center">1</td>
                                        <td align="center">2</td>
                                        <td align="center">3</td>
                                        <td align="center">4</td>
                                        <td align="center">5</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            Album Size
                                        </td>
                                        <td align="center"><input type="text" class="form-control" name="size_1" style="text-align: left"></td>
                                        <td align="center"><input type="text" class="form-control" name="size_2" style="text-align: left"></td>
                                        <td align="center"><input type="text" class="form-control" name="size_3" style="text-align: left"></td>
                                        <td align="center"><input type="text" class="form-control" name="size_4" style="text-align: left"></td>
                                        <td align="center"><input type="text" class="form-control" name="size_5" style="text-align: left"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Pages
                                        </td>
                                        <td align="center"><input type="number" class="form-control" name="pages_1" style="text-align: left"></td>
                                        <td align="center"><input type="number" class="form-control" name="pages_2" style="text-align: left"></td>
                                        <td align="center"><input type="number" class="form-control" name="pages_3" style="text-align: left"></td>
                                        <td align="center"><input type="number" class="form-control" name="pages_4" style="text-align: left"></td>
                                        <td align="center"><input type="number" class="form-control" name="pages_5" style="text-align: left"></td>
                                    </tr>
                                    @foreach($job_data as $key => $data)
                                        @if($key > 1)
                                            <tr>
                                                <td>
                                                    {{$data->name}}
                                                </td>
                                                <td align="center">
                                                    <select name="data_1_{{$key}}" class="chosen"
                                                            style="width:100%;font-family:'FontAwesome', 'Open Sans', sans-serif">
                                                        @foreach($data->children as $child)
                                                            <option value="{{$child->id}}">{{$child->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td align="center">
                                                    <select name="data_2_{{$key}}" class="chosen"
                                                            style="width:100%;font-family:'FontAwesome', 'Open Sans', sans-serif">
                                                        @foreach($data->children as $child)
                                                            <option value="{{$child->id}}">{{$child->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td align="center">
                                                    <select name="data_3_{{$key}}" class="chosen"
                                                            style="width:100%;font-family:'FontAwesome', 'Open Sans', sans-serif">
                                                        @foreach($data->children as $child)
                                                            <option value="{{$child->id}}">{{$child->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td align="center">
                                                    <select name="data_4_{{$key}}" class="chosen"
                                                            style="width:100%;font-family:'FontAwesome', 'Open Sans', sans-serif">
                                                        @foreach($data->children as $child)
                                                            <option value="{{$child->id}}">{{$child->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td align="center">
                                                    <select name="data_5_{{$key}}" class="chosen"
                                                            style="width:100%;font-family:'FontAwesome', 'Open Sans', sans-serif">
                                                        @foreach($data->children as $child)
                                                            <option value="{{$child->id}}">{{$child->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td>
                                            Album Attachment
                                        </td>
                                        <td align="center"><input type="file" class="form-control" name="attachment_1" style="text-align: left"></td>
                                        <td align="center"><input type="file" class="form-control" name="attachment_2" style="text-align: left"></td>
                                        <td align="center"><input type="file" class="form-control" name="attachment_3" style="text-align: left"></td>
                                        <td align="center"><input type="file" class="form-control" name="attachment_4" style="text-align: left"></td>
                                        <td align="center"><input type="file" class="form-control" name="attachment_5" style="text-align: left"></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="col-xs-8 col-xs-offset-1">
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Delivery</label>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <input type="radio"
                                                   name="delivery" id="delivery" value="Showroom" checked>
                                            <label for="checkbox">
                                                Showroom
                                            </label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                            <input type="radio"
                                                   name="delivery" id="delivery" value="Courier">
                                            <label for="checkbox">
                                                Courier
                                            </label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                            <input type="radio"
                                                   name="delivery" id="delivery" value="By Rep">
                                            <label for="checkbox">
                                                By Rep
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Due Date</label>
                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                            <div class="input-group input-daterange">
                                                <input type="text" class="form-control"
                                                       name="due_date" required style="text-align: left">
                                                <span class="input-group-addon"><i
                                                            class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Job Attachment</label>
                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                            <div class="input-group">
                                                <input type="file" class="form-control"
                                                       name="attachment">
                                                <span class="input-group-addon"><i
                                                            class="fa fa-paperclip"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Remark</label>
                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                    <textarea class="form-control" placeholder="Type here..." id="remark"
                                              name="remark"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="col-md-12 btn btn-primary"><i
                                                class="fa fa-floppy-o"></i> Save
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
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-daterangepicker/js/daterangepicker.js')}}"></script>
    {{--<script src="{{asset('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>--}}
    <script src="{{asset('assets/vendor/jquery-mask/jquery.mask.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            /*$('input[name="dateRange"]').daterangepicker({
             locale: {
             format: 'YYYY-MM-DD'
             },
             startDate: new Date(),
             minDate: new Date()

             });*/

            $('.input-daterange').datepicker({
                format: "yyyy-mm-dd",
                daysOfWeekHighlighted: "0,6",
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true
            });

        });

        function change_customer() {
            $('.panel').addClass('panel-refreshing');
            $.get('getData/' + $('select[name="customer_name"]').val(), function (data) {
                if (data.customer.is_credit_limit_block == 1) {

                    $('input[name="customer_address"]').val(data.customer.address);
                    $('input[name="customer_contact"]').val(data.customer.mobile + ' / ' + data.customer.telephone);

                } else {
                    $('input[name="customer_address"]').val(data.customer.address);
                    $('input[name="customer_contact"]').val(data.customer.mobile + ' / ' + data.customer.telephone);
                    $('input[name="customer_email"]').val(data.customer.email);
                }
                $('.panel').removeClass('panel-refreshing');

            });
        }


        function validValue(feild, event) {
            var regex = /^\d+(?:\.\d{2})$/;
            if (feild.value.length == 0) {
                return false || (event.keyCode <= 105 && event.keyCode >= 96) || (event.keyCode >= 48 && event.keyCode <= 57);
            }
            if (regex.test(feild.value)) {
                return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40;
            }
            if (feild.value.length == 10) {
                return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40;
            }
            if ((!isInt(feild.value) && (!isFloat(feild.value)))) {
                return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || (event.keyCode <= 105 && event.keyCode >= 96) || (event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode == 110 || event.keyCode == 190;
            }
            return true;
        }

        function isInt(n) {
            return Number(n) === n && n % 1 === 0;
        }

        function isFloat(n) {
            return Number(n) === n && n % 1 !== 0;
        }

        function setDate() {
            var date = new Date();


            var year_month = $('#month_picker_txt').val();
            var day = year_month.split("-");
            var year = day[0];
            var month = day[1];

            var firstDay = new Date(year, month - 1, 1),

                first_month = '' + (firstDay.getMonth() + 1),
                first_day = '' + firstDay.getDate(),
                first_year = firstDay.getFullYear();

            if (first_month.length < 2) first_month = '0' + first_month;
            if (first_day.length < 2) first_day = '0' + first_day;

            var lastDay = new Date(year, month, 0),

                last_month = '' + (lastDay.getMonth() + 1),
                last_day = '' + lastDay.getDate(),
                last_year = lastDay.getFullYear();

            if (last_month.length < 2) last_month = '0' + last_month;
            if (last_day.length < 2) last_day = '0' + last_day;

            var from = [first_year, first_month, first_day].join('-');
            var to = [last_year, last_month, last_day].join('-');


            var dateRange = from + ' - ' + to;
            $('#dateRange').val(dateRange);
        }


    </script>
@stop
