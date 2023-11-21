@extends('layouts.master') @section('title','Add Type')
@section('css')
<style type="text/css">
	.panel.panel-bordered {
	    border: 1px solid #ccc;
	}

	.btn-primary {
	    color: white;
	    background-color: #C51C6A;
	    border-color: #C51C6A;
	}

	.chosen-container{
		font-family: 'FontAwesome', 'Open Sans',sans-serif;
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
    	<a href="javascript:;">Employee Management</a>
  	</li>
	<li>
		<a href="javascript:;">Employee Type</a>
	</li>
  	<li class="active">Add Type</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Add Type</strong>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          		{!!Form::token()!!}
          			<div class="form-group{{ $errors->has('type')? ' has-error':'' }}">
	            		<label class="col-sm-3 control-label required">Type</label>
	            		<div class="col-sm-6">
	            			<input type="text" class="form-control" name="type" placeholder="Type" value="{{Input::old('type')}}">
	            			@if($errors->has('type'))
	            				<label id="label-error" class="error" for="label">{{$errors->first('type')}}</label>
	            			@endif
	            		</div>
	                </div>
					<div class="form-group{{ $errors->has('parent')? ' has-error':'' }}">
						<label class="col-sm-3 control-label required">Parent</label>
						<div class="col-sm-6">

							{!! Form::select('parent', $parentList, Input::old('parent'),['class'=>'chosen','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'']) !!}
							@if($errors->has('parent'))
								<div class="help-block">{{ $errors->first('parent') }}</div>
							@endif
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-1 col-md-offset-8">
							<div class="pull-right">
								<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" style="width:15px;"></i>Save</button>
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
	});
</script>
@stop
