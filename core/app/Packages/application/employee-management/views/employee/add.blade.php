@extends('layouts.master') @section('title','Add Employee')
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

        .chosen-container {
            font-family: 'FontAwesome', 'Open Sans', sans-serif;
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
        <li class="active">Add Employee</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered refresh">
                <div class="panel-heading border">
                    <strong>Add Employee</strong>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post" onsubmit="return check()">
                        {!!Form::token()!!}
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Employee Type</label>

                            <div class="col-sm-6">
                                <select name="empType" class="chosen" style="width:100%;font-family:'FontAwesome', 'Open Sans', sans-serif">
                                    <option value="0" selected="selected">-- select employee type --</option>
                                    @foreach($type as $typeObj)
                                        <option value="{{$typeObj->id}}" {{(Input::old("empType") == $typeObj->id ? "selected":"")}}>{{$typeObj->type}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('empType'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('empType')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Name</label>

                            <div class="col-sm-3">
                                <input type="text" class="form-control @if($errors->has('fName')) error @endif"
                                       name="fName" placeholder="First Name" required value="{{Input::old('fName')}}">
                                @if($errors->has('fName'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('fName')}}</label>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control @if($errors->has('lName')) error @endif"
                                       name="lName" placeholder="Last Name" value="{{Input::old('lName')}}" required>
                                @if($errors->has('lName'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('lName')}}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-3 control-label">Address </label>
                            <div class="col-sm-6">
                              <input type="text" class="form-control @if($errors->has('address')) error @endif" id="address"
                                    name="address" placeholder="Address" value="{{Input::old('address')}}">
                                      @if($errors->has('address'))
                                        <label id="label-error" class="error" for="label">{{$errors->first('address')}}</label>
                                      @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label ">Email</label>

                            <div class="col-sm-6">
                                <input type="text" class="form-control @if($errors->has('email')) error @endif"
                                       name="email" placeholder="Email" value="{{Input::old('email')}}" >
                                @if($errors->has('email'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('email')}}</label>
                                @endif
                            </div>
                        </div>

                           <div class="form-group">
                                <label class="col-sm-3 control-label ">Mobile </label>
                                     <div class="col-sm-6">
                                           <input type="text" class="form-control @if($errors->has('mobile')) error @endif"
                                                               name="mobile" placeholder="Mobile" value="{{Input::old('mobile')}}" >
                                           @if($errors->has('mobile'))
                                                 <label id="label-error" class="error"
                                                    for="label">{{$errors->first('mobile')}}</label>
                                           @endif
                               </div>
                            </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label ">Land No </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @if($errors->has('land')) error @endif"
                                       name="land" placeholder="Land"  value="{{Input::old('land')}}" >
                                @if($errors->has('land'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('land')}}</label>
                                @endif
                            </div>
                        </div>



                        <div class="togDis" hidden>
                            <div class="form-group" hidden>
                                <label class="col-sm-3 control-label">Cheque Name </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @if($errors->has('cheque_name')) error @endif" id="cheque_name"
                                           name="cheque_name" placeholder="Cheque Name" value="{{Input::old('cheque_name')}}">
                                    @if($errors->has('cheque_name'))
                                        <label id="label-error" class="error" for="label">{{$errors->first('cheque_name')}}</label>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="togDis" hidden>
                            <div class="form-group" hidden>
                                <label class="col-sm-3 control-label">Business Name </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="business_name"
                                           name="business_name" placeholder="Business Name" value="{{Input::old('business_name')}}">
                                </div>
                            </div>
                            <div class="form-group" hidden>
                                <label class="col-sm-3 control-label">Short Code</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @if($errors->has('sCode')) error @endif" name="sCode" placeholder="Short Code" value="{{Input::old('sCode')}}">
                                    @if($errors->has('sCode'))
                                        <label id="label-error" class="error" for="label">{{$errors->first('sCode')}}</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tog" hidden>
                            <div class="form-group" hidden>
                                <label class="col-sm-3 control-label" >Short Code</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @if($errors->has('sCode')) error @endif" name="sCode" placeholder="Short Code" value="{{Input::old('sCode')}}">
                                    @if($errors->has('sCode'))
                                        <label id="label-error" class="error" for="label">{{$errors->first('sCode')}}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label required">Mobile User Name</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @if($errors->has('uName')) error @endif" name="uName" placeholder="Mobile User Name" value="{{Input::old('uName')}}" >
                                    @if($errors->has('uName'))
                                        <label id="label-error" class="error" for="label">{{$errors->first('uName')}}</label>
                                    @endif
                                    <p id="uName_error" style="display:none;"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label required">Mobile Password</label>
                                <div class="col-sm-3">
                                    <input type="password" class="form-control @if($errors->has('password')) error @endif" name="password" placeholder="Mobile Password" value="{{Input::old('password')}}" required>
                                    @if($errors->has('password'))
                                        <label id="label-error" class="error" for="label">{{$errors->first('password')}}</label>
                                    @endif
                                </div>
                                <div class="col-sm-3">
                                    <input type="password" class="form-control @if($errors->has('cPassword')) error @endif "  name="cPassword" placeholder="Confirm Password" value="{{Input::old('cPassword')}}" required>
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
                                           name="credit_limit" placeholder="Credit Limit" value="{{Input::old('credit_limit')}}">
                                    @if($errors->has('cheque_name'))
                                        <label id="label-error" class="error" for="label">{{$errors->first('credit_limit')}}</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-offset-8">
                                <div class="pull-right">
                                    <button type="submit" id="submit" class="btn btn-primary"><i class="fa fa-floppy-o" style="width:15px;"></i> Save
                                    </button>
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

            password.val('');
            cPassword.val('');
            changeForm();
            //changeParent();
            type.change(function (e) {
                changeForm();
              //  changeParent();
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
                }
            });


           // var password = $('input[name="password"]');
            //var cPassword = $('input[name="cPassword"]');
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
                $('#address').prop('required', false);
            } else {
                $('.togDis').hide();
                $('#address').prop('required', false);
            }
        }

        //get the parents by chosen location type
        /*function changeParent(i) {
            $('.panel').addClass('panel-refreshing');
            var type = $('select[name="empType"]');
            var parent = $('select[name="parent"]');
            if(type.val())
            {
                $('#parent').css("display","none");
            }
            $.get(

                    {'type': type.val()},
                    function (data) {
                        var str = [];
                        var parentID =

                        str.push('<option value="0" selected>-- select parent --</option>');
                        for (var i = 0; i < data.length; i++) {

                            if(data[i][0]==parentID){
                                console.log(parentID);
                                str.push('<option value="' + data[i][0] + '" selected>' + data[i][1] + '</option>');
                            }else{
                                $('#parent').css("display","block");
                                str.push('<option value="' + data[i][0] + '">' + data[i][1] + '</option>');
                            }

                        }
                        parent.html(str.join());
                        parent.trigger("chosen:updated");
                        $('.panel').removeClass('panel-refreshing');
                    });
            var val = type.val();
        }*/

        function changeMsg(){
            var password = $('input[name="password"]');
            var cPassword = $('input[name="cPassword"]');

            if(isMatch(password.val(),cPassword.val())){
                cPassword.parent().addClass( "has-success has-feedback" ).append('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span><span id="inputSuccess2Status" class="sr-only">(success)</span>');
                $('#submit').prop('disabled', false);
                cPassword.parent().removeClass( "has-error has-feedback" );

            }else{
                $('#submit').prop('disabled', true);
                cPassword.parent().addClass( "has-error has-feedback" ).append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(error)</span>');
            }
        }

        function check()
        {
            var type = $('select[name="empType"] :selected');
            if(type.text() == "Rep" || type.text() == "rep")
            {
                var uName = $('input[name="uName"]').val();
                if(uName == 0)
                {
                    $('input[name="uName"]').addClass("error").focus();
                    $('#uName_error').css("display", "block").html("<span style='color:#d96557;'>The user name field required.</>");
                    return false;
                    $('#submit').prop('disabled', true);
                }
                $('#submit').prop('disabled', false);

                return true;

            }
        }

        function isMatch(val1, val2){
            return val1 === val2;
        }

    </script>
@stop
