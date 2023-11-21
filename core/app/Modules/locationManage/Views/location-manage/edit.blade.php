
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
        <li class="active"><a href="{{ route('admin.location.edit', isset($location->id)? $location->id : '') }}"></a></li>
    </ol>
    <br>
</section>

<!-- SEARCH -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Location Edit</h3>
            <div class="box-tools pull-right">
                <!-- <a href="{{ route('admin.location.index') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a> -->
                <button type="button" class="btn btn-warning btn-xs" onclick="window.history.back()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>  
            </div>
        </div>
        <div class="box-body">
            <!-- @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
             -->
            <form action="{{ route('admin.location.edit', isset($location->id)? $location->id : '') }}" enctype="multipart/form-data" class="form-horizontal" method="post" style="padding-top: 50px;">
                {{ csrf_field() }}
                @include ('locationManage::location-manage.form', ['submitButtonText' => 'Update'])
            </form>
        </div>
    </div>  
</section>



@stop
@section('js')

<script type="text/javascript">
    //global variable
    var map;
    var latlon
    var mapOption;
    var marker;

    //load map
    function myMap()
    { 
        latlon =  new google.maps.LatLng({{ isset($location->latitude)? $location->latitude : 6.927079 }}, {{ isset($location->longitude)? $location->longitude : 79.861243 }});

        mapOption = {
            zoom:7,
            center: latlon
        };

        map = new google.maps.Map(document.getElementById("googleMap"), mapOption);

        marker = new google.maps.Marker({
            position: latlon,
            map: map,
            draggable:false,
            title:"Drag me!"
        });

        google.maps.event.addListener(map, 'click', function(event){
            marker.setMap(null);    
            marker = new google.maps.Marker({
                map:       map,
                position:  event.latLng
            });     
        });

        map.setCenter(marker.position);
        marker.setMap(map);
    }

    function hideModal()
    {
        $('#btn_pick_location').click(function(){
            $('#txt_lat_lng').css('display', 'block');
            $('#pickLocationModal').modal('hide');
        });
    }

    function pinMarker()
    {
        document.getElementById('latitude').value = marker.position.lat();
        document.getElementById('longitude').value = marker.position.lng();

        map.setCenter(marker.position);
        marker.setMap(map);
        hideModal();
    }

    $(document).ready(function() {
        $('#pickLocationModal').on('shown.bs.modal', function() {
            myMap();
            pinMarker();
            hideModal();
        });            
    });
</script>
@if($errors->has('img_icon') && $errors->has('img_location'))
    <script type="text/javascript">
        swal("Error!.", "Please select valid image for icon and location.", "error")
    </script>
@elseif($errors->has('img_icon'))
<script type="text/javascript">
        swal("Error!.", "Please select valid image for icon.", "error")
</script>
@elseif($errors->has('img_location'))
    <script type="text/javascript">
        swal("Error!.", "Please select valid image for location image.", "error")
    </script>
@endif
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVkA60Ss-CVwaUppa6kw3WcJQ8Mdhj3i8&callback=myMap"> </script>
@stop




































