@extends('layouts.master') @section('title','Add Album Paper Type')
@section('css')
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

        .form-group {
            margin-bottom: 8px !important;
        }

    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;"> Album Paper Type</a>
        </li>
        <li class="active">Add Album Paper Type</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Add Album Paper Type</strong>
                </div>
                <div class="panel-body ">
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token() !!}

                        <div class="form-group{{ $errors->has('type')? ' has-error':'' }}">
                            <label class="col-sm-3 control-label required">Album Paper Type</label>

                            <div class="col-sm-6">
                                <input type="text" autocomplete="off"
                                       class="form-control" name="type"
                                       id="type" placeholder="Paper Type"
                                       value="{{Input::old('type')}}">
                                <label hidden="true" id="label_des" name="label_des" class="error"></label>
                                @if($errors->has('type'))
                                    <div class="help-block">{{ $errors->first('type') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-1 col-md-offset-8">
                                <div class="pull-right">
                                    <button type="submit" name="save" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"
                                        style="padding-right: 16px;width: 12px;"></i>
                                        Save
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
    <script src="{{asset('assets/newcolorbox/jquery.colorbox.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/newcolorbox/colorbox.css')}}"/>
    <script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/vendor/jquery-mask/jquery.mask.min.js')}}"></script>
    <script src="{{asset('assets/scripts/custom/validation.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {







        });

    </script>
@stop
