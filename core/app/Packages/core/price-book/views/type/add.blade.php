@extends('layouts.master') @section('title','Add Price Book Type')
@section('css')
<style type="text/css">
	.panel.panel-bordered {
	    border: 1px solid #ccc;
	}

	.btn-primary {
	    color: white;
	    background-color: #C51C6A;
	    border-color: #C51C6A;
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
    	<a href="javascript:;">Price Book Type Management</a>
  	</li>
  	<li class="active">Add Price Book Type</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Add Type</strong>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          		{!!Form::token()!!}
          			
          			<div class="form-group">
	            		<label class="col-sm-2 control-label required">Price Book Type</label>
	            		<div class="col-sm-5">
	            			<input type="text" class="form-control @if($errors->has('name')) error @endif" name="name" id="name" placeholder="Type Name" required value="{{Input::old('name')}}">
	            			@if($errors->has('label'))
	            				<label id="label-error" class="error" for="label">{{$errors->first('name')}}</label>
	            			@endif
	            			<input type="hidden" id="type_id" name="type_id"/>
	            		</div>
	            		<div class="col-sm-1 pull-left" id="btn_save" name="btn_save">
		                	<button type="button" class="btn btn-primary" onclick="saveType()"><i class="fa fa-floppy-o"></i> Save</button>
		                </div>
		                <div class="col-sm-1 pull-left" id="btn_update" name="btn_update" style="display: none">
		                	<button type="button" class="btn btn-warning" onclick="updateType()"><i class="fa fa-floppy-o"></i> Update</button>
		                </div>	            		
		                <div class="col-sm-1 pull-left" id="btn_cancel" name="btn_cancel" style="display: none">
		                	<button type="button" class="btn btn-danger" onclick="cancel()"><i class="fa fa-close"></i> Cancel</button>
		                </div>
	                </div>

	                <div class="row">

	                	<label class="col-sm-2 control-label"></label>
	                	<div class="col-sm-8">
	                		
			          		<table class="table table-bordered bordered table-striped table-condensed datatable">
				              	<thead>
					                <tr>
					                  	<th rowspan="2" class="text-center">#</th>
					                  	<th rowspan="2" class="text-center" style="font-weight:normal;">Price Book Type</th>
					                  	<th colspan="1" class="text-center" style="font-weight:normal;">Action</th>
					                </tr>
					                <tr style="display: none;">
					                	<th style="display: none;"></th>
					                </tr>
				              	</thead>
				            </table>
	                	</div>
	                	<label class="col-sm-2 control-label"></label>		
					</div>	                
            	</form>
          	</div>
        </div>
	</div>
</div>
@stop
@section('js')
<script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
	var id = 0;
	var table = '';
	$(document).ready(function(){
		$(".panel").addClass('panel-refreshing');
		$('.form-validation').validate();

		table = generateTable('.datatable', '{{url('price/type/json/list')}}',[1],[0,1,2]);

		table.on('draw.dt',function(){
			$("[data-toggle=tooltip]").tooltip();			
		});
		$(".panel").removeClass('panel-refreshing');
	});

	function saveType(){
		$(".panel").addClass('panel-refreshing');
		var type=$("#name").val();

		if(type!==""){

			$.ajax({
				url: "{{url('price/type/add')}}",
				type: 'POST',
				data: {'type': type },
				success: function (data) {
					if(data==1){
						swal("Good Job!", "Price Book Type Added.", "success");	
						table.ajax.url('{{url('price/type/json/list')}}').load(function(){
				            $(".panel").removeClass('panel-refreshing');
				        });		
					}else{
						swal("Oops!", "Something went wrong.", "error");	
						$(".panel").removeClass('panel-refreshing');		
					}
				},
				error: function (xhr, textStatus, thrownError) {
					console.log(thrownError);
				}
			});

		}else{		
			swal("Oops!", "Price Book Type is empty.", "error");
		}
		$(".panel").removeClass('panel-refreshing');
	}

	function set_update(id){
		$("#name").val($("#value_"+id).val());
		$("#type_id").val(id);
		$("#btn_save").hide();
		$("#btn_update").show();
		$("#btn_cancel").show();
	}

	function cancel(){
		$("#name").val("");
		$("#type_id").val('');
		$("#btn_save").show();
		$("#btn_update").hide();
		$("#btn_cancel").hide();
	}

	function updateType(){
		$(".panel").addClass('panel-refreshing');
		var type=$("#name").val();

		if(type!==""){
			var type_id=$("#type_id").val();
			$.ajax({
				url: "{{url('price/type/edit')}}",
				type: 'POST',
				data: {'type_id': type_id,'type': type},
				success: function (data) {
					if(data==1){
						swal("Good Job!", "Price Book Type Updated.", "success");
						$("#name").val("");
						$("#type_id").val('');
						$("#btn_save").show();
						$("#btn_update").hide();
						$("#btn_cancel").hide();	
						table.ajax.url('{{url('price/type/json/list')}}').load(function(){
				            $(".panel").removeClass('panel-refreshing');
				        });		

					}else{
						swal("Oops!", "Something went wrong.", "error");	
						$(".panel").removeClass('panel-refreshing');		
					}
				},
				error: function (xhr, textStatus, thrownError) {
					console.log(thrownError);
				}
			});

		}else{		
			swal("Oops!", "Price Book Type is empty.", "error");
		}
		$(".panel").removeClass('panel-refreshing');
	}
</script>
@stop
