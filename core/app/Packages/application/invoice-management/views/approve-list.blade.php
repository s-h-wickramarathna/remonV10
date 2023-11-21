@extends('layouts.master') @section('title','Pending List')
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
            <a href="javascript:">Invoice Management</a>
        </li>
        <li class="active">Pending List</li>
    </ol>
    <div class="col">
        <div class="col-xs-12">
            <div class="box box-widget box-list box-primary">
                <div class="box-body">
                    <form role="form" method="get">
                        <div class="form-group" style="padding-left: 10px;padding-top: 10px;padding-bottom: 10px">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" autocomplete="off" class="form-control" style="width: 400px;"
                                               name="invoice_no"
                                               id="invoice_no"
                                               placeholder="search.." value="{{$invoice_no}}"/>
                                        <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                    </div>
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
                                    <select class="chosen" name="status">
                                        <option value="0" @if($status == 0 ) selected @endif>Pending</option>
                                        <option value="1" @if($status == 1 ) selected @endif>Approved</option>
                                        <option value="-1" @if($status == -1 ) selected @endif>Reject</option>
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
                                        <a href="pending-approve" class="btn btn-warning" data-toggle="tooltip"
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
                        <div class="row" style="margin-left: 0px;margin-right: 0px">
                            <table class="table table-bordered bordered table-striped table-condensed"
                                   id="orderTable">
                                <thead style="background-color:rgba(52, 73, 94,0.5);color:#fff;">
                                <tr>
                                    <td width="5%">
                                        #
                                    </td>
                                    <td width="10%">Job No</td>
                                    <td width="10%">Amount</td>
                                    <td width="12%">Date</td>
                                    <td width="19%">Customer</td>
                                    <td width="10%">Status</td>
                                    <td width="12%">Action
                                        {{--<ul class="pager">
                                            <li><a onclick="" class="fa fa-arrow-circle-o-down"> Excel</a></li>
                                        </ul>--}}
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;?>
                                @foreach($orders as $order)
                                    <tr id="{{$i}}">
                                        <td>{{$i}}</td>
                                        <td>{{$order->job_no}}
                                        </td>
                                        <td style="text-align: right">
                                            Rs.{{number_format($order->amount,2)}}</td>
                                        <td>{{$order->created_at}}</td>
                                        <td>{{$order->customer->f_name}} {{$order->customer->l_name}}</td>
                                        <td>
                                            @if($order->status == 0)
                                                <span class="badge" style="background:#fca600"><i
                                                            class="fa fa-clock-o fa-spin"></i> Pending</span>
                                            @elseif($order->status == 1)
                                                <span class="badge" style="background:#0081c2"><i
                                                            class="fa fa-check-circle"></i> Approved</span>
                                            @else
                                                <span class="badge" style="background:#E12222"><i
                                                            class="fa fa-ban"></i> Rejected</span>
                                            @endif
                                        </td>

                                        <td style="text-align: center">
                                            @if($order->status == 0)
                                                <a href="{{url('invoice/approve?id='.$order->id)}}"
                                                   data-toggle="tooltip" data-placement="top"
                                                   title="Approve" style="padding-left: 2px;cursor: hand">
            <span class="badge" style="background:#ffa726">
                <i class="fa fa-check"></i>
            </span>
                                                </a>

                                                <a href="{{url('invoice/reject?id='.$order->id)}}" data-toggle="tooltip"
                                                   data-placement="top"
                                                   title="Reject" style="padding-left: 2px;cursor: hand">
            <span class="badge" style="background:#7b261c">
                <i class="fa fa-ban"></i>
            </span>
                                                    @endif
                                                </a>


                                                {{--<a href="done/{{$order->id}}" data-toggle="tooltip" data-placement="top"
                                                   title="Job Done" style="padding-left: 2px;cursor: hand">
                                                    <span class="badge" style="background:#0081c2">
                                                        <i class="fa fa-check-circle fa-lg"></i>
                                                    </span>
                                                </a>
                                                <a href="delivered/{{$order->id}}" data-toggle="tooltip" data-placement="top"
                                                   title="Package Delivered" style="padding-left: 2px;cursor: hand">
                                                    <span class="badge" style="background:#E12222">
                                                        <i class="fa fa-truck"></i>
                                                    </span>
                                                </a>--}}
                                        </td>


                                    </tr>
                                    <?php $i++;?>
                                @endforeach
                                </tbody>
                            </table>
                            @if(count($orders) > 0)
                                {!! $orders->render() !!}
                            @else
                                {{ 'Empty' }}
                            @endif
                        </div>
                    </div>
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

        function deleteInvoice(orderId) {
            sweetAlertConfirm('Confirm', 'Are you sure delete this invoice..?', 3, function (e) {
                if (e) {
                    console.log(e);
                    //$('.refresh').addClass('panel-refreshing');
                    $.post('delete', {
                        order_id: orderId
                    }, function (data) {
                        if (data == 1) {
                            sweetAlert('Success', 'Delete success..', 1);
                            window.location.reload();
                        } else if (data == 0) {
                            sweetAlert('Failed', 'Delete failed..', 2);
                            //$('.refresh').removeClass('panel-refreshing');
                        } else {
                            sweetAlert('Warning', 'This invoice has payment..', 3);
                        }

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
                }
            })
        }

    </script>
@stop
