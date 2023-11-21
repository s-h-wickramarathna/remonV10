@extends('layouts.master') @section('title','Menu List')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('/assets/mgonto-angular-wizard/angular-wizard.css')}}">
    <link rel="stylesheet"
          href="{{asset('/assets/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

    <style type="text/css">

        .info-wrap {
            margin-bottom: 10px !important;
            font-size: 12px;
        }

        .info-wrap .fa {
            font-size: 15px;
        }

        .personel-info {
            background: #fff;
            padding: 8px 0;
            text-align: left;
        }

        .personel-info span.icon {
            font-size: 16px;
            display: inline-block;
            width: 16px;
            height: 16px;
            line-height: 16px;
            margin-right: 16px;
            text-align: center;
            float: left;
        }

        .outlet-name h5 {
            font-weight: 600;
            font-size: 15px;
        }

        .outlet-name h6 {
            color: #999;
            font-size: 9px;
        }

        .link {
            color: #456eff;
            text-decoration: underline;
        }

        .box-widget {
            border: none;
            position: relative;
        }

        }

        .widget-user .widget-user-header {
            padding: 20px;
            border-top-right-radius: 3px;
            border-top-left-radius: 3px;
        }

        .widget-user .box-footer {
            padding-top: 5px;
        }

        .box-footer {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            border-top: 1px solid #f4f4f4;
            padding: 10px;
            background-color: #fff;
        }

        .widget-user .widget-user-username {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 25px;
            font-weight: 300;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
        }

        .widget-user .widget-user-header {
            padding: 10px;
            height: 85px;
            border-top-right-radius: 3px;
            border-top-left-radius: 3px;
        }

        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }

        .box .border-right {
            border-right: 1px solid #f4f4f4;
        }

        .description-block {
            display: block;
            margin: 5px 0;
            text-align: center;
        }

        .description-block h6 {
            margin: 0;
            padding: 0;
        }

        .description-block > .description-header {
            margin: 0;
            padding: 0;
            font-weight: 600;
            font-size: 14px;
        }

        .description-block > .description-text {
            text-transform: uppercase;
            font-size: 12px;
        }

        .about-box .fa {
            color: rgb(111, 108, 246);
        }

        .step-wrapper {
            padding: 20px;
        }

        .steps-indicator {
            margin-bottom: 20px;
        }

        .wizard-title {

        }

        .wizard-content {
            padding: 10px;
        }

        .wizard-buttons {

        }

        .wizard-buttons .pager {
            margin: 0;
            text-align: right;
        }

        .note {
            margin: 0;
            font-size: 11px;
        }

        .error {
            color: red;
            font-size: 11px;
        }

        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }

        .details-title {
            color: #3c3c3c;
            font-weight: 600;
            margin: 0;
            margin-top: 2px;
        }

        .details-text {
            margin-top: 5px;
        }

        .steps-indicator:before {
            background-color: #ddd;
            content: '';
            position: absolute;
            height: 5px;
        }

        .steps-indicator li {
            position: relative;
            float: left;
            margin: 0;
            padding: 0;
            padding-top: 10px;
            text-align: center;
            line-height: 32px;
        }

        .steps-indicator {
            right: 0;
            bottom: 0;
            left: 0;
            margin: 0;
            padding: 8px 0 0 0;
            height: 30px;
            list-style: none;
            margin-bottom: 30px;
        }

        .steps-indicator li a:before {

            font-family: 'FontAwesome';
            position: absolute;
            top: -12px;
            left: calc(50% - 16px);
            width: 30px;
            height: 30px;
            border-radius: 100%;
            background-color: #ddd;
            content: '';
            transition: 0.25s;
            color: #fff;
            content: '\f02b';
        }

        .steps-indicator li.current a:before {
            background-color: #ddd;
        }

        .steps-indicator li.editing a:before {
            background-color: #c1c1c1;
            content: '\f00d';
            /* margin: -2px; */
        }

        .steps-indicator li.done a:before {
            background-color: #9c9c9c;
            content: '\f00c';
            /* margin: -2px; */
        }

        .btn-save {
            border-radius: 50px;
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .btn-save:hover {
            border-radius: 50px;
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 5px;
            padding-bottom: 5px;
            color: white;
            background-color: #C51C6A !important;
            order-color: #C51C6A !important;
        }

    </style>


@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Payment Management</a>
        </li>
        <li>
            <a href="{{url('payment/new')}}">Customer List</a>
        </li>
        <li class="active">Customer details</li>
    </ol>
    <section ng-app="AppModule" ng-cloak>
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="panel panel-bordered">
                    <div class="panel-heading border">
                        <input type="hidden" name="route_type" id="route_type" value="{{$type}}">
                        <h4 class="widget-user-username"><a
                                    href="{{url('outlet/detail/')}}/{{$outlet->id}}">@if($outlet->f_name || $outlet->f_name!=""){{$outlet->f_name .' '.$outlet->l_name }}@endif</a>
                        </h4>
                        <h5 class="widget-user-desc">@if($outlet->short_code || $outlet->short_code!="")
                                (#{{$outlet->short_code}})@endif</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="details-title">Remaining</h5>
                                <h6 class="details-text" style="font-weight: 600">Rs.{{$outlet_invoice_details->remain}}
                                    <span class="badge"
                                          style="background-color: green">{{$outlet_invoice_details->count}}</span></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="details-title">Overpayments</h5>
                                <h6>Cash - Rs.{{$overpaids['cash_overpaid']}}</h6>
                                <h6>Cheque - Rs.{{$overpaids['cheque_overpaid']}}</h6>
                                <h6>Online - Rs.{{$overpaids['online_overpaid']}}</h6>
                                <h6>Cash Deposit - Rs.{{$overpaids['cash_deposit_overpaid']}}</h6>
                                <h6>Credit Note - Rs.{{$overpaids['return_overpaid']}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div ng-controller="AppController">
                            <section @if($outlet_invoice_details->remain != 0) style="display: none" @endif>
                                <div class="row" style="padding-top: 10px;height: 400px;">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="text-center" style="margin-top: 100px">
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"
                                                   style="font-size: 30px"></i>
                                                <h5>No Invoices To Pay</h5>
                                                <h6>Currently you have none invoices to pay</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </div>
                            </section>

                            <section @if($outlet_invoice_details->remain  ==0) style="display: none" @endif>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                        <div class="about-box">
                                            <a ng-click="test()">.</a>

                                            <wizard on-finish="finishedWizard()">
                                                <wz-step wz-title="Method" canexit="exitValidationStep1">
                                                    <div class="step-wrapper">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                <div class="wizard-title">
                                                                    <div class="row">
                                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                            <h4 style="margin:0">Choose payment
                                                                                method</h4>
                                                                            <p>Select payment method auto or manual
                                                                                and</p>
                                                                        </div>
                                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                            <div class="wizard-buttons text-right">
                                                                                <ul class="pager">
                                                                                    <li><a href="#" wz-next>Next <span
                                                                                                    aria-hidden="true">&rarr;</span></a>
                                                                                    </li>

                                                                                </ul>
                                                                            </div>
                                                                        </div>


                                                                    </div>

                                                                    <hr>
                                                                </div>

                                                                <div class="wizard-content" id="pay_method_step">
                                                                    <form name="forms.cashForm">
                                                                        <div class="row">
                                                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                                <h4>Payment Method</h4>
                                                                                <select class="form-control"
                                                                                        ng-model="payment.type"
                                                                                        ng-change="clearScope()"
                                                                                        ng-init="payment.type='1'">
                                                                                    @foreach($payment_types as $payment_type)
                                                                                        <option value="{{$payment_type->id}}">{{$payment_type->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                                <h4 ng-init="payment.setoff_type=true">
                                                                                    Set-off method</h4>
                                                                                <div class="row"
                                                                                     style="margin-top: -5px">
                                                                                    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4"
                                                                                         style="margin: 0">
                                                                                        <div class="radio">
                                                                                            <label>
                                                                                                <input ng-model="payment.setoff_type"
                                                                                                       type="radio"
                                                                                                       name="chk-setoff"
                                                                                                       id="input-cheuqe"
                                                                                                       ng-value="true">
                                                                                                Auto set-off
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 text-left"
                                                                                         style="margin: 0">
                                                                                        <div class="radio"
                                                                                             ng-hide="(payment.type>2) && (payment.type!=6) && (payment.type!=7)">
                                                                                            <label>
                                                                                                <input ng-model="payment.setoff_type"
                                                                                                       type="radio"
                                                                                                       name="chk-setoff"
                                                                                                       id="input-cash"
                                                                                                       ng-value="false">
                                                                                                Manual set-off
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <p class="note" style="font-size: 13px">
                                                                                    Note : </p>
                                                                                <p class="note">In Manual set-off you
                                                                                    have to select the invoices you like
                                                                                    to pay </p>
                                                                                <p class="note">In auto mode system will
                                                                                    set off the amount you entered here
                                                                                    to the invoices from oldes to lates
                                                                                    order </p>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /row -->
                                                                        <div class="row" style="margin-top: 10px;"
                                                                             ng-show="(payment.type==1) || (payment.type==6) || (payment.type==7)">
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <h4>Cash details</h4>
                                                                                <div class="input-group"
                                                                                     ng-class="{ 'has-error': forms.cashForm.cash_amount.$invalid  && !forms.cashForm.cash_amount.$pristine }">
                                                                                    <span class="input-group-addon"
                                                                                          id="basic-addon1">Rs.</span>
                                                                                    <input name="cash_amount"
                                                                                           ng-pattern="/^[1-9]+\d*\.?\d*$/"
                                                                                           required
                                                                                           ng-model="payment.cash.amount"
                                                                                           type="text"
                                                                                           class="form-control"
                                                                                           placeholder="payment amount"
                                                                                           aria-describedby="basic-addon1">
                                                                                </div>
                                                                                <p ng-show="forms.cashForm.cash_amount.$invalid && !forms.cashForm.cash_amount.$pristine"
                                                                                   class="help-block">Invalid
                                                                                    Amount.</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row" style="margin-top: 10px;"
                                                                             ng-show="(payment.type==6) || (payment.type==7)">
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <div class="input-group"
                                                                                     >
                                                                                            <span class="input-group-addon"
                                                                                                  id="basic-addon1"><i
                                                                                                        class="fa fa-university"
                                                                                                        aria-hidden="true"></i></span>

                                                                                    <select name="account"
                                                                                            class="form-control"
                                                                                            ng-model="payment.cash.account"
                                                                                            >
                                                                                        @foreach($accounts as $account)
                                                                                            <option value="{{$account->id}}">{{$account->account_no.' ('.$account->banks->name.' - '.$account->branch.')'}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <p ng-show="!payment.cash.account"
                                                                                   class="help-block">Invalid
                                                                                    Account.</p>
                                                                            </div>
                                                                        </div>
                                                                    </form>


                                                                    <div class="row" style="margin-top: 10px;"
                                                                         ng-show="payment.type==2">
                                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <h4>Cheque details</h4>
                                                                            <form name="forms.chequeForm">
                                                                                <div class="row">
                                                                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                                        <div class="input-group"
                                                                                             ng-class="{ 'has-error': forms.chequeForm.bank.$invalid  && !forms.chequeForm.bank.$pristine }">
                                                                                            <span class="input-group-addon"
                                                                                                  id="basic-addon1"><i
                                                                                                        class="fa fa-university"
                                                                                                        aria-hidden="true"></i></span>

                                                                                            <select name="bank"
                                                                                                    class="form-control"
                                                                                                    ng-model="payment.cheque.bank"
                                                                                                    ng-change="changeBank()"
                                                                                                    required>
                                                                                                @foreach($banks as $bank)
                                                                                                    <option value="{{$bank->id}}">{{$bank->code}}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <p ng-show="forms.chequeForm.bank.$invalid && !forms.cashForm.bank.$pristine"
                                                                                           class="help-block">Invalid
                                                                                            Amount.</p>
                                                                                    </div>
                                                                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-addon"
                                                                                                  id="basic-addon1"><i
                                                                                                        class="fa fa-university"
                                                                                                        aria-hidden="true"></i></span>
                                                                                            <input name="bank_name"
                                                                                                   id="bank_name"
                                                                                                   ng-model="payment.cheque.bank_name"
                                                                                                   required type="text"
                                                                                                   class="form-control"
                                                                                                   placeholder="Bank"
                                                                                                   disabled
                                                                                                   aria-describedby="basic-addon1" required>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>

                                                                                <div class="row"
                                                                                     style="margin-top: 20px;">
                                                                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                                        <div class="input-group"
                                                                                             ng-class="{'has-error': forms.chequeForm.no.$invalid  && !forms.chequeForm.no.$pristine }">
                                                                                            <span class="input-group-addon"
                                                                                                  id="basic-addon1">No.</span>
                                                                                            <input name="no"
                                                                                                   ng-model="payment.cheque.no"
                                                                                                   required type="text"
                                                                                                   class="form-control"
                                                                                                   placeholder="Cheque No"
                                                                                                   aria-describedby="basic-addon1">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                                        <div class="input-group"
                                                                                             ng-class="{ 'has-error': forms.chequeForm.amount.$invalid  && !forms.chequeForm.amount.$pristine }">
                                                                                            <span class="input-group-addon"
                                                                                                  id="basic-addon1">Rs.</span>
                                                                                            <input name="amount"
                                                                                                   ng-pattern="/^[1-9]+\d*\.?\d*$/"
                                                                                                   required
                                                                                                   ng-model="payment.cheque.amount"
                                                                                                   type="text"
                                                                                                   class="form-control"
                                                                                                   placeholder="payment amount"
                                                                                                   aria-describedby="basic-addon1">
                                                                                        </div>
                                                                                        <p ng-show="forms.chequeForm.amount.$invalid && !forms.chequeForm.amount.$pristine"
                                                                                           class="help-block">Invalid
                                                                                            Amount.</p>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="row"
                                                                                     style="margin-top: 20px;">
                                                                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                                        <div class="input-group date"
                                                                                             data-provide="datepicker"
                                                                                             ng-class="{ 'has-error': forms.chequeForm.date.$invalid  && !forms.chequeForm.date.$pristine }">
                                                                                                <span class="input-group-addon">
                                                                                                    <span class="fa fa-calendar"></span>
                                                                                                </span>
                                                                                            <input id="cheque_date"
                                                                                                   name="date"
                                                                                                   ng-model="payment.cheque.date"
                                                                                                   placeholder="Select Cheque Date"
                                                                                                   type="text"
                                                                                                   class="form-control"/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <p ng-show="forms.chequeForm.$invalid && !forms.chequeForm.$pristine"
                                                                                   class="help-block">Please fill all
                                                                                    the data.</p>
                                                                            </form>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row" style="margin-top: 10px;"
                                                                         ng-show="(payment.type>2) && (payment.type!=6)&& (payment.type!=7)">
                                                                        <form name="forms.overForm">
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <h4 ng-show="payment.type==3">Credit
                                                                                    note Amounts</h4>
                                                                                <h4 ng-show="payment.type==4">Cash
                                                                                    Overpaid Amounts</h4>
                                                                                <h4 ng-show="payment.type==5">Cheque
                                                                                    Overpaid Amounts</h4>
                                                                                <h4 ng-show="payment.type==8">Online
                                                                                    Overpaid Amounts</h4>
                                                                                <h4 ng-show="payment.type==9">Cash Deposit
                                                                                    Overpaid Amounts</h4>
                                                                                <div class="input-group"
                                                                                     ng-class="{ 'has-error': forms.overForm.overpaid_amount.$invalid  && !forms.overForm.overpaid_amount.$pristine }">
                                                                                    <span class="input-group-addon"
                                                                                          id="basic-addon1">Rs.</span>
                                                                                    <input disabled
                                                                                           name="overpaid_amount"
                                                                                           ng-pattern="/^[1-9]+\d*\.?\d*$/"
                                                                                           required
                                                                                           ng-model="payment.overpaid.amount"
                                                                                           type="text"
                                                                                           class="form-control"
                                                                                           placeholder="payment amount"
                                                                                           aria-describedby="basic-addon1">
                                                                                </div>
                                                                                <p ng-show="forms.overForm.overpaid_amount.$invalid && !forms.overForm.overpaid_amount.$pristine"
                                                                                   class="help-block">Invalid
                                                                                    Amount.</p>
                                                                            </div>
                                                                        </form>
                                                                        <div ng-hide="payment.overpaid.amount>0">
                                                                            <div class="required">No overpaid amount
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row"
                                                                         style="margin-top: 20px;">
                                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                            <div class="input-group date"
                                                                                 >
                                                                                                <span class="input-group-addon">
                                                                                                    <span class="fa fa-calendar"></span>
                                                                                                </span>
                                                                                <input id="payment_date"
                                                                                       name="date"
                                                                                       ng-model="payment.cash.date"
                                                                                       placeholder="Select Payment Date"
                                                                                       type="text"
                                                                                       class="form-control"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                            <div class="input-group">
                                                                                            <span class="input-group-addon"
                                                                                                  id="basic-addon1"><i
                                                                                                        class="fa fa-comment"
                                                                                                        aria-hidden="true"></i></span>
                                                                                <input name="remark"
                                                                                       required
                                                                                       ng-model="payment.cash.remark"
                                                                                       type="text"
                                                                                       class="form-control"
                                                                                       placeholder="Remark"
                                                                                       aria-describedby="basic-addon1">
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </wz-step>


                                                <wz-step wz-title="setoff" canexit="exitValidationStep2">
                                                    <div class="step-wrapper">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                <div class="wizard-title">
                                                                    <div class="row">
                                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                            <h4 style="margin:0">Setoff</h4>
                                                                            <p>Setoff the amount to invoices </p>
                                                                        </div>
                                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                            <div class="wizard-buttons text-right">
                                                                                <ul class="pager">
                                                                                    <li><a href="#" wz-previous><span
                                                                                                    aria-hidden="true">&larr;</span>
                                                                                            Previous </a></li>
                                                                                    <li><a href="#" wz-next>Next <span
                                                                                                    aria-hidden="true">&rarr;</span></a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                </div>

                                                                <div class="wizard-content">
                                                                    <div class="row">
                                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <form name="forms.payment">
                                                                                <table class="table table-hover"
                                                                                       width="100%">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>Invoice No</th>
                                                                                        <th>Invoice Date</th>
                                                                                        <th>Total(Rs.)</th>
                                                                                        <th>Payment(Rs.)</th>
                                                                                        <th>Due(Rs.)</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <tr ng-repeat="(key, setoff) in setoff_details.details">
                                                                                        <td>@{{setoff.invoice.manual_id}}</td>
                                                                                        <td>@{{setoff.invoice.created_date}}</td>
                                                                                        <td>@{{setoff.invoice.total | currency: "Rs."}}</td>
                                                                                        <td>
                                                                                            <input type="text"
                                                                                                   name="setoff_@{{key}}"
                                                                                                   ng-pattern="/^[0-9]+\d*\.?\d*$/"
                                                                                                   class="form-control"
                                                                                                   ng-model="setoff.setoff"
                                                                                                   ng-disabled="payment.setoff_type">
                                                                                            <i class="error"
                                                                                               ng-show="forms.payment.setoff_@{{key}}.$invalid && !forms.payment.setoff_@{{key}}.$pristine"
                                                                                               class="help-block">Invalid
                                                                                                Qty.</i>
                                                                                        </td>
                                                                                        <td>@{{(setoff.invoice.total-setoff.setoff) | currency: "Rs." }}</td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </form>
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <div class="text-right">
                                                                                    <table width="100%">
                                                                                        <tr>
                                                                                            <td width="80%">
                                                                                                <h4>Payment :</h4>
                                                                                            </td>
                                                                                            <td width="20%">
                                                                                                <h4>@{{amount | currency: "Rs."}}</h4>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr ng-if="setoff_details.overpaid>0">
                                                                                            <td width="80%">
                                                                                                <h5>Overpayment : </h5>
                                                                                            </td>
                                                                                            <td width="20%">
                                                                                                <h5>@{{setoff_details.overpaid | currency: "Rs."}}</h5>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                                                 ng-show="setoff_details.isRemain">
                                                                                <h6 style="color:red"><i>Note : Your
                                                                                        payment is not enough, Could'nt
                                                                                        set the amount fully for all
                                                                                        invoices</i></h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- /row -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </wz-step>
                                                <wz-step wz-title="Done">
                                                    <div class="step-wrapper">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                <div class="wizard-title">
                                                                    <div class="row">
                                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                            <h4 style="margin:0">Done</h4>
                                                                            <p>Payment completed</p>
                                                                        </div>
                                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                            <div class="wizard-buttons text-right">
                                                                                <ul class="pager">
                                                                                    <li><a href="#" wz-previous><span
                                                                                                    aria-hidden="true">&larr;</span>
                                                                                            Previous </a></li>
                                                                                    <li>
                                                                                        <a style="background-color: #C51C6A !important;border: none"
                                                                                           href="#" class="btn-primary"
                                                                                           ng-click="save()">Save </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                </div>

                                                                <div class="wizard-content">
                                                                    <div class="row">
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <h6></h6>
                                                                            <h6>Recipt date
                                                                                : @{{ nowDate | date:'yyyy-MM-dd'}}</h6>

                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
                                                                            <h6 style="font-weight: 800">Outlet
                                                                                : @if($outlet->outlet_name || $outlet->outlet_name!=""){{$outlet->outlet_name}}@endif</h6>
                                                                            <h6>
                                                                                <?php $array = explode(',', $outlet->outlet_address); ?>
                                                                                @foreach($array as $address)
                                                                                    {{$address}}<br>
                                                                                @endforeach
                                                                            </h6>

                                                                        </div>
                                                                    </div>

                                                                    <div class="row" style="margin-top: 20px;">
                                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <table class="table table-hover"
                                                                                   width="100%">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>Invoice No</th>
                                                                                    <th>Invoice Date</th>
                                                                                    <th>Total</th>
                                                                                    <th>Payment</th>
                                                                                    <th>Due</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr ng-repeat="(key, setoff) in setoff_details.details"
                                                                                    ng-if="setoff.setoff>0">
                                                                                    <td>@{{setoff.invoice.manual_id}}</td>
                                                                                    <td>@{{setoff.invoice.created_date}}</td>
                                                                                    <td>@{{setoff.invoice.total | currency: "Rs."}}</td>
                                                                                    <td>@{{setoff.setoff | currency: "Rs."}}</td>
                                                                                    <td>@{{(setoff.invoice.total-setoff.setoff) | currency: "Rs."}}</td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <div class="text-right">
                                                                                    <table width="100%">
                                                                                        <tr>
                                                                                            <td width="80%">
                                                                                                <h4>Payment :</h4>
                                                                                            </td>
                                                                                            <td width="20%">
                                                                                                <h4>@{{amount | currency: "Rs."}}</h4>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr ng-if="setoff_details.overpaid>0">
                                                                                            <td width="80%">
                                                                                                <h5>Overpayment : </h5>
                                                                                            </td>
                                                                                            <td width="20%">
                                                                                                <h5>@{{setoff_details.overpaid | currency: "Rs."}}</h5>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </wz-step>
                                            </wizard>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </section>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>

@stop
@section('js')
    <script src="{{url('/assets/angular/angular/angular.min.js')}}"></script>
    <script src="{{url('/assets/accounting_js/accounting.min.js')}}"></script>
    <script src="{{url('/assets/mgonto-angular-wizard/angular-wizard.js')}}"></script>
    <script src="{{url('/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>


    <script src="{{url('/assets/angular/mac_scripts/filters.js')}}"></script>


    <script type="text/javascript">
        $(document).ready(function () {

            $('#payment_date').datepicker({
                format: "yyyy-mm-dd",
                daysOfWeekHighlighted: "0,6",
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true
            });

            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            $( '#payment_date' ).datepicker( 'setDate', today );


        });
        /* 
         * A CRAFT from MAC
         * Be a hacker but aint be a thief
         *
         */

        /*
         *cash   -> 1
         *cheque -> 2
         */

        var module = angular.module("AppModule", ["currency_filters", "mgo-angular-wizard"]);
        //ANGULAR CONSTANT TO SET THE BASE URL
        module.constant('BASE_URL', '{{url("/")}}');


        module.service('payment_service', function ($http, BASE_URL) {
            return {
                getInvoices: function (_URL, data) {
                    //return the promise directly.
                    $('.panel').addClass('panel-refreshing');
                    return $.ajax({
                        url: BASE_URL + '/' + _URL,
                        type: "GET",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        data: data,
                        success: function (response) {
                            return response.data;
                        },
                        error: function (response) {
                            console.log("failed");
                        }
                    });
                }, send: function (_URL, _TYPE, data) {
                    //return the promise directly.
                    $('.panel').addClass('panel-refreshing');
                    return $.ajax({
                        url: BASE_URL + '/' + _URL,
                        type: _TYPE,
                        data: data,
                        success: function (response) {
                            return response.data;
                        },
                        error: function (response) {
                            console.log("failed");
                        }
                    });
                },
                autosetoff: function (amount, invoices) {
                    var setoff_ = {};
                    var setoff_array = [];
                    var amount = parseFloat(amount);
                    var isRemain = false;

                    $.each(invoices, function (index, value) {
                        var setoff = 0;
                        if (amount > 0) {
                            if (amount < value.total) {
                                //invoice amount is greater than the amount your paying
                                setoff = parseFloat(amount);
                                amount = 0;
                            } else if (amount == value.total) {
                                //end
                                setoff = parseFloat(amount);
                                amount = 0;
                            } else if (amount > value.total) {
                                //you can pay more
                                setoff = parseFloat(value.total);
                                amount = amount - setoff;
                            }

                            if (value.total > setoff || value.total != setoff) {
                                isRemain = true;
                            }

                            setoff_array.push({
                                invoice: value,
                                setoff: setoff
                            });

                        }
                    });

                    setoff_ = {
                        details: setoff_array,
                        overpaid: amount,
                        isRemain: isRemain
                    };

                    return setoff_;
                }, manualsetoff: function (invoices) {
                    var setoff_ = {};
                    var setoff_array = [];
                    var amount = parseFloat(amount);
                    var isRemain = false;

                    $.each(invoices, function (index, value) {
                        var setoff = 0;
                        setoff_array.push({
                            invoice: value,
                            setoff: 0
                        });
                    });

                    setoff_ = {
                        details: setoff_array,
                        overpaid: 0,
                        isRemain: isRemain
                    };

                    return setoff_;
                }

            }
        });


        module.controller("AppController", function ($scope, BASE_URL, payment_service, WizardHandler) {

            $scope.payment = {};
            $scope.payment.overpaid = {};
            $scope.invoices = [];
            $scope.forms = {};
            $scope.nowDate = new Date();

            $scope.overpaids = <?php echo json_encode($overpaids) ?>;
            $scope.forms = {};

            $scope.test = function () {
                console.log($scope);
            };

            $scope.isGreaterThan = function (prop, val) {
                return function (item) {
                    return item[prop] > val;
                }
            }


            var send_data = {
                outlet: <?php echo json_encode($outlet) ?>
            };

            $scope.exitValidationStep1 = function () {

                $scope.setoff_details = null;
                var isFormOk = true;
                $scope.amount = 0;

                $scope.comment = '';

                if ((($scope.payment.type == 1) || ($scope.payment.type == 6)|| ($scope.payment.type == 7)) && $scope.payment.cash != null && $scope.forms.cashForm.$valid) {
                    //cash
                    $scope.amount = $scope.payment.cash.amount;
                } else if ($scope.payment.type == 2 && $scope.payment.cheque != null
                    && $scope.forms.chequeForm.$valid && $('#cheque_date').val() != "") {
                    //cheque
                    $scope.amount = $scope.payment.cheque.amount;
                } else if ($scope.payment.type > 2 && $scope.payment.overpaid.amount > 0) {
                    //overpaid
                    $scope.amount = $scope.payment.overpaid.amount;
                } else {
                    isFormOk = false;
                }
                //$scope.payment_date = $scope.payment.cash.date;
                $scope.comment = $scope.payment.cash.remark;

                if (isFormOk) {
                    if ($scope.payment.setoff_type) {
                        //auto
                        payment_service.getInvoices('payment/json/getInvoicesFor', send_data).then(function (response) {
                            $('.panel').removeClass('panel-refreshing');
                            $scope.invoices = response.invoices;
                            $scope.setoff_details = payment_service.autosetoff($scope.amount, $scope.invoices);
                            //console.log($scope.setoff_details);
                            $scope.$digest();
                        });
                    } else {
                        //manual
                        payment_service.getInvoices('payment/json/getInvoicesFor', send_data).then(function (response) {
                            $('.panel').removeClass('panel-refreshing');
                            $scope.invoices = response.invoices;
                            $scope.setoff_details = payment_service.manualsetoff($scope.invoices);
                            $scope.$digest();
                        });
                    }
                }


                return isFormOk;

            };

            $scope.exitValidationStep2 = function () {
                if ($scope.invoices.length == 1) {
                    if ($scope.setoff_details.details[0].setoff > 0) {
                        return true;
                    } else {
                        swal('Error', 'You Have to pay atleast one invoice, Recipt cannot be made without paying to atleast one invoice', 'error');
                        return false;
                    }
                } else {
                    var notPaid = 0;
                    $.each($scope.setoff_details.details, function (index, value) {
                        if (value.setoff == 0) {
                            notPaid++;
                        }
                    });
                    if (notPaid == $scope.setoff_details.details.length) {
                        swal('Error', 'You Have to pay atleast one invoice, Recipt cannot be made without paying to atleast one invoice', 'error');
                        return false;
                    } else {
                        return true;
                    }
                }
            };


            $scope.clearScope = function () {
                $scope.payment.cash = {};
                $scope.payment.cheque = {};
                $scope.payment.overpaid = {};
                $scope.payment.cheque.bank = 0;
                $scope.amount = 0;
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth() + 1; //because January is 0!
                var yyyy = today.getFullYear();
                if (dd < 10) {
                    dd = '0' + dd;
                }
                if (mm < 10) {
                    mm = '0' + mm;
                }
                var today = yyyy+'-'+ mm+'-'+ dd;
                console.log(today);
                $scope.payment.cash.date = today;
                //$( '#payment_date' ).datepicker( 'setDate', today );
            };

            $scope.changeBank = function () {
                payment_service.send('payment/json/bank/'+$scope.payment.cheque.bank,'GET').then(function (response) {
                    $scope.payment.cheque.bank_name = response.name;
                    $('#bank_name').val(response.name);
                    $('.panel').removeClass('panel-refreshing');
                    //console.log(response);
                });
            };

            var type = $('#route_type').val();

            $scope.save = function () {

                var send_data = {
                    outlet: <?php echo json_encode($outlet) ?>,
                    setoff_details: $scope.setoff_details,
                    payment: $scope.payment,
                    type: type
                };

//                console.log($scope.payment);

                payment_service.send('payment/json/addPayment','POST', send_data).then(function (response) {
                    $('.panel').removeClass('panel-refreshing');
                    if (type == 1) {
                        var url = "{{url('invoice/print?ids=')}}" + response.ids;
                        window.open(url);

                        var refresh_url = "{{url("invoice/customerlist")}}";
                        window.location.href = refresh_url;

                    } else {
                        var url = "{{url('payment/receipt/print/')}}" + "/" + response.ids;
                        window.open(url);
                        location.reload();
                    }
                });

            };


            $scope.$watch('setoff_details.details', function (newVal, oldVal) {
                /*...*/
                var invoice_total = 0;
                if (newVal != null) {
                    for (var i = 0; i < newVal.length; i++) {
                        if (newVal[i].invoice.total < newVal[i].setoff) {
                            if (oldVal != null) {
                                newVal[i].setoff = oldVal[i].setoff;
                            }
                        }

                        if (newVal[i].setoff == null) {
                            newVal[i].setoff = 0;
                        }
                        invoice_total += parseFloat(newVal[i].setoff);
                    }

                    var overpaid = parseFloat($scope.amount) - parseFloat(invoice_total);
                    if ($scope.setoff_details != null) {
                        $scope.setoff_details.overpaid = overpaid;
                    }
                }
            }, true);


            $scope.$watch('payment.type', function (newVal, oldVal) {
                /*...*/
                if (newVal == 3) {
                    $scope.payment.overpaid.amount = $scope.overpaids.return_overpaid;
                } else if (newVal == 4) {
                    $scope.payment.overpaid.amount = $scope.overpaids.cash_overpaid;
                } else if (newVal == 5) {
                    $scope.payment.overpaid.amount = $scope.overpaids.cheque_overpaid;
                }else if (newVal == 8) {
                    $scope.payment.overpaid.amount = $scope.overpaids.online_overpaid;
                }else if (newVal == 9) {
                    $scope.payment.overpaid.amount = $scope.overpaids.cash_deposit_overpaid;
                }
            }, true);


        });

    </script>
@stop
