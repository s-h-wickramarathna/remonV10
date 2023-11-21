@extends('layouts.back_master') @section('title','Menu List')
@section('css')
  	<link rel="stylesheet" href="{{asset('assets/core/css/map.css')}}">
<style type="text/css">
 #map {
	width: 100%;
 }

 .vehicle-color{
	width: 10px;
	height: 10px;
	display: block;
	margin: 5px;
}
</style>


@stop
@section('content')

<!-- Main content -->
<section class="content" ng-app="ngApp" ng-controller="TrackingController" ng-cloak>	
	
	<!-- <div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  text-left mb10" >
			<button type="button" class="btn btn-danger btn-xs">Full View</button>
			<button type="button" class="btn btn-info btn-xs">Reload</button>
			<button type="button" class="btn btn-info btn-xs" data-toggle="control-sidebar">Side Bar</button>
		</div>
	</div> -->
	<div class="row">		
		<div ng-repeat="map in maps">
	     	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
		     	<div class="box box-default multimap"  id="hud">
			        <div class="box-header with-border" style="margin-bottom:2px">
			          <!-- <div class="box-tools pull-right">	
			          	<button type="button" class="btn btn-box-tool" data-widget="remove"
			            ng-click="removeVehicle(map)">
			            	<i class="fa fa-times"></i>
			            </button>
			          </div> -->
			          <h5 class="box-title pull-left">
			          	<b>@{{map.map.vehicleNo}}</b><br>
			          	<span style="font-size:9px">@{{map.imei}}</span>
			          </h5>
			          <p class="multimap-driver-name pull-right" style="margin-top:5px">@{{map.vehicle.map.status}}
			          </p>			          	 
			        </div>
			        <!-- /.box-header -->
			        <div class="box-body" style="padding: 0;">
			          	<div class="map">
				 			<ng-map zoom="8" center="@{{map.map.center}}" id="@{{map.map.imei}}"></ng-map>
				      	</div>
			        </div>
			        <!-- /.box-body -->	          
			    </div>			      
	     	</div>
	    </div>	
	</div>

	



	<aside class="control-sidebar control-sidebar-dark control-sidebar-open">
		<div class="sidebar-controller">
			<a href="#" data-toggle="control-sidebar">
				<i class="fa fa-gears"></i>
			</a>
		</div>
	    <!-- Create the tabs -->
	    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
	      <li class="active">
	      	<a href="#control-sidebar-active-vehicles-tab" data-toggle="tab"><i class="fa fa-truck"></i></a>
	      </li>
	      <li>
	      	<a href="#control-sidebar-alerts-tab" data-toggle="tab">
	      		<i class="fa fa-bell"></i>      	
	      		<span data-toggle="tooltip" title="" class="badge bg-green" data-original-title="3 New Messages">3</span>
	      	</a>
	      </li>
	      <li><a href="#control-sidebar-warnnings-tab" data-toggle="tab">
	      	<i class="fa fa-exclamation-triangle"></i></a>
	      </li>
	    </ul>
	    <!-- Tab panes -->
	    <div class="tab-content" >
	      <!-- Home tab content -->
		    <div class="tab-pane active" id="control-sidebar-active-vehicles-tab">
		        <div class="row">
		        	<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
		        		<h5 class="control-sidebar-heading">Active Vehicles</h5>
		        	</div>
		        	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right filter-panel">		        		
		        		<a href="javascript:void(0)" style="margin-right:10px;">
		        			<i class="fa fa-filter " aria-hidden="true" style="color:#fff"
		        			data-toggle="modal" href='#modal-id'></i>
		        		</a>
		        		<a href="javascript:void(0)">
		        			<i class="fa fa-ellipsis-v " aria-hidden="true" style="color:#fff"></i>
		        		</a>
		        	</div>
		        </div>
		        <div class="row">
		        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		        		<div style="margin-bottom:10px">
						 	<!-- <div class="input-group "> -->
							  <!-- <span class="input-group-addon custom-text-view" id="basic-addon1">
							  	<i class="fa fa-search" aria-hidden="true"></i>
							  </span> -->
							  <input type="text"
							  class="form-control custom-text-view" 
							  placeholder="Vehicle No" 
							  aria-describedby="basic-addon1"
							  ng-model="vehicleNo">
							<!-- </div>	 -->
						</div>
		        	</div>
		        </div>

		        <ul class="control-sidebar-menu" id="vehicle-list">
		          <li>
	          	 	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "  style="margin-bottom:8px;">
	          	 		 <p class="pull-left" style="font-size:10px;">Total : @{{filtered.length}}</p>
	          	 	</div>
		          </li>
		          <li ng-repeat="vehicle in vehicles | filter : vehicleNo as filtered">			          	
						<div class="vehicles_list">
			            	<div class="row">
	            				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	            					<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
		            					<i class="vehicle-color bg-red" ></i>
		            				</div>
		            				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
		            					@{{vehicle.plate_no}}
		            				</div>
		            				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
		            					<!-- @{{vehicle.status}} -->
		            					<p>uknown</p>
		            				</div>
		            				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
		            					<p>5 @{{vehicle.lastUpdateUnit}} ago</p>
		            				</div>
		            				<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">		            					
						          		  <input type="checkbox" 
											     ng-checked="maps.indexOf(vehicle) != -1" 
											     ng-click="loadVehicle(vehicle)"
											     class="pull-right" 
										  	/>
		            				</div>
	            				</div>
			            	</div>
			            </div>	
		          </li>
		        </ul>
		    </div>    

		    <!-- alerts-tab -->
		    <div class="tab-pane" id="control-sidebar-alerts-tab">
	        	<h3 class="control-sidebar-heading">Alerts</h3>
	      	</div>
	      	<!--// alerts-tab -->

	      	<!-- warnnings-tab -->
	      	<div class="tab-pane" id="control-sidebar-warnnings-tab">
	        	<h3 class="control-sidebar-heading">warnnings</h3>
	      	</div>
	      	<!-- //warnnings-tab -->
	</aside>

	<div class="modal fade" id="modal-id">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	




</section><!-- /.content -->

@stop
@section('js')


<!-- datatables -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVkA60Ss-CVwaUppa6kw3WcJQ8Mdhj3i8"> </script>


<script src="{{asset('assets/dist/socket.io/socket.io.js')}}"></script>
<script src="{{asset('assets/dist/angular/angular/angular.min.js')}}"></script>
<script src="{{asset('assets/dist/ng-map/ng-map.js')}}"></script>

<!-- ANGULAR -->
<script>
	var app = angular.module('ngApp', ['ngMap']);
	app.constant('BASE_URL', "{{asset('/')}}");
	app.constant('USER_ID', "{{$user->id}}");
	app.constant('GPS_SOCKET', "{{config('app.gps_socket')}}");
</script>


<script src="{{asset('/angular/modules/MultiMapManage/app.js')}}"></script>
<script src="{{asset('/angular/modules/services/services.js')}}"></script>
<script src="{{asset('/angular/modules/MultiMapManage/directives/directives.js')}}"></script>
<script src="{{asset('/angular/modules/MultiMapManage/controllers/TrackingController.js')}}"></script>
<!-- <script src="{{asset('assets/dist/interact/interact.min.js')}}"></script> -->




<script type="text/javascript">
	$('body').addClass('sidebar-collapse');

	$('#vehicle-list').slimScroll({
        height: '450px',
        color: '#fff',
        size: '10px',
        position:'right'
    });

			
</script>
@stop
