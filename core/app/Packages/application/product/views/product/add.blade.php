@extends('layouts.master') @section('title','Add Product')
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
            <a href="javascript:;">Product Management</a>
        </li>
        <li class="active">Add Product</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Add Product</strong>
                </div>
                <div class="panel-body ">
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token() !!}

                        <div class="form-group{{ $errors->has('category')? ' has-error':'' }}">
                            <label class="col-sm-3 control-label required">Product Category</label>
                            <div class="col-sm-6">
                                {!! Form::select('category', $categoryList, Input::old('category'),['class'=>'chosen-select-category','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'']) !!}
                                @if($errors->has('category'))
                                    <div class="help-block">{{$errors->first('category')}}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('size')? ' has-error':'' }}">
                            <label class="col-sm-3 control-label required">Product Size</label>
                            <div class="col-sm-6">
                                {!! Form::select('size', $sizeList, Input::old('size'),['class'=>'chosen-select-size','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'']) !!}
                                @if($errors->has('size'))
                                    <div class="help-block">{{$errors->first('size')}}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('productName')? ' has-error':'' }}">
                            <label class="col-sm-3 control-label required">Product Name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control"
                                       name="productName" placeholder="Product Name" value="{{Input::old('productName')}}">
                                @if($errors->has('productName'))
                                    <div class="help-block">{{ $errors->first('productName') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('description')? ' has-error':'' }}">
                            <label class="col-sm-3 control-label">Description</label>

                            <div class="col-sm-6">
                                <input type="text" autocomplete="off"
                                       class="form-control" name="description"
                                       id="description" placeholder="Descriptoin"
                                       value="{{Input::old('description')}}">
                                <label hidden="true" id="label_des" name="label_des" class="error"></label>
                                @if($errors->has('description'))
                                    <div class="help-block">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('pack_size')? ' has-error':'' }}" hidden>
                            <label class="col-sm-3 control-label">Pack Size</label>

                            <div class="col-sm-6">
                                <input type="text" autocomplete="off"
                                       class="form-control" name="pack_size"
                                       id="pack_size" placeholder="Pack Size" value="{{ Request::old('pack_size') }}">
                                @if($errors->has('pack_size'))
                                    <div class="help-block">{{ $errors->first('pack_size') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('short_code')? ' has-error':'' }}" >
                            <label class="col-sm-3 control-label">Short Code</label>

                            <div class="col-sm-6">
                                <input type="text" autocomplete="off"
                                       class="form-control" name="short_code"
                                       id="short_code" placeholder="Short Code" value="{{ Request::old('short_code') }}">
                                @if($errors->has('short_code'))
                                    <div class="help-block">{{ $errors->first('short_code') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group" hidden>
                            <label class="col-sm-3 control-label ">Tax Code</label>
                            <div class="col-sm-6">
                                <input type="text" autocomplete="off"
                                       class="form-control @if($errors->has('name')) error @endif" name="tax_code"
                                       id="tax_code" placeholder="Tax Code">
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
            var select_category,select_size, chosen_range;



            //category new entry
            select_category = $(".chosen-select-category");
            select_category.chosen({ no_results_text: 'Press Enter to add new category:' });
            chosen_range = select_category.data('chosen');
            chosen_range.dropdown.find('input').on('keyup', function(e)
            {
                if (e.which == 13 && chosen_range.dropdown.find('li.no-results').length > 0)
                {
                    var option = $("<option>").val(this.value).text(this.value);
                    select_category.prepend(option);
                    select_category.find(option).prop('selected', true);
                    select_category.trigger("chosen:updated");
                }
            });

            //Range new entry
            select_size = $(".chosen-select-size");
            select_size.chosen({ no_results_text: 'Press Enter to add new size:' });
            chosen_size = select_size.data('chosen');
            chosen_size.dropdown.find('input').on('keyup', function(e)
            {
                if (e.which == 13 && chosen_size.dropdown.find('li.no-results').length > 0)
                {
                    var option = $("<option>").val(this.value).text(this.value);
                    select_size.prepend(option);
                    select_size.find(option).prop('selected', true);
                    select_size.trigger("chosen:updated");
                }
            });

        });

    </script>
@stop
