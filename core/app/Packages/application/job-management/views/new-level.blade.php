@extends('layouts.master') @section('title','Level Configure')
@section('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
                    <form id="search" role="form" class="form-horizontal form-validation" method="get"
                          action="{{url('job/getData/'.$job_id)}}">
                        <div class="form-group {{ $errors->has('job_id')? ' has-error':'' }}">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" align="right">
                                <label class="control-label">Job No </label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <select name="job_id" class="chosen" disabled onchange="change_job()"
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
                    </form>
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token()!!}
                        <div class="form-group">
                            <input type="hidden" name="job_id" value="{{$job_id}}">
                            <input type="hidden" name="level_order">
                            <label class="col-sm-3 control-label required">User Levels</label>
                            <div class="col-sm-3">
                                <ul id="sortable2" class="sortable-list connectedSortable ui-sortable">
                                    @foreach($not_added as $level)
                                        <li id="{{$level}}" class="ui-sortable-handle">{{$level}}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-sm-3">
                                <ul id="sortable1" class="sortable-list connectedSortable ui-sortable">
                                    @foreach($added_levels as $level)
                                        <li id="{{$level}}" class="ui-sortable-handle">{{$level}}</li>
                                    @endforeach
                                </ul>
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.form-validation').validate();
            $("#sortable1").sortable({
                connectWith: ".connectedSortable",
                update: function(event, ui) {
                    var changedList = this.id;
                    var order = $(this).sortable('toArray');
                    var positions = order.join(';');
                    var level_order = $('input[name="level_order"]');
                    level_order.val(positions);
                    console.log(level_order.val());
                }
            }).disableSelection();
            $("#sortable2").sortable({
                connectWith: ".connectedSortable"
            }).disableSelection();
        });

        function change_job() {
            $('.panel').addClass('panel-refreshing');
            $(this).closest('form').trigger('submit');
        }
    </script>
@stop
