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
        <li class="active">Rep's DB</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <h3>Rep's DB <span class="fa fa-list"></span></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered bordered table-striped table-condensed datatable">
                        <thead style="background-color:rgba(52, 73, 94,0.5);color:#fff;">
	                        <tr>
	                            <th class="text-center" width="4%">#</th>
	                            <th class="text-center" style="font-weight:normal;">DB Name</th>
	                            <th class="text-center" style="font-weight:normal;">Action</th>
	                        </tr>
                        </thead>
                        <?php 
                        	$path = base_path().'\dbbackup\\';
                        	if(is_dir($path))
                        	{
                        		if($folder = opendir($path))
                        		{
                        			$all_files = array();

                        			while(($file = readdir($folder)) !== false)
                        			{
                        				if($file != '.gitignore' && $file != '.' && $file != '..')
                        				{
                        					array_push($all_files, $file);
			                        	}
		                        	}
		                        	closedir($folder);

		                        	rsort($all_files);

		                        	foreach($all_files as $key => $file)
		                        	{
		                        		?>
				                        	<tr>
					                        	<td style="vertical-align:middle;">{{ ++$key }}</td>
					                        	<td style="vertical-align:middle;">{{ $file }}</td>
					                        	<td width="15%">
					                        		<div align="center">
					                        			<a href="../../core/dbbackup/{{ $file }}"  download class="btn btn-block btn-green" style="color: #fff;vertical-align:middle;text-align:center;padding: 1px 20px;">Download <span class="fa fa-save"></span>
					                        			</a>
					                        		</div>
					                        	</td>
					                        </tr>
			                        	<?php
		                        	}
                        		}
                        	}
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
 	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwpSpV-AFnaIOwRsH5tsDLYAUARdY20AQ&callback=initMap"
    async defer></script>

    <script>
    	$(document).ready(function(e){
    		$('.datatable').dataTable();
    	});
	</script>
  </body>
@stop
