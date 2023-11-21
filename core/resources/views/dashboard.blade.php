@extends('layouts.master') @section('title','Dashboard')
@section('current_title','')
@section('content')

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('assets/global/plugins/uniform/css/uniform.default.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet"
          type="text/css"/>


    <link href="{{asset('assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{asset('assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css"/>

    <style type="text/css">
        .widget {
            height: 70px;
            padding: 20px;
            box-shadow: none;
        }

        .panel-detail {
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.075);
            border-radius: 0;
            border: 0;
            margin-bottom: 15px;
            background-color: #91c957;
            border-color: #91c957;
            padding: 15px;
        }

        .panel-detail p {
            color: #fff;
        }

        .mar-no {
            margin: 0 !important;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        .text-semibold {
            font-weight: 600;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .extra-data p {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        strong {
            font-weight: 600;
        }

        .widget .widget-list .widget-list-item {
            position: relative;
            display: block;
            padding: 5px 0;
            color: #616161;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .widget .widget-list .badge {
            font-size: 14px !important;
            font-weight: 300;
            height: 18px;
            color: #fff;
            padding: 1px 6px;
            -webkit-border-radius: 12px !important;
            -moz-border-radius: 12px !important;
            border-radius: 12px !important;
            text-shadow: none !important;
            text-align: center;
        }

        .pli-first {
            font-size: 25px;
        }

        .pop-list-title {
            font-size: 12px;
            margin-bottom: 0;
            margin-top: 2px;
        }

        .pop-list-details {
            font-size: 10px;
            margin-bottom: 0;
            margin-top: 2px;
        }

        .popover {
            top: -106px;
            left: 50.5px;
            display: block;
            min-width: 250px;
        }

        .list-group-item {
            position: relative;
            display: block;
            padding: 8px 10px;
            margin-bottom: -1px;
            background-color: #fff;
            border-bottom: 1px solid #ddd !important;
            border: none;
            margin-bottom: 1px;

        }

        .popover-content {
            padding: 0;
        }

        .list-group > li:last-child {
            border: none !important;
            padding-bottom: 0px;
        }

        .layout-fixed-header .main-content {
            padding-top: 70px;
            background: #EFEFEF;
        }

        .btn-group > .btn:first-child:not(:last-child):not(.dropdown-toggle) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .btn-group > .btn:first-child {
            margin-left: 0;
        }

        .btn.btn-default, .btn.white {
            box-shadow: inset 0 0 1px rgba(0, 0, 0, .3);
        }

        .btn.rounded {
            padding-left: 1.2em;
            padding-right: 1.2em;
        }

        .btn-group-vertical > .btn, .btn-group > .btn {
            position: relative;
            float: left;
        }

        .circle, .rounded {
            border-radius: 500px;
        }

        .dark-white, .white {
            background-color: #fff;
        }

        .btn-sm {
            padding: .3445rem .75rem;
        }

        .btn {
            font-weight: 500;
            outline: 0 !important;
            border-width: 0;
            padding: .4375rem 1rem;
        }

        .btn-group-sm > .btn, .btn-sm {
            padding: .25rem .75rem;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: .2rem;
        }

        .btn {
            display: inline-block;
            padding: .375rem 1rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: 1px solid transparent;
            border-radius: .25rem;
        }

        .btn.btn-default:not([disabled]).active, .btn.btn-default:not([disabled]):focus, .btn.btn-default:not([disabled]):hover, .btn.white:not([disabled]).active, .btn.white:not([disabled]):focus, .btn.white:not([disabled]):hover {
            box-shadow: inset 0 -10rem 0 rgba(158, 158, 158, .1);
        }

        .rounded {
            border-top-left-radius: 500px !important;
            border-bottom-left-radius: 500px !important;
            border-top-right-radius: 0px !important;
            border-bottom-right-radius: 0px !important;
        }

        .rounded1 {
            border-top-right-radius: 500px !important;
            border-bottom-right-radius: 500px !important;
        }

        .rounded2 {
            border-radius: none !important;
            border-radius: none !important;
        }

        .btn.white:not(.btn-outline) {
            color: #666;
            background-color: #fff !important;
            border-color: #e6e6e6 !important;
        }

        .btn.white:not(.btn-outline).active, .btn.white:not(.btn-outline):active, .btn.white:not(.btn-outline):hover, .open > .btn.white:not(.btn-outline).dropdown-toggle {
            color: #666;
            background-color: #e6e6e6 !important;
            border-color: #e0e0e0 !important;
        }

        .btn-info {
            color: white;
            background-color: #37444e;
            border-color: #37444e;
            border-radius: 50% !important;
        }

        .btn-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            background-color: #37444e;
            -moz-opacity: 0;
            -khtml-opacity: 0;
            -webkit-opacity: 0;
            opacity: 0;
            -ms-filter: progid:DXImageTransform.Microsoft.Alpha(opacity=0 *100);
            filter: alpha(opacity=0 *100);
            -webkit-transform: scale3d(0.7, 1, 1);
            -moz-transform: scale3d(0.7, 1, 1);
            -o-transform: scale3d(0.7, 1, 1);
            -ms-transform: scale3d(0.7, 1, 1);
            transform: scale3d(0.7, 1, 1);
            -webkit-transition: transform 0.4s, opacity 0.4s;
            -moz-transition: transform 0.4s, opacity 0.4s;
            -o-transition: transform 0.4s, opacity 0.4s;
            transition: transform 0.4s, opacity 0.4s;
            -webkit-animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            -moz-animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            -o-animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 50%;
        }

    </style>

    <section>
        <div class="row" style="margin-bottom: 8px">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="text-left  pull-left">
                    <h4 style="margin:0"><strong>Dashboard</strong></h4>
                    <h6 style="margin:0" class="hidden-xs hidden-sm">Welcome to Job Automation</h6>
                </div>


                <div class="text-right pull-right" style="margin-left: 10px;">
                    <button type="button" class="btn btn-info"><i class="fa fa-cog" aria-hidden="true"></i></button>
                </div>

                {{--<div class="pull-right filters text-center m-b" >
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-sm rounded white" onclick="load_weekly()">
                      <input type="radio" name="options" id="option3">
                      <!-- <i class="fa fa-star" aria-hidden="true"></i> -->weekly
                    </label>
                    <label class="btn btn-sm white active" onclick="load_monthly()">
                      <input type="radio" name="options" id="option1">
                      <!-- <i class="fa fa-star" aria-hidden="true"></i> -->Monthly
                    </label>

                    <label class="btn btn-sm rounded1 white" onclick="load_yearly()">
                      <input type="radio" name="options" id="option2">
                      <!-- <i class="fa fa-cubes" aria-hidden="true"></i> -->Yearly
                    </label>
                  </div>
                </div>--}}
            </div>
        </div>
    </section>
    @if($user->id == 1 || trim($user->roles[0]->name) != 'customer')
        <section>
            <div class="row">

                <div class="col-xs-6 col-sm-6 col-md-2 col-lg-3">
                    <div class="widget bg-primary" style="background-color: #9369cc">
                        <div class="widget-bg-icon">
                            <i class="fa fa-bar-chart"></i>
                        </div>
                        <div class="widget-details">
                            <span class="block h4 mt0 mb5" id="target" name="target">Rs.{{$target}}</span>
                            <span>Monthly Target</span>
                            <span style="color: #9369cc">Monthly Target</span>
                        </div>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-2 col-lg-3">
                    <div class="widget bg-primary" style="background-color: #f9482c">
                        <div class="widget-bg-icon">
                            <i class="fa fa-paper-plane"></i>
                        </div>
                        <div class="widget-details">
                            <span class="block h4 mt0 mb5" id="achievement"
                                  name="achievement">Rs.{{$invoice_total}}</span>
                            <span>Invoice Amount</span>
                            <span>( - Discount <span id="discount" name="discount"></span>)</span>
                        </div>
                    </div>
                </div>

                {{--<div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
                  <div class="widget bg-primary" style="background-color: #2fb2b3">
                    <div class="widget-bg-icon">
                      <i class="fa fa-tags"></i>
                    </div>
                    <div class="widget-details">
                      <span class="block h4 mt0 mb5" id="delivered" name="delivered"></span>
                      <span>Delivery Amount<strong id="delivered_count" name="delivered_count"></strong></span>
                    </div>
                  </div>
                </div>--}}

                <div class="col-xs-6 col-sm-6 col-md-2 col-lg-3">
                    <div class="widget bg-primary" style="background-color: #fa2164">
                        <div class="widget-bg-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="widget-details">
                            <span class="block h4 mt0 mb5" id="invoice" name="invoice">{{$invoice_count}}</span>
                            <span>Invoice Count</span>
                            <span style="color: #fa2164">Invoice Count</span>
                        </div>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-2 col-lg-3">
                    <div class="widget bg-primary" style="background-color: #fca600">
                        <div class="widget-bg-icon">
                            <i class="fa fa-file-text"></i>
                        </div>
                        <div class="widget-details">
                            <span class="block h4 mt0 mb5" id="free" name="free">{{$job_count}}</span>
                            <span>Job Count</span>
                            <span style="color: #fca600">Invoice</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row" style="margin-bottom: 30px">

                {{--<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="widget bg-white">
                        <div class="widget-details widget-list">
                          <div class="mb20">
                            <h4 class="no-margin text-uppercase"><strong>Today Summary</strong></h4>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>

                </div>--}}

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="bg-white loadable">
                        <div id="container" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>

                </div>

            </div>

        </section>
    @endif

@stop
@section('js')
    <script src="{{asset('/assets/highcharts/highcharts.js')}}"></script>
    <script>
        $('.loadable').addClass('panel-refreshing');
        $.get('json/listMonthly', function (data) {
            //data = JSON.stringify(data);
            //console.log(data);

            var value = data.chart_value.map(function (item) {
                return parseFloat(item);
            });
            var payment = data.payment.map(function (item) {
                return parseFloat(item);
            });
            var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Sales Analysis'
                },
                subtitle: {
                    text: 'monthly'
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    floating: true,
                    backgroundColor: '#FFFFFF',
                    itemStyle: {
                        fontSize: '8px'
                    }
                },
                colors: ['#9369cc', '#2fb2b3'],
                xAxis: {
                    categories: month,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Value'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Invoice (Rs)',
                    data: value
                }, {
                    name: 'Collection (Rs)',
                    data: payment
                }]
            });
            $('.loadable').removeClass('panel-refreshing');

        });

    </script>
@stop
