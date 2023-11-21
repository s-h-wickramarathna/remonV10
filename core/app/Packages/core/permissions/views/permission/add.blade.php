@extends('layouts.master') @section('title','Add Menu')
@section('css')
<style type="text/css">
	.panel.panel-bordered {
	    border: 1px solid #ccc;
	}

	.btn-primary {
	    color: white;
	    background-color:#CC1A6C ;
	    border-color: #CC1A6C;
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
    	<a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
  	</li>
  	<li>
    	<a href="javascript:;">Menu Management</a>
  	</li>
  	<li class="active">Add Menu</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Add Menu</strong>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          		{!!Form::token()!!}
          			<div class="form-group">
	            		<label class="col-sm-2 control-label required">Menu Label</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control @if($errors->has('label')) error @endif" name="label" placeholder="Menu Label" required value="{{Input::old('label')}}">
	            			@if($errors->has('label'))
	            				<label id="label-error" class="error" for="label">{{$errors->first('label')}}</label>
	            			@endif
	            		</div>
	                </div>
		          	<div class="form-group">
	            		<label class="col-sm-2 control-label required">Menu URL</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control @if($errors->has('menu_url')) error @endif" name="menu_url" placeholder="Ex: menu/list" value="{{Input::old('menu_url')}}" required>
	            			@if($errors->has('menu_url'))
	            				<label id="label-error" class="error" for="label">{{$errors->first('menu_url')}}</label>
	            			@endif
	            		</div>
	                </div>
          			<div class="form-group">
	            		<label class="col-sm-2 control-label">Menu Icon</label>
	            		<div class="col-sm-10">
	            			{!! Form::select('menu_icon', $fonts, Input::old('menu_icon'),['class'=>'chosen','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'Choose Icon']) !!}
	            		</div>
	                </div>
		          	<div class="form-group">
	            		<label class="col-sm-2 control-label required">Menu Parent</label>
	            		<div class="col-sm-10">
	            			@if($errors->has('parent_menu'))
	            				{!! Form::select('parent_menu',$menus, Input::old('parent_menu'),['class'=>'chosen error','style'=>'width:100%;','required','data-placeholder'=>'Choose Parent Menu']) !!}
	            				<label id="label-error" class="error" for="label">{{$errors->first('parent_menu')}}</label>
	            			@else
	            				{!! Form::select('parent_menu',$menus, Input::old('parent_menu'),['class'=>'chosen','style'=>'width:100%;','required','data-placeholder'=>'Choose Parent Menu']) !!}
	            			@endif
	            		</div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-2 control-label required">Permissions</label>
	            		<div class="col-sm-10">
	            			@if($errors->has('permissions[]'))
	            				{!! Form::select('permissions[]',$permissionArr, null,['class'=>'error', 'multiple','id'=>'permissions','style'=>'width:100%;','required']) !!}
	            				<label id="label-error" class="error" for="label">{{$errors->first('permissions[]')}}</label>
	            			@else
	            				{!! Form::select('permissions[]',$permissionArr, Input::old('permissions[]'),['multiple','id'=>'permissions','style'=>'width:100%;','required']) !!}
	            			@endif
	            		</div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-2 control-label">Menu Order</label>
	            		<div class="col-sm-10">
	            			<?php $menus->pull('0') ?>
	            			@if($errors->has('menu_order'))
	            				{!! Form::select('menu_order',$menus, Input::old('menu_order'),['class'=>'chosen error','style'=>'width:100%;','required','data-placeholder'=>'Set After']) !!}
	            				<label id="label-error" class="error" for="label">{{$errors->first('menu_order')}}</label>
	            			@else
	            				{!! Form::select('menu_order',$menus, Input::old('menu_order'),['class'=>'chosen','style'=>'width:100%;','required','data-placeholder'=>'Set After']) !!}
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
		$('#permissions').multiSelect();
	});
</script>
@stop
