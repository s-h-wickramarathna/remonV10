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
    	<a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
  	</li>
  	<li>
    	<a href="javascript:;">Product Management</a>
  	</li>
  	<li>
    	<a href="{{{url('device/list')}}}">Product List</a>
  	</li>
  	<li class="active">Edit Produsct</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
      			<div style="padding-bottom: 6px;padding-top: 6px; display: inline-block;">
      				<strong>Edit Product</strong>
      			</div>
      			<a class="pull-right btn btn-danger" href="{{{url('/product/list')}}}"><i class="fa fa-arrow-left"></i> Back</a>
      		</div>
          	<div class="panel-body">
          		<form product="form" class="form-horizontal" id="product_form" name="product_form" method="post">
          		{!!Form::token()!!}
          			<div class="form-group">
	            		<label class="col-sm-2 control-label required">Product Name</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control " name="productName" placeholder="Device" required value="@if($product) {{$product[0]->product_name}} @endif">
	            		</div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-2 control-label required">Description</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control " name="description" placeholder="Device" required value="@if($product) {{$product[0]->description}} @endif">
	            		</div>
	                </div>
	                 <div class="form-group">
	            		<label class="col-sm-2 control-label required">Product Category</label>
	            		<div class="col-sm-10">
	            			<input type="text" readonly="true" class="form-control "  placeholder="Device" required value="@if($product) {{$product[0]->category_name}} @endif">
	            		</div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-2 control-label required">Pack Size</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control " name="packSize" placeholder="Device" required value="@if($product) {{$product[0]->pack_size}} @endif">
	            		</div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-2 control-label required">Short Code</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control " name="shortCode" placeholder="Device" required value="@if($product) {{$product[0]->short_code}} @endif">
	            		</div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-2 control-label required">Sales Unit</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control " name="salesUnit" placeholder="Device" required value="@if($product) {{$product[0]->sales_unit}} @endif">
	            		</div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-2 control-label required">Tax Code</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control " name="taxCode" placeholder="Device" required value="@if($product) {{$product[0]->tax_code}} @endif">
	            		</div>
	                </div>
	                <div class="pull-right">
	                	<button type="button" onclick="confirmAlert();" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
	                </div>
            	</form>
          	</div>
        </div>
	</div>
</div>
@stop
@section('js')
<script src="{{asset('assets/sammy_new/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.form-validation').validate();

	});
function confirmAlert() {
	var station =$('#station_id').val();
	var location =$('#location_id').val();
if(station == 0 || location == 0){
	alert('Please select Data');
}else{
	 var y = confirm("Are you Sure..?");
    if (y) {
        document.product_form.submit();
    }
}
   
}
</script>
@stop
