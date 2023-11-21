@extends('layouts.master') @section('title','Edit Category')
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
  	<li class="active">Edit Category</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
      			<div style="padding-bottom: 6px;padding-top: 6px; display: inline-block;">
      				<strong>Edit Category</strong>
      			</div>
      			<a class="pull-right btn btn-danger" href="{{{url('/productCategory/list')}}}"><i class="fa fa-arrow-left"></i> Back</a>
      		</div>
          	<div class="panel-body">
          		<form product="form" class="form-horizontal" id="productCategory_form" name="productCategory_form" method="post">
          		{!!Form::token()!!}
          			<div class="form-group">
	            		<label class="col-sm-2 control-label required">Product Category</label>
	            		<div class="col-sm-10">
	            			<input type="text" class="form-control " id="productCategory" name="productCategory" placeholder="Product Category" required value="@if($productCategory) {{$productCategory[0]->category_name}} @endif">
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
	 var y = confirm("Are you Sure..?");
    if (y) {
        document.productCategory_form.submit();
    }  
}
</script>
@stop
