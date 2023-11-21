@include('app')
@extends('layouts.master')
@section('title','List Employee')
@section('css')
	 <link rel="stylesheet" href="{{asset('assets/css/ui-grid.css')}}">
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

		.breadcrumb > li:hover {
    	color: #030C3C;
		}
  		b, strong {
    	font-weight: bold;
		}	
	</style>
@stop
@section('content')
	<ol class="breadcrumb">
		<li>
			<a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
		</li>
		<li>
			<a href="javascript:;">Employee Management</a>
		</li>
		<li class="active">List Employee</li>
	</ol>
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-bordered">
				<div class="panel-heading border">
					<div class="row"><div class="col-xs-6"><strong>Employee List</strong></div>
						<div class="col-xs-6 text-right"><form action="{{url('employee/add')}}" method="get"><button class="btn btn-success" type="submit"><i class="fa fa-plus" style="width: 21px;padding-right: 16px;"></i>Add</button></form></div>
					</div>
				</div>
				<div class="panel-body">
					<div class="form-group ">
						<div class="col-md-offset-6 loadingModel">
							<img src="{{asset('assets/images/loading.gif')}}">
						</div>
						<div class="noDataModel">
							<h5>No Data...</h5>
						</div>
					</div>
					<div ui-grid="gridOptions" ui-grid-selection ui-grid-exporter ui-grid-pagination class="grid form-group"></div>
					<script>
						var col = [
                            {name: 'id',enableFiltering:false, enableSorting: true},
							{name: 'first_name' , filters: [{type: 'text', placeholder: 'search..'}]},
							{name: 'last_name', filters: [{type: 'text', placeholder: 'search..'}], enableSorting: true},
							{name: 'address',enableFiltering:false, enableSorting: true},
							{name: 'email',enableFiltering:false, enableSorting: true},
							{name: 'mobile',enableFiltering:false, enableSorting: true},
							{name: 'type', filters: [{type: 'text', placeholder: 'search..'}], enableSorting: true}
						];
						var per = {viewable: true, editable: true};
						var routes = {
							data: 'json/list',
							filter: 'filter',
							view: 'view',
							update: 'edit',
							delete: 'delete',
							barcode: 'barcode'
						};
						_genarateTable('{{url("/")}}', col, per, routes);
					</script>
				</div>
			</div>
		</div>
	</div>
@stop

