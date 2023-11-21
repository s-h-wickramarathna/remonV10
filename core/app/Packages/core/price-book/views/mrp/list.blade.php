@extends('layouts.master') @section('title','List MRP Book')
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
    	<a href="javascript:;">MRP Book Management</a>
  	</li>
  	<li class="active">List MRP Book</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>List Mrp</strong>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          		{!!Form::token()!!}
          			
	                <div class="form-group">	            		
	            		<div class="col-sm-12">
	            			<table class="table table-bordered bordered table-striped table-condensed">
	            				<thead>
			                        <tr>
			                            <th rowspan="2" class="text-center" width="4%">#</th>
			                            <th rowspan="2" class="text-center" style="font-weight:normal;">Short Code</th>
			                            <th rowspan="2" class="text-center" style="font-weight:normal;">Name</th>
			                            <th rowspan="2" class="text-center" style="font-weight:normal;">Description</th>
			                            <th rowspan="2" class="text-center" style="font-weight:normal;">Category</th>
			                            <th rowspan="2" class="text-center" style="font-weight:normal;">MRP</th>
			                            </th>
			                        </tr>
                        		</thead>
                        		<tbody id="tbody" name="tbody">
                        			
                        		</tbody>
	            			</table>
	            		
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
<script src="{{asset('assets/file/bootstrap-fileinput-master/js/fileinput.min.js')}}" type="text/javascript"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script> -->
<script type="text/javascript">
	$(document).ready(function(){
		$('.form-validation').validate();	
		get_product();		
	});	

	i=1;

	function get_product(){		
		$('.panel').addClass('panel-refreshing');

		$.ajax({
			url: "{{url('price/mrp/json/productList')}}",
			type: 'GET',
			success: function(data) {
				
				$.each( data, function( key, value ) {
					if(value.mrp){
						$('#tbody').append(
							'<tr>'
							+'<td class="text-center">'+i+'</td>'
							+'<td class="text-center">'+value.short_code+'</td>'
							+'<input type="hidden" id="product_id_'+i+'" name="product_id_'+i+'" value="'+value.id+'"/>'
							+'<td class="text-center">'+value.product_name+' '+value.sizes.sizes+'</td>'
							+'<td class="text-center">'+value.description+'</td>'
							+'<td class="text-center">'+value.category.category_name+'</td>'						
							+'<td class="text-center">'+value.mrp.mrp+'</td>'
							+'</tr>'
						);
					}else{
						$('#tbody').append(
							'<tr>'
							+'<td class="text-center">'+i+'</td>'
							+'<td class="text-center">'+value.short_code+'</td>'
							+'<input type="hidden" id="product_id_'+i+'" name="product_id_'+i+'" value="'+value.id+'"/>'
							+'<td class="text-center">'+value.product_name+' '+value.sizes.sizes+'</td>'
							+'<td class="text-center">'+value.description+'</td>'
							+'<td class="text-center">'+value.category.category_name+'</td>'						
							+'<td class="text-center">0.00</td>'
							+'</tr>'
						);
					}
					i++;
				});	

				$('.panel').removeClass('panel-refreshing');
			},error: function(data){

			}
		});		
		
	}	
	
</script>
@stop
