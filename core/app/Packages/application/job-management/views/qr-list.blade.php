@extends('layouts.master') @section('title','QR List')
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
        <li class="active">QR List</li>
        <div class="pull-right"><a href="{{url('job/qr/print/'.$job->id)}}" data-placement="top"><i
                        class="fa fa-download"></i> download all</a></div>
    </ol>

    <div class="col refresh">
        <div class="col-md-12">
            <ul class="cards">
                @if($job->qr_1)
                    <li class="cards__item col-md-2 col-lg-2 col-lx-2">

                        <div class="card">
                            <div class="card__image card__image--fence"><a class="block text-center relative"
                                                                           href="data:image/png;base64,{!! $barcode->generate($job->qr_1,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG') !!}"
                                                                           download="{{$job->qr_1.'.png'}}">
                                     <img style="padding: 10px"  src="data:image/png;base64, {!! $barcode->generate($job->qr_1,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG') !!} " alt="barcode">
                                    <?php /*echo '<img src="data:image/png;base64,' . $code . '">'*/?>
                                </a>
                            </div>
                            <div class="card__content">
                                <div class="card__title" style="text-align: center">
                                    <a href="{{url('job/print/'.$job->id)}}"
                                       target="_blank" data-toggle="tooltip"
                                       data-placement="top"><span style="text-align: center"> {{$job->qr_1}}</span></a>
                                </div>
                                <div class="card__text">
                                    <div class="col-md-12">
                                        <a style="width: 100%;margin-top: 10px" class="btn btn-info"
                                           href="data:image/png;base64,{!! $barcode->generate($job->qr_1,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!}"
                                           download="{{$job->qr_1.'.png'}}">download</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>
                @endif
                @if($job->qr_2)
                    <li class="cards__item col-md-2 col-lg-2 col-lx-2">
                        <div class="card">
                            <div class="card__image card__image--fence"><a class="block text-center relative"
                                                                           href="data:image/png;base64,{!! $barcode->generate($job->qr_2,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!}"
                                                                           download="{{$job->qr_2.'.png'}}">
                                    <img style="padding: 10px" src="data:image/png;base64, {!! $barcode->generate($job->qr_2,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!} ">

                                </a>
                            </div>
                            <div class="card__content">
                                <div class="card__title" style="text-align: center">
                                    <a href="{{url('job/print/'.$job->id)}}"
                                       target="_blank" data-toggle="tooltip" style="text-align: center"
                                       data-placement="top"><span style="text-align: center"> {{$job->qr_2}}</span></a>
                                </div>
                                <div class="card__text">
                                    <div class="col-md-12">
                                        <a style="width: 100%;margin-top: 10px" class="btn btn-info"
                                           href="data:image/png;base64,{!! $barcode->generate($job->qr_2,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!}"
                                           download="{{$job->qr_2.'.png'}}">download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
                @if($job->qr_3)
                    <li class="cards__item col-md-2 col-lg-2 col-lx-2">
                        <div class="card">
                            <div class="card__image card__image--fence"><a class="block text-center relative"
                                                                           href="data:image/png;base64,{!! $barcode->generate($job->qr_3,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!}"
                                                                           download="{{$job->qr_3.'.png'}}">
                                    <img style="padding: 10px" src="data:image/png;base64, {!! $barcode->generate($job->qr_3,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!} ">
                                </a>
                            </div>
                            <div class="card__content">
                                <div class="card__title" style="text-align: center">
                                    <a href="{{url('job/print/'.$job->id)}}"
                                       target="_blank" data-toggle="tooltip" style="text-align: center"
                                       data-placement="top"><span style="text-align: center"> {{$job->qr_3}}</span></a>
                                </div>
                                <div class="card__text">
                                    <div class="col-md-12">
                                        <a style="width: 100%;margin-top: 10px" class="btn btn-info"
                                           href="data:image/png;base64,{!! $barcode->generate($job->qr_3,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!}"
                                           download="{{$job->qr_3.'.png'}}">download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
                @if($job->qr_4)
                    <li class="cards__item col-md-2 col-lg-2 col-lx-2">
                        <div class="card">
                            <div class="card__image card__image--fence"><a class="block text-center relative"
                                                                           href="data:image/png;base64,{!! $barcode->generate($job->qr_4,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!}"
                                                                           download="{{$job->qr_4.'.png'}}">
                                    <img style="padding: 10px" src="data:image/png;base64, {!! $barcode->generate($job->qr_4,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!} ">
                                </a>
                            </div>
                            <div class="card__content">
                                <div class="card__title" style="text-align: center">
                                    <a href="{{url('job/print/'.$job->id)}}"
                                       target="_blank" data-toggle="tooltip" style="text-align: center"
                                       data-placement="top"><span style="text-align: center"> {{$job->qr_4}}</span></a>
                                </div>
                                <div class="card__text">
                                    <div class="col-md-12">
                                        <a style="width: 100%;margin-top: 10px" class="btn btn-info"
                                           href="data:image/png;base64,{!! $barcode->generate($job->qr_4,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!}"
                                           download="{{$job->qr_4.'.png'}}">download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
                @if($job->qr_5)
                    <li class="cards__item col-md-2 col-lg-2 col-lx-2">
                        <div class="card">
                            <div class="card__image card__image--fence"><a class="block text-center relative"
                                                                           href="data:image/png;base64,{!! $barcode->generate($job->qr_5,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!}"
                                                                           download="{{$job->qr_5.'.png'}}">
                                    <img style="padding: 10px" src="data:image/png;base64, {!! $barcode->generate($job->qr_5,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!} ">
                                </a>
                            </div>
                            <div class="card__content">
                                <div class="card__title" style="text-align: center">
                                    <a href="{{url('job/print/'.$job->id)}}"
                                       target="_blank" data-toggle="tooltip" align="center"
                                       data-placement="top"><span> {{$job->qr_5}}</span></a>
                                </div>
                                <div class="card__text">
                                    <div class="col-md-12">
                                        <a style="width: 100%;margin-top: 10px" class="btn btn-info"
                                           href="data:image/png;base64,{!! $barcode->generate($job->qr_5,\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128,'PNG')!!}"
                                           download="{{$job->qr_5.'.png'}}">download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
            </ul>
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
