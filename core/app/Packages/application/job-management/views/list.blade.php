@extends('layouts.master') @section('title','Job List')
@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/vendor/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/vendor/jquery-toast-plugin-master/dist/jquery.toast.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/mgonto-angular-wizard/angular-wizard.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/assets/ag_grid/themes/theme-material.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/assets/css/build.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/card.less')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/styles/card_date.scss')}}">
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

        .date {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #77d7b9;
            color: #fff;
            padding: 0.8em;
            z-index: 2;
            box-shadow: 0 20px 40px -14px rgba(0, 0, 0, 0.25);
        }

        .date > span {
            display: block;
            text-align: center;
        }

        .job_day {
            font-weight: 700;
            font-size: 24px;
        }

        .job_month {
            text-transform: uppercase;
        }

        .job_month,
        .job_year {
            font-size: 12px;
        }

    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:">Job Management</a>
        </li>
        <li class="active">Job List</li>
    </ol>
    <div class="col">
        <div class="col-xs-12">
            <div class="box box-widget box-list box-primary">
                <div class="box-body">
                    <form role="form" method="get" action="{{url('job/new/search')}}">
                        <div class="form-group" style="padding-left: 10px;padding-top: 10px;padding-bottom: 10px">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4 col-sm-offset-4">
                                        <div class="input-group">
                                            <input type="text" autocomplete="off" class="form-control" autofocus
                                                   name="qr"
                                                   id="qr"
                                                   placeholder="search by job no, ex :- 00001" value=""/>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit"><i
                                                            class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-sm-offset-4" style="text-align: center">
                                        <h3>Search Now</h3>
                                        <p>you can search manual job no OR qr code</p>
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
        <div class="col-md-12">
            <ul class="cards">
                <?php $i = 1;?>

                @foreach($orders as $order)
                    <li class="cards__item col-md-3 col-lg-3 col-lx-2">

                        <div class="card">
                            <div class="date">
                                <span class="job_day">{{date_format($order->created_at,'d')}}</span>
                                <span class="job_month">{{date_format($order->created_at,'M')}}</span>
                                <span class="job_year">{{date_format($order->created_at,'Y')}}</span>
                            </div>

                            <div class="card__image card__image--fence"><a target="_blank"
                                                                           @if($user->id == 1 || trim($user->roles[0]->name) != 'customer') href="@if(strlen($order->original_img) > 0){{url($order->original_img)}} @else # @endif" @endif
                                                                           class="block text-center relative"> <img
                                            style="min-width:100% ; max-height: 200px;height: 200px;min-height: 200px"
                                            src="@if(strlen($order->img_url) > 0){{url($order->img_url)}} @else {{asset('assets/images/no_image.png')}} @endif"/></a>
                            </div>
                            <div class="card__content">
                                <div class="card__title">
                                    <a href="{{url('job/print/'.$order->id)}}"
                                       target="_blank" data-toggle="tooltip"
                                       data-placement="top"><span> {{$order->job_no}}</span></a>
                                    <a @if($user->id == 1 || trim($user->roles[0]->name) != 'customer') href="{{url('job/qr/list/'.$order->id)}}" @endif target="_blank" title="print QR" class="pull-right"><span class="fa fa-qrcode fa-lg"></span></a>
                                </div>
                                <div class="card__text">
                                    <div class="col-md-12"
                                         style="text-transform: uppercase;padding-left: 0px;font-size: 12px;font-weight: bold;letter-spacing: 2px">
                                        Customer
                                    </div>
                                    <div class="col-md-12" style="padding-left: 0px;">
                                        {{$order->customer->f_name.' '.$order->customer->l_name}}
                                    </div>
                                    <div class="col-md-12"
                                         style="text-transform: uppercase;padding-left: 0px;font-size: 12px;font-weight: bold;letter-spacing: 2px">
                                        Marketeer
                                    </div>

                                    <div class="col-md-12" style="padding-left: 0px;">
                                        {{$order->customer->marketeer->first_name .' '.$order->customer->marketeer->last_name}}
                                    </div>

                                    <div class="col-md-6"
                                         style="text-transform: uppercase;padding-left: 0px;font-size: 12px;font-weight: bold;letter-spacing: 2px">
                                        Due Date
                                    </div>
                                    <div class="col-md-6 pull-right"
                                         style="text-transform: uppercase;padding-left: 0px;font-size: 12px;font-weight: bold;letter-spacing: 2px;padding-right: 0px;text-align: right;">
                                        Section
                                    </div>
                                    <div class="col-md-6 " style="padding-left: 0px;">
                                        {{$order->due_date}}
                                    </div>
                                    <div class="col-md-6 "
                                         style="padding-left: 0px;;padding-right: 0px;text-align: right;">
                                        {{$order->section}}
                                    </div>

                                    <div class="col-md-8" style="padding-left: 0px;">
                                        @if(strlen($order->ended_at) == 0)
                                            <span class="badge" style="border-radius: 0px">{{$order->confirm_type ? $order->confirm_type : 'JOB CREATOR'}}
                                                Confirmed</span>
                                        @else
                                            <span class="badge btn-info" style="border-radius: 0px;">Done</span>
                                        @endif
                                        {{--<div class="col-md-12"
                                             style="text-transform: uppercase;padding-left: 0px;font-size: 12px;font-weight: bold;letter-spacing: 2px">
                                            Date
                                        </div>--}}
                                        {{--<div class="col-md-12" style="padding-left: 0px;">
                                            {{$order->created_at}}
                                        </div>--}}
                                    </div>

                                    @if($user->hasAnyAccess(['job.change']))
                                        <div class="@if(strlen($order->ended_at) == 0)col-md-2 @else col-md-4 @endif"
                                             align="right" style="padding-right: 0px">
                                            <a data-toggle="tooltip" data-placement="top"
                                               href="{{url('job/edit/'.$order->id)}}"
                                               title="Edit Job" style="padding-left: 2px;cursor: hand">
                                                <span class="badge btn-warning " style="border-radius: 0px">
                                                    <i class="fa fa-pencil"></i>
                                                </span>
                                            </a>
                                        </div>
                                    @endif
                                    @if(strlen($order->ended_at) == 0 && $user->hasAnyAccess(['job.confirm']))
                                        <div class="@if($user->hasAnyAccess(['job.change']))col-md-2 @else col-md-4 @endif"
                                             align="right" style="padding-right: 0px">
                                            <a data-toggle="tooltip" data-placement="top"
                                               href="{{url('job/confirm/'.$order->id)}}"
                                               title="Confirmation" style="padding-left: 2px;cursor: hand">
                                                <span class="badge btn-danger" style="border-radius: 0px">
                                                    <i class="fa fa-check"></i>
                                                </span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php $i++;?>
                @endforeach
            </ul>
            @if(count($orders) > 0)
                {!! $orders->render() !!}
            @else
                {{ 'Empty' }}
            @endif
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

            var approve = $('select[name="status"]');
            approve.change(function (e) {
                $('.refresh').addClass('panel-refreshing');
                $(this).closest('form').trigger('submit');
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
                /*var invoiceNo = $('#invoice_no').val().trim();
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
                }*/


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
