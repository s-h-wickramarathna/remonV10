@extends('layouts.master') @section('title','Edit Employee')
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
    	<a href="{{url('employee/list')}}">Employee List</a>
  	</li>
  	<li class="active">Edit Employee</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Edit Employee</strong>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          			{!!Form::token()!!}
					<div class="form-group">
						<label class="col-sm-3 control-label required">Employee Type</label>
						<div class="col-sm-6">
							{!! Form::select('empType', $type, $employee->employee_type_id,['class'=>'chosen','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'']) !!}
						</div>
					</div>
          			<div class="form-group">
						<label class="col-sm-3 control-label required">Name</label>
						<div class="col-sm-3">
							<input type="text" class="form-control @if($errors->has('fName')) error @endif" name="fName" placeholder="First Name" required value="{{$employee->first_name}}">
							@if($errors->has('fName'))
								<label id="label-error" class="error" for="label">{{$errors->first('fName')}}</label>
							@endif
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control @if($errors->has('lName')) error @endif" name="lName" placeholder="Last Name" value="{{$employee->last_name}}" required>
							@if($errors->has('lName'))
								<label id="label-error" class="error" for="label">{{$errors->first('lName')}}</label>
							@endif
						</div>
					</div>
					<div class="form-group">
                          <label class="col-sm-3 control-label">Address </label>
                                 <div class="col-sm-6">
                                       <input type="text" class="form-control @if($errors->has('address')) error @endif"
                                            name="address" placeholder="Address" value="{{$employee->address}}">
                                                 @if($errors->has('address'))
                                                                <label id="label-error" class="error"
                                                                    for="label">{{$errors->first('address')}}</label>
                                                          @endif
                                       </div>
                    </div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Email</label>
						<div class="col-sm-6">
							<input type="text" class="form-control @if($errors->has('email')) error @endif" name="email" placeholder="Email" value="{{$employee->email}}">
							@if($errors->has('email'))
								<label id="label-error" class="error" for="label">{{$errors->first('email')}}</label>
							@endif
						</div>
					</div>

					<div class="form-group">
                           <label class="col-sm-3 control-label">Mobile </label>
                             <div class="col-sm-6">
                               <input type="text" class="form-control @if($errors->has('mobile')) error @endif"
                                     name="mobile" placeholder="Mobile"  value="{{$employee->mobile}}"  >
                                      @if($errors->has('mobile'))
                                            <label id="label-error" class="error"
                                                for="label">{{$errors->first('mobile')}}</label>
                                      @endif
                            </div>
                    </div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Land </label>
						<div class="col-sm-6">
							<input type="text" class="form-control @if($errors->has('land')) error @endif"
								   name="land" placeholder="Land"  value="{{$employee->land}}"  >
							@if($errors->has('land'))
								<label id="label-error" class="error"
									   for="label">{{$errors->first('land')}}</label>
							@endif
						</div>
					</div>

					<div class="togDis" hidden>
						<div class="form-group">
							<label class="col-sm-3 control-label required">Business Name </label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="business_name"
									   name="business_name" placeholder="Business Name" value="{{$employee->business_name}}" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label required">Short Code</label>
							<div class="col-sm-6">
								<input type="text" class="form-control @if($errors->has('dis_sCode')) error @endif" name="dis_sCode" placeholder="Short Code"  value="{{$employee->short_code}}" required>
								@if($errors->has('dis_sCode'))
									<label id="label-error" class="error" for="label">{{$errors->first('dis_sCode')}}</label>
								@endif
							</div>
						</div>
					</div>
					<div class="tog" hidden>
						<div class="form-group">
							<label class="col-sm-3 control-label required">Short Code</label>
							<div class="col-sm-6">
								<input type="text" class="form-control @if($errors->has('sCode')) error @endif" name="sCode" placeholder="Short Code"  value="{{$employee->short_code}}" required>
								@if($errors->has('sCode'))
									<label id="label-error" class="error" for="label">{{$errors->first('sCode')}}</label>
								@endif
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label ">Mobile User Name</label>
							<div class="col-sm-6">
								<input type="text" class="form-control @if($errors->has('uName')) error @endif" name="uName" placeholder="Mobile User Name" value="{{$employee->mobile_user_name}}" required>
								<input type="hidden"  name="uID" value="{{$employee->repId}}">
								@if($errors->has('uName'))
									<label id="label-error" class="error" for="label">{{$errors->first('uName')}}</label>
								@endif
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label ">Old Password</label>
							<div class="col-sm-6">
								<input type="password" class="form-control @if($errors->has('oPassword')) error @endif" name="oPassword" placeholder="Old Password" value="" >
								@if($errors->has('oPassword'))
									<label id="label-error" class="error" for="label">{{$errors->first('oPassword')}}</label>
								@endif
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">New Password</label>
							<div class="col-sm-3">
								<input type="password" class="form-control @if($errors->has('password')) error @endif" name="password" placeholder="Mobile Password" value="{{Input::old('password')}}" >
								@if($errors->has('password'))
									<label id="label-error" class="error" for="label">{{$errors->first('password')}}</label>
								@endif
							</div>
							<div class="col-sm-3">
								<input type="password" class="form-control @if($errors->has('cPassword')) error @endif "  name="cPassword" placeholder="Confirm Password" value="{{Input::old('cPassword')}}" >
								@if($errors->has('cPassword'))
									<label id="label-error" class="error" for="label">{{$errors->first('cPassword')}}</label>
								@endif
							</div>
						</div>
					</div>
					<div class="togMarketeer" hidden>
						<div class="form-group">
							<label class="col-sm-3 control-label">Credit Limit </label>
							<div class="col-sm-6">
								<input type="text" class="form-control @if($errors->has('credit_limit')) error @endif" id="credit_limit"
									   name="credit_limit" placeholder="Credit Limit" value="{{$employee->credit_limit}}">
								@if($errors->has('cheque_name'))
									<label id="label-error" class="error" for="label">{{$errors->first('credit_limit')}}</label>
								@endif
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-6 col-md-offset-3">
							<div class="pull-left">
								<button type="submit" id="save" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
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
<script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('.form-validation').validate();
		var type = $('select[name="empType"]');
		var password = $('input[name="password"]');
		var cPassword = $('input[name="cPassword"]');
		var oPassword = $('input[name="oPassword"]');
		password.val('');
		cPassword.val('');
		changeForm();
		//changeParent();
		type.change(function (e) {
			changeForm();
			//changeParent();
		});

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

	function changeForm(){
		var type = $('select[name="empType"]');
		console.log(type.val());
		if ($('select[name="empType"] option[value=' + type.val() + ']').text() === 'Rep') {
			$('.tog').show();
		} else {
			$('.tog').hide();
		}

        if ($('select[name="empType"]').val()==2) {
            $('.togMarketeer').show();
        } else {
            $('.togMarketeer').hide();
        }

		if ($('select[name="empType"]').val()==4) {
			$('.togDis').show();
			$('#address').prop('required', true);
		} else {
			$('.togDis').hide();
			$('#address').prop('required', false);
		}
	}

	//get the parents by chosen location type
	function changeParent() {
		var type = $('select[name="empType"]');
		var parent = $('select[name="parent"]');
		$.get(
				'{{url("employee/getParent")}}',
				{'type': type.val()},
				function (data) {
					var str = [];
					for (var i = 0; i < data.length; i++) {
						str.push('<option value="' + data[i][0] + '">' + data[i][1] + '</option>');
					}
					parent.html(str.join());
					parent.trigger("chosen:updated");
				});
		var val = type.val();
	}

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
