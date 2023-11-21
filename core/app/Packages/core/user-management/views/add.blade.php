@extends('layouts.master') @section('title','Add User')
@section('css')
    <link rel="stylesheet"
          href="{{asset('assets/vendor/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <style type="text/css">
        .panel.panel-bordered {
            border: 1px solid #ccc;
        }

        .btn-primary {
            color: white;
            background-color:#CC1A6C;
            border-color: #CC1A6C;
        }

        .chosen-container {
            font-family: 'FontAwesome', 'Open Sans', sans-serif;
        }
        b, strong {
    font-weight: bold;
}
.bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-warning, .bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-warning {
    background: #314608;
    color: #fff;
}

.bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-default, .bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-default {
    color: #fff;
    background: #314608;
}

.selectbox-error{
    border:1px solid red;
}

</style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">User Management</a>
        </li>
        <li>
            <a href="javascript:;">Users</a>
        </li>
        <li class="active">Add User</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Add User</strong>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token()!!}

                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Employee</label>
                            <div class="col-sm-8">
                                <select name="employee" id="employee" class="form-control chosen error">
                                    <option value="">-- employee --</option>
                                    @foreach($empList as $emp)
                                        <option value="{{ $emp->id }}" {{ Request::old('employee') == $emp->id ? 'selected':'' }}>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('employee'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('employee')}}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required">User Name</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control @if($errors->has('uName')) error @endif"
                                       name="uName" placeholder="User Name" value="{{Input::old('uName')}}">
                                @if($errors->has('uName'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('uName')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Password</label>

                            <div class="col-sm-8">
                                <input type="password" class="form-control @if($errors->has('password')) error @endif"
                                       name="password" placeholder="Password"
                                       >
                                @if($errors->has('password'))
                                    <label id="label-error" class="error"  for="label">{{$errors->first('password')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Confirm password</label>

                            <div class="col-sm-8">
                                <input type="password" class="form-control @if($errors->has('password_confirmation')) error @endif"
                                       name="password_confirmation" placeholder="Confrim password."
                                       >
                                @if($errors->has('password_confirmation'))
                                    <label id="label-error" class="error"  for="label">{{$errors->first('password_confirmation')}}</label>
                                @endif

                            </div>

                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Role</label>
                            <div class="col-sm-8">
                                <select class="form-control chosen" name="role">
                                    <option value="">-- role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('role'))
                                    <label id="label-error" class="error"  for="label">{{$errors->first('role')}}</label>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-sm-2 col-md-offset-8" style="padding-right:0px;">
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
    <script src="{{asset('assets/vendor/bootstrap-switch-master/dist/js/bootstrap-switch.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            //$('.form-validation').validate();

            /*$('input[name="permissions"]').tagsinput({
                typeahead: {
                    source: function(query) {
                        return $.get('{{url('permission/api/list')}}');
                    }
                },
                freeInput: true
            });*/
        });
    </script>
@stop
