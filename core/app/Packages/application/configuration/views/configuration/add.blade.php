@extends('layouts.master') @section('title','Add Menu')
@section('css')
<style type="text/css">
	.panel.panel-bordered {
	    border: 1px solid #ccc;
	}

	.btn-primary {
	    color: white;
	    background-color: #030C3C;
	    border-color: #030C3C;
	}

	.chosen-container{
		font-family: 'FontAwesome', 'Open Sans',sans-serif;
	}
	
	.spacing-table {
    font-family: 'Helvetica', 'Arial', sans-serif;
    font-size: 0px;
    border-collapse: separate;
    border-spacing: 0px; /* this is the ultimate fix */
}
.spacing-table th {
    text-align: left;
    padding: 0px 0px;
}
.spacing-table td {
    border-width: 3px 0;
    border-style: solid;
    color: white;
    padding: 5px 5px;
}
.spacing-table td:first-child {
    border-left-width: 1px;
    border-radius: 1px 0 0 1px;
}
.spacing-table td:last-child {
    border-right-width: 1px;
    border-radius: 0 1px 1px 0;
}
b, strong {
    font-weight: bold;
}
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: rgba(238, 238, 238, 0.64);
    opacity: 1;
}

</style>
@stop
@section('content')
<ol class="breadcrumb">
	<li>
    	<a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
  	</li>
  	<li>
    	<a href="javascript:;">Batch Price</a>
  	</li>
  	<li class="active">Add Batch</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Add Batch</strong>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          		{!!Form::token()!!}
          			<div class="form-group">
	            		<label class="col-sm-2 ">Curent Date</label>
	            		<div class="col-sm-3">
	            			<input type="text" value="<?php echo date("F j, Y, g:i a") ?>" readonly="true" class="form-control @if($errors->has('grn_no')) error @endif" >
	            		</div>
	                </div>

	                </table>
	                <div class="form-group">
	                	<input type="hidden" id="raw_count" name="raw_count" value="1">
						<table id="grn_tbl" name="grn_tbl" border="0" class="table">
							<thead>
								<tr>
									<th style="text-align:center; width:5% ">Add</th>
									<th style="text-align:center; width:40%">Name</th>
									<th style="text-align:center; width:5%">Price</th>
									<th style="text-align:center; width:5%">Remove</th>
								</tr>
							</thead>
							<tbody id="grn_detail">
								<tr id="selected_raw_1">
									<td style="text-align:center">
	                                    <a class="btn btn-primary" id="add_raw_1" name="add_raw_1" style="width:60%;height:30px" onclick="createField();"><i class="fa fa-plus" style="width: 15px;"></i></a>
	                                </td>    
									<td style="text-align:center">
										{!! Form::select('pr_name_1',$product_list, Input::old('pr_name_1'),['class'=>'chosen error','style'=>'width:500px;','required','data-placeholder'=>'Choose Product','id'=>'pr_name_1']) !!}
									</td>
									<td style="text-align:center">
										<input type="text" name="pr_price_1" name="pr_price_1" class="form-control @if($errors->has('pr_price_1')) error @endif" style="width:100%" required value="{{Input::old('pr_price_1')}}">
									</td>
									<td style="text-align:center"></td>
								</tr>
							</tbody>							
						</table>	                	
	                </div>
					<div class="form-group">
						<div class="col-sm-6 col-md-offset-11">
							<div class="pull-left">
								<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" style="padding-right: 14px;"></i> Save</button>
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
<script type="text/javascript">
	$(document).ready(function(){
		$('.form-validation').validate();
		$('#permissions').multiSelect();
	});
</script>
<script type="text/javascript">
	var product =<?php echo json_encode($product) ?>;	

	function createField(){
		var raw_count = $('#raw_count').val();	
		raw_count++;
		$('#grn_detail').append(
			'<tr id="selected_raw_'+ raw_count +'">'
			+	'<td style="text-align:center"><a class="btn btn-primary" id="add_raw_'+ raw_count +'" name="add_raw_'+ raw_count +'" style="width:60%;height:30px" onclick="createField();"  ><i class="fa fa-plus" style="width: 15px;"></i></a></td>'
			+	'<td style="text-align:center">'+createSelect(product,raw_count)+'</td>'
			+	'<td style="text-align:center"><input type="text" id="pr_price_'+ raw_count +'" name="pr_price_'+ raw_count +'" class="form-control @if($errors->has("pr_price_'+ raw_count +'")) error @endif" style="width:100%" required value="{{Input::old("pr_price_'+ raw_count +'")}}"></td>'
			+ 	'<td style="text-align:center"><a class="btn btn-success" id="remove_raw_'+ raw_count +'" name="remove_raw_'+ raw_count +'" style="width:60%;height:30px" onclick="removeRaw('+ raw_count +');"  ><i class="fa fa-minus" style="width: 18px;"></i></a></td>'
			+'</tr>'
			);
		$('#raw_count').val(raw_count);
		$('#pr_name_'+raw_count).chosen();
		// $('#pr_price_'+raw_count).chosen();
	}

	function createSelect(array,raw_count){
		var html = '<select id="pr_name_'+raw_count+'" name="pr_name_'+raw_count+'" class="chosen error" onchange="getPrice('+ raw_count +');">';
		html += '<option value="0">Select Product</option>';
		$.each(array, function(index, data) {
            html += ("<option value='"+ data.id +"'> [" + data.short_code +"] "+ data.product_name +"</option>");
        });
        html += '</select>';
        return html;
	}

	function removeRaw(id){
		$('#selected_raw_'+id).remove();
	}

	// function getPrice(id){
	// 	$.get("{{ url('grn/json/getPrice')}}", 
	// 	{ productId: $('#pr_name_'+id).val() }, 
	// 	function(data) {
	// 		var model = $('#pr_price_'+id);
	// 		$('#pr_price_'+id).empty();
	//         model.append("<option value='0'>Select Price</option>");
	// 		$.each(data.data, function(index, element) {
	//             model.append("<option value='"+ element.id +"'>" + element.mrp + "</option>");
	//         });
	// 		$('#pr_price_'+id).trigger("chosen:updated");			        
	// 	});
	// }
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.form-validation').validate();

		// $('#pr_name_1').change(function(){
		// 	$.get("{{ url('grn/json/getPrice')}}", 
		// 	{ productId: $('#pr_name_1').val() }, 
		// 	function(data) {
		// 		var model = $('#pr_price_1');
		// 		$('#pr_price_1').empty();
		//         model.append("<option value='0'>Select Price</option>");
		// 		$.each(data.data, function(index, element) {
		//             model.append("<option value='"+ element.id +"'>" + element.mrp + "</option>");
		//         });
		// 		$('#pr_price_1').trigger("chosen:updated");			        
		// 	});
		// });

	});
</script>
@stop
