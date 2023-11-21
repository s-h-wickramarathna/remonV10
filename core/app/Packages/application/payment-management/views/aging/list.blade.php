@extends('layouts.master') @section('title','Aging List')
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
            <a href="javascript:">Reports</a>
        </li>
        <li class="active">Customer Receivables Aging - Details</li>
    </ol>
    <div class="col">
        <div class="col-xs-12">
            <div class="box box-widget box-list box-primary">
                <div class="box-body">
                    <form role="form" method="get" action="{{url('payment/aging')}}">
                        <div class="form-group" style="padding-left: 10px;padding-top:10px;padding-bottom: 10px">
                            <div class="row">
                                <div class="col-md-3 pull-left">
                                    <select class="chosen" name="marketeer">
                                        <option value="0">-- Search Marketeer --
                                        </option>
                                        <?php $i = 0;?>
                                        @foreach($marketeerList as $marketeerObj)
                                            <option value="{{$marketeerObj->id}}"
                                                    @if($marketeerObj->id == $marketeer ) selected @endif>{{$marketeerObj->first_name .' '.$marketeerObj->last_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="padding-top: 8px">
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
                                        <a href="aging" class="btn btn-warning" data-toggle="tooltip"
                                           data-placement="top"><i class="fa fa-refresh"></i></a>
                                        {{--<button class="btn btn-warning" ><i class="fa fa-refresh"></i></button>--}}
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
                            <div class="col-md-1 pull-right">
                                <form role="form" method="get" action="{{url('payment/aging/download')}}">
                                    <div class="pull-right"
                                         style="padding-right: 10px;padding-top: 10px;padding-left: 10px">
                                        <input type="hidden" name="marketeer" value="{{$marketeer}}">
                                        <input type="hidden" name="customer" value="{{$customer}}">
                                        <input type="hidden" name="from" value="{{$from}}">
                                        <input type="hidden" name="to" value="{{$to}}">
                                        <input type="hidden" name="area" value="{{$area}}">
                                        <button type="submit" class="btn btn-info">PDF <i class="fa fa-download"></i>
                                        </button>
                                        {{--<button class="btn btn-warning" ><i class="fa fa-refresh"></i></button>--}}
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-1 pull-right">
                                <form role="form" method="get" action="{{url('payment/aging/excel')}}">
                                    <div class="pull-right"
                                         style="padding-right: 10px;padding-top: 10px;padding-left: 10px">
                                        <input type="hidden" name="marketeer" value="{{$marketeer}}">
                                        <input type="hidden" name="customer" value="{{$customer}}">
                                        <input type="hidden" name="from" value="{{$from}}">
                                        <input type="hidden" name="to" value="{{$to}}">
                                        <input type="hidden" name="area" value="{{$area}}">
                                        <button type="submit" class="btn btn-warning">EXCEL <i class="fa fa-download"></i>
                                        </button>
                                        {{--<button class="btn btn-warning" ><i class="fa fa-refresh"></i></button>--}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <table class="table table-bordered bordered table-striped"
                                   id="orderTable">
                                <thead style="background-color:rgba(52, 73, 94,0.5);color:#fff;">
                                <tr>
                                    <td width="15%">Customer</td>
                                    <td width="10%">Date</td>
                                    <td width="10%">Invoice No</td>
                                    <td align="right" width="12%">Total</td>
                                    <td align="right" width="8%">Inv Due</td>
                                    <td align="right" width="8%">No Of Days (Current Date)</td>
                                    <td align="right" width="10%">0 - 15</td>
                                    <td align="right" width="10%">16 - 30</td>
                                    <td align="right" width="9%">31 - 60</td>
                                    <td align="right" width="8%">60+</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $old_name = ' ';$total = 0;$inv_due = 0;$first = 0;$second = 0;$third = 0;$fourth = 0;$index=1;?>
                                <?php $g_total = 0;$g_inv_due = 0;$g_first = 0;$g_second = 0;$g_third = 0;$g_fourth = 0;?>
                                @foreach($orders as $key => $order)
                                    
                                    @if($order->customer_name != $old_name)
                                        @if($key > 0 && $inv_due > 0)
                                            <tr>
                                                <td style="text-align: right;font-weight: bold;" colspan="3">
                                                    Total
                                                </td>
                                                <td style="text-align: right;font-weight: bold;">{{number_format($total,2)}}</td>
                                                <td style="text-align: right;font-weight: bold;">{{number_format($inv_due,2)}}</td>
                                                <td style="text-align: right;font-weight: bold;"
                                                    colspan="2">{{number_format($first,2)}}</td>
                                                <td style="text-align: right;font-weight: bold;">{{number_format($second,2)}}</td>
                                                <td style="text-align: right;font-weight: bold;">{{number_format($third,2)}}</td>
                                                <td style="text-align: right;font-weight: bold;">{{number_format($fourth,2)}}</td>
                                            </tr>
                                            <?php $g_total += $total; ?>
                                            <?php $g_inv_due += $inv_due; ?>
                                            <?php $g_first += $first; ?>
                                            <?php $g_second += $second; ?>
                                            <?php $g_third += $third; ?>
                                            <?php $g_fourth += $fourth; ?>
                                            <?php $total = 0;$inv_due = 0;$first = 0;$second = 0;$third = 0;$fourth = 0;?>
                                        @endif
                                        <tr>
                                            <td style="font-weight: bold;padding-left: 20px"
                                                colspan="10">{{$order->customer_name}}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td></td>
                                        <td> {{$order->created_date}}</td>
                                        <td> {{$order->manual_id}} <br> {{$order->job_no}}
                                            <br> {{$order->couple_name}} </td>
                                        <td style="text-align: right">Rs.{{number_format($order->total,2)}}</td>
                                        <td style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
                                        <td style="text-align: right">{{$order->no_of_days}}</td>
                                        @if($order->no_of_days > 0 && $order->no_of_days <= 15 )
                                            <td style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <?php $first += $order->invoice_due; ?>
                                        @elseif($order->no_of_days > 15 && $order->no_of_days <= 30 )
                                            <td></td>
                                            <td style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
                                            <td></td>
                                            <td></td>
                                            <?php $second += $order->invoice_due; ?>
                                        @elseif($order->no_of_days > 30 && $order->no_of_days <= 60 )
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
                                            <td></td>
                                            <?php $third += $order->invoice_due; ?>
                                        @else
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
                                            <?php $fourth += $order->invoice_due; ?>
                                        @endif
                                    </tr>

                                    
                                    <?php $total += $order->total; ?>
                                    <?php $inv_due += $order->invoice_due; ?>
                                    <?php $old_name = $order->customer_name; ?>

                                    @if(sizeof($orders) == $index && $index > 0)
                                        <tr>
                                            <td style="text-align: right;font-weight: bold;" colspan="3">
                                                Total
                                            </td>
                                            <td style="text-align: right;font-weight: bold;">{{number_format($total,2)}}</td>
                                            <td style="text-align: right;font-weight: bold;">{{number_format($inv_due,2)}}</td>
                                            <td style="text-align: right;font-weight: bold;"
                                                colspan="2">{{number_format($first,2)}}</td>
                                            <td style="text-align: right;font-weight: bold;">{{number_format($second,2)}}</td>
                                            <td style="text-align: right;font-weight: bold;">{{number_format($third,2)}}</td>
                                            <td style="text-align: right;font-weight: bold;">{{number_format($fourth,2)}}</td>
                                        </tr>
                                        <?php $g_total += $total; ?>
                                        <?php $g_inv_due += $inv_due; ?>
                                        <?php $g_first += $first; ?>
                                        <?php $g_second += $second; ?>
                                        <?php $g_third += $third; ?>
                                        <?php $g_fourth += $fourth; ?>
                                        <?php $total = 0;$inv_due = 0;$first = 0;$second = 0;$third = 0;$fourth = 0;?>
                                    @endif
                                    <?php $index++;?>
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
        var tpl_reject = new EJS({url: '{{url('core/app/Packages/application/purchaseOrder-management/views/template/tpl_reject')}}'});
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

        function rejectPO(index, orderId) {
            $('.refresh').addClass('panel-refreshing');
            $('.fade').modal('show');
            $('.po-model').html(tpl_reject.render({
                orderNo: orderId,
                type: 4,
                index: index,
                textLabel: 'Reject Reason'

            }));
            $('.refresh').removeClass('panel-refreshing');
        }

        function saveReject() {

            var poNo = $('#poId').val();
            var type = $('#type').val();
            var reason = $('#reason').val();
            var index = $('#index').val();

            var msgType = type === 3 ? 'Hold' : 'Reject';

            if (reason.length > 0) {
                $.post('edit/po', {
                    poNo: poNo,
                    type: type,
                    reason: reason
                }, function (data) {
                    $('.fade').modal('hide');
                    console.log(data);
                    if (data !== 0) {
                        showToast('success', 'Success', 'PO ' + msgType + ' Success..');
                        removeRow(index, 1);
                    } else {
                        showToast('warning', 'Failed', 'Invalid PO..');
                    }
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

        function removeRow(index, status) {
            console.log(status);
            if (status == 1) {
                $('.refresh').addClass('panel-refreshing');
                setTimeout(function () {
                    $('#' + index).remove();
                    $('.refresh').removeClass('panel-refreshing');
                }, 3000);
            }
        }

    </script>
@stop
