@extends('layouts.master') @section('title','Edit Job')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('/assets/css/build.css')}}">
    <style type="text/css">

        .panel.panel-bordered {
            border: 1px solid #ccc;
        }

        .btn-primary {
            color: white;
            background-color: #CC1A6C;
            border-color: #CC1A6C;
        }

        .chosen-container {
            font-family: 'FontAwesome', 'Open Sans', sans-serif;
        }

        b, strong {
            font-weight: bold;
        }
    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Job Management</a>
        </li>
        <li class="active">Edit Job</li>
        <li class="pull-right"><a  href="{{url('job/qr/list/'.$job->id)}}">DOWNLOAD QR</a></li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">

                <div class="panel-body">

                    <div class="panel panel-bordered">
                        <div class="panel-heading clearfix">
                            <h6 class="panel-title pull-left" style="padding-top: 7.5px;"><strong>Customer
                                    Details</strong></h6>
                            <div class="btn-group col-sm-4 pull-right">
                                <form role="form" method="get" action="{{url('job/new/search')}}">
                                    <div class="input-group">
                                        <input type="text" autocomplete="off" class="form-control" autofocus
                                               name="invoice_no"
                                               id="invoice_no"
                                               placeholder="search by job no, ex :- 00001" value=""/>
                                        <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit"><i
                                                            class="fa fa-search"></i>
                                                </button>
                                            </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form role="form" class="form-horizontal form-validation" method="post"
                                  enctype="multipart/form-data">
                                {!!Form::token()!!}
                                <div class="col-xs-8 col-xs-offset-1">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Date</label>
                                        <label class="col-sm-3 control-label"
                                               style="text-align: left">{{date('Y-m-d H:i:s')}}</label>
                                        <label class="col-sm-3 control-label required">Job No</label>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <input type="text" class="form-control" disabled
                                                   name="job_no" value="{{$job->job_no}}" required>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-8 col-xs-offset-1">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label required">Customer Name </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input type="hidden" name="user" value="{{$user}}">
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled
                                                       name="customer_address"
                                                       value="{{$job->customer->f_name.' '.$job->customer->l_name}}"
                                                ><span class="input-group-addon"><i
                                                            class="fa fa-user"></i></span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label ">Address </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled
                                                       name="customer_address"
                                                       value="{{$job->customer->address}}"
                                                ><span class="input-group-addon"><i
                                                            class="fa fa-globe"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Contact No </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled
                                                       name="customer_contact"
                                                       value="{{$job->customer->mobile}}"
                                                       required><span class="input-group-addon"><i
                                                            class="fa fa-phone-square"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Email </label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled
                                                       name="customer_email" value="{{$job->customer->email}}"
                                                       required><span class="input-group-addon"><i
                                                            class="fa fa-envelope-square"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Couple Name</label>
                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled
                                                       name="couple_name" value="{{$job->couple_name}}">
                                                <span class="input-group-addon"><i
                                                            class="fa fa-venus-mars"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label required">Album</label>
                                        <?php $index = 1;?>
                                        @foreach($album_types as $key => $album_type)
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 @if(($index%3) == 0) col-xs-offset-3 @endif">
                                                <input type="radio"
                                                       name="radio_type[]" value="{{$album_type->id}}" disabled
                                                       @if($job->album == $album_type->id) checked @endif>
                                                <label for="checkbox">
                                                    {{$album_type->name}}
                                                </label>
                                            </div>
                                            <?php $index += 1;?>
                                        @endforeach
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="panel panel-bordered">
                        <div class="panel-heading border">
                            <strong>Job Specification</strong>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered bordered table-striped table-condensed"
                                   id="orderTable">
                                <thead>
                                <tr>
                                    <td>
                                        Album No
                                    </td>
                                    <td align="center">1</td>
                                    <td align="center">2</td>
                                    <td align="center">3</td>
                                    <td align="center">4</td>
                                    <td align="center">5</td>
                                </tr>
                                </thead>
                                <tbody>

                                <?php $index = 1;$pos = 0; ?>
                                <tr>
                                    <td>{{$job_data[$pos]->name}}</td>
                                    @foreach($job->data as $key => $data)
                                        <td align="center">
                                            <span><input type="radio" name="{{$data->id}}"
                                                         @if($data->qa_res == 1) checked @endif value="1"/>OK</span>
                                            <span><input type="radio" name="{{$data->id}}"
                                                         @if($data->qa_res == 0) checked @endif value="0"/>NO</span>
                                        </td>
                                        @if($index%5 == 0 && sizeof($job->data) != $index)
                                </tr>
                                <tr>
                                    <?php $pos++; ?>
                                    <td>{{$job_data[$pos]->name}}</td>
                                    @endif
                                    <?php $index++; ?>

                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button type="submit" class="col-md-12 btn btn-primary"><i
                                            class="fa fa-floppy-o"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-daterangepicker/js/daterangepicker.js')}}"></script>
    {{--<script src="{{asset('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>--}}
    <script src="{{asset('assets/vendor/jquery-mask/jquery.mask.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            /*$('input[name="dateRange"]').daterangepicker({
             locale: {
             format: 'YYYY-MM-DD'
             },
             startDate: new Date(),
             minDate: new Date()

             });*/

            $('.input-daterange').datepicker({
                format: "yyyy-mm-dd",
                daysOfWeekHighlighted: "0,6",
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true
            });

        });

        function change_customer() {
            $('.panel').addClass('panel-refreshing');
            $.get('getData/' + $('select[name="customer_name"]').val(), function (data) {
                if (data.customer.is_credit_limit_block == 1) {
                    if (data.outstanding < 0) {
                        $.confirm({
                            title: 'Admin authentication!',
                            content: '' +
                            '<form action="" class="formName">' +
                            '<div class="form-group">' +
                            '<label>Marketeer credit limit has been exceed</label>' +
                            '<input type="password" placeholder="Enter admin password" class="name form-control" required />' +
                            '</div>' +
                            '</form>',
                            buttons: {
                                formSubmit: {
                                    text: 'Submit',
                                    btnClass: 'btn-blue',
                                    action: function () {
                                        var name = this.$content.find('.name').val();
                                        if (!name) {
                                            $.alert('provide a valid password');
                                            return false;
                                        }
                                        $.get('{{url('invoice/admin_authentication')}}', {password: name}, function (data) {
                                            if (data == 1) {
                                                $('input[name="customer_address"]').val(data.customer.address);
                                                $('input[name="customer_contact"]').val(data.customer.mobile + ' / ' + data.customer.telephone);
                                                $('input[name="customer_email"]').val(data.customer.email);

                                            } else {
                                                $.alert('Invalid admin password..!');
                                                $('select[name="customer_name"]').val('0');
                                                $('select[name="customer_name"]').trigger("chosen:updated");
                                                $('input[name="customer_address"]').val('');
                                                $('input[name="customer_contact"]').val('');
                                            }
                                            return false;
                                        });
                                        // $.alert('Your name is ' + name);
                                    }
                                },
                                cancel: function () {
                                    $('select[name="customer_name"]').val('0');
                                    $('select[name="customer_name"]').trigger("chosen:updated");
                                    $('input[name="customer_address"]').val('');
                                    $('input[name="customer_contact"]').val('');
                                    return true;
                                },
                            },
                            onContentReady: function () {
                                // bind to events
                                var jc = this;
                                this.$content.find('form').on('submit', function (e) {
                                    // if the user submits the form by pressing enter in the field.
                                    e.preventDefault();
                                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                                });
                            }
                        });
                    } else {
                        $('input[name="customer_address"]').val(data.customer.address);
                        $('input[name="customer_contact"]').val(data.customer.mobile + ' / ' + data.customer.telephone);
                        $('input[name="customer_email"]').val(data.customer.email);
                    }
                } else {
                    $('input[name="customer_address"]').val(data.customer.address);
                    $('input[name="customer_contact"]').val(data.customer.mobile + ' / ' + data.customer.telephone);
                    $('input[name="customer_email"]').val(data.customer.email);
                }
                $('.panel').removeClass('panel-refreshing');

            });
        }


        function validValue(feild, event) {
            var regex = /^\d+(?:\.\d{2})$/;
            if (feild.value.length == 0) {
                return false || (event.keyCode <= 105 && event.keyCode >= 96) || (event.keyCode >= 48 && event.keyCode <= 57);
            }
            if (regex.test(feild.value)) {
                return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40;
            }
            if (feild.value.length == 10) {
                return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40;
            }
            if ((!isInt(feild.value) && (!isFloat(feild.value)))) {
                return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || (event.keyCode <= 105 && event.keyCode >= 96) || (event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode == 110 || event.keyCode == 190;
            }
            return true;
        }

        function isInt(n) {
            return Number(n) === n && n % 1 === 0;
        }

        function isFloat(n) {
            return Number(n) === n && n % 1 !== 0;
        }

        function setDate() {
            var date = new Date();


            var year_month = $('#month_picker_txt').val();
            var day = year_month.split("-");
            var year = day[0];
            var month = day[1];

            var firstDay = new Date(year, month - 1, 1),

                first_month = '' + (firstDay.getMonth() + 1),
                first_day = '' + firstDay.getDate(),
                first_year = firstDay.getFullYear();

            if (first_month.length < 2) first_month = '0' + first_month;
            if (first_day.length < 2) first_day = '0' + first_day;

            var lastDay = new Date(year, month, 0),

                last_month = '' + (lastDay.getMonth() + 1),
                last_day = '' + lastDay.getDate(),
                last_year = lastDay.getFullYear();

            if (last_month.length < 2) last_month = '0' + last_month;
            if (last_day.length < 2) last_day = '0' + last_day;

            var from = [first_year, first_month, first_day].join('-');
            var to = [last_year, last_month, last_day].join('-');


            var dateRange = from + ' - ' + to;
            $('#dateRange').val(dateRange);
        }


    </script>
@stop
