@extends('layouts.master') @section('title','Add Category')
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

	.chosen-container{
		font-family: 'FontAwesome', 'Open Sans',sans-serif;
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
    	<a href="javascript:;">Product Management</a>
  	</li>
  	<li class="active">Add Category</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Add Category</strong>
      		</div>
          	<div class="panel-body">
          		<form product="form"  name="productcat_form" id="productcat_form"  class="form-horizontal form-validation" method="post">
          		   {!!Form::token() !!}
		          	<div class="form-group{{ $errors->has('productCategory')? ' has-error':'' }}">
	            		<label class="col-sm-3 control-label required">Product Category</label>
	            		<div class="col-sm-6">
	            			<input type="text" autocomplete="off" class="form-control @if($errors->has('name')) error @endif" name="productCategory" id="productCategory" placeholder="Product Category">
	            		@if($errors->has('productCategory'))
	            			<div class="help-block">{{ $errors->first('productCategory') }}</div>
	            		@endif
	            	</div>
	                </div>
					<div class="form-group">
						<div class="col-sm-1 col-md-offset-8">
							<div class="pull-right">
								<button type="submit" name="save" class="btn btn-primary"><i class="fa fa-floppy-o"
								style="padding-right: 16px;width: 13px;"></i>
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
<script type="text/javascript">
	$(document).ready(function(){
		
	});

function confirmAlert() {
		var y = confirm("Are you Sure..?");
    if (y) {
        document.productcat_form.submit();
    }
    
}
</script>
@stop
