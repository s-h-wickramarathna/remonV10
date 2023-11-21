@extends('layouts.master') @section('title','Standard Price Book List')
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
    	<a href="javascript:;">Standard Price Book Management</a>
  	</li>
  	<li class="active">Standard Price Book List</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
        		<strong>Price Book List</strong>
      		</div>
          	<div class="panel-body">
          		<form role="form" class="form-horizontal form-validation" method="post">
          		{!!Form::token()!!} 			          			

	                <div class="row">	                	
	                	<div class="col-sm-12">
	                		
			          		<table class="table table-bordered bordered table-striped table-condensed datatable" id="price_book" name="price_book">
				              	<thead>
					                <tr>
					                  	<th rowspan="2" class="text-center">#</th>
					                  	<th rowspan="2" class="text-center" style="font-weight:normal;">Price Book</th>
					                  	<th rowspan="2" class="text-center" style="font-weight:normal;">Category</th>
					                  	<th rowspan="2" class="text-center" style="font-weight:normal;">Created At</th>
					                  	<th rowspan="2" class="text-center" style="font-weight:normal;">Status</th>
					                  	<th colspan="2" class="text-center" style="font-weight:normal;">Action</th>
					                </tr>
					                <tr style="display: none;">
					                	<th style="display: none;"></th>
					                	<th style="display: none;"></th>
					                </tr>
				              	</thead>
				            </table>
	                	</div>						
					</div>	                
            	</form>
          	</div>
        </div>
	</div>
</div>

<div class="modal fade" id="priceBookDetailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
            	<div class="row" style="text-align: center">
            		<h4>Price Book Detail</h4>
            	</div>
            	<div class="row" style="margin: 0px !important">
	            	<table class="table table-bordered bordered table-striped table-condensed datatable" id="price_book_detail" name="price_book_detail">
		              	<thead>
			                <tr>
			                  	<th rowspan="2" class="text-center">#</th>
			                  	<th rowspan="2" class="text-center" style="font-weight:normal;">Price Book</th>
			                  	<th rowspan="2" class="text-center" style="font-weight:normal;">Product Category</th>
			                  	<th rowspan="2" class="text-center" style="font-weight:normal;">Product Code</th>
			                  	<th rowspan="2" class="text-center" style="font-weight:normal;">Product Name</th>
			                  	<th rowspan="2" class="text-center" style="font-weight:normal;">Effective Date</th>
			                  	<th colspan="2" class="text-center" style="font-weight:normal;">Price</th>
			                </tr>
			                <tr style="display: none;">
			                	<th style="display: none;"></th>
			                </tr>
		              	</thead>
		            </table>
		        </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
	var id = 0;
	var table1 = '';
	var table2 = '';
	$(document).ready(function(){
		$(".panel").addClass('panel-refreshing');
		$('.form-validation').validate();

		table1 = generateTable('#price_book', '{{url('price/standerd/json/listPriceBook')}}',[],[0,1,2,3,4,5,6],[],[],[0,"asc"]);
		table2 = generateTable('#price_book_detail', '{{url('price/standerd/json/listPriceBookDetail')}}/0',[],[0,1,2,3,4,5],[],[],[0,"asc"]);

		table1.on('draw.dt',function(){
			$("[data-toggle=tooltip]").tooltip();			
		});
		$(".panel").removeClass('panel-refreshing');
	});

	function view_detail(id){		
		$(".panel").addClass('panel-refreshing');
		table2.ajax.url('{{url('price/standerd/json/listPriceBookDetail')}}/'+id).load(function(){
            $(".panel").removeClass('panel-refreshing');
        });			
		$('#priceBookDetailModal').modal();
	}
</script>
@stop
