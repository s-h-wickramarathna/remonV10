
@extends('layouts.master') @section('title','Add User Role')
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
</style>
@stop
@section('content')
<ol class="breadcrumb">
	<li>
    	<a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
  	</li>
  	<li>
    	<a href="javascript:;">User Role Management</a>
  	</li>
  	<li class="active">Add User Role</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Add Role</strong>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          		{!!Form::token()!!}
          			<div class="form-group">
	            		<label class="col-sm-3 control-label required">Role Name</label>
	            		<div class="col-sm-6">
	            			<input type="text" class="form-control @if($errors->has('name')) error @endif" name="name" placeholder="Role Name" required value="{{Input::old('name')}}">
	            			@if($errors->has('label'))
	            				<label id="label-error" class="error" for="label">{{$errors->first('name')}}</label>
	            			@endif
	            		</div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-3 control-label required">Permissions</label>
	            		<div class="col-sm-6">
	            			<input type="text" class="form-control @if($errors->has('permissions')) error @endif" name="permissions" placeholder="Permissions" required value="{{Input::old('permissions')}}">
	            			@if($errors->has('permissions'))
	            				<label id="label-error" class="error" for="label">{{$errors->first('permissions')}}</label>
	            			@endif
	            		</div>
	                </div>
	               <div class="col-sm-1 col-md-offset-8" style="padding-left:0px;">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" style="width:19px;"></i> Save
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
	$(document).ready(function(){
		$('.form-validation').validate();

		$('input[name="permissions"]').tagsinput({
			typeahead: {
	        	source: function(query) {
	            	return $.get('{{{url('permission/api/list')}}}');
	            }
	        },
			freeInput: true
		});
	});
</script>
@stop
