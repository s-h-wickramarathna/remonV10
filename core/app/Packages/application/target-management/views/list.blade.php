@include('app')
@extends('layouts.master')
@section('title','List Target')
@section('css')
	<link rel="stylesheet" href="{{asset('assets/css/ui-grid.css')}}">
	<style type="text/css">
		.panel.panel-bordered {
			border: 1px solid #ccc;
		}

		.btn-primary {
			color: white;
			background-color: #1E2444;
			border-color: #1E2444;
		}

		.chosen-container{
			font-family: 'FontAwesome', 'Open Sans',sans-serif;
		}
	</style>
@stop
@section('content')
	<ol class="breadcrumb">
		<li>
			<a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
		</li>
		<li>
			<a href="javascript:;">Target Management</a>
		</li>
		<li class="active">List Target</li>
	</ol>
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-bordered">
				<div class="panel-heading border">
					<div class="row"><div class="col-xs-6"><strong>Target List</strong></div>

						@if($isadd)
							<div class="col-xs-6 text-right"><form action="{{url('marketeerTarget/add')}}" method="get"><button class="btn btn-success" type="submit"><i class="fa fa-plus" style="width: 28px;"></i>Add</button></form></div>
						@endif						
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
							{name: 'first_name' , filters: [{type: 'text', placeholder: 'Search'}]},
							{name: 'value' , filters: [{type: 'text', placeholder: 'Search'}]},
							{name: 'from', filters: [{type: 'text', placeholder: 'Search'}]},
							{name: 'to', filters: [{type: 'text', placeholder: 'Search'}]}
						];
						var per =  {viewable: false, editable: true};
						var routes = {
							data: 'json/list',
							filter: 'filter',
							view: 'view',
							update: 'edit',
							delete: 'delete'
						};
						_genarateTable('{{url("/")}}', col, per, routes);
					</script>
				</div>
			</div>
		</div>
	</div>
@stop

