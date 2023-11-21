@extends('layouts.master') @section('title','Monthly Collection')
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

        .inv_link {
            color: #0081c2;
            text-decoration: underline;
            font-style: italic;
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
        <li class="active">Monthly Collection</li>
    </ol>
    <div class="col">
        <div class="col-xs-12">
            <div class="box box-widget box-list box-primary">
                <div class="box-body">
                    <form role="form" method="get" action="{{url('payment/report/monthly')}}">
                        {{--<div class="form-group" style="padding-left: 10px;padding-top: 10px;padding-bottom: 10px">
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="checkbox" name="filter-checkbox" data-on-text="Marketeer Vice"
                                           data-off-text="Cashier Vice" checked>
                                    --}}{{--<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Active Disabled - Permission Deaned"><input class="menu-activate" type="checkbox" value="1"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>--}}{{--
                                </div>
                            </div>
                        </div>--}}
                        <div class="form-group" style="padding-left: 10px;padding-top: 10px;padding-bottom: 10px">
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="checkbox" name="filter-checkbox" data-on-text="Cashier"
                                           data-off-text="Marketeer" @if($filter_checkbox == 'on') checked @endif >
                                    {{--<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Active Disabled - Permission Deaned"><input class="menu-activate" type="checkbox" value="1"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>--}}
                                </div>

                                <div class="col-md-3 ma_div" @if($filter_checkbox == 'on') hidden @endif>
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
                                <div class="col-sm-3 ca_div" @if($filter_checkbox != 'on') hidden @endif>
                                    <select class="chosen" name="cashier">
                                        <option value="0">-- Create by --
                                        </option>
                                        <?php $i = 0;?>
                                        @foreach($cashierList as $cashierObj)
                                            <option value="{{$cashierObj->employee_->id}}"
                                                    @if($cashierObj->employee_->id == $cashier ) selected @endif>{{$cashierObj->employee_->first_name .' '.$cashierObj->employee_->last_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 input-daterange">
                                    <input type="text" autocomplete="off" class="form-control" name="from"
                                           id="from"
                                           placeholder="From Date" value="{{$from}}"/>
                                </div>
                                <div class="col-md-2">
                                    <div class="pull-left">
                                        <button type="submit" class="btn btn-info" id="plan"><i class="fa fa-search"
                                                                                                style="padding-right: 16px;width: 28px;"></i>Find
                                        </button>
                                    </div>
                                    <div class="pull-right" style="padding-right: 10px">
                                        <a href="monthly" class="btn btn-warning" data-toggle="tooltip"
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
                            <form role="form" method="get" action="{{url('payment/report/monthly/download')}}">
                                <div class="col-md-1 pull-right">
                                    <div class="pull-right"
                                         style="padding-right: 10px;padding-top: 10px;padding-left: 10px">
                                        <input type="hidden" name="marketeer" value="{{$marketeer}}">
                                        <input type="hidden" name="cashier" value="{{$cashier}}">
                                        <input type="hidden" name="filter_checkbox" value="{{$filter_checkbox}}">
                                        <input type="hidden" name="from" value="{{$from}}">
                                        <button type="submit" class="btn btn-info">PDF <i class="fa fa-download"></i>
                                        </button>
                                        {{--<button class="btn btn-warning" ><i class="fa fa-refresh"></i></button>--}}
                                    </div>
                                </div>
                            </form>
                            <form role="form" method="get" action="{{url('payment/report/monthly/excel')}}">
                                <div class="col-md-1 pull-right">
                                    <div class="pull-right"
                                         style="padding-right: 10px;padding-top: 10px;padding-left: 10px">
                                        <input type="hidden" name="marketeer" value="{{$marketeer}}">
                                        <input type="hidden" name="cashier" value="{{$cashier}}">
                                        <input type="hidden" name="filter_checkbox" value="{{$filter_checkbox}}">
                                        <input type="hidden" name="from" value="{{$from}}">
                                        <button type="submit" class="btn btn-warning">Excel <i
                                                    class="fa fa-download"></i>
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
                                    <td align="center" width="5%">
                                        #
                                    </td>
                                    <td width="30%">Customer</td>
                                    <td width="15%">Date</td>
                                    <td width="15%">Receipt</td>
                                    <td width="15%">Amount</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;?>
                                @foreach($orders as $order)
                                    <tr id="{{$i}}">
                                        <td align="center">{{$i}}</td>
                                        <td>{{$order->customer->f_name.' '.$order->customer->l_name}}</td>
                                        <td>{{$order->recipt_date}}</td>
                                        <td><a href="{{url('payment/receipt/print/'.$order->id)}}" class="inv_link"
                                               target="_blank" data-toggle="tooltip" data-placement="top"><span><i
                                                            class="fa fa-thumb-tack"></i> {{$order->recipt_no}}</span></a>
                                        </td>
                                        <td style="text-align: right">{{number_format($order->amount,2)}}</td>
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
                format: "yyyy-mm",
                daysOfWeekHighlighted: "0,6",
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true,
                minViewMode: "months"
            });

            $("[name='filter-checkbox']").bootstrapSwitch();

            /*$('.input-month').datepicker({
                format: "MM",
                daysOfWeekHighlighted: "0,6",
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true,
                minViewMode: "months"
            });*/


            $('input[name="filter-checkbox"]').on('switchChange.bootstrapSwitch', function (event, state) {
                if (state) {
                    $('.ma_div').hide();
                    $('.ca_div').show();
                } else {
                    $('.ca_div').hide();
                    $('.ma_div').show();
                }
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
