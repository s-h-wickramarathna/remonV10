@extends('layouts.master') @section('title','Edit Account')
@section('css')
    <link rel="stylesheet"
          href="{{asset('assets/vendor/bootstrap-daterangepicker/css/daterangepicker.css')}}">
    <link rel="stylesheet"
          href="{{asset('assets/vendor/bootstrap-calendar/components/bootstrap2/css/bootstrap-responsive.css')}}">
    <style type="text/css">
        .panel.panel-bordered {
            border: 1px solid #ccc;
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
        <li>
            <a href="{{url('account/list')}}">List Account</a>
        </li>
        <li class="active">Edit Account</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Edit Account</strong>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token()!!}
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label required">Bank</label>
                                <div class="col-sm-6">
                                    {!! Form::select('bank', $banks, $account->bank,['class'=>'chosen','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'Select Bank']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Branch</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @if($errors->has('branch')) error @endif"
                                           id="branch"
                                           name="branch" placeholder="Input branch" value="{{$account->branch}}">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label required">Account No</label>
                                <div class="col-sm-6">
                                    <input type="text"
                                           class="form-control @if($errors->has('acc_no')) error @endif"
                                           name="acc_no" placeholder="Input Account No" required
                                           value="{{$account->account_no}}">
                                    @if($errors->has('acc_no'))
                                        <label id="label-error" class="error"
                                               for="label">{{$errors->first('acc_no')}}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-goup">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-primary"><i
                                                        class="fa fa-floppy-o" style="width: 28px;"></i> Save
                                            </button>
                                        </div>
                                    </div>
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
    <script src="{{asset('assets/vendor/jquery-mask/jquery.mask.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
//			$('input[name="dateRange"]').daterangepicker({
//				locale: {
//					format: 'YYYY-MM'
//				},
//				minViewMode: "months",
//				startDate: new Date()
//
//			});
            $('#dateRange').datepicker({
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
    </script>
@stop
