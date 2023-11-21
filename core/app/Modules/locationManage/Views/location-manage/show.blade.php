

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
        <li><a href="#">Location Management</a></li>
        <li class="active"><a href="{{ route('admin.location.view', isset($location->id)? $location->id:'') }}">Show</a></li>
    </ol>
    <br>
</section>

<!-- SEARCH -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <div class="box-title pull-right">
                <!-- <a href="{{ route('admin.location.index') }}" title="Back">
                    <button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
                </a> -->
                <button class="btn btn-warning btn-xs" type="button" onclick="window.history.back()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
                <a href="{{ route('admin.location.edit', isset($location->id)? $location->id:'') }}" title="Edit Location">
                    <button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>
                </a>
            </div>
            <div class="box-title pull-left">
                <form action="{{ route('admin.location.delete', isset($location->id)? $location->id:'') }}" name="form_action">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger btn-xs delete" title="Delete location">
                        <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        <br>
        <br>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <table class="table table-borderless table-responsive">
                        <tbody>
                            @if(count($location) > 0)
                                <tr>
                                    <th width="20%">ID</th>
                                    <td>{{ $location->id }}</td>
                                </tr>
                                <tr>
                                    <th>Location Name</th>
                                    <td>{{ $location->name }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $location->address }}</td>
                                </tr>
                                <tr>
                                    <th>Location Type</th>
                                    <td>{{ $location->location_type }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $location->address }}</td>
                                </tr>
                                <tr>
                                    <th>Remark</th>
                                    <td>{{ $location->remark }}</td>
                                </tr>
                                <tr>
                                    <th>Radius</th>
                                    <td>{{ $location->radius }}km</td>
                                </tr>
                                <tr>
                                    <th>Latitude</th>
                                    <td>{{ $location->latitude }}</td>
                                </tr>
                                <tr>
                                    <th>Longitude</th>
                                    <td>{{ $location->longitude }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($location->status == 1)
                                            <span class="fa fa-check-square" style="color: #16a085;"></span> Active
                                        @else
                                            <span class="fa fa-check-square" style="color: #c0392b;"></span> Deactive
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Icon</th>
                                    <td>
                                        <img src="{{ asset(!empty($location->icon_path)? $location->icon_path : 'assets/front/img/default-img.jpg') }}" width="100">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Location Image</th>
                                    <td>
                                        <img src="{{ asset(!empty($location->location_img_path)? $location->location_img_path : 'assets/front/img/default-img.jpg') }}" width="200">
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>
                                        <div align="center" style="color: #999;font-size: 24px;padding-top: 20px;">Data not found!.</div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</section>



@stop
@section('js')

<script type="text/javascript">
$(document).ready(function() {
    $('.delete').on('click',function(e){
        e.preventDefault();
        var status = $(this).find('input[name=type_status]').val();
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
@stop




























