@extends('layouts.master') @section('title','Playback')
@section('css')

    <link rel="stylesheet" href="{{asset('assets/css/map.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/ng-slider/dist/rzslider.min.css')}}">
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

        .controller-hud .hud-item {
            margin: 0;
            padding: 0 0 0 0;
        }

        .controller-hud {
            border-radius: 0;
            border-top: 0;
            margin-bottom: 0;
        }

        .custom-slider.rzslider {
            position: relative;
            display: inline-block;
            width: 100%;
            height: 4px;
            margin: 18px 0 15px 0;
            vertical-align: middle;
            user-select: none;
        }

        .custom-slider.rzslider .rz-bar {
            background: #ffe4d1;
            height: 2px;
        }

        .custom-slider.rzslider .rz-selection {
            background: orange;
        }

        .custom-slider.rzslider .rz-pointer {
            width: 8px;
            height: 16px;
            top: auto; /* to remove the default positioning */
            bottom: 0;
            background-color: #333;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
        }

        .custom-slider.rzslider .rz-pointer:after {
            display: none;
        }

        .custom-slider.rzslider .rz-bubble {
            bottom: 14px;
        }

        .custom-slider.rzslider .rz-limit {
            font-weight: bold;
            color: orange;
        }

        .custom-slider.rzslider .rz-tick {
            width: 1px;
            height: 10px;
            margin-left: 4px;
            border-radius: 0;
            background: #ffe4d1;
            top: -1px;
        }

        .custom-slider.rzslider .rz-tick.rz-selected {
            background: orange;
        }

        .dropup .btn p {
            font-size: 10px;
            margin: 3px 0 3px 0;
        }

        .vehicle-color {
            width: 10px;
            height: 10px;
            display: block;
            margin: 5px;
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
        </div>

        @if($device_id > 0)
            <div class="row" style="background:#fff;padding:5px 0 0 0;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class=" controller-hud box">
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 hud-item">
                            <div class=" border-right description-block">
                                <div>
                                    <h5 style="margin:0">
                                        {{$vehicle->first_name.' '. $vehicle->last_name}}
                                    </h5>
                                    <p style="margin:0;font-size:10px">
                                        {{$vehicle->mobile}}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5  hud-item">
                            <div class="border-right description-block">
                                <div class="col-md-4">
                                    <input type="text" class="form-control date" ng-model="date">
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group input-daterange" id="datepicker">
                                        <input type="text" class="form-control" id="from_t" ng-model="fromDate">
                                        <div class="input-group-addon">to</div>
                                        <input type="text" class="form-control" id="to_t" ng-model="toDate">
                                        <a class="btn input-group-addon" ng-click="getData(1)">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"
                             style="padding-left:0;padding-right:0">
                            <div class="border-left description-block">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"
                                            ng-disabled="btn_status"
                                            ng-click="play()">
                                        <i class="fa fa-play"></i>
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm"
                                            ng-disabled="btn_status"
                                            ng-click="reset()">
                                        <i class="fa fa-repeat"></i>
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm"
                                            ng-disabled="btn_status"
                                            ng-click="pause()">
                                        <i class="fa fa-pause"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"
                                            ng-disabled="btn_status"
                                            ng-click="prev()">
                                        <i class="fa fa-backward" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm"
                                            ng-disabled="btn_status"
                                            ng-click="next()">
                                        <i class="fa fa-forward" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{--<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="border-left description-block">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <rzslider class="custom-slider"
                                              rz-slider-model="slider.value"
                                              rz-slider-options="slider.options"></rzslider>
                                </div>
                            </div>
                        </div>--}}
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="margin:0">
                            <div class=" border-right description-block">
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <p>@{{(step_time*100)/100}}x</p>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 0;">
                                        <li><a ng-click="changeSpeed(3)">3X</a></li>
                                        <li><a ng-click="changeSpeed(2)">2X</a></li>
                                        <li><a ng-click="changeSpeed(1.5)">1.5X</a></li>
                                        <li><a ng-click="changeSpeed(1)">1X</a></li>
                                        <li><a ng-click="changeSpeed(0.5)">0.5X</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                            <div class=" border-right description-block">
                                <h5 style="margin:0">
                                    @{{distance | number : 1}}
                                </h5>
                                <p style="margin:0;font-size:10px">
                                    @{{ data.length }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row" style="background:#fff;padding:5px 0 0 0;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                    <h5>please select marketeer</h5>
                </div>
            </div>
        @endif


        <aside class="control-sidebar control-sidebar-dark control-sidebar-closed">
            <div class="sidebar-controller">
                <a href="#" data-toggle="control-sidebar">
                    <i class="fa fa-gears"></i>
                </a>
            </div>
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active">
                    <a href="#control-sidebar-active-vehicles-tab" data-toggle="tab"><i class="fa fa-truck"></i></a>
                </li>
                <li>
                    <a href="#control-sidebar-alerts-tab" data-toggle="tab">
                        <i class="fa fa-bell"></i>
                        <span data-toggle="tooltip" title="" class="badge bg-green"
                              data-original-title="3 New Messages">3</span>
                    </a>
                </li>
                <li><a href="#control-sidebar-warnnings-tab" data-toggle="tab">
                        <i class="fa fa-exclamation-triangle"></i></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane active" id="control-sidebar-active-vehicles-tab">

                    <div class="row vehicles-menu-item">
                        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                            <h5 class="control-sidebar-heading">Active Marketeer</h5>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right filter-panel">
                            <a href="javascript:void(0)" style="margin-right:10px;">
                                <i class="fa fa-filter " aria-hidden="true" style="color:#fff"
                                   data-toggle="modal" href='#modal-id'></i>
                            </a>
                            <a href="javascript:void(0)">
                                <i class="fa fa-ellipsis-v " aria-hidden="true" style="color:#fff"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row vehicles-menu-item">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div style="margin-bottom:10px">
                                <!-- <div class="input-group "> -->
                                <!-- <span class="input-group-addon custom-text-view" id="basic-addon1">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </span> -->
                                <input type="text"
                                       class="form-control custom-text-view"
                                       placeholder="marketeer"
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
                                <a href="{{url('admin/tracking/playback')}}/@{{vehicle.id}}">
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

                <!-- alerts-tab -->
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
    <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVkA60Ss-CVwaUppa6kw3WcJQ8Mdhj3i8&callback=initMap"> </script> -->

    <script src="{{asset('assets/angular/angular/angular.min.js')}}"></script>
    <script src="{{asset('assets/socket.io/socket.io.js')}}"></script>
    <script src="{{asset('assets/vendor/moment/moment.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-datetimepicker/dist/js/bootstrap-datetimepicker.js')}}"></script>
    <script src="{{asset('assets/ng-slider/dist/rzslider.min.js')}}"></script>
    <script type="text/javascript" src={{asset("assets/vendor/bootstrap-timepicker/js/bootstrap-timepicker.min.js")}}></script>

    <!-- ANGULAR -->
    <script>
        var app = angular.module('ngApp', ['rzModule']);
        app.constant('BASE_URL', "{{asset('/')}}");
        app.constant('USER_ID', "{{$user->id}}");
        app.constant('DEVICE_ID', "{{$device_id}}");
    </script>


    <script type="text/javascript">
        $('body').addClass('sidebar-collapse');

        $("#map").css('height', $(window).height() - 105);
        $(window).on('resize', function () {
            $("#map").css('height', $(window).height() - 105);
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

        $('.input-daterange input').each(function () {
            $(this).datetimepicker({
                format: 'HH:mm:ss'
            });
        });
        $('.date').datepicker({
            format: 'yyyy-mm-dd'
        });

        function initMap() {
            // initialize your map
            map = new google.maps.Map(document.getElementById("map"));
            var latlng = new google.maps.LatLng(7.28445900, 80.63745900);
            map.setCenter(latlng); // center your map on the marker location	   .
            map.setZoom(8);
        }

    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVkA60Ss-CVwaUppa6kw3WcJQ8Mdhj3i8&v=3&libraries=geometry&callback=initMap"></script>

    <script src="{{asset('/angular/modules/PlaybackMapManage/app.js')}}"></script>
    <script src="{{asset('/angular/modules/services/services.js')}}"></script>
    <script src="{{asset('/angular/modules/PlaybackMapManage/controllers/TrackingController.js')}}"></script>

@stop
