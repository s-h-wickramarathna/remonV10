@extends('layouts.master') @section('title','Add Price Book')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap-fileinput-master/css/fileinput.min.css')}}">
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

        .btn-file {
            position: relative;
            overflow: hidden;

        }

        .right {
            color: #137F05;
        }

        .file-preview-status{
            color: #E12222;
        }


        .incorrect {
            color: #E12222;

        }

        .correct {
            color: #117700;

        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Price Book</a>
        </li>
        <li class="active">Upload Excel</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Upload Excel</strong>
                </div>
                <div class="panel-body ">

                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token() !!}
                        <div class="form-group">
                            <nav class="col-sm-6 col-md-offset-3">

                                <ul class="pager pull-right">
                                    <li><a href="template" class="fa fa-arrow-circle-o-down"> Template</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Select Excel File :</label>

                            <div class="col-sm-6">
                                <input id="input-id" name="fileToUpload" id="fileToUpload" type="file" accept=".xls" class="file" multiple data-upload-url="#">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="{{asset('assets/images/vsd_img.png')}}" class="img-responsive">
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
    <script src="{{asset('assets/vendor/bootstrap-fileinput-master/js/plugins/canvas-to-blob.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/vendor/bootstrap-fileinput-master/js/plugins/sortable.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/vendor/bootstrap-fileinput-master/js/plugins/purify.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/vendor/bootstrap-fileinput-master/js/fileinput.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.glyphicon').removeClass('glyphicon-exclamation-sign text-danger');
            $("#input-id").fileinput({
                showPreview: false,
                msgInvalidFileExtension: 'Invalid extension for file "{name}". Only "{extensions}" files are supported.',
                uploadAsync: true,
                uploadUrl:"{{url('product/excel')}}"
            });

            $('#input-id').on('fileuploaderror', function(event, data, msg) {
                var form = data.form, files = data.files, extra = data.extra,
                        response = data.response, reader = data.reader;
                console.log(form);
              // $('.file-error-message').html('<span class="close kv-error-close">�</span><ul><li data-file-id="preview-1467901246382-0"><b>Invalid CSV Format...</li></ul>');

            });

            $('#input-id').on('fileuploaded', function(event, data) {
                var form = data.form, files = data.files, extra = data.extra,
                        response = data.response, reader = data.reader;

                if(response.type == 1){
                    $('.file-preview-status').html('<span class="close kv-error-close">�</span><ul><li ><b>'+response.msg+'</li></ul>');
                    $('.glyphicon').addClass('glyphicon-exclamation-sign').addClass('text-danger');
                }

            });

            $('#input-id').on('fileclear', function(event) {
                console.log('aaaa');
                $('.file-preview-status').html('');
                $('.glyphicon').removeClass('glyphicon-exclamation-sign text-danger');
            });


        });
    </script>
@stop
