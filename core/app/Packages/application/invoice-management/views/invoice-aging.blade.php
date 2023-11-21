@extends('layouts.master') @section('title','Invoice Aging')
@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/vendor/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/vendor/jquery-toast-plugin-master/dist/jquery.toast.min.css')}}">
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
        <li>
            <a href="javascript:">Invoice List</a>
        </li>
        <li class="active">Invoice Aging</li>
    </ol>
    <section>
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="panel panel-bordered">
                    <div class="panel-heading border">
                        <input type="hidden" name="route_type" id="route_type" value="">
                        <h4 class="widget-user-username">asdw
                        </h4>
                        <h5 class="widget-user-desc">asdw</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="details-title">Remaining</h5>
                                <h6 class="details-text" style="font-weight: 600">Rs.100
                                    <span class="badge"
                                          style="background-color: green">10</span></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="details-title">Overpayments</h5>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="timeline">
                            <div class="timeline-panel">
                                <div class="timeline-icon bg-danger">
                                    <i class="fa fa-bell"></i>
                                </div>
                                <section class="timeline-content">
                                    <div class="timeline-heading border"> Message from Rachael</div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cum sociis natoque
                                        penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id
                                        dolor id nibh ultricies vehicula ut id elit. Maecenas faucibus mollis
                                        interdum.</p>
                                    <p>Maecenas faucibus mollis interdum. Fusce dapibus, tellus ac cursus commodo,
                                        tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                                    <div class="timeline-date">13:12 am</div>
                                </section>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-icon bg-info">
                                    <i class="fa fa-user"></i>
                                </div>
                                <section class="timeline-content">
                                    <a href="#" class="pb5"> <em>@Envato</em> </a>
                                    <small>Vestibulum id ligula porta felis euismod semper. Maecenas faucibus mollis
                                        interdum. Donec id elit non mi porta gravida at eget metus.
                                    </small>
                                    <div class="timeline-date">13:12 am</div>
                                </section>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-icon bg-primary"><i class="fa fa-calendar"></i>
                                </div>
                                <section class="timeline-content"><a class="pull-left" href="javascript:;"> <img
                                                class="avatar avatar-md img-circle mr15"
                                                src="images/avatar.21d1cc35.jpg" alt=""> </a>
                                    <div class="overflow-hidden">
                                        <div class="h6 no-margin"><a href="javascript:;"><strong>Jane Doe</strong></a>
                                        </div>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eu leo quam.
                                            Pellentesque ornare sem lacinia quam venenatis vestibulum.</p></div>
                                    <div class="timeline-date">13:12 am</div>
                                </section>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-icon bg-warning"><i class="fa fa-photo"></i></div>
                                <section class="timeline-content">
                                    <div class="timeline-heading border"> Profile updates</div>
                                    <p>3 more people joined your campaign.</p>
                                    <div class="timeline-date">13:12 am</div>
                                </section>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-icon bg-success"><i class="fa fa-video-camera"></i></div>
                                <section class="timeline-content">
                                    <div class="timeline-heading border"> New friend list</div>
                                    <div class="clearfix"><a href="javascript:;"> <img alt=""
                                                                                       class="pull-left mr5 avatar avatar-xs img-circle"
                                                                                       src="images/face1.75317f48.jpg">
                                        </a> <a href="javascript:;"> <img alt=""
                                                                          class="pull-left mr5 avatar avatar-xs img-circle"
                                                                          src="images/face5.535c103a.jpg"> </a> <a
                                                href="javascript:;"> <img alt=""
                                                                          class="pull-left mr5 avatar avatar-xs img-circle"
                                                                          src="images/face3.0306ffff.jpg"> </a> <a
                                                href="javascript:;"> <img alt=""
                                                                          class="pull-left mr5 avatar avatar-xs img-circle"
                                                                          src="images/face4.cea90747.jpg"> </a> <a
                                                href="javascript:;"> <img alt=""
                                                                          class="pull-left mr5 avatar avatar-xs img-circle"
                                                                          src="images/avatar.21d1cc35.jpg"> </a> <a
                                                href="javascript:;" class="pull-left btn btn-primary btn-round">
                                            (+34) </a></div>
                                    <div class="timeline-date">13:12 am</div>
                                </section>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-icon bg-default"><i class="fa fa-send"></i></div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>


@stop
@section('js')

@stop
