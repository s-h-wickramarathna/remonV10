@extends('layouts.master') @section('title','Add Target')
@section('css')
    <link rel="stylesheet"
          href="{{asset('assets/vendor/bootstrap-daterangepicker/css/daterangepicker.css')}}">
    <link rel="stylesheet"
          href="{{asset('assets/vendor/bootstrap-calendar/components/bootstrap2/css/bootstrap-responsive.css')}}">
    <link rel="stylesheet"
          href="{{asset('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}">
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
            <a href="javascript:;"> Target Management</a>
        </li>
        <li class="active">Add Target</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Add Target</strong>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token()!!}
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 col-lg-offset-2">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="right">
                                            <label class="control-label required">Marketeer </label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                            {!! Form::select('marketeer', $marketeerList, Input::old('marketeer'),['class'=>'chosen','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'Select Marketeer']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="right">
                                            <label class="control-label required">Target Month</label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="month_picker_txt"
                                                       name="month_picker_txt" placeholder="Select a Target Month"
                                                       required onchange="setDate();"><span class="input-group-addon"><i
                                                            class="glyphicon glyphicon-th"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="right">
                                            <label class="control-label required">Value (Rs.)</label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                            <input type="text"
                                                   class="form-control @if($errors->has('value')) error @endif"
                                                   name="value" placeholder="Input value" required
                                                   value="{{Input::old('value')}}"
                                                   onkeydown="return validValue(this, event)">
                                            @if($errors->has('value'))
                                                <label id="label-error" class="error"
                                                       for="label">{{$errors->first('value')}}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-goup">
                                        <div class="row">
                                            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                                <div class="pull-right">
                                                    <button type="submit" class="btn btn-primary"><i
                                                                class="fa fa-floppy-o" style="width: 28px;"></i> Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="dateRange" id="dateRange"
                               placeholder="Select Date Range"/>
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


            $('#month_picker_txt').datepicker({
                format: 'yyyy-mm',
                minViewMode: "months",
                startDate: new Date()
            });
        });

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
