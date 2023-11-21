@extends('layouts.master') @section('title','Product List')
@section('css')
    <style type="text/css">
        .switch.switch-sm {
            width: 30px;
            height: 16px;
        }

        .switch.switch-sm span i::before {
            width: 16px;
            height: 16px;
        }

        .btn-success:hover, .btn-success:focus, .btn-success.focus, .btn-success:active, .btn-success.active, .open > .dropdown-toggle.btn-success {
            color: white;
            background-color: #3b3e81;
            border-color: #3b3e81;
        }

        .btn-success {
            color: white;
            background-color: #1E2444;
            border-color: #1E2444;
        }

        .btn-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            background-color: #3b3e81;
            -moz-opacity: 0;
            -khtml-opacity: 0;
            -webkit-opacity: 0;
            opacity: 0;
            -ms-filter: progid:DXImageTransform.Microsoft.Alpha(opacity=0 *100);
            filter: alpha(opacity=0 *100);
            -webkit-transform: scale3d(0.7, 1, 1);
            -moz-transform: scale3d(0.7, 1, 1);
            -o-transform: scale3d(0.7, 1, 1);
            -ms-transform: scale3d(0.7, 1, 1);
            transform: scale3d(0.7, 1, 1);
            -webkit-transition: transform 0.4s, opacity 0.4s;
            -moz-transition: transform 0.4s, opacity 0.4s;
            -o-transition: transform 0.4s, opacity 0.4s;
            transition: transform 0.4s, opacity 0.4s;
            -webkit-animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            -moz-animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            -o-animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            animation-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
        }

        .switch :checked + span {
            border-color: #689A07;
            -webkit-box-shadow: #689A07 0px 0px 0px 21px inset;
            -moz-box-shadow: #2ecc71 0px 0px 0px 21px inset;
            box-shadow: #689A07 0px 0px 0px 21px inset;
            -webkit-transition: border 300ms, box-shadow 300ms, background-color 1.2s;
            -moz-transition: border 300ms, box-shadow 300ms, background-color 1.2s;
            -o-transition: border 300ms, box-shadow 300ms, background-color 1.2s;
            transition: border 300ms, box-shadow 300ms, background-color 1.2s;
            background-color: #689A07;
        }

        .datatable a.blue {
            color: #1975D1;
        }

        .datatable a.blue:hover {
            color: #003366;
        }

        b, strong {
            font-weight: bold;
        }
			
		.online{
			position: relative;
			width: 15px;
			height: 15px;
			background-color: rgba(26, 188, 156,1.0); 
			color:rgba(26, 188, 156,1.0);
			margin: 0 auto;
		}
		.offline{
			position: relative;
			width: 15px;
			height: 15px;
			background-color: rgba(127, 140, 141,1.0); 
			color:rgba(127, 140, 141,1.0);
			margin: 0 auto;
		}
        .online-info-box{
            position: relative;
            width: 15px;
            height: 15px;
            background-color: rgba(26, 188, 156,1.0); 
            color:rgba(26, 188, 156,1.0);
            text-align: left;
            margin-top: 5px;
        }
        .offline-info-box{
            position: relative;
            width: 15px;
            height: 15px;
            background-color: rgba(127, 140, 141,1.0); 
            color:rgba(127, 140, 141,1.0);
            text-align: left;
            margin-top: 5px;
        }
		.btn-green{
			background-color: rgba(22, 160, 133,1.0);
			border-color: rgba(22, 160, 133,1.0);
			color: #fff; 
		}
		.btn-green:hover{
			background-color: rgba(127, 140, 141,1.0);
			color: rgba(236, 240, 241,0.8);
		}
		.thead{
			width: 100px;
			background-color: rgba(127, 140, 141,1.0);
			color: #fff;
		}
		.sm-head{
			color: rgba(127, 140, 141,0.8);
			text-align:center;
			padding-top: 10px;
		}
		.opacity{
			background-color: rgba(236, 240, 241,0.5);
		}
		.error{
			border: 1px solid #e74c3c;
		}
		.error:focus{
			border: 1px solid #e74c3c;
		}
		.firstHeading{
			width: 100%;
			height: auto;
            text-align: center;
            font-weight: bold;
            font-size: 22px;
		}
        .img-user{
            float:left;
            margin-bottom: 20px;
            margin-top: -5px;
        }
        .username{
            float:right;
            margin-bottom: -60px;
            margin-top: -60px;
            margin-left: 160px;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
@stop
@section('content')
	<input type="hidden" name="_token" value="{{ Session::token() }}">
    <ol class="breadcrumb">
        <li>
            <a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Configuration</a>
        </li>
        <li class="active">View Rep details</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <h3>Details <span class="fa fa-list"></span></h3>
                </div>
                <div class="panel-body">
                    @if(sizeof($table) > 0)
						<?php echo $table ?>
                    @else

                    @endif
                    <br>
                    <br>
                    <div class="row">
                    	<div class="col-lg-12">
                    		<h3>Locations <span class="fa fa-map-marker"></span></h3>
                    		<hr>
                    		<div class="">
                    			<div id="map" style="height:500px;width: 100%;"></div>
                    		</div>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
		
 	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwpSpV-AFnaIOwRsH5tsDLYAUARdY20AQ&callback=initMap"
    async defer></script>
    <?php
	$inc = 0;
    ?>
    <script>
      var map;
      var jsNameArray = new Array();
      var onlineUsers = new Array();

      jsNameArray = <?php print_r(json_encode($nameArray))?>;
      onlineUsers = <?php print_r(json_encode($onlineUsers))?>;

      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 7.642602, lng: 80.776978},
          zoom: 7
        });

        var latitude = {{ count($latitude) }};
        var longitude = {{ count($longitude) }};
        var lat = <?php print_r(json_encode($latitude));?>;
        var lon = <?php print_r(json_encode($longitude));?>;

        for (var i = 0; i < longitude; i++) {

            if(onlineUsers[i]['online'])
            {
                var image = "<?php echo asset('assets/images/online-map-marker.png') ?>";
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat[i], lon[i]),
                    draggable:false,
                    map:map,
                    icon: image
                });

                var contentString = 
                '<div id="content">'+
                    '<div id="siteNotice"></div>'+
                    '<h5 id="firstHeading" class="firstHeading">';
                        contentString += onlineUsers[i]['online'];        
                        contentString +=
                    '</h5>'+
                    '<div id="bodyContent">'+

                    '</div>'+
                '</div>';

                var infowindow = new google.maps.InfoWindow({
                  content: contentString
                });

                google.maps.event.addListener(marker,'mouseover', (function(marker, contentString,infowindow)
                { 
                    return function()
                    {
                        infowindow.setContent( contentString);
                        infowindow.open(map,marker);
                    };
                })(marker, contentString,infowindow)); 

                google.maps.event.addListener(marker,'mouseout', (function(marker, contentString,infowindow)
                { 
                    return function()
                    {
                        infowindow.close();
                    };
                })(marker, contentString,infowindow)); 
            }
            else
            {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat[i], lon[i]),
                    draggable:false,
                    map:map
                });

                var contentString = 
                '<div id="content">'+
                    '<div id="siteNotice"></div>'+
                    '<h5 id="firstHeading" class="firstHeading">';
                        contentString += onlineUsers[i]['offline'];        
                        contentString +=
                    '</h5>'+
                    '<div id="bodyContent">'+

                    '</div>'+
                '</div>';

                var infowindow = new google.maps.InfoWindow({
                  content: contentString
                });

                google.maps.event.addListener(marker,'mouseover', (function(marker, contentString,infowindow)
                { 
                    return function()
                    {
                        infowindow.setContent( contentString);
                        infowindow.open(map,marker);
                    };
                })(marker, contentString,infowindow)); 

                google.maps.event.addListener(marker,'mouseout', (function(marker, contentString,infowindow)
                { 
                    return function()
                    {
                        infowindow.close();
                    };
                })(marker, contentString,infowindow)); 
            }

            map.setCenter(marker.position);
            marker.setMap(map);
        }
      }
    </script>
    <script>

    	$(document).ready(function(e){
    		/*$('.datatable').dataTable();*/

    		$('#notify_txt').keyup(function(e){
    			var len = $(this).val();

    			if(len.length == 0)
    			{
    				$('#notify_txt').addClass('error');
    			}
    			else if(len.length > 0)
    			{
    				$('#notify_txt').removeClass('error');
    			}
    		});


    	});

    	var id = "";

    	$('.get_id').click(function(e){
    		id = $(this).attr('data-user-id');
    		$('#myModal_'+id).modal('show');	
    	});

    	$('.notify_btn').click(function(){
    		$('#get_notify_'+id).toggle();
    		$('#save_btn_'+id).toggle();
    	});

    	/*$('.notify_btn').click(function(e){
    		$('#notify_txt').val('');
    		$('#notify_txt').removeClass('error');
    		$('#notify_txt').val('');
    		var uID = $('#notify_msg').find('input').val();
    		$('#notify_msg, #save_btn').toggle();
    	});*/

    	$('.save_btn').click(function(e){
    		$(this).prop("disabled", true);
    		var message = $('#myModal_'+id).find('textarea').val();
    		$(this).find('textarea').removeClass('error');

    		var data = new FormData();
	    	data.append('userID', $('#notify_btn').attr('data-user-id'));
	    	data.append('message', message);
	    	data.append('command', 'notification');

	    	if(message.length == 0)
	    	{
	    		$(this).prop("disabled", false);
	    		$('#myModal_'+id).find('textarea').addClass('error');
	    	}
	    	else
	    	{
	    		$('#myModal_'+id).find('textarea').removeClass('error');
	    		$('#myModal_'+id).addClass('opacity');
	    		$('.loading-img').css('display','block');

	    		$.ajax({
		    		url: '{{url('gcm/send')}}',
		    		method: 'post',
		    		processData: false,
		    		contentType: false,
		    		cache: false,
		    		headers:{
		    			'X-CSRF_TOKEN' : $('input[name=_token]').val()
		    		},
		    		data: data,	    		
		    		success: function(response){
		    			if(response['success'] == true)
		    			{
		    				$('#myModal_'+id).removeClass('opacity');
		    				sweetAlert('Success','Done!.','Success');
		    				$('#myModal_'+id).modal('hide');
		    				$('#myModal_'+id).find('textarea').removeClass('error');
		    				$('#myModal_'+id).find('textarea').val('');
		    				$('.save_btn').prop("disabled", false);
		    				$('.loading-img').css('display','none');
		    			}

		    			if(response['success'] == false)
		    			{
		    				sweetAlert('Error','Something went wrong!.','Error');
		    				$('#myModal_'+id).modal('hide');
		    				$('.loading-img').css('display','none');
		    			}
		    			console.log(response);
		    		},
		    		error: function(xhr){
		    			console.log(xhr);
		    		}
		    	});
	    	}
    	});

	    function getAction(id, command)
	    {
	    	$('.loading-img').css('display','block');
	    	$('#myModal_'+id).addClass('opacity');

	    	var data = new FormData();
	    	data.append('userID', id);
	    	data.append('command', command);

	    	$.ajax({
	    		url: '{{url('gcm/send')}}',
	    		method: 'post',
	    		processData: false,
	    		contentType: false,
	    		cache: false,
	    		headers:{
	    			'X-CSRF_TOKEN' : $('input[name=_token]').val()
	    		},
	    		data: data,	    		
	    		success: function(response){
	    			if(response['success'] == true)
	    			{
	    				$('#myModal_'+id).removeClass('opacity');
	    				sweetAlert('Success','Done!.','Success');
	    				$('#myModal').modal('hide');
	    				$('#notify_txt').removeClass('error');
						$('.loading-img').css('display','none');
	    			}

	    			if(response['success'] == false)
	    			{
	    				sweetAlert('Error','Something went wrong!.','Error');
	    				$('#myModal_'+id).modal('hide');
						$('.loading-img').css('display','none');
	    			}
	    		},
	    		error: function(xhr){
	    			console.log(xhr);
	    		}
	    	});
	    }
	</script>
  </body>
@stop
