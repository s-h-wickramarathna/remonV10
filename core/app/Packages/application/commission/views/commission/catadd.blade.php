@extends('layouts.master') @section('title','Add Product')
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
<div class="panel-body">
          		<form product="form"  name="product_form" id="product_form"  class="form-horizontal form-validation" method="post">
          		   {!!Form::token() !!}
          			<div class="form-group">
	            		<label class="col-sm-2 control-label required">Product Category</label>
	            		<div class="col-sm-10">	            			
	            			<select id="category_id" name="category_id"  class="form-control chosen" onchange="load_new_one();">
	            				<option value="0">Select Category</option>
	            				<option style="color: green" value="-1" >Add New One</option>
	            				@foreach ($category as $key=>$value)
	            					<option value="<?php echo $value->id ?>" >{{$value->category_name}}</option>
	            				@endforeach	            				
	            			</select>
	            		</div>
	                </div>
	            </form>
</div>
@stop
@section('js')
<script src="{{asset('assets/newcolorbox/jquery.colorbox.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/newcolorbox/colorbox.css')}}" />
<script type="text/javascript">
	$(document).ready(function(){
		
	});

function confirmAlert() {
	var cat_id = $('#category_id');
	if(cat_id == 0){
		alert('Please Select Category..');
	}else{
		var y = confirm("Are you Sure..?");
    if (y) {
        document.product_form.submit();
    }
	}
    
}

function load_new_one(){
	if($('#category_id').val() == -1){
	  $.colorbox({
	  	onOpen: false,href:'{{url('product/catadd')}}'
	  });
	}
}
</script>
@stop
