@extends('layouts.master') @section('title','Live')
@section('css')

    <link rel="stylesheet" href="{{asset('assets/css/map.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/AdminLTE.css')}}">
    <style type="text/css">
        #map {
            width: 100%;
        }

        #vehicle-list .active {
            background: #424242;
        }

        .vehicle-sidebar-menu {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .vehicle-sidebar-menu li {
            padding: 5px 5px 0 0;
        }

        .vehicle-sidebar-menu li:hover {
            background: #424242;
        }

        .control-sidebar > .tab-content {
            padding: 0;
        }

        .vehicles-menu-item {
            margin: 0
        }

        .vehicle-color {
            width: 10px;
            height: 10px;
            display: block;
            margin: 5px;
        }

        .sizing-box {
            height: 15px;
            width: 25px;
        }

        .signal-bars {
            display: inline-block;
        }

        .signal-bars .bar {
            width: 8%;
            margin-left: 1%;
            min-height: 20%;
            display: inline-block;
        }

        .signal-bars .bar.first-bar {
            height: 20%;
        }

        .signal-bars .bar.second-bar {
            height: 40%;
        }

        .signal-bars .bar.third-bar {
            height: 60%;
        }

        .signal-bars .bar.fourth-bar {
            height: 80%;
        }

        .signal-bars .bar.fifth-bar {
            height: 99%;
        }

        .good .bar {
            background-color: #16a085;
            border: thin solid darken(#16a085, 7%);
        }

        .bad .bar {
            background-color: #e74c3c;
            border: thin solid darken(#e74c3c, 20%);
        }

        .ok .bar {
            background-color: #f1c40f;
            border: thin solid darken(#f1c40f, 7%);
        }

        .four-bars .bar.fifth-bar,
        .three-bars .bar.fifth-bar,
        .three-bars .bar.fourth-bar,
        .one-bar .bar:not(.first-bar),
        .two-bars .bar:not(.first-bar):not(.second-bar) {
            background-color: #fafafa;
            border: thin solid #f3f3f3;
        }

        .main-content {
            padding-left: 0px !important;
            padding-top: 55px !important;
        }

    </style>

    <script type="text/javascript">
        var map;
    </script>
@stop
@section('content')

    <!-- Main content -->
    <section class="content" ng-app="ngApp" ng-controller="TrackingController" style="padding: 0;">
        <!-- <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  text-left mb10" >
                <button type="button" class="btn btn-danger btn-xs">Full View</button>
                <button type="button" class="btn btn-info btn-xs">Reload</button>
                <button type="button" class="btn btn-info btn-xs" data-toggle="control-sidebar">Side Bar</button>
            </div>
        </div> -->
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div id="map"></div>
            </div>

            {{--<div style="position:absolute">
                <div class="box box-default"  id="hud">
                    <div class="box-header with-border">
                      <h3 class="box-title"></h3>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="">
                      <canvas data-type="radial-gauge" id="speed"
                                    data-width="220"
                                    data-height="220"
                                    data-units="Km/h"
                                    data-min-value="0"
                                    data-max-value="220"
                                    data-major-ticks="0,20,40,60,80,100,120,140,160,180,200,220"
                                    data-minor-ticks="5"
                                    data-stroke-ticks="true"
                                    data-highlights='[
                                        {"from": 160, "to": 220, "color": "rgba(200, 50, 50, .75)"}
                                    ]'
                                    data-color-plate="#fff"
                                    data-color-major-ticks="#000"
                                    data-color-minor-ticks="#000"
                                    data-color-title="#000"
                                    data-color-units="#000"
                                    data-color-numbers="#000"
                                    data-border-shadow-width="0"
                                    data-borders="false"
                                    data-needle-type="arrow"
                                    data-needle-width="8"
                                    data-needle-circle-size="5"
                                    data-needle-circle-outer="true"
                                    data-needle-circle-inner="false"
                                    data-animation-duration="1500"
                                    data-animation-rule="linear"
                                    data-font-numbers-weight="800"
                                    data-font-units-weight="800"
                                    data-value-box-stroke = "1"
                                    data-value="@{{aa}}"
                            ></canvas>



                            <canvas
                                data-type="radial-gauge"
                                data-width="150"
                                data-height="150"
                                data-units="GPRS"
                                data-min-value="0"
                                data-start-angle="90"
                                data-ticks-angle="180"
                                data-value-box="false"
                                data-max-value="50"
                                data-major-ticks="0,10,20,30,40,50"
                                data-minor-ticks="2"
                                data-stroke-ticks="true"
                                data-highlights='[
                                    {"from": 30, "to": 50, "color": "green"},
                                    {"from": 20, "to": 30, "color": "yellow"},
                                    {"from": 0, "to": 20, "color": "red"}
                                ]'
                                data-color-plate="#fff"
                                data-color-major-ticks="#000"
                                data-color-minor-ticks="#000"
                                data-color-title="#000"
                                data-color-units="#000"
                                data-color-numbers="#000"
                                data-border-shadow-width="0"
                                data-borders="false"
                                data-needle-type="arrow"
                                data-needle-width="2"
                                data-needle-circle-size="7"
                                data-needle-circle-outer="true"
                                data-needle-circle-inner="false"
                                data-animation-duration="1500"
                                data-animation-rule="linear"
                                data-value="@{{aa+10}}"
                            ></canvas>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>--}}
        </div>

        <aside class="control-sidebar control-sidebar-dark control-sidebar-open app layout-fixed-header">
            <div class="sidebar-controller">
                <a href="#" data-toggle="control-sidebar">
                    <i class="fa fa-gears"></i>
                </a>
            </div>
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active">
                    <a href="#control-sidebar-active-vehicles-tab" data-toggle="tab"><i
                                class="fa fa-map-marker fa-2x"></i></a>
                </li>
                <li>
                    <a href="#control-sidebar-alerts-tab" data-toggle="tab">
                        <i class="fa fa-clock-o fa-2x"></i>
                    </a>
                </li>

            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane active" id="control-sidebar-active-vehicles-tab">

                    <div class="row vehicles-menu-item">
                        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                            <h5 class="control-sidebar-heading">Active Marketeers</h5>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right filter-panel">
                            <a ng-click="refresh()" style="margin-right:10px;">
                                <i class="fa fa-refresh " aria-hidden="true" style="color:#fff"
                                   data-toggle="modal" ng-click="refresh()"></i>
                            </a>
                            <a href="javascript:void(0)">
                                <i class="fa fa-ellipsis-v " aria-hidden="true" style="color:#fff"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row vehicles-menu-item">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div style="margin-bottom:10px;">
                                <!-- <div class="input-group "> -->
                                <!-- <span class="input-group-addon custom-text-view" id="basic-addon1">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </span> -->
                                <input type="text"
                                       class="form-control custom-text-view"
                                       placeholder="Search"
                                       aria-describedby="basic-addon1"
                                       ng-model="vehicleNo">
                                <!-- </div>	 -->
                            </div>
                        </div>
                    </div>
                    <div class="row vehicles-menu-item">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <p class="pull-left" style="font-size:10px;">Total : @{{filtered.length}}</p>
                        </div>
                    </div>
                    <div class="row vehicles-menu-item">
                        <ul class="vehicle-sidebar-menu" id="vehicle-list">
                            <li>
                                <div class="vehicles_list">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                                #
                                            </div>
                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                <p>Marketeer</p>
                                            </div>
                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                <p>Battery</p>

                                            </div>
                                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                <p>Last Update</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li ng-repeat="vehicle in vehicles | filter : vehicleNo as filtered"
                                ng-click="loadVehicle(vehicle.assigned_device.device.imei)"
                                ng-class="{active: vehicle.assigned_device.device.imei === '{{$device_id}}'}">
                                <a href="{{url('admin/tracking/live')}}/@{{vehicle.id}}">
                                    <div class="vehicles_list">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                                    <i class="vehicle-color bg-red"></i>
                                                </div>
                                                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                    @{{vehicle.first_name+' '+vehicle.last_name}}
                                                </div>
                                                <div ng-if="vehicle.tracking_rep.length > 0"
                                                     class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                    <p>@{{vehicle.tracking_rep[0].battery}}</p>

                                                </div>
                                                <div ng-if="vehicle.tracking_rep.length < 1"
                                                     class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                    <p>uknown</p>
                                                </div>
                                                <div ng-if="vehicle.tracking_rep.length > 0"
                                                     class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    <p>@{{vehicle.tracking_rep[0].created_at}}</p>
                                                </div>
                                                <div ng-if="vehicle.tracking_rep.length < 1"
                                                     class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                    <p>uknown</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="tab-pane" id="control-sidebar-alerts-tab">
                    <h3 class="control-sidebar-heading">Alerts</h3>
                </div>
                <!--// alerts-tab -->

                <!-- warnnings-tab -->
                <div class="tab-pane" id="control-sidebar-warnnings-tab">
                    <h3 class="control-sidebar-heading">warnnings</h3>
                </div>
                <!-- //warnnings-tab -->
        </aside>

        <div class="modal fade" id="modal-id">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>


    </section><!-- /.content -->

@stop
@section('js')


    <!-- datatables -->

    <script src="{{asset('assets/angular/angular/angular.min.js')}}"></script>
    <script src="{{asset('assets/socket.io/socket.io.js')}}"></script>
    <!-- <script src="{{asset('assets/dist/canvGague/gauge.min.js')}}"></script> -->
    <!-- <script src="{{asset('assets/dist/interact/interact.min.js')}}"></script> -->

    <!-- ANGULAR -->
    <script>
        var app = angular.module('ngApp', []);
        app.constant('BASE_URL', "{{asset('/')}}");
        app.constant('USER_ID', "{{$user->id}}");
        app.constant('DEVICE_ID', "{{$device_id}}");
        app.constant('GPS_SOCKET', "{{config('app.gps_socket')}}");
    </script>




    <script type="text/javascript">
        $('body').addClass('sidebar-collapse');

        $("#map").css('height', $(window).height() - 50);
        $(window).on('resize', function () {
            $("#map").css('height', $(window).height() - 50);
        });

        $('.sidebar-controller').click(function () {
            if($('.control-sidebar').hasClass('control-sidebar-open')){
                $('.control-sidebar').removeClass('control-sidebar-open')
            }else{
                $('.control-sidebar').addClass('control-sidebar-open')
            }
        });

        /*$('#vehicle-list').slimScroll({
         height: '450px',
         color: '#fff',
         size: '10px',
         });*/

        function initMap() {
            // initialize your map
            map = new google.maps.Map(document.getElementById("map"));
            var latlng = new google.maps.LatLng(7.28445900, 80.63745900);
            map.setCenter(latlng); // center your map on the marker location	   .
            map.setZoom(8);
        }

    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVkA60Ss-CVwaUppa6kw3WcJQ8Mdhj3i8&callback=initMap"></script>

    <script src="{{asset('angular/modules/LiveTrackingManage/app.js')}}"></script>
    <script src="{{asset('angular/modules/services/services.js')}}"></script>
    <script src="{{asset('angular/modules/LiveTrackingManage/directives/directives.js')}}"></script>
    <script src="{{asset('angular/modules/LiveTrackingManage/controllers/TrackingController.js')}}"></script>

@stop
