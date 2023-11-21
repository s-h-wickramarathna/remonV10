@extends('layouts.master') @section('title','Level Configure')
@section('css')
    <style type="text/css">
        .panel.panel-bordered {
            border: 1px solid #ccc;
        }

        .btn-primary {
            color: white;
            background-color: #C51C6A;
            border-color: #C51C6A;
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
            <a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Job Management</a>
        </li>
        <li class="active">Level Configure</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Level Configure</strong>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token()!!}
                        <div class="form-group {{ $errors->has('job_id')? ' has-error':'' }}">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" align="right">
                                <label class="required">Job No </label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <select name="job_id" class="chosen" onchange="change_job()"
                                        style="width:100%;font-family:'FontAwesome', 'Open Sans', sans-serif">
                                    <option value=" " selected="selected">-- Select Job No --</option>
                                    @foreach($jobs as $job)
                                        <option value="{{$job->id}}"
                                                @if($job_id == $job->id) selected @endif>{{$job->job_no}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('job_id'))
                                    <div class="help-block">{{$errors->first('job_id')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">User Levels</label>
                            <div class="col-sm-6">
                                @if($errors->has('levels[]'))
                                    {!! Form::select('levels[]',$emp_types, $added_levels,['class'=>'error', 'multiple','id'=>'levels','style'=>'width:100%;','required']) !!}
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('levels[]')}}</label>
                                @else
                                    {!! Form::select('levels[]',$emp_types, $added_levels,['class'=>'multiple','id'=>'levels','style'=>'width:100%;','required']) !!}
                                @endif
                                <input type="hidden" name="ordered_levels" id="ordered_levels">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-offset-8">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"
                                                                                     style="width:15px;"></i> Save
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
    <script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.form-validation').validate();
            $('#levels').multiSelect({
                keepOrder: true,
                afterSelect: function (value, text) {
                    var get_val = $("#ordered_levels").val();
                    var hidden_val = (get_val != "") ? get_val + "," : get_val;
                    $("#ordered_levels").val(hidden_val + "" + value);
                    console.log($("#ordered_levels").val());
                },
                afterDeselect: function (value, text) {
                    var get_val = $("#ordered_levels").val();
                    var new_val = get_val.replace(value, "");
                    $("#ordered_levels").val(new_val);
                    console.log($("#ordered_levels").val());
                }
            });
        });

        function change_job() {
            $('#levels').multiSelect('deselect_all');
            if ($('select[name="job_id"]').val() != " ") {
                $('.panel').addClass('panel-refreshing');
                $.get('getData/' + $('select[name="job_id"]').val(), function (data) {
                    $('#levels').multiSelect({keepOrder: true});
                    $('#levels').multiSelect('select', data);
                    $('.panel').removeClass('panel-refreshing');
                });
            }
        }
    </script>
@stop
