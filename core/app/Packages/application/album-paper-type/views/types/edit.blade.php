@extends('layouts.master') @section('title','Edit Album Paper Type')
@section('css')
<style type="text/css">
	.panel.panel-bordered {
	    border: 1px solid #ccc;
	}

	.btn-primary {
	    color: white;
	    background-color: #005C99;
	    border-color: #005C99;
	}

	.chosen-container{
		font-family: 'FontAwesome', 'Open Sans',sans-serif;
	}
</style>
@stop
@section('content')
<ol class="breadcrumb">
	<li>
    	<a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
  	</li>
  	<li>
    	<a href="javascript:;">Album Paper Type Management</a>
  	</li>
  	<li>
    	<a href="{{url('product/list')}}">Type List</a>
  	</li>
  	<li class="active">Edit Album Paper Type</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
      			<div style="padding-bottom: 6px;padding-top: 6px; display: inline-block;">
      				<strong>Edit Album Paper Type</strong>
      			</div>
      			<a class="pull-right btn btn-danger" href="{{url('/albumType/list')}}"><i class="fa fa-arrow-left"></i> Back</a>
      		</div>
          	<div class="panel-body">
          		<form product="form" class="form-horizontal" id="size_form" name="product_form" method="post">
          		{!!Form::token()!!}

					  <div class="form-group{{ $errors->has('type')? ' has-error':'' }}">
                                                <label class="col-sm-3 control-label required">Size</label>

                                                <div class="col-sm-6">
                                                    <input type="text" autocomplete="off"
                                                           class="form-control" name="type"
                                                           id="type" placeholder="type"
                                                           value="{{$type->type}}">

                                                    <label hidden="true" id="lasizebel_des" name="label_des" class="error"></label>
                                                    @if($errors->has('type'))
                                                        <div class="help-block">{{ $errors->first('type') }}</div>
                                                    @endif
                                                </div>
                                            </div>


					<div class="form-group">
						<div class="col-sm-6 col-md-offset-3">
							<div class="pull-left">
								<button type="submit" name="save" class="btn btn-primary"><i class="fa fa-floppy-o"
																							 style="padding-right: 16px;width: 28px;"></i>
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
<script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/vendor/jquery-mask/jquery.mask.min.js')}}"></script>
<script src="{{asset('assets/scripts/custom/validation.js')}}"></script>

@stop
