@extends('layouts.back_master') @section('title','Device Manage')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/file/bootstrap-fileinput-master/css/fileinput.css')}}" media="all" />
<style type="text/css">
    
</style>
@stop
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    Device
    <small> Management</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a></li>
        <li class="active">Device List</li>
    </ol>
</section>


<!-- SEARCH -->
<section class="content">

    
    <!-- Default box -->
    <div class="box">        
        <div class="box-body">
           {!! Form::open(['method' => 'GET', 'url' => '/admin/device/index', 'role' => 'search'])  !!}
            <div class="row">
                <div class="col-md-2">
                    <input data-toggle="tooltip" data-placement="top" title="Plate No" type="text" class="form-control" name="type" placeholder="Device Type..." value="{{$old['type']}}">
                </div>
                <div class="col-md-2">
                    <input data-toggle="tooltip" data-placement="top" title="Device Code" type="text" class="form-control" name="code" placeholder="Device Code..." value="{{$old['code']}}">
                </div>
                <div class="col-md-3">
                    <input data-toggle="tooltip" data-placement="top" title="Imei No" type="text" class="form-control" name="imei_no" placeholder="Imei No..." value="{{$old['imei_no']}}">
                </div>
                <div class="col-md-3">
                    <input data-toggle="tooltip" data-placement="top" title="Mobile No" type="text" class="form-control" name="mobile_no" placeholder="Mobile No..." value="{{$old['mobile_no']}}">
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
            <h3 class="box-title">Device List</h3>
            <div class="box-tools pull-right">
                <a href="{{ url('/admin/device/create') }}" class="btn btn-success btn-sm" title="Add New Devices">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                </a>
                <a href="#" class="btn btn-info btn-sm upload" title="Upload Device">
                    <i class="fa fa-upload" aria-hidden="true"></i> Upload
                </a>

                <!-- <button class="btn btn-info btn-sm upload" style="margin-left: 5%"><i class="fa fa-arrow-up" aria-hidden="true"></i> Assign</button> -->

            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Type</th>
                            <th style="text-align: center">Device Code</th>
                            <th style="text-align: center">Imei No</th>
                            <th style="text-align: center">Mobile No</th>
                            <th style="text-align: center">Status</th>
                            <th style="text-align: center">Actions</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($devicemanage as $item)
                        <tr>
                            <td style="text-align: center">{{ $item->id }}</td>
                            <td style="text-align: center">{{ $item->types->name}}</td>
                            <td style="text-align: center">{{ $item->code }}</td>
                            <td style="text-align: center">{{ $item->imei_no }}</td>
                            <td style="text-align: center">{{ $item->mobile_no }}</td>
                            @if($item->status==1)
                                <td style="text-align: center"><label class="label label-success">Active</label></td>
                            @else
                                <td style="text-align: center"><label class="label label-danger">Deactivated</label></td>
                            @endif
                            <td style="text-align: center">
                                <a href="{{ url('/admin/device/edit/' . $item->id) }}" title="Edit Device"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                @if($item->status==1)
                                    <button class="btn btn-warning btn-xs inactive" data-id="{{$item->id}}"><i class="fa fa-arrow-down" aria-hidden="true"></i> Deactivate</button>
                                @else
                                    <button class="btn btn-info btn-xs active" data-id="{{$item->id}}" style="margin-left: 5%"><i class="fa fa-arrow-up" aria-hidden="true"></i> Active</button>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper"> {!! $devicemanage->appends(['search' => Request::get('search')])->render() !!} </div>
            </div>            
        </div>
    </div>  
</section>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">            
            <div class="modal-header">              
                <center><h4>Upload Tracking Devices</h4></center>
            </div>            
            <div class="modal-body" style="height: 370px !important">
                {!! Form::open(['method'=>'POST','name'=>'upload_form','id'=>'upload_form','url' => ['admin/device/upload'],'style' => 'display:inline','enctype'=>'multipart/form-data']) !!}
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
                                <button type="submit" style="margin-top: 5px" class="btn btn-primary pull-right upload-device-data">Upload</button>
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

        $('.inactive').on('click',function(){
            $('.refresh').addClass('panel-refreshing');
            ajaxRequest( '{{url('admin/device/inactive')}}' , { 'id' : $(this).data('id')}, 'post', resultFuncInactivate);
        });

        $('.active').on('click',function(){
            $('.refresh').addClass('panel-refreshing');
            ajaxRequest( '{{url('admin/device/active')}}' , { 'id' : $(this).data('id')}, 'post', resultFuncActivate);
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

    function resultFuncInactivate(data){
        $('.refresh').removeClass('panel-refreshing');
        if(data.status=='success'){
            swal('Success','Successfully Device Deactivated','success');
            window.location.reload();
        }else if(data.status=='invalid_id'){
            swal('Oops!','Cannot find vehicle','error');
        }else if(data.status=='not_ajax'){
            swal('Oops!','Try Again','error');
        }else if(data.status=='using'){
            swal('Oops!','This device currently attached to the vehicle. Un-assign it first to deactivate','error');
        }
    }

    function resultFuncActivate(data){
        $('.refresh').removeClass('panel-refreshing');
        if(data.status=='success'){
            swal('Success','Successfully Device Activated','success');
            window.location.reload();
        }else if(data.status=='invalid_id'){
            swal('Oops!','Cannot find vehicle','error');
        }else if(data.status=='not_ajax'){
            swal('Oops!','Try Again','error');
        }
    }

</script>
@stop