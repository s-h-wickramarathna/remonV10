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
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Job Card Edit</strong>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post" enctype="multipart/form-data">
                        {!!Form::token()!!}

                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label required">Customer Name </label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <input type="hidden" name="user" value="{{$user}}">
                                    <select name="customer_name" class="chosen" onchange="change_customer()"
                                            style="width:100%;font-family:'FontAwesome', 'Open Sans', sans-serif">
                                        <option value="0" selected="selected">-- Select Customer --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}" @if($job->customer_id == $customer->id) selected @endif>{{$customer->f_name}} {{$customer->l_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label ">Address </label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" disabled
                                               name="customer_address" value="{{$job->customer->address}}"
                                        ><span class="input-group-addon"><i
                                                    class="fa fa-globe"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label">Contact No </label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" disabled
                                               name="customer_contact" value="{{$job->customer->mobile}}"
                                               required><span class="input-group-addon"><i
                                                    class="fa fa-phone-square"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4 required">Album</label>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <div class="checkbox checkbox-success">
                                        <input type="hidden" class="calcHide" value="">
                                        <input id="checkbox_p_only" onclick="clear_album(1)" class="styled calc"
                                               name="checkbox_p_only" type="checkbox" @if($job->album == 'Print Only') checked @endif>
                                        <label for="checkbox" onclick="clear_album(1)">
                                            P.ONLY
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <div class="checkbox checkbox-success">
                                        <input type="hidden" class="calcHide" value="">
                                        <input id="checkbox_story" onclick="clear_album(2)" class="styled calc"
                                               type="checkbox" name="checkbox_story" @if($job->album == 'Story') checked @endif>
                                        <label for="checkbox" onclick="clear_album(2)">
                                            STORY
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <div class="checkbox checkbox-success">
                                        <input type="hidden" class="calcHide" value="">
                                        <input id="checkbox_magazine" onclick="clear_album(3)" class="styled calc"
                                               type="checkbox" name="checkbox_magazine" @if($job->album == 'Magazine') checked @endif>
                                        <label for="checkbox" onclick="clear_album(3)">
                                            MAGAZINE
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Type</label>
                                @foreach($album_types as $key => $album_type)
                                    <div class="col-md-2 col-sm-2 col-xs-2 @if($key%4==0 && $key > 0){{'col-md-offset-4'}}@endif">
                                        <input type="radio"
                                               name="radio_type" value="{{$album_type->id}}" @if($job->type == $album_type->id) checked @endif>
                                        <label for="radio">
                                            {{$album_type->type}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label">Page Count</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               name="page_count" value="{{$job->count}}"
                                               ><span class="input-group-addon"><i
                                                    class="fa fa-file"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label">Extra Pages</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               name="extra_pages" value="{{$job->extra_page}}"
                                               ><span class="input-group-addon"><i
                                                    class="fa fa-files-o"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label required">Size</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               name="size" value="{{$job->size}}"
                                               required><span class="input-group-addon"><i
                                                    class="fa fa-arrows"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Lamination</label>
                                @foreach($lamination_types as $key => $lamination_type)
                                    <div class="col-md-2 col-sm-2 col-xs-2 @if($key%4==0 && $key > 0){{'col-md-offset-4'}}@endif">
                                        <input type="checkbox"
                                               name="radio_lamination_type[]"
                                               value="{{$lamination_type->id}}"
                                               @foreach($job->lamination as $lamination) @if($lamination->lamination_id == $lamination_type->id) checked @endif @endforeach>
                                        <label for="checkbox">
                                            {{$lamination_type->laminate_type}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4 required">Paper Type</label>
                                @foreach($paper_types as $key => $paper_type)
                                    <div class="col-md-2 col-sm-2 col-xs-2 @if($key%4==0 && $key > 0){{'col-md-offset-4'}}@endif">
                                        <input type="checkbox"
                                               name="radio_paper_type[]" value="{{$paper_type->id}}" @foreach($job->paper as $paper) @if($paper->paper_type == $paper_type->id) checked @endif @endforeach>
                                        <label for="checkbox">
                                            {{$paper_type->type}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Cover</label>
                                @foreach($album_covers as $key => $album_cover)
                                    <div class="col-md-2 col-sm-2 col-xs-2 @if($key%4==0 && $key > 0){{'col-md-offset-4'}}@endif">
                                        <input type="radio"
                                               name="radio_cover" value="{{$album_cover->id}}" @if($job->cover == $album_cover->id) checked @endif>
                                        <label for="radio">
                                            {{$album_cover->cover}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Box</label>
                                @foreach($boxes as $key => $box)
                                    <div class="col-md-3 col-sm-3 col-xs-3 @if($key%3==0 && $key > 0){{'col-md-offset-4'}}@endif">
                                        <input type="radio"
                                               name="radio_box" value="{{$box->id}}" @if($job->box == $box->id) checked @endif>
                                        <label for="radio">
                                            {{$box->box}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label">Discount Rate</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               name="discount" value="{{$job->discount}}"
                                               ><span class="input-group-addon"><i
                                                    class="fa fa-database"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label required">Job No</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               name="job_no" value="{{$job->job_no}}" required>
                                               <span class="input-group-addon"><i
                                                    class="fa fa-venus-mars"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label">Couple Name</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{$job->couple_name}}"
                                               name="couple_name">
                                               <span class="input-group-addon"><i
                                                    class="fa fa-venus-mars"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label required">Due Date</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="input-group input-daterange">
                                        <input type="text" class="form-control"
                                               name="due_date" required style="text-align: left" value="{{$job->due_date}}">
                                        <span class="input-group-addon"><i
                                                    class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label">Attachment</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="input-group">
                                        <input type="file" class="form-control"
                                               name="attachment" >
                                        <span class="input-group-addon"><i
                                                    class="fa fa-paperclip"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" align="right">
                                    <label class="control-label">Remark</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <textarea class="form-control" placeholder="Type here..." id="remark"
                                              name="remark">{{$job->remark}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-10 col-md-offset-2">
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



            //clear_album(3);

            $('.input-daterange').datepicker({
                format: "yyyy-mm-dd",
                daysOfWeekHighlighted: "0,6",
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true
            });

            //clear_all();


            function changeState(el) {
                if (el.readOnly) el.checked = el.readOnly = false;
                else if (!el.checked) el.readOnly = el.indeterminate = true;
            }
            
        });

        function change_customer() {
            $('.panel').addClass('panel-refreshing');
            $.get('getData/'+$('select[name="customer_name"]').val(),function (data) {
                if(data.customer.is_credit_limit_block == 1) {
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
                                                $('input[name="customer_contact"]').val(data.customer.mobile);

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
                        $('input[name="customer_contact"]').val(data.customer.mobile);
                    }
                }else{
                    $('input[name="customer_address"]').val(data.customer.address);
                    $('input[name="customer_contact"]').val(data.customer.mobile);
                }
                $('.panel').removeClass('panel-refreshing');

            });
        }

        function clear_album(index) {
            var print_only = $('input[name="checkbox_p_only"]');
            var story_album = $('input[name="checkbox_story"]');
            var magazine = $('input[name="checkbox_magazine"]');
            if (1 === index) {
                story_album.prop("checked", false);
                magazine.prop("checked", false);
                print_only.prop("checked", true);
                //print_only();
            } else if (2 === index) {
                print_only.prop("checked", false);
                magazine.prop("checked", false);
                story_album.prop("checked", true);
                // story();
            } else if (3 === index) {
                print_only.prop("checked", false);
                story_album.prop("checked", false);
                magazine.prop("checked", true);
            }
        }

        function clear_paper_type(index) {
            var metallic = $('input[name="checkbox_metallic"]');
            var gold = $('input[name="checkbox_gold"]');
            var trans = $('input[name="checkbox_trans"]');
            var photo = $('input[name="checkbox_photo_p"]');

            switch (index) {
                case 1: {
                    gold.prop("checked", false);
                    trans.prop("checked", false);
                    photo.prop("checked", false);
                    metallic.prop("checked", true);
                }
                    break;
                case 2 : {
                    metallic.prop("checked", false);
                    trans.prop("checked", false);
                    photo.prop("checked", false);
                    gold.prop("checked", true);
                }
                    break;
                case 3 : {
                    metallic.prop("checked", false);
                    gold.prop("checked", false);
                    photo.prop("checked", false);
                    trans.prop("checked", true);
                }
                    break;
                case 4 : {
                    metallic.prop("checked", false);
                    gold.prop("checked", false);
                    trans.prop("checked", false);
                    photo.prop("checked", true);
                }
                    break;

            }
        }


        function clear_all() {
            var print_only = $('input[name="checkbox_p_only"]');
            var story_album = $('input[name="checkbox_story"]');
            var magazine = $('input[name="checkbox_magazine"]');
            var original = $('input[name="checkbox_original"]');
            var sample = $('input[name="checkbox_sample"]');
            var family = $('input[name="checkbox_family"]');
            var p_shoot = $('input[name="checkbox_p_shoot"]');
            var mini = $('input[name="checkbox_mini"]');
            var uv = $('input[name="checkbox_uv"]');
            var gloss = $('input[name="checkbox_gloss"]');
            var mate = $('input[name="checkbox_matte"]');
            var velvet = $('input[name="checkbox_velvet"]');
            var s_mate = $('input[name="checkbox_s_matte"]');
            var metallic = $('input[name="checkbox_metallic"]');
            var gold = $('input[name="checkbox_gold"]');
            var trans = $('input[name="checkbox_trans"]');
            var photo_p = $('input[name="checkbox_photo_p"]');
            var glass = $('input[name="checkbox_glass"]');
            var photo = $('input[name="checkbox_photo"]');
            var t_ton = $('input[name="checkbox_t_ton"]');
            var full_p = $('input[name="checkbox_full_p"]');
            var half_p = $('input[name="checkbox_half_p"]');
            var back_p = $('input[name="checkbox_back_p"]');
            var wood_c = $('input[name="checkbox_wood_c"]');
            var wood = $('input[name="checkbox_wood"]');
            var rexine = $('input[name="checkbox_rexine"]');

            story_album.prop("checked", false);
            magazine.prop("checked", false);
            print_only.prop("checked", false);
            mini.prop("checked", false);
            sample.prop("checked", false);
            family.prop("checked", false);
            p_shoot.prop("checked", false);
            original.prop("checked", false);
            s_mate.prop("checked", false);
            gloss.prop("checked", false);
            mate.prop("checked", false);
            velvet.prop("checked", false);
            uv.prop("checked", false);
            gold.prop("checked", false);
            trans.prop("checked", false);
            photo_p.prop("checked", false);
            metallic.prop("checked", false);
            gold.prop("checked", false);
            trans.prop("checked", false);
            photo_p.prop("checked", false);
            metallic.prop("checked", false);
            photo.prop("checked", false);
            t_ton.prop("checked", false);
            full_p.prop("checked", false);
            half_p.prop("checked", false);
            back_p.prop("checked", false);
            wood_c.prop("checked", false);
            glass.prop("checked", false);
            rexine.prop("checked", false);
            wood.prop("checked", false);

        }

        function print_only() {

        }

        function story() {

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
