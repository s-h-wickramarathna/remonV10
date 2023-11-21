@extends('layouts.master') @section('title','Edit Type')
@section('css')
<style type="text/css">
	.chosen-container{
		font-family: 'FontAwesome', 'Open Sans',sans-serif;
	}
</style>
@stop
@section('content')
<ol class="breadcrumb">
	<li>
		<a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
	</li>
	<li>
		<a href="javascript:;">Employee Management</a>
	</li>
	<li>
		<a href="{{url('employee/type/list')}}">Employee Type List</a>
	</li>
	<li class="active">Edit Type</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
			<div class="panel-heading border">
				<strong>Edit Type</strong>
			</div>
			<div class="panel-body">
				<form role="form" class="form-horizontal form-validation" method="post">
					{!!Form::token()!!}
					<div class="form-group">
						<label class="col-sm-3 control-label required">Type</label>
						<div class="col-sm-6">
							<input type="text" class="form-control @if($errors->has('type')) error @endif" name="type" placeholder="Type" required value="{{$type->type}}">
							@if($errors->has('type'))
								<label id="label-error" class="error" for="label">{{$errors->first('type')}}</label>
							@endif
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label required">Parent</label>
						<div class="col-sm-6">
							{!! Form::select('parent', $parentList,$type->parent,['class'=>'chosen','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'']) !!}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-6 col-md-offset-3">
							<div class="pull-left">
								<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
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
<script src="{{asset('assets/sammy_new/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.form-validation').validate();
		$('#permissions').multiSelect();
	});
</script>
@stop
