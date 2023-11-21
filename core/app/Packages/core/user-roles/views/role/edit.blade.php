@extends('layouts.master') @section('title','Edit User Role')
@section('css')
<style type="text/css">
	.panel.panel-bordered {
	    border: 1px solid #ccc;
	}

	.btn-primary {
	    color: white;
	    background-color: #005C99;
	    border-color: #005C99;
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
  	<li>
    	<a href="{{{url('user/role/list')}}}">User Roles List</a>
  	</li>
  	<li class="active">Edit User Role</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
      			<div style="padding-bottom: 6px;padding-top: 6px; display: inline-block;">
      				<strong>Edit Role</strong>
      			</div>
      			<a class="pull-right btn btn-danger"><i class="fa fa-arrow-left"></i> Back</a>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          		{!!Form::token()!!}
          			<div class="form-group">
	            		<label class="col-sm-2 control-label required">Role Name</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control @if($errors->has('name')) error @endif" name="name" placeholder="Role Name" required value="@if($role) {{$role->name}} @endif">
	            			@if($errors->has('label'))
	            				<label id="label-error" class="error" for="label">{{$errors->first('name')}}</label>
	            			@endif
	            		</div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-2 control-label required">Permissions</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control @if($errors->has('permissions')) error @endif" name="permissions" placeholder="Permissions" required value="@if($role){{implode(',', array_keys(json_decode($role->permissions, true)))}}@endif">
	            			@if($errors->has('permissions'))
	            				<label id="label-error" class="error" for="label">{{$errors->first('permissions')}}</label>
	            			@endif
	            		</div>
	                </div>
	                <div class="pull-right">
	                	<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
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
