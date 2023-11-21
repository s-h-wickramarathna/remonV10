@extends('layouts.master') @section('title','PO List')
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

        .box-list {
            min-height: 500px;
            max-height: 500px;
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

        .panel-head {
            color: #fff;
            background: #cacaca;
            background-color: #cacaca;
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

        a:visited, a:active, a:focus, a:hover {
            color: inherit;
            outline: 0;
            text-decoration: none;
            cursor: pointer;
            color: #fff;
        }

        a:hover {
            color: inherit;
            outline: 0;
            text-decoration: none;
            cursor: pointer;
            color: #000;
        }

    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Invoice Management</a>
        </li>
        <li class="active">Order List</li>
    </ol>
    <div class="col ">
        <div class="col-xs-6 refresh">
            <div class="box box-widget box-list box-primary">
                <div class="box-body" style="max-height: 500px;min-height: 500px;overflow-y: scroll">
                    <table class="table" id="orderTable">
                        <thead>
                        <tr>
                            <td width="10%">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a onclick="unCheckAll();">None</a></li>
                                        <li><a onclick="checkAll();">All Green</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td width="15%">Order ID</td>
                            <td width="30%">Amount</td>
                            <td width="35%">Date</td>
                            <td width="2%" colspan="3">
                                <ul class="pager">
                                    <li><a onclick="toPrint();">Print</a></li>
                                </ul>
                            </td>
                        </tr>
                        </thead>
                        <tbody id="orderTableBody">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>



    <div class="col">
        <div class="col-xs-6 orderView">
            {{--<div class="row">
                <div class="col-md-6">

                    <div class="box box-widget widget-user">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-aqua-active">
                            <h3 class="widget-user-username">Alexander Pierce</h3>
                            <h5 class="widget-user-desc">Founder &amp; CEO</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" style="width: 60px;height: 60px;" src="{{asset('assets/images/avatar.jpg')}}" alt="User Avatar">
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">3,200</h5>
                                        <span class="description-text">SALES</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">13,000</h5>
                                        <span class="description-text">FOLLOWERS</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">35</h5>
                                        <span class="description-text">PRODUCTS</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div><!-- Widget: user widget style 1 -->

                    <!-- /.widget-user -->

                </div>
            </div>--}}
        </div>
    </div>
    <div class='notifications top-right' style="color: red"></div>
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
        var tpl_row_green = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_row_green')}}'});
        var tpl_row_red = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_row_red')}}'});
        var tpl_row_no_record = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_row_no_record')}}'});
        var tpl_order = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_order')}}'});
        $(document).ready(function () {
            window.App = {
                orderList: [],
                tempAr: [],
                redList: [],
                greenList: []
            };


            /*$('.top-right').notify({
             message: { text: 'Aw yeah, It works!' }
             }).show();*/

            $.get('json/list', function (data) {
                if (data.greenList.length == 0 && data.redList.length == 0) {
                    $('#orderTableBody').append(tpl_row_no_record.render());
                }
                for (var i = 0; i < data.greenList.length; i++) {
                    $('#orderTableBody').append(tpl_row_green.render({
                        row: (i + 1),
                        rowID: data.greenList[i].id,
                        orderID: data.greenList[i].manual_id,
                        amount: formatCurrency(data.greenList[i].total - data.greenList[i].discount),
                        date: data.greenList[i].created_date,
                    }));
                    window.App.tempAr.push(data.greenList[i].id);
                    window.App.greenList.push(data.greenList[i].id);
                }
                for (var i = 0; i < data.redList.length; i++) {
                    $('#orderTableBody').append(tpl_row_red.render({
                        row: (i + 1),
                        rowID: data.redList[i].id,
                        orderID: data.redList[i].manual_id,
                        amount: formatCurrency(data.redList[i].total - data.redList[i].discount) ,
                        date: data.redList[i].created_date,
                    }));
                    window.App.tempAr.push(data.redList[i].id);
                    window.App.redList.push(data.redList[i].id);

                }
                $('.refresh').removeClass('panel-refreshing');
            });
            $('.refresh').addClass('panel-refreshing');
        });

        function checkAll() {
            $('.checkGreen').prop('checked', true);
            window.App.orderList = window.App.greenList;
        }

        function unCheckAll() {
            $('.checkGreen').prop('checked', false);
            window.App.orderList = [];
        }

        function viewDetail(orderId) {
            $('.refresh').addClass('panel-refreshing');
            $.get('get/' + orderId, function (data) {
                $('.orderView').html(tpl_order.render({
                    orderNo: data[0].manual_id.toUpperCase(),
                    date: data[0].created_date,
                    outlet: data[0].location.name,
                    address: data[0].location.outlet[0].outlet_address,
                    rep: data[0].employee.name,
                    data: data[0].details,
                    discount: data[0].discount.length > 0 ? data[0].discount[0].discount : 0
                }));
                $('.refresh').removeClass('panel-refreshing');
            });
        }

        function discardOrder(orderId) {
            $('.refresh').addClass('panel-refreshing');
            $.get('discard/' + orderId, function (data) {
                showToast('success', 'Information', 'Discard success...');
                window.location.reload();
            });
        }

        function changeCheck(id) {
            var isCheck = $('#' + id).is(':checked');
            if (isCheck) {
                window.App.orderList.push(id);
            } else {
                var index = window.App.orderList.indexOf(id);
                if (index > -1) {
                    window.App.orderList.splice(index, 1);
                }
            }
        }

        function toPrint() {
            console.log(window.App.orderList);
            if (window.App.orderList.length > 0) {
                $('.refresh').addClass('panel-refreshing');
                $.post('{{url("invoice/add")}}', {orderList: window.App.orderList}, function (data) {
                    var url = "{{url('invoice')}}" + "/print?ids=" + data;
                    window.open(url, "print", "location=1,status=1,scrollbars=1, width=800,height=800");
                    window.location.reload();
                });
            } else {
                if (window.App.greenList.length == 0) {
                    showToast('info', 'Information', 'No more set-off applicable orders...');
                } else {
                    if (window.App.redList.length == 0) {
                        showToast('info', 'Information', 'No more set-off applicable orders...');
                    } else {
                        showToast('info', 'Information', 'Please select the order one or more...');
                    }
                }
            }
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

        function formatCurrency(str) {
            var n = str.toFixed(2).toString().split(".");
            n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return n.join(".");
        }

    </script>
@stop
