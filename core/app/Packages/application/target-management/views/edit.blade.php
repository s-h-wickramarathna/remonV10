@extends('layouts.master') @section('title','Edit Target')
@section('css')
	<link rel="stylesheet"
		  href="{{asset('assets/vendor/bootstrap-daterangepicker/css/daterangepicker.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap-calendar/components/bootstrap2/css/bootstrap-responsive.css')}}">
	<style type="text/css">
		.panel.panel-bordered {
			border: 1px solid #ccc;
		}

		.btn-primary {
			color: white;
			background-color: #030C3C;
			border-color: #030C3C;
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
			<a href="javascript:;">Rep Target Management</a>
		</li>
		<li>
			<a href="{{url('repTarget/list')}}">List Target</a>
		</li>
		<li class="active">Edit Target</li>
	</ol>
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-bordered">
				<div class="panel-heading border">
					<strong>Edit Rep Target</strong>
				</div>
				<div class="panel-body">
					<form role="form" class="form-horizontal form-validation" method="post">
						{!!Form::token()!!}
						<div class="form-group">
							<label class="col-sm-3 control-label required">Rep</label>
							<div class="col-sm-6">
							    <input type="text" disabled class="form-control" name="rep" required value="{{$target->employee->first_name .' '.$target->employee->last_name}}">
							     <input type="hidden"   name="empID"   value="{{$target->employee->id}}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label required">Date Range</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <?php $arr = explode('-', $target->from);
                                    $newDate = $arr[0].'-'.$arr[1];?>
                                    <input type="text" class="form-control @if($errors->has('dateRange')) error @endif" id="dateRange"
                                        name="dateRange" placeholder="Select a Target Month"  value="{{$newDate}}" required><span class="input-group-addon">
                                            <i class="glyphicon glyphicon-th"></i></span>
                                        @if($errors->has('dateRange'))
                                            <label id="label-error" class="error" for="label">{{$errors->first('dateRange')}}</label>
                                        @endif
                                </div>
                            </div>
						</div>
					<div class="form-group">
							<label class="col-sm-3 control-label required">Value</label>
							<div class="col-sm-6">
								<input type="text" class="form-control @if($errors->has('value')) error @endif" name="value" placeholder="Input value" required value="{{$target->value}}" onkeydown="return validValue(this, event)">
								@if($errors->has('value'))
									<label id="label-error" class="error" for="label">{{$errors->first('value')}}</label>
								@endif
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6 col-md-offset-8">
								<div class="pull-left">
									<button type="submit" class="btn btn-primary" name="save"><i class="fa fa-floppy-o" style="width: 28px;"></i>Save</button>
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
			var date = new Date();
			if(Date.parse(date) > Date.parse('{{$target->to}}')) {
				$('button[name="save"]').prop('disabled', true);
				sweetAlert("Can't edit","End date is already passed.. " ,3);
			}
		});

		function validValue(feild, event){
			var regex  = /^\d+(?:\.\d{2})$/;
			if(feild.value.length == 0){
				return false || (event.keyCode <= 105 && event.keyCode >= 96) || (event.keyCode >= 48 && event.keyCode <= 57);
			}
			if(regex.test(feild.value)){
				return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40;
			}
			if(feild.value.length == 10){
				return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40;
			}
			if((!isInt(feild.value) && (!isFloat(feild.value)))){
				return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || (event.keyCode <= 105 && event.keyCode >= 96) || (event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode == 110 || event.keyCode == 190;
			}
			return true;
		}

		function isInt(n){
			return Number(n) === n && n % 1 === 0;
		}

		function isFloat(n){
			return Number(n) === n && n % 1 !== 0;
		}
	</script>
@stop
