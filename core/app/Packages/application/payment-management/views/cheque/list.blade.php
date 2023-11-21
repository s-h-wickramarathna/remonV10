@extends('layouts.master') @section('title','Cheque List')
@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/vendor/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/vendor/jquery-toast-plugin-master/dist/jquery.toast.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/mgonto-angular-wizard/angular-wizard.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/assets/ag_grid/themes/theme-material.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/assets/css/build.css')}}">
    <style type="text/css">

        .box-widget {
            border: none;
            position: relative;
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

        .order-view {
            padding: 20px;
            height: 85px;
            border-top-right-radius: 3px;
            border-top-left-radius: 3px;
        }

        .box.box-primary {
            border-top-color: #3c8dbc;
        }

        .box.box-info {
            border-top-color: #00c0ef;
        }

        .box-header.with-border {
            border-bottom: 1px solid #f4f4f4;
        }

        .pager {
            margin: 0px;
        }

        .widget-user .widget-user-header {
            padding: 20px;
            height: 120px;
            border-top-right-radius: 3px;
            border-top-left-radius: 3px;
        }

        .bg-aqua-active, .modal-info .modal-header, .modal-info .modal-footer {
            background-color: #00a7d0 !important;
        }

        .widget-user .widget-user-username {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 25px;
            font-weight: 300;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
        }

        .widget-user .widget-user-desc {
            margin-top: 0;
        }

        .widget-user .widget-user-image {
            position: absolute;
            top: 65px;
            left: 50%;
            margin-left: -45px;
        }

    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:">Payment Management</a>
        </li>
        <li class="active">Cheque List</li>
    </ol>
    <div class="col">
        <div class="col-xs-12">
            <div class="box box-widget box-list box-primary">
                <div class="box-body">
                    <form role="form" method="get" action="{{url('payment/cheque/list')}}">
                        <div class="form-group" style="padding-left: 10px;padding-top: 10px;padding-bottom: 10px">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" autocomplete="off" class="form-control"
                                               name="no"
                                               id="no"
                                               placeholder="Receipt No/Cheque No" value="{{$no}}"/>
                                        <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <select class="chosen" name="status">
                                            <option value="0"> -- All Status --</option>
                                            <option value="1" @if($status == 1) selected @endif> Pending</option>
                                            <option value="2" @if($status == 2) selected @endif> Received</option>
                                            <option value="3" @if($status == 3) selected @endif> Bounced</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="chosen" name="area">
                                        <option value="0">-- Search Area --
                                        </option>
                                        @foreach($areas as $area_obj)
                                            <option value="{{$area_obj->id}}"
                                                    @if($area_obj->id == $area ) selected @endif>{{$area_obj->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="padding-left: 10px;padding-bottom: 10px">
                            <div class="row">
                                <div class="col-md-3">
                                    <select class="chosen" name="customer">
                                        <option value="0">-- Search Customer --
                                        </option>
                                        <?php $i = 0;?>
                                        @foreach($customerList as $customerObj)
                                            <option value="{{$customerObj->id}}"
                                                    @if($customerObj->id == $customer ) selected @endif>{{$customerObj->f_name .' '.$customerObj->l_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="chosen" name="bank">
                                        <option value="0">-- Search Bank --
                                        </option>
                                        <?php $i = 0;?>
                                        @foreach($bankList as $bankObj)
                                            <option value="{{$bankObj->id}}"
                                                    @if($bankObj->id == $bank ) selected @endif>{{$bankObj->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 input-daterange">
                                    <input type="text" autocomplete="off" class="form-control" name="from"
                                           id="from"
                                           placeholder="From Date" value="{{$from}}"/>
                                </div>
                                <div class="col-md-2 input-daterange">
                                    <input type="text" autocomplete="off" class="form-control" name="to"
                                           id="to"
                                           placeholder="To Date" value="{{$to}}"/>
                                </div>
                                <div class="col-md-2">
                                    <div class="pull-left">
                                        <button type="submit" class="btn btn-info" id="plan"><i class="fa fa-search"
                                                                                                style="padding-right: 16px;width: 28px;"></i>Find
                                        </button>
                                    </div>
                                    <div class="pull-right" style="padding-right: 10px">
                                        <a href="list" class="btn btn-warning" data-toggle="tooltip"
                                           data-placement="top"><i class="fa fa-refresh"></i></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col refresh">
        <div class="col-xs-12">

            <div class="box box-widget box-list box-primary">
                <div class="box-body" style="max-height: 500px;min-height: 500px;overflow-y: scroll;">

                    <div class="form-group">
                        <div class="row">
                            <form role="form" method="get" action="{{url('payment/cheque/download')}}">
                                <div class="col-md-1 pull-right">
                                    <div class="pull-right"
                                         style="padding-right: 10px;padding-top: 10px;padding-left: 10px">
                                        <input type="hidden" name="no" value="{{$no}}">
                                        <input type="hidden" name="customer" value="{{$customer}}">
                                        <input type="hidden" name="status" value="{{$status}}">
                                        <input type="hidden" name="bank" value="{{$bank}}">
                                        <input type="hidden" name="from" value="{{$from}}">
                                        <input type="hidden" name="to" value="{{$to}}">
                                        <input type="hidden" name="area" value="{{$area}}">
                                        <button type="submit" class="btn btn-info">PDF <i class="fa fa-download"></i>
                                        </button>
                                        {{--<button class="btn btn-warning" ><i class="fa fa-refresh"></i></button>--}}
                                    </div>
                                </div>
                            </form>
                            <form role="form" method="get" action="{{url('payment/cheque/excel')}}">
                                <div class="col-md-1 pull-right">
                                    <div class="pull-right"
                                         style="padding-right: 10px;padding-top: 10px;padding-left: 10px">
                                        <input type="hidden" name="no" value="{{$no}}">
                                        <input type="hidden" name="customer" value="{{$customer}}">
                                        <input type="hidden" name="status" value="{{$status}}">
                                        <input type="hidden" name="bank" value="{{$bank}}">
                                        <input type="hidden" name="from" value="{{$from}}">
                                        <input type="hidden" name="to" value="{{$to}}">
                                        <input type="hidden" name="area" value="{{$area}}">
                                        <button type="submit" class="btn btn-warning">Excel <i class="fa fa-download"></i>
                                        </button>
                                        {{--<button class="btn btn-warning" ><i class="fa fa-refresh"></i></button>--}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <table class="table table-bordered bordered table-striped table-condensed"
                                   id="orderTable">
                                <thead style="background-color:rgba(52, 73, 94,0.5);color:#fff;">
                                <tr>
                                    <td width="5%">
                                        #
                                    </td>
                                    <td width="15%">Customer</td>
                                    <td width="25%">Receipt</td>
                                    <td width="12%">Date</td>
                                    <td width="10%">Amount</td>
                                    <td width="15%">Remark</td>
                                    <td width="10%">Status</td>
                                    @if($user->id == 1 || $user->roles[0]->slug == 'admin')
                                        <td width="15%">Action
                                            {{--<ul class="pager">
                                                <li><a onclick="" class="fa fa-arrow-circle-o-down"> Excel</a></li>
                                            </ul>--}}
                                        </td>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;?>
                                @foreach($orders as $order)
                                    <tr id="{{$i}}">
                                        <td>{{$i}}</td>
                                        <td>{{$order->customer->f_name.' '.$order->customer->l_name}}</td>
                                        <td>
                                            <span><i class="fa fa-thumb-tack"></i> {{$order->recipt_no}}</span><br>
                                            <span><i class="fa fa-google-wallet"></i> {{$order->cheque_no}}</span><br>
                                            <span><i class="fa fa-university"></i> {{$order->name}}</span>
                                        </td>
                                        <td>{{$order->recipt_date}}</td>
                                        <td style="text-align: right">{{number_format($order->cheque_amount,2)}}</td>
                                        <td class="rmk">{{$order->remark}}</td>
                                        <td class="status_col">
                                            @if($order->status == 1)
                                                <span class="badge" style="background:#0081c2"><i
                                                            class="fa fa-clock-o"></i> Pending</span>
                                            @elseif($order->status == 2)
                                                <span class="badge" style="background:#9369cc"><i
                                                            class="fa fa-check-circle"></i> Received</span>
                                            @else
                                                <span class="badge" style="background:#8b130f"><i
                                                            class="fa fa-retweet"></i> Bounced</span>
                                            @endif
                                        </td>
                                        @if($user->id == 1 || $user->roles[0]->slug == 'admin' || $user->roles[0]->slug == 'account')
                                            <td style="text-align: center">
                                                <a onclick="changeStatus({{$i}},1,{{$order->cheque_no}},'{{$order->recipt_no}}')"
                                                   data-toggle="tooltip" target="_blank" data-placement="top"
                                                   title="Change To Done" style="padding-left: 2px;cursor: hand">
                                      <span class="badge" style="background:#9369cc">
                                          <i class="fa fa-check-circle"></i>
                                      </span>
                                                </a>
                                                <a onclick="changeStatus({{$i}},2,{{$order->cheque_no}},'{{$order->recipt_no}}')"
                                                   data-toggle="tooltip" target="_blank" data-placement="top"
                                                   title="Change To Bounced" style="padding-left: 2px;cursor: hand">
                                       <span class="badge" style="background:#8b130f">
                                           <i class="fa fa-retweet"></i>
                                       </span>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                    <?php $i++;?>
                                @endforeach
                                </tbody>
                            </table>
                            @if(count($orders) > 0)
                                {!! $orders->render() !!}
                            @else
                                <center><label class="badge" style="background:#0081c2">{{ 'No Records' }}</label>
                                </center>
                            @endif
                        </div>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content po-model">

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@stop
@section('js')
    <script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-notify-master/js/bootstrap-notify.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-switch-master/dist/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('assets/vendor/jquery-toast-plugin-master/dist/jquery.toast.min.js')}}"></script>
    <script src="{{url('assets/angular/angular/angular.min.js')}}"></script>
    <script src="{{url('assets/mgonto-angular-wizard/angular-wizard.js')}}"></script>
    <script src="{{url('/assets/ag_grid/ag-grid.js')}}"></script>
    <script src="{{url('/assets/ag_grid/grid/grid.js')}}"></script>
    <script src="{{url('/assets/js/ejs.js')}}"></script>
    <script type="text/javascript">
        var tpl_order = new EJS({url: '{{url('core/app/Packages/application/purchaseOrder-management/views/template/tpl_order')}}'});
        var tpl_invoice_no = new EJS({url: '{{url('core/app/Packages/application/purchaseOrder-management/views/template/tpl_invoice_no')}}'});
        var tpl_invoice_row = new EJS({url: '{{url('core/app/Packages/application/purchaseOrder-management/views/template/tpl_invoice_row')}}'});
        var tpl_reject = new EJS({url: '{{url('core/app/Packages/application/payment-management/views/template/tpl_reject')}}'});
        $('.refresh').addClass('panel-refreshing');
        $(document).ready(function () {

            $('.form-validation').validate();

            $('.refresh').removeClass('panel-refreshing');


            $('.input-daterange').datepicker({
                format: "yyyy-mm-dd",
                daysOfWeekHighlighted: "0,6",
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true
            });


        });
        function viewDetail(orderId) {
            $('.refresh').addClass('panel-refreshing');
            $.get('get/' + orderId, function (data) {
                $('.fade').modal('show');
                $('.po-model').html(tpl_order.render({
                    orderNo: data[0].order_no,
                    date: data[0].created_at,
                    outlet: data[0].location.name,
                    address: data[0].location.dealer.address,
                    rep: data[0].employee.first_name + ' ' + data[0].employee.last_name,
                    data: data[0].detail,
                    vat: data[0].vat.value,
                    remark: data[0].remark,
                    poStatus: data[0].download_status,
                    reason: data[0].reason,
                    discount: 0 > 0 ? data[0].discount[0].discount : 0
                }));
                $('.refresh').removeClass('panel-refreshing');
            });
        }

        function holdPO(index, orderId) {
            $('.refresh').addClass('panel-refreshing');
            $('.fade').modal('show');
            $('.po-model').html(tpl_reject.render({
                orderNo: orderId,
                type: 3,
                index: index,
                textLabel: 'Hold Reason'

            }));
            $('.refresh').removeClass('panel-refreshing');
        }

        function rejectPO(index, recipt_no, cheque_no) {
            $('.refresh').addClass('panel-refreshing');
            $('.fade').modal('show');
            $('.po-model').html(tpl_reject.render({
                chequeNo: cheque_no,
                no: recipt_no + ' / ' + cheque_no,
                textLabel: 'Remark',
                index: index

            }));
            $('.refresh').removeClass('panel-refreshing');
        }

        function saveReject() {

            var cheque_no = $('#cheque_no').val();
            var reason = $('#reason').val();
            var index = $('#index').val();


            if (reason.length > 0) {
                $('.refresh').addClass('panel-refreshing');
                $.post('change-status', {
                    status: 3,
                    cheque_no: cheque_no,
                    remark: reason
                }, function (data) {
                    if (data == 1) {
                        window.location.reload();
                        //$('#'+index).find('td.status_col').html('<span class="badge" style="background:#8b130f"><i class="fa fa-retweet"></i> Bounced</span>')
                    }
                    //$('.fade').modal('hide');
                    //$('.refresh').removeClass('panel-refreshing');
                });
            } else {
                showToast('warning', 'Failed', 'Reason is Required..');
            }
        }

        function addInvoice(order_no, order_id, index) {
            console.log(order_id);

            $('.fade').modal('show');
            $('.po-model').html(tpl_invoice_no.render({
                orderNo: order_no
            }));

            $('#save').click(function () {
                var invoiceNo = $('#invoice_no').val().trim();
                var vipNo = $('#vip_no').val().trim();
                if (invoiceNo.length > 0 && vipNo.length) {
                    $.post('edit/invoice_no', {
                        order_id: order_id,
                        invoice_no: invoiceNo,
                        vip_no: vipNo
                    }, function (data) {
                        $('.fade').modal('hide');
                        console.log(data);
                        window.location.reload();
//                        if (data !== 0) {
//                            $('#' + index).html(tpl_invoice_row.render({
//                                data: data,
//                                i: index
//                            }));
//                            showToast('success', 'Success', 'Invoice Number Added Success..');
//                            removeRoadd.blade.phpw();
//                        } else {
//                            showToast('warning', 'Failed', 'Invalid PO');
//                        }
                    });
                } else {
                    showToast('warning', 'Failed', 'Can\'t save without entering value..');
                }


            });

        }

        function showToast(type, heading, text) {
            $.toast({
                heading: heading,
                text: text,
                icon: type,
                loader: true,
                position: {top: '130px', bottom: '-', left: '-', right: '10px'},
                allowToastClose: false,
                showHideTransition: 'slide',// Change it to false to disable loader
                loaderBg: '#9EC600'  // To change the background
            });
        }

        function changeStatus(index, status, cheque_no, receipt) {
            //          console.log($('#'+index).find('td.status_col').html());
//            $('#'+index).find('status_col').html('asda');
            $('.refresh').addClass('panel-refreshing');
            //setTimeout(function () {
            if (status == 1) {
                $.post('change-status', {
                    status: 2,
                    cheque_no: cheque_no
                }, function (data) {
                    if (data == 1) {
                        $('#' + index).find('td.status_col').html('<span class="badge" style="background:#9369cc"><i class="fa fa-check-circle"></i> Received</span>')
                    }
                    $('.refresh').removeClass('panel-refreshing');
                });
            } else {
                rejectPO(index, receipt, cheque_no);

                /*$.post('change-status', {
                 status: 3,
                 cheque_no:cheque_no
                 }, function (data) {
                 if(data == 1){
                 $('#'+index).find('td.status_col').html('<span class="badge" style="background:#8b130f"><i class="fa fa-retweet"></i> Bounced</span>')
                 }
                 $('.refresh').removeClass('panel-refreshing');
                 });*/
            }

            // }, 3000);

        }

    </script>
@stop
