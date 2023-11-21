
@extends('layouts.back_master') @section('title','Add Menu')
@section('css')
<style type="text/css">
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }  
    .red-borded{
        border:1px solid #dd4b39;
    }  
    .red-txt{
        color: #dd4b39;
    }
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
        <li class="active"><a href="{{ route('admin.location.create') }}">Create</a></li>
    </ol>
    <br>
</section>


<!-- SEARCH -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Create Location</h3>
            <div class="box-tools pull-right">
                <!-- <a href="{{ route('admin.location.index') }}" class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> -->
                <button type="button" class="btn btn-warning btn-xs" onclick="window.history.back()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
            </div>
        </div>
        <div class="box-body" style="padding-top: 50px;">
            <form method="post" action="{{ route('admin.location.store') }}" class="form-horizontal" accept-charset="utf-8" enctype="multipart/form-data">
                {{ csrf_field() }}
                @include ('locationManage::location-manage.form')    
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
        latlon =  new google.maps.LatLng(6.927079,79.861243);

        mapOption = {
            zoom:7,
            center: latlon
        };

        map = new google.maps.Map(document.getElementById("googleMap"), mapOption);

        marker = new google.maps.Marker({
            position: latlon,
            map: map,
            draggable:true,
            title:"Drag me!"
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
<script type="text/javascript">

    function previewImg(evt, selector)
    {
        var tgt = evt.target || window.event.srcElement,
        files = tgt.files;
        // FileReader support
        if (FileReader && files && files.length) {
            var fr = new FileReader();
            fr.onload = function () {
                document.getElementById(selector).src = fr.result;
                document.getElementById('note').style.display = 'none'
            }
            fr.readAsDataURL(files[0]);
        }

        // Not supported
        else {
            // fallback -- perhaps submit the input to an iframe and temporarily store
            // them on the server until the user's session ends.
        }
    }

    document.getElementById('img_icon').onchange = function (evt)
    {
        previewImg(evt, 'icon_preview');
    }

    document.getElementById('img_location').onchange = function (evt)
    {
        previewImg(evt, 'img_preview');
    }
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




































