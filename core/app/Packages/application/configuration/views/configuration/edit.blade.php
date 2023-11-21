@extends('layouts.master') @section('title','Edit Menu')
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
    	<a href="javascript:;">Batch Price Management</a>
  	</li>
  	<li>
    	<a href="{{url('employee/list')}}">Batch Price List</a>
  	</li>
  	<li class="active">Edit Batch Price</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Edit Batch Price</strong>
        		<a class="pull-right btn btn-danger" href="{{{url('/batchPrice/list')}}}"><i class="fa fa-arrow-left"></i> Back</a>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          			{!!Form::token()!!}
          			<div class="form-group">
						<label class="col-sm-3 control-label required">M.R.P.</label>
						<div class="col-sm-3">
							<input type="text" style="text-align: center;" class="form-control @if($errors->has('fName')) error @endif" name="mrp" placeholder="M.R.P." required value="{{$price}}">
							@if($errors->has('fName'))
								<label id="label-error" class="error" for="label">{{$errors->first('fName')}}</label>
							@endif
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
