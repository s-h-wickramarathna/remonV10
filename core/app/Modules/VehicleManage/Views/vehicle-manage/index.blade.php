@extends('layouts.back_master') @section('title','Vehicle Manage')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/file/bootstrap-fileinput-master/css/fileinput.css')}}" media="all" />
<style type="text/css">
  
    
</style>
@stop
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    Vehicle 
    <small> Management</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a></li>
        <li class="active">Vehicle Manage</li>
    </ol>
</section>


<!-- SEARCH -->
<section class="content">

    
    <!-- Default box -->
    <div class="box">        
        <div class="box-body">
           {!! Form::open(['method' => 'GET', 'url' => '/admin/vehicle/index', 'role' => 'search'])  !!}
            <div class="row">
                <div class="col-md-4">
                    <input data-toggle="tooltip" data-placement="top" title="Plate No" type="text" class="form-control" name="plate_no" placeholder="Plate No..." value="{{$old['plate_no']}}">
                </div>
                <div class="col-md-4">
                    <input data-toggle="tooltip" data-placement="top" title="Chassis No" type="text" class="form-control" name="chassis_no" placeholder="Chassis No..." value="{{$old['chassis_no']}}">
                </div>
                <div class="col-md-2 pull-right">
                   <button class="btn btn-default pull-right" type="submit">
                        Find
                   </button>
                </div>
            </div>
            <div class="row">
               
            </div>
            {!! Form::close() !!}
        </div>
    </div> 



    <!-- Default box -->
    <div class="box refresh">
        <div class="box-header with-border">
            <h3 class="box-title">Vehicle List</h3>
            <div class="box-tools pull-right">
                <a href="{{ url('/admin/vehicle/create') }}" class="btn btn-success btn-sm" title="Add New Vehicle">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                </a>

                @if($user->hasAnyAccess(['admin.vehicle.upload', 'admin']))
                    <a href="#" class="btn btn-info btn-sm upload" title="Upload Device">
                        <i class="fa fa-upload" aria-hidden="true"></i> Upload
                    </a>
                @else
                    <a href="#" class="btn btn-info btn-sm upload" title="Upload Device">
                        <i class="fa fa-upload" aria-hidden="true"></i> Upload
                    </a>
                @endif
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Type</th>
                            <th style="text-align: center">Plate No</th>
                            <th style="text-align: center">Chassis No</th>
                            <th style="text-align: center">Assigned Device</th>
                            <th style="text-align: center">Vehicle Status</th>
                            <th style="text-align: center">Actions</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($vehiclemanage as $item)
                        <tr>
                            <td style="text-align: center">{{ $item->id }}</td>
                            <td style="text-align: center">{{ $item->types->name}}</td>
                            <td style="text-align: center">{{ $item->plate_no }}</td>
                            <td style="text-align: center">{{ $item->chassis_no }}</td>
                            @if($item->assignedDevice!=Null)
                                <td style="text-align: center">{{$item->assignedDevice->device->code}}</td>
                            @else
                                <td style="text-align: center"><label class="label label-danger">Not Assigned</label></td>
                            @endif

                            @if($item->status==1)
                                <td style="text-align: center"><label class="label label-success">Active</label></td>
                            @else
                                <td style="text-align: center"><label class="label label-danger">Deactivated</label></td>
                            @endif
                            <td style="text-align: center">

                                <a href="{{ url('/admin/vehicle/edit/' . $item->id) }}" title="Edit Vehicle"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                
                                @if($item->status==1)

                                    @if($item->assignedDevice!=Null)
                                        
                                        @if($user->hasAnyAccess(['admin.vehicle.unassign', 'admin']))
                                            <button class="btn btn-warning btn-xs unassign" data-id="{{$item->id}}"><i class="fa fa-arrow-down" aria-hidden="true"></i> Un-Assign</button>
                                        @else
                                            <button class="btn btn-warning btn-xs unassign" disabled="true" data-id="{{$item->id}}"><i class="fa fa-arrow-down" aria-hidden="true"></i> Un-Assign</button>
                                        @endif

                                    @else
                                        @if($user->hasAnyAccess(['admin.vehicle.assign', 'admin']))
                                            <button class="btn btn-info btn-xs assign" data-id="{{$item->id}}" style="margin-left: 5%"><i class="fa fa-arrow-up" aria-hidden="true"></i> Assign</button>
                                        @else
                                            <button class="btn btn-info btn-xs assign" data-id="{{$item->id}}" disabled="true" style="margin-left: 5%"><i class="fa fa-arrow-up" aria-hidden="true"></i> Assign</button>
                                        @endif                                        
                                        
                                    @endif

                                    @if($user->hasAnyAccess(['admin.vehicle.inactive', 'admin']))
                                        <button class="btn btn-warning btn-xs inactive" data-id="{{$item->id}}"><i class="fa fa-arrow-down" aria-hidden="true"></i> Deactivate</button>
                                    @else
                                        <button class="btn btn-warning btn-xs inactive" data-id="{{$item->id}}"><i class="fa fa-arrow-down" aria-hidden="true"></i> Deactivate</button>
                                    @endif

                                @else

                                    @if($item->assignedDevice!=Null)
                                        <button class="btn btn-warning btn-xs unassign" disabled="true" data-id="{{$item->id}}"><i class="fa fa-arrow-down" aria-hidden="true"></i> Un-Assign</button>
                                    @else
                                        <button class="btn btn-info btn-xs assign" disabled="true" data-id="{{$item->id}}" style="margin-left: 5%"><i class="fa fa-arrow-up" aria-hidden="true"></i> Assign</button>
                                    @endif

                                    @if($user->hasAnyAccess(['admin.vehicle.active', 'admin']))
                                        <button class="btn btn-info btn-xs active" data-id="{{$item->id}}" style="margin-left: 5%"><i class="fa fa-arrow-up" aria-hidden="true"></i> Active</button>
                                    @else
                                        <button class="btn btn-info btn-xs active" disabled="true" data-id="{{$item->id}}" style="margin-left: 5%"><i class="fa fa-arrow-up" aria-hidden="true"></i> Active</button>
                                    @endif

                                @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper"> {!! $vehiclemanage->appends(['search' => Request::get('search')])->render() !!} </div>
            </div>            
        </div>
    </div>  
</section>

<div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">            
            <div class="modal-header">              
                <center><h4>Attach Device to Vehicle</h4></center>
            </div>
            
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 40px">
                    <label class="col-sm-2 control-label required">Device</label>
                    <div class="col-sm-8">
                        {!! Form::select('assign_device',$deviceList, [],['class'=>'chosen form-control','style'=>'width:100%;','required','data-placeholder'=>'Choose Device','id'=>'assign_device']) !!}

                        <input type="hidden" name="vehicle_id" id="vehicle_id">
                    </div>                          
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary pull-right" onclick="assign()">Save</button>
                    </div>
                </div>              
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">            
            <div class="modal-header">              
                <center><h4>Upload Vehicles</h4></center>
            </div>            
            <div class="modal-body" style="height: 370px !important">
                {!! Form::open(['method'=>'POST','name'=>'upload_form','id'=>'upload_form','url' => ['admin/vehicle/upload'],'style' => 'display:inline','enctype'=>'multipart/form-data']) !!}
                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('type', 'Type', ['class' => 'col-md-2 control-label']) !!}
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                {!! Form::select('type', $typeList, null, ['class' => 'form-control']) !!}
                                {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 6px">
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Select File</label>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input id="input-ficons-1" name="inputficons1" multiple type="file" class="file-loading">
                            </div>                          
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2 pull-right">
                                <button type="submit" style="margin-top: 5px" class="btn btn-primary pull-right upload-vehicle-data">Upload</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@stop
@section('js')

<script src="{{asset('assets/file/bootstrap-fileinput-master/js/fileinput.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#input-ficons-1").fileinput({
            uploadUrl: "",
            uploadAsync: true,
            showUpload:false,
            previewFileIcon: '<i class="fa fa-file"></i>',
            allowedPreviewTypes: null, // set to empty, null or false to disable preview for all types
            previewFileIconSettings: {
                'docx': '<i class="fa fa-file-word-o text-primary"></i>',
                'xlsx': '<i class="fa fa-file-excel-o text-success"></i>',
                'pptx': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
                'jpg': '<i class="fa fa-file-photo-o text-warning"></i>',
                'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
                'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
            }
        });
        
        $('.unassign').on('click',function(){
            $('.refresh').addClass('panel-refreshing');
            ajaxRequest( '{{url('admin/vehicle/unassign')}}' , { 'id' : $(this).data('id')}, 'post', resultFuncUnassign);
        });

        $('.assign').on('click',function(){
            vid=$(this).data('id');
            $('#vehicle_id').val(vid);
            $('#assignModal').modal();
            // ajaxRequest( '{{url('admin/vehicle/assign')}}' , { 'id' : $(this).data('id')}, 'post', resultFuncAssign);
        });

        $('.inactive').on('click',function(){
            $('.refresh').addClass('panel-refreshing');
            ajaxRequest( '{{url('admin/vehicle/inactive')}}' , { 'id' : $(this).data('id')}, 'post', resultFuncInactivate);
        });

        $('.active').on('click',function(){
            $('.refresh').addClass('panel-refreshing');
            ajaxRequest( '{{url('admin/vehicle/active')}}' , { 'id' : $(this).data('id')}, 'post', resultFuncActivate);
        });

        $('.upload').on('click',function(){
            $('#uploadModal').modal();            
        });

        $("#upload_form").submit(function(e) {                
            $('#uploadModal').modal('toggle');        
            $('.refresh').addClass('panel-refreshing');
            $.ajax({
                type: "POST",
                url: "{{'upload'}}",
                data: new FormData(this),
                contentType: false,       
                cache: false,             
                processData:false,
                success: function(data){
                    
                    $('.refresh').removeClass('panel-refreshing');
                    
                    if(data.status=='success'){
                        swal('Done',data.msg,data.status);
                        window.location.reload();
                    }else if(data.status=='error'){
                        swal('Oops',data.msg,data.status);
                    }

                },error: function(data){

                }
            });

            e.preventDefault(); // avoid to execute the actual submit of the form.
        });

    });

    function resultFuncUnassign(data){
        $('.refresh').removeClass('panel-refreshing');
        if(data.status=='success'){
            swal('Success','Successfully Device Un-assinged','success');
            window.location.reload();
        }else if(data.status=='invalid_id'){
            swal('Oops!','Cannot find vehicle','error');
        }else if(data.status=='not_ajax'){
            swal('Oops!','Try Again','error');
        }

    }

    function assign(){
        assignDevice=$('#assign_device').val();
        if(assignDevice!=""){
            $('#assignModal').modal('toggle');
            $('.refresh').addClass('panel-refreshing');
            ajaxRequest( '{{url('admin/vehicle/assign')}}' , { 'did' : assignDevice,'vid': $('#vehicle_id').val()}, 'post', resultFuncAssign);
        }else{
            swal('Oops!','No device to attach','error');   
        }
    }

    function resultFuncAssign(data){
        $('.refresh').removeClass('panel-refreshing');
        if(data.status=='success'){
            swal('Success','Successfully Device assinged','success');
            window.location.reload();
        }else if(data.status=='invalid_id'){
            swal('Oops!','Cannot find vehicle','error');
        }else if(data.status=='not_ajax'){
            swal('Oops!','Try Again','error');
        }else if(data.status=='error'){
            swal('Oops!','Transaction Error','error');
        }

    }

    function resultFuncInactivate(data){
        $('.refresh').removeClass('panel-refreshing');
        if(data.status=='success'){
            swal('Success','Successfully Vehicle Deactivated','success');
            window.location.reload();
        }else if(data.status=='invalid_id'){
            swal('Oops!','Cannot find vehicle','error');
        }else if(data.status=='not_ajax'){
            swal('Oops!','Try Again','error');
        }else if(data.status=='using'){
            swal('Oops!','This Vehicle has attached a device. Un-assign it first to deactivate','error');
        }
    }

    function resultFuncActivate(data){
        $('.refresh').removeClass('panel-refreshing');
        if(data.status=='success'){
            swal('Success','Successfully Vehicle Activated','success');
            window.location.reload();
        }else if(data.status=='invalid_id'){
            swal('Oops!','Cannot find vehicle','error');
        }else if(data.status=='not_ajax'){
            swal('Oops!','Try Again','error');
        }
    }

</script>
@stop