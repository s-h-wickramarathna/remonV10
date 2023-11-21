@extends('layouts.back_master') @section('title','Add Menu')
@section('css')
<style type="text/css">
      
</style>
@stop
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- <h1>
    Locationmanage 
    <small> Management</small>
    </h1> -->
    <ol class="breadcrumb">
        <li><a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a></li>
        <li>Location Management</li>
        <li>Location</li>
        <li class="active"><a href="{{ route('admin.location.index') }}">List</a></li>
    </ol>
    <br>
</section>

<!-- SEARCH -->
<section class="content">
    <!-- Default box -->
    <div class="box">        
        <div class="box-body">
           {!! Form::open(['method' => 'GET', 'url' => '/admin/location', 'role' => 'search'])  !!}
            <div class="row">
                <div class="col-lg-11">
                    <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ Input::get('search') !== ''? Input::get('search') : old('search') }}">  
                </div>
                <div class="col-lg-1">
                    <button class="btn btn-default btn-sm btn-block pull-right" type="submit">
                        Find
                    </button>
               </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div> 



    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Location List</h3>
            <div class="box-tools pull-right">
                <a href="{{ url('/admin/location/create') }}" class="btn btn-success btn-sm" title="Add New Location">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                </a>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th style="text-align:right;">ID</th>
                            <th style="text-align:center;">Name</th>
                            <th style="text-align:center;">Address</th>
                            <th style="text-align:center;">Location Type</th>
                            <th style="text-align:right;">Radius</th>
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:center;">Date</th>
                            <th style="text-align:center;" colspan="4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(sizeof($locations) > 0)
                        @foreach($locations as $key => $location)
                            <tr>
                                <td style="text-align: right;">{{ (++$key) }}</td>
                                <td style="text-align: center;">{{ $location->name }}</td>
                                <td style="text-align: center;">{{ $location->address }}</td>
                                <td style="text-align: center;">{{ $location->location_type }}</td>
                                <td style="text-align: right;">{{ $location->radius }}km</td>
                                <td style="text-align: center;">
                                    @if($location->status == 1)
                                        <span class="fa fa-check-square" style="color: #16a085;"></span> Active
                                    @else
                                        <span class="fa fa-check-square" style="color: #c0392b;"></span> Deactive
                                    @endif
                                </td>
                                <td style="text-align: center;">{{ $location->created_at->format('Y-m-d') }}</td>
                                <td style="text-align: center;" width="5%">
                                    <button class="btn btn-default btn-xs btn-map" data-toggle="modal" data-target="#showLocation" data-lat="{{ $location->latitude }}"  data-lon="{{ $location->longitude }}" data-name="{{ $location->name }}">
                                        <span class="fa fa-eye"></span> Map
                                    </button>
                                </td>
                                <td style="text-align: center;" width="5%">
                                    <a href="{{ route('admin.location.view', $location->id) }}" title="View Location">
                                        <button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button>
                                    </a>
                                </td>
                                <td style="text-align: center;" width="5%">
                                    <a href="{{ route('admin.location.edit', $location->id) }}" title="Edit Location">
                                        <button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>
                                    </a>
                                </td>
                                <td style="text-align: center;" width="5%">
                                    <form action="{{ route('admin.location.delete', $location->id) }}" method="get" name="form_action">    
                                        <button class="btn btn-danger btn-xs delete"><i class="fa fa-trash-o" aria-hidden="true" data-url="{{ route('admin.location.delete', $location->id) }}"></i> Delete</button>                                 
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">
                                <div align="center" style="color: #999;font-size: 24px;padding-top: 20px;">Data not found!.</div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="pagination-wrapper"> {!! $locations->appends(['search' => Request::get('search')])->render() !!} </div>
            </div>            
        </div>
    </div>  
</section>

<!-- Modal -->
<div id="showLocation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="location-name"></span> <span class="fa fa-map-marker"></span></h4>
      </div>
      <div class="modal-body">
        <div style="width: 100%;height: 400px;border:1px solid #999;" id="googleMap"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="btn_pick_location" onclick="pinMarker()">Pick Location</button>
      </div>
    </div>

  </div>
</div>


@stop
@section('js')

<script type="text/javascript">
$(document).ready(function() {
  $('.delete').click(function(e){
    e.preventDefault();
    var _url = $(this).data('url');
    var form = $(this).parents('form[name=form_action]');

    swal({
        title: "Are you sure?",
        text: "You want to delete this location?.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    },
    function(isConfirm){
        if(isConfirm)
        {
            $('.confirm').text('Waiting...');
            form.submit();
        }
    });
  });
});
</script>
<script type="text/javascript">
    //global variable
    var map;
    var latlon
    var mapOption;
    var marker;

    var mapBtn;

    //load map
    function myMap(lat, lon, locationName)
    { 
        var loc_name = '';

        if(locationName !== '')
        { 
            loc_name = locationName;
        }
        else
        { 
            loc_name = '-';
        }

        latlon =  new google.maps.LatLng(lat, lon);

        mapOption = {
            zoom:15,
            center: latlon
        };

        map = new google.maps.Map(document.getElementById("googleMap"), mapOption);

        marker = new google.maps.Marker({
            position: latlon,
            map: map,
            draggable:false,
            title: loc_name
        });

        map.setCenter(marker.position);
        marker.setMap(map);
        $('#location-name').text(loc_name);
    }

    function hideModal()
    {
        $('#btn_pick_location').click(function(){
            $('#txt_lat_lng').css('display', 'block');
            $('#pickLocationModal').modal('hide');
        });
    }

    $(document).ready(function() {
        $('#showLocation').on('shown.bs.modal', function() {
            myMap(mapBtn.data('lat'), mapBtn.data('lon'), mapBtn.data('name'));
            hideModal();
        });

        $('.btn-map').click(function(){
            mapBtn = $(this);
        });
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVkA60Ss-CVwaUppa6kw3WcJQ8Mdhj3i8&callback=myMap"> </script>
@stop


































