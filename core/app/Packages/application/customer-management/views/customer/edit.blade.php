@extends('layouts.master') @section('title','Edit Customer')
@section('css')
    <style type="text/css">
        .chosen-container {
            font-family: 'FontAwesome', 'Open Sans', sans-serif;
        }
    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Customers Management</a>
        </li>
        <li>
            <a href="{{url('customer/list')}}">Customers List</a>
        </li>
        <li class="active">Edit Customers</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Edit Vehicle</strong>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token()!!}
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Name</label>

                            <div class="col-sm-3">
                                <input type="text" class="form-control @if($errors->has('customer_fName')) error @endif"
                                       name="customer_fName" placeholder="First Name" required
                                       value="{{$customer->f_name}}">
                                @if($errors->has('customer_fName'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('First Name')}}</label>
                                @endif
                            </div>

                            <div class="col-sm-3">
                                <input type="text" class="form-control @if($errors->has('customer_lName')) error @endif"
                                       name="customer_lName" placeholder="Last Name" value="{{$customer->l_name}}"
                                       required>
                                @if($errors->has('customer_lName'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('customer_lName')}}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label required"> Address</label>
                            <div class="col-sm-6">
                                <input type="text"
                                       class="form-control @if($errors->has('customer_address')) error @endif"
                                       name="customer_address" placeholder="Customer Address"
                                       value="{{$customer->address}}">
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
                                        <option value="{{$area->id}}" @if($customer->area == $area->id) selected
                                                @endif>{{$area->name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('area'))
                                    <div class="help-block">{{$errors->first('area')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required"> NIC</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @if($errors->has('nic')) error @endif"
                                       name="nic" placeholder="NIC"
                                       value="{{$customer->nic}}">
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
                                       value="{{$customer->mobile}}">
                                @if($errors->has('customer_mobile'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('customer_mobile')}}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label  ">Telephone</label>
                            <div class="col-sm-6">
                                <input type="text"
                                       class="form-control @if($errors->has('customer_telephone')) error @endif"
                                       name="customer_telephone" placeholder=" Telephone"
                                       value="{{$customer->telephone}}">
                                @if($errors->has('customer_telephone'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('customer_telephone')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label  ">E-mail</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @if($errors->has('customer_email')) error @endif"
                                       name="customer_email" placeholder=" E-mail"
                                       value="{{$customer->email}}">
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
                                       value="{{$customer->credit_limit}}">
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
                                       value="{{$customer->credit_period}}">
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
                                        <option @if($customer->marketeer_id == $mar->id) selected
                                                @endif value="{{$mar->id}}">{{$mar->first_name .' '.$mar->last_name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('marketeer'))
                                    <div class="help-block">{{$errors->first('marketeer')}}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label required">User Name</label>

                            <div class="col-sm-6">
                                <input type="text" class="form-control @if($errors->has('uName')) error @endif"
                                       name="uName" placeholder="User Name" value="{{$customer->user ? $customer->user->username : ''}}">
                                @if($errors->has('uName'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('uName')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Password</label>

                            <div class="col-sm-6">
                                <input type="password" class="form-control @if($errors->has('password')) error @endif"
                                       name="password" placeholder="Password">
                                @if($errors->has('password'))
                                    <label id="label-error" class="error"  for="label">{{$errors->first('password')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Confirm password</label>

                            <div class="col-sm-6">
                                <input type="password" class="form-control @if($errors->has('password_confirmation')) error @endif"
                                       name="password_confirmation" placeholder="Confirm password.">
                                @if($errors->has('password_confirmation'))
                                    <label id="label-error" class="error"  for="label">{{$errors->first('password_confirmation')}}</label>
                                @endif

                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-sm-6 col-md-offset-3">
                                <div class="pull-left">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
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
            var select, chosen;

            // cache the select element as we'll be using it a few times
            select = $(".chosen-select");

            // init the chosen plugin
            select.chosen({no_results_text: 'Press Enter to add new entry:'});

            // get the chosen object
            chosen = select.data('chosen');

            // Bind the keyup event to the search box input
            chosen.dropdown.find('input').on('keyup', function (e) {
                // if we hit Enter and the results list is empty (no matches) add the option
                if (e.which == 13 && chosen.dropdown.find('li.no-results').length > 0) {
                    var option = $("<option>").val(this.value).text(this.value);

                    // add the new option
                    select.prepend(option);
                    // automatically select it
                    select.find(option).prop('selected', true);
                    // trigger the update
                    select.trigger("chosen:updated");
                }
            });

            $('.form-validation').validate();
            var type = $('select[name="stewardType"]');
            type.change(function (e) {
                changeParent(this);
            });
        });

        //get the parents by chosen location type
        function changeParent() {
            var steward = $('select[name="steward"]');
            var type = $('select[name="stewardType"]');
            $.get(
                '{{url("vehicle/getSteward")}}',
                {'type': type.val()},
                function (data) {
                    var str = [];
                    for (var i = 0; i < data.length; i++) {
                        str.push('<option value="' + data[i][0] + '">' + data[i][1] + '</option>');
                    }
                    steward.html(str.join());
                    steward.trigger("chosen:updated");
                });
        }
    </script>
@stop
