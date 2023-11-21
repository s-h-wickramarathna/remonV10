@extends('layouts.master') @section('title','Edit User')
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
    	<a href="javascript:;">User Management</a>
  	</li>
  	<li class="active">Edit User</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Edit User</strong>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          			{!!Form::token()!!}
					<div class="form-group">
						<label class="col-sm-3 control-label required">Employee</label>

						<div class="col-sm-6">
							@if($errors->has('employee'))
								{!! Form::select('employee',$empList, $userC->employee_id,['class'=>'chosen error','style'=>'width:100%;','required','disabled','data-placeholder'=>'Choose Employee']) !!}

								<label id="label-error" class="error" for="label">{{$errors->first('employee')}}</label>
							@else
								{!! Form::select('employee',$empList, $userC->employee_id,['class'=>'chosen','style'=>'width:100%;','required','disabled','data-placeholder'=>'Choose Employee']) !!}
							@endif
							<input type="hidden" name="emp" value="{{$userC->employee_id}}">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label required">User Name</label>

						<div class="col-sm-6">
							<input type="text" class="form-control @if($errors->has('uName')) error @endif"
								   name="uName" placeholder="User Name" required value="{{$userC->username}}">
							@if($errors->has('uName'))
								<label id="label-error" class="error"
									   for="label">{{$errors->first('uName')}}</label>
							@endif
						</div>
					</div>
					@if($login_user->employee_id != 1 && $login_user->roles[0]->id != 3 )
					<div class="form-group">
						<label class="col-sm-3 control-label ">Old Password</label>
						<div class="col-sm-6">
							<input type="password" class="form-control @if($errors->has('oPassword')) error @endif" name="oPassword" placeholder="Old Password" value="" >
							@if($errors->has('oPassword'))
								<label id="label-error" class="error" for="label">{{$errors->first('oPassword')}}</label>
							@endif
						</div>
					</div>
					@endif
					<div class="form-group">
						<label class="col-sm-3 control-label">New Password</label>
						<div class="col-sm-3">
							<input type="password" class="form-control @if($errors->has('password')) error @endif" name="password" placeholder="New Password" value="">
							@if($errors->has('password'))
								<label id="label-error" class="error" for="label">{{$errors->first('password')}}</label>
							@endif
						</div>
						<div class="col-sm-3">
							<input type="password" class="form-control @if($errors->has('cPassword')) error @endif "  name="cPassword" placeholder="Confirm Password" >
							@if($errors->has('cPassword'))
								<label id="label-error" class="error" for="label">{{$errors->first('cPassword')}}</label>
							@endif
						</div>
					</div>
					<div class="form-group">

						@if($login_user->employee_id != 1 && $login_user->roles[0]->id != 1 )
							<label class="col-sm-3 control-label required">Role</label>
							<div class="col-sm-6">
								<select class="form-control chosen" disabled name="role">
									@foreach($roles as $role)
										<option value="{{$role->id}}" <?php echo $role->id == $userC->role->role_id ? 'selected':'' ?> >{{$role->name}}</option>
									@endforeach
								</select>
							</div>
						@else
							<label class="col-sm-3 control-label required">Role</label>
							<div class="col-sm-6">
								<select class="form-control chosen" name="role">
									@foreach($roles as $role)
										<option value="{{$role->id}}" <?php echo $role->id == $userC->role->role_id ? 'selected':'' ?> >{{$role->name}}</option>
									@endforeach
								</select>
							</div>
						@endif
					</div>
					<div class="col-sm-3 col-md-offset-6" style="padding-right:0px;">
						<div class="pull-right">
							<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" style="width:23px;"></i> Save
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
		var password = $('input[name="password"]');
		var cPassword = $('input[name="cPassword"]');
		var oPassword = $('input[name="oPassword"]');

		cPassword.keyup(function(e){
			cPassword.parent().removeClass("has-error has-feedback").removeClass("has-success has-feedback");
			$('span.glyphicon').remove();
			$('sr-only').remove();
			if(cPassword.val().length > 0 && password.val().length > 0) {
				changeMsg();
			}
		});

		password.keyup(function(e){
			cPassword.parent().removeClass("has-error has-feedback").removeClass("has-success has-feedback");
			$('span.glyphicon').remove();
			$('sr-only').remove();
			if(cPassword.val().length > 0 && password.val().length > 0) {
				changeMsg();
			}else if(password.val().length == 0){
				$('#save').prop('disabled', false);
			}else if(cPassword.val().length == 0 && password.val().length > 0){
				$('#save').prop('disabled', true);
			}
		});

	});

	function changeMsg(){
		var password = $('input[name="password"]');
		var cPassword = $('input[name="cPassword"]');

		if(isMatch(password.val(),cPassword.val())){
			cPassword.parent().addClass( "has-success has-feedback" ).append('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span><span id="inputSuccess2Status" class="sr-only">(success)</span>');
			$('#save').prop('disabled', false);
		}else{
			cPassword.parent().addClass( "has-error has-feedback" ).append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(error)</span>');
			$('#save').prop('disabled', true);
		}

	}

	function isMatch(val1, val2){
		return val1 === val2;
	}
</script>
@stop
