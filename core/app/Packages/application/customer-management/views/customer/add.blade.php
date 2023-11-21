@extends('layouts.master') @section('title','Add Customer')
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
            <a href="javascript:;">Customer Management</a>
        </li>
        <li class="active">Add Customer</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Add Customer</strong>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token()!!}


                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Name</label>

                            <div class="col-sm-3">
                                <input type="text" class="form-control @if($errors->has('customer_fName')) error @endif"
                                       name="customer_fName" placeholder="First Name" required
                                       value="{{Input::old('customer_fName')}}">
                                @if($errors->has('customer_fName'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('First Name')}}</label>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control @if($errors->has('customer_lName')) error @endif"
                                       name="customer_lName" placeholder="Last Name"
                                       value="{{Input::old('customer_lName')}}" required>
                                @if($errors->has('customer_lName'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('customer_lName')}}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"> Address</label>
                            <div class="col-sm-6">
                                <input type="text"
                                       class="form-control @if($errors->has('customer_address')) error @endif"
                                       name="customer_address" placeholder="Customer Address"
                                       value="{{Input::old('customer_address')}}">
                                @if($errors->has('customer_address'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('customer_address')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('area')? ' has-error':'' }}">
                            <label class="col-sm-3 control-label required">Area</label>
                            <div class="col-sm-6">
                                <select id="area" name="area" class="chosen">
                                    <option value="0">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{$area->id}}">{{$area->name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('area'))
                                    <div class="help-block">{{$errors->first('area')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"> NIC</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @if($errors->has('nic')) error @endif"
                                       name="nic" placeholder="NIC"
                                       value="{{Input::old('nic')}}">
                                @if($errors->has('nic'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('nic')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Mobile</label>
                            <div class="col-sm-6">
                                <input type="text"
                                       class="form-control @if($errors->has('customer_mobile')) error @endif"
                                       name="customer_mobile" placeholder="Customer Mobile"
                                       value="{{Input::old('customer_mobile')}}">
                                @if($errors->has('customer_mobile'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('customer_mobile')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label ">Telephone</label>
                            <div class="col-sm-6">
                                <input type="text"
                                       class="form-control @if($errors->has('customer_telephone')) error @endif"
                                       name="customer_telephone" placeholder=" Telephone"
                                       value="{{Input::old('customer_telephone')}}">
                                @if($errors->has('customer_telephone'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('customer_telephone')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label ">E-mail</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @if($errors->has('customer_email')) error @endif"
                                       name="customer_email" placeholder=" E-mail"
                                       value="{{Input::old('customer_email')}}">
                                @if($errors->has('customer_email'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('customer_email')}}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Credit Limit</label>
                            <div class="col-sm-6">
                                <input type="text"
                                       class="form-control @if($errors->has('customer_credit_limit')) error @endif"
                                       name="customer_credit_limit" placeholder="Credit Limit"
                                       value="{{Input::old('customer_credit_limit')}}">
                                @if($errors->has('customer_credit_limit'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('customer_credit_limit')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Credit Period</label>
                            <div class="col-sm-6">
                                <input type="text"
                                       class="form-control @if($errors->has('customer_credit_period')) error @endif"
                                       name="customer_credit_period" placeholder="Credit Period Default 45 days"
                                       value="{{Input::old('customer_credit_period')}}">
                                @if($errors->has('customer_credit_period'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('customer_credit_period')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('marketeer')? ' has-error':'' }}">
                            <label class="col-sm-3 control-label required">Marketeer</label>
                            <div class="col-sm-6">
                                <select id="marketeer" name="marketeer" class="chosen">
                                    <option value="0">Select Marketeer</option>
                                    @foreach($marketeer as $mar)
                                        <option value="{{$mar->id}}">{{$mar->first_name .' '.$mar->last_name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('marketeer'))
                                    <div class="help-block">{{$errors->first('marketeer')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6 col-md-offset-3">
                                <div class="pull-left">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"
                                                                                     style="width: 28px;"></i> Save
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

@stop
