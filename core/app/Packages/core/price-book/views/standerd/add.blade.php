@extends('layouts.master') @section('title','Add Standard Price Book')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/file/bootstrap-fileinput-master/css/fileinput.css')}}"media="all" />
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
    	<a href="javascript:;">Standard Price Book Management</a>
  	</li>
  	<li class="active">Add Standard Price Book</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Add Price Book</strong>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          		{!!Form::token()!!}
          			<div class="form-group">
	            		<label class="col-sm-2 control-label required">SBU</label>
	            		<div class="col-sm-10">
	                        @if($errors->has('sbu'))
	                            {!! Form::select('sbu',$sbu, Input::old('sbu'),['class'=>'chosen error','style'=>'width:100%;','required','data-placeholder'=>'Choose Sbu','disabled'=>'disabled']) !!}
	                            <label id="label-error" class="error"
	                                   for="label">{{$errors->first('sbu')}}</label>
	                        @else
	                            {!! Form::select('sbu',$sbu, Input::old('sbu'),['class'=>'chosen','style'=>'width:100%;','required','data-placeholder'=>'Choose Sbu','disabled'=>'disabled']) !!}
	                        @endif
	                    </div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-2 control-label required">Price Book For</label>
	            		<div class="col-sm-10">
	                        @if($errors->has('book_category'))
	                            {!! Form::select('book_category',$sale_party, Input::old('book_category'),['class'=>'chosen error','style'=>'width:100%;','required','data-placeholder'=>'Choose Sale-Party','id'=>'book_category']) !!}
	                            <label id="label-error" class="error"
	                                   for="label">{{$errors->first('book_category')}}</label>
	                        @else
	                            {!! Form::select('book_category',$sale_party, Input::old('book_category'),['class'=>'chosen','style'=>'width:100%;','required','data-placeholder'=>'Choose Sale-Party','id'=>'book_category']) !!}
	                        @endif
	                    </div>
	                </div>
	                <div class="form-group">
	            		<label class="col-sm-2 control-label required">Price Book</label>
	            		<div class="col-sm-4">
                            <input type="text" class="form-control @if($errors->has('name')) error @endif"
                                   name="name" id="name" placeholder="Price Book Name" required value="{{Input::old('name')}}">
                            @if($errors->has('name'))
                                <label id="label-error" class="error"
                                       for="label">{{$errors->first('name')}}</label>
                            @endif
                        </div>
                        <label class="col-sm-2 control-label required">Effective Date</label>
                        <div class="col-sm-4">
							<div class="input-daterange" id="datepicker">
                                <input type="text" class="input-sm form-control @if($errors->has('effective_date')) error @endif required" name="effective_date" id="effective_date" placeholder="Effective Date"  value="{{Input::old('effective_date')}}">
                                @if($errors->has('effective_date'))
                                    <label id="effective_date_label-error" class="error" for="label">{{$errors->first('effective_date','The effective date field is required.')}}</label>
                                @endif
                            </div>                                
                        </div>                        
	                </div>	       

	                <div class="form-group">
	                	<label class="col-sm-2 control-label required">Select Option</label>
	                	<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="optradio" id="product_radio">Product Wise</label>
		            		<label class="radio-inline"><input type="radio" name="optradio" id="excel_radio">Excel Upload</label>
		            		<button type="button" class="btn btn-warning" title="View Format" onclick="load_modal()"><i class="fa fa-eye"></i></button>
		            		<button type="button" class="btn btn-danger" title="Download Format"><a href="{{asset('assets/price_excel_format.xls')}}" download="Upload Format"><i class="fa fa-download"></i></a></button>
						</div>
	                </div>     	                    

	                <div class="form-group" id="excel_div" name="excel_div">
	            		<label class="col-sm-2 control-label required">Upload Product</label>
	            		<div class="col-sm-10">
	            			<input id="input-ficons-1" name="inputficons1" multiple type="file" class="file-loading">
	            		</div>
	                </div>

	                <div id="product_div" name="product_div">
		                <div class="form-group">
		            		<label class="col-sm-2 control-label required">Product Category</label>
		            		<div class="col-sm-10">
		                        @if($errors->has('category'))
		                            {!! Form::select('category',$category, Input::old('category'),['class'=>'chosen error','style'=>'width:100%;','required','data-placeholder'=>'Choose Category','id'=>'category']) !!}
		                            <label id="label-error" class="error"
		                                   for="label">{{$errors->first('category')}}</label>
		                        @else
		                            {!! Form::select('category',$category, Input::old('category'),['class'=>'chosen','style'=>'width:100%;','required','data-placeholder'=>'Choose Category','id'=>'category']) !!}
		                        @endif

		                    </div>
		                </div>

		                <div class="form-group">
		            		<label class="col-sm-2 control-label required">Product</label>
		            		<div class="col-sm-6" id="pro_div" name="pro_div">
		            			@if($errors->has('product[]'))
		            				{!! Form::select('product[]',[], null,['class'=>'error', 'multiple','id'=>'product','style'=>'width:100%;','required']) !!}
		            				<label id="label-error" class="error" for="label">{{$errors->first('product[]')}}</label>
		            			@else
		            				{!! Form::select('product[]',[], Input::old('product[]'),['multiple'=>'multiple','id'=>'product','style'=>'width:100%;','required']) !!}
		            			@endif
		            		</div>
		            		<div class="col-sm-4">
		            			<div class="row">
		            				<a href='#' id='select-all' style="font-size: 16px;color: green">Select All</a>
		            			</div>
		            			<div class="row">
		            				<a href='#' id='deselect-all' style="font-size: 16px;color: red">Deselect All</a>
		            			</div>
		            			<div class="row" style="margin-top: 36%">
		            				<div class="pull-left">
					                	<button type="button" class="btn btn-warning" onclick="get_product()"><i class="fa fa-plus"></i> Add</button>
					                </div>
		            			</div>
		            		</div>
		                </div>
		            
		                <div class="form-group">
		            		<label class="col-sm-2 control-label required">Product Details</label>
		            		<div class="col-sm-10">
		            			<table class="table table-bordered bordered table-striped table-condensed">
		            				<thead>
				                        <tr>
				                            <th rowspan="2" class="text-center" width="4%">#</th>
				                            <th rowspan="2" class="text-center" style="font-weight:normal;">Short Code</th>
				                            <th rowspan="2" class="text-center" style="font-weight:normal;">Name</th>
				                            <th rowspan="2" class="text-center" style="font-weight:normal;">Description</th>
				                            <th rowspan="2" class="text-center" style="font-weight:normal;">Category</th>
				                            <th rowspan="2" class="text-center" style="font-weight:normal;">MRP</th>
				                            <th rowspan="2" class="text-center" style="font-weight:normal;">Standard Price</th></th>
				                        </tr>
	                        		</thead>
	                        		<tbody id="tbody" name="tbody">
	                        			
	                        		</tbody>
		            			</table>
		            		
		            		</div>
		                </div>
		            </div>

	                <div class="pull-right">
	                	<button type="button" class="btn btn-primary" onclick="save_data()"><i class="fa fa-floppy-o"></i> Save</button>
	                </div>
            	</form>
          	</div>
        </div>
	</div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
            	<div class="row" style="text-align: center">
					<img border="0" src="{{asset('assets/price_excel_format.png')}}" alt="Upload Format" width="500" height="400">
            	</div>
            	<div class="row" style="text-align: center;margin-top: 10px">
            		<button type="button" class="btn btn-primary"><i class="fa fa-floppy-o"></i> <a href="{{asset('assets/price_excel_format.xls')}}" download="Upload Format">Download Excel Format</a></button>            		
            	</div>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/file/bootstrap-fileinput-master/js/fileinput.min.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.form-validation').validate();
		$('#product').multiSelect();

		$('#select-all').click(function(){
			$('#product').multiSelect('select_all');
		  	return false;
		});
		$('#deselect-all').click(function(){
		  	$('#product').multiSelect('deselect_all');
		  	return false;
		});

		var oFileIn;

		oFileIn = document.getElementById('input-ficons-1');
	    if(oFileIn.addEventListener) {
	        oFileIn.addEventListener('change', get_excel_data, false);
	    }

		$("#product_div").hide();

		$("#excel_radio").attr('checked','checked');

		set_product();

		$("#excel_radio").click(function() {
			if($("#excel_radio").is(':checked')){
				$("#product_div").hide();
				$("#excel_div").show();
			}else if($("#product_radio").is(':checked')){
				$("#excel_div").hide();
				$("#product_div").show();
			}
		});

		$("#product_radio").click(function() {
			if($("#excel_radio").is(':checked')){
				$("#product_div").hide();
				$("#excel_div").show();
			}else if($("#product_radio").is(':checked')){
				$("#excel_div").hide();
				$("#product_div").show();
			}
		});

		$('#category').change(function(){
			set_product();			
		});

		$('#effective_date').change(function(){
			if($('#effective_date').val()==""){
				$('#effective_date').removeClass("success");
				$('#effective_date').addClass("error");
			}else{
				$('#effective_date').removeClass("error");
				$('#effective_date').addClass("success");
			}
		});	

		$('#name').change(function(){
			if($('#name').val()==""){
				$('#name').removeClass("success");
				$('#name').addClass("error");
			}else{
				$('#name').removeClass("error");
				$('#name').addClass("success");				
			}
		});	

		$('.input-daterange').datepicker({
            format: "yyyy-mm-dd",
            startDate: '0d',
            daysOfWeekHighlighted: "0,6",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true,
            toggleActive: true
        });

        $("#input-ficons-1").fileinput({
		    uploadUrl: "",
		    uploadAsync: true,
		    previewFileIcon: '<i class="fa fa-file"></i>',
		    allowedPreviewTypes: null, // set to empty, null or false to disable preview for all types
		    previewFileIconSettings: {
		        'docx': '<i class="fa fa-file-word-o text-primary"></i>',
		        'xlsx': '<i class="fa fa-file-excel-o text-success"></i>',
		        'pptx': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
		        'jpg': '<i class="fa fa-file-photo-o text-warning"></i>',
		        'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
		        'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
		    }
		});

	});

	function set_product(){		
		$('.panel').addClass('panel-refreshing');
		$("#pro_div").html("");
		$("#pro_div").append('<select multiple="multiple" name="product[]" id="product" style="width: 100%" required></select>');
		$('#product').multiSelect('refresh');

		$.ajax({
			url: "{{url('price/standerd/json/list')}}",
			type: 'GET',
			data: {'category': $("#category").val()},
			success: function(data) {
				$.each( data[0], function( key, value ) {					
					$('#product').append('<option value="'+value.id+'">['+value.short_code+']  - '+value.product_name+'</option>');
					$('#product').multiSelect('refresh');					
				});
				$('.panel').removeClass('panel-refreshing');
			},error: function(data){

			}
		});
	}

	i=1;

	temp_array=[];

	function get_product(){		
		if($("#product").val()!=null){

			$('.panel').addClass('panel-refreshing');

			$.ajax({
				url: "{{url('price/standerd/json/productList')}}",
				type: 'GET',
				data: {'product': $("#product").val()},
				success: function(data) {
					
					$.each( data, function( key, value ) {

						p=0;

                        $.each( temp_array, function( h, old ) {
                            if(value.id==old){
                                p++;
                            }
                        });						

						if(p==0){

                            temp_array.push(value.id);

							if(value.mrp && value.mrp.mrp!="0.00"){
								$('#tbody').append(
									'<tr>'
									+'<td>'+i+'</td>'
									+'<td>'+value.short_code+'</td>'
									+'<input type="hidden" id="product_id_'+i+'" name="product_id_'+i+'" value="'+value.id+'"/>'
									+'<td>'+value.product_name+'</td>'
									+'<td>'+value.description+'</td>'
									+'<input type="hidden" id="category_id_'+i+'" name="category_id_'+i+'" value="'+value.category.id+'"/>'
									+'<td>'+value.category.category_name+'</td>'
									+'<td>'+value.mrp.mrp+'</td>'
									+'<td><input type="number" min="0" class="form-control" width="100%" id="product_price_'+i+'" name="product_price_'+i+'"/></td>'
									+'</tr>'
								);	
							}
							// else{
							// 	$('#tbody').append(
							// 		'<tr>'
							// 		+'<td>'+i+'</td>'
							// 		+'<td>'+value.short_code+'</td>'
							// 		+'<input type="hidden" id="product_id_'+i+'" name="product_id_'+i+'" value="'+value.id+'"/>'
							// 		+'<td>'+value.product_name+'</td>'
							// 		+'<td>'+value.description+'</td>'
							// 		+'<input type="hidden" id="category_id_'+i+'" name="category_id_'+i+'" value="'+value.category.id+'"/>'
							// 		+'<td>'+value.category.category_name+'</td>'
							// 		+'<td>0.00</td>'
							// 		+'<td><input type="number" min="0" class="form-control" width="100%" id="product_price_'+i+'" name="product_price_'+i+'"/></td>'
							// 		+'</tr>'
							// 	);
							// }
							i++;
						}
					});	

					$('#product').multiSelect('deselect_all');			
					$('.panel').removeClass('panel-refreshing');
				},error: function(data){

				}
			});
		}else{
			swal('Oops!','Please select product','error');
		}
		
	}

	var sCSV;

	function get_excel_data(oEvent){		
		var oFile = oEvent.target.files[0];
	    var sFilename = oFile.name;
	    // Create A File Reader HTML5
	    var reader = new FileReader();
	    
	    // Ready The Event For When A File Gets Selected
	    reader.onload = function(e) {
	        var data = e.target.result;
	        var cfb = XLS.CFB.read(data, {type: 'binary'});
	        var wb = XLS.parse_xlscfb(cfb);
	        // Loop Over Each Sheet
	        wb.SheetNames.forEach(function(sheetName) {
	            // Obtain The Current Row As CSV
	            //sCSV = XLS.utils.make_csv(wb.Sheets[sheetName]);   
	            //var oJS = XLS.utils.sheet_to_row_object_array(wb.Sheets[sheetName]);   	            
	            sCSV = XLS.utils.sheet_to_row_object_array(wb.Sheets[sheetName]);   	            
	            
	        });
	    };
	    
	    // Tell JS To Start Reading The File.. You could delay this if desired
	    reader.readAsBinaryString(oFile);
	}

	function save_data(){		
		$('.panel').addClass('panel-refreshing');
		k=0;

		if($("#name").val()==""){			
			$("#name").removeClass("valid");
			$("#name").addClass("error");
			k++;
		}

		if($("#effective_date").val()==""){			
			$("#effective_date").removeClass("valid");
			$("#effective_date").addClass("error");
			k++;
		}

		basic=[];
		tmp={};
		tmp['price_book_type']=1;
		tmp['price_book_category']=$("#book_category").val();
		tmp['price_book_name']=$("#name").val();		

		basic.push(tmp);

		detail=[];

		l=0;
		m=0;
		n=0;

		clue=0;

		no_mrp=[];

		if($("#excel_radio").is(':checked')){
			clue=0;
			if($("#input-ficons-1").val()!=""){
				detail=[];
				detail=sCSV;				
				$.ajax({
					url: "{{url('price/standerd/check-mrp')}}",
					type: 'GET',
					data: {'detail': detail},
					async: false,
					success: function(data) {
						if(data.data.length!=detail.length){
							n=1;
							no_mrp=data.no_mrp;
						}else{
							detail=data.data;
							$.each(detail,function(index,value){										
								value.effective_date=$("#effective_date").val();					
							});							
						}
					},error: function(data){

					}
				});			
				
			}else{				
				l++;
			}
			
		}else if($("#product_radio").is(':checked')){
			detail=[];
			clue=1;
			for (var j = 1; j <i; j++) {
				tmp={};			
				if($("#product_id_"+j)){
					tmp['category_id']=$("#category_id_"+j).val();
					tmp['pro_id']=$("#product_id_"+j).val();
					tmp['price']=$("#product_price_"+j).val();
					if($("#product_price_"+j+"-error").css('display') == 'inline-block'){
					    m++;
					}
					tmp['effective_date']=$("#effective_date").val();
					if($("#product_price_"+j).val()==""){
						$("#product_price_"+j).addClass("error")
						l++;
					}
				}
				detail.push(tmp);
			}	
		}		

		if(detail.length>0 && $("#name").val()!="" && $("#effective_date").val()!="" && l==0 && m==0 && $("#book_category").val()!=0){
			if(n==0){
				$.ajax({
					url: "{{url('price/standerd/add')}}",
					type: 'POST',
					data: {'basic':basic,'detail': detail,'clue': clue},
					success: function(data) {					
						if(data==1){
							$('.panel').removeClass('panel-refreshing');
							swal({ 
								title: "Hooray",
							   	text: "Successfully Created",
							    type: "success" 
							},function(){

							    window.location.href = "{{url('price/standerd/add')}}";
							});					
						}else{
							$('.panel').removeClass('panel-refreshing');
							swal('Oops!','Something went wrong','error');			
						}
					},error: function(data){

					}
				});	
			}else{
				$('.panel').removeClass('panel-refreshing');

				str=" ";

				$.each(no_mrp,function(key,value){
					str+=value+" , ";
				});

				swal("Warning", "Missing MRP of follow product "+str+" .Remove these from excel", "error");
			}			
		}else if($("#book_category").val()==0){
			$('.panel').removeClass('panel-refreshing');
			swal('Oops!','Please Select Price book Category','error');
		}else if($("#name").val()==""){
			$('.panel').removeClass('panel-refreshing');
			swal('Oops!','No Valid Name for Price Book','error');
		}else if($("#effective_date").val()==""){
			$('.panel').removeClass('panel-refreshing');
			swal('Oops!','No Valid Effective Date for Price Book','error');
		}else if(detail.length==0){
			if($("#excel_radio").is(':checked')){
				$('.panel').removeClass('panel-refreshing');
				swal('Oops!','No file to Upload','error');
			}else if($("#product_radio").is(':checked')){
				$('.panel').removeClass('panel-refreshing');
				swal('Oops!','No Product Selected','error');
			}
		}else if(detail.length!=0 && l!=0){
			$('.panel').removeClass('panel-refreshing');
			swal('Oops!','Invalid Price','error');
		}else if(detail.length!=0 && m!=0){
			$('.panel').removeClass('panel-refreshing');
			swal('Oops!','Invalid Price','error');
		}

	}

	function load_modal(){
		$('#imageModal').modal();
	}
</script>
@stop
