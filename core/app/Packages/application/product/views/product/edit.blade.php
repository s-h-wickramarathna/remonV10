@extends('layouts.master') @section('title','Edit Product')
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
    	<a href="javascript:;">Product Management</a>
  	</li>
  	<li>
    	<a href="{{url('product/list')}}">Product List</a>
  	</li>
  	<li class="active">Edit Product</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
      			<div style="padding-bottom: 6px;padding-top: 6px; display: inline-block;">
      				<strong>Edit Product</strong>
      			</div>
      			<a class="pull-right btn btn-danger" href="{{url('/product/list')}}"><i class="fa fa-arrow-left"></i> Back</a>
      		</div>
          	<div class="panel-body">
          		<form product="form" class="form-horizontal" id="product_form" name="product_form" method="post">
          		{!!Form::token()!!}

					<div class="form-group">
						<label class="col-sm-3 control-label required">Product Category</label>
						<div class="col-sm-6">
							{!! Form::select('category', $categoryList, $product->product_category_id,['class'=>'chosen-select-category','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'']) !!}
							@if($errors->has('category'))
								<label id="label-error" class="error"
									   for="label">{{$errors->first('category')}}</label>
							@endif
						</div>
					</div>

					<div class="form-group{{ $errors->has('size')? ' has-error':'' }}">
						<label class="col-sm-3 control-label required">Product Size</label>
						<div class="col-sm-6">
							{!! Form::select('size', $sizeList, $product->size_id,['class'=>'chosen-select-size','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'']) !!}
							@if($errors->has('size'))
								<div class="help-block">{{$errors->first('size')}}</div>
							@endif
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label required">Product Name</label>
						<div class="col-sm-6">
							<input type="text" autocomplete="off"
								   class="form-control @if($errors->has('productName')) error @endif"
								   name="productName" id="productName" placeholder="Product Name" required
								   value="{{$product->product_name}}">
							@if($errors->has('productName'))
								<label id="label-error" class="error"
									   for="label">{{$errors->first('productName')}}</label>
							@endif

						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>

						<div class="col-sm-6">
							<input type="text" autocomplete="off"
								   class="form-control @if($errors->has('description')) error @endif" name="description"
								   id="description" placeholder="Description"
								   value="{{$product->description}}">
							@if($errors->has('description'))
								<label id="label-error" class="error"
									   for="label">{{$errors->first('description')}}</label>
							@endif

						</div>
					</div>
					<div class="form-group" hidden>
						<label class="col-sm-3 control-label required">Pack Size</label>

						<div class="col-sm-6">
							<input type="text" autocomplete="off"
								   class="form-control @if($errors->has('pack_size')) error @endif" name="pack_size"
								   id="pack_size" placeholder="Pack Size" value="">
							@if($errors->has('pack_size'))
								<label id="label-error" class="error"
									   for="label">{{$errors->first('pack_size')}}</label>
							@endif

						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Short Code</label>

						<div class="col-sm-6">
							<input type="text" autocomplete="off"
								   class="form-control @if($errors->has('short_code')) error @endif" name="short_code"
								   id="short_code" placeholder="Short Code" value="{{$product->short_code}}">
							@if($errors->has('short_code'))
								<label id="label-error" class="error"
									   for="label">{{$errors->first('short_code')}}</label>
							@endif
						</div>
					</div>
					<div class="form-group" hidden>
						<label class="col-sm-3 control-label ">Tax Code</label>
						<div class="col-sm-6">
							<input type="text" autocomplete="off"
								   class="form-control @if($errors->has('tax_code')) error @endif" name="tax_code"
								   id="tax_code" placeholder="Tax Code" value="">
							@if($errors->has('tax_code'))
								<label id="label-error" class="error"
									   for="label">{{$errors->first('tax_code')}}</label>
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
<script type="text/javascript">
	$(document).ready(function(){
		$('.form-validation').validate();
		var select_category, chosen_range;


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
