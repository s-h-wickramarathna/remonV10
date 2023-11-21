

@extends('layouts.back_master') @section('title','Add Menu')
@section('css')
<style type="text/css">
  
    
</style>
@stop
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    Vehiclemanage 
    <small> Management</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a></li>
        <li><a href="{{ url('/admin/vehicle/vehicle-manage/') }}">Vehiclemanage list</a></li>
        <li class="active">Vehiclemanage</li>
    </ol>
</section>


<!-- SEARCH -->
<section class="content">



    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">VehicleManage {{ $vehiclemanage->id }}</h3>
            <div class="box-tools pull-right">
                <a href="{{ url('/admin/vehicle/vehicle-manage') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                <a href="{{ url('/admin/vehicle/vehicle-manage/' . $vehiclemanage->id . '/edit') }}" title="Edit VehicleManage"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
            </div>
        </div>
        <div class="box-body">

            {!! Form::open([
                'method'=>'DELETE',
                'url' => ['admin/vehicle/vehiclemanage', $vehiclemanage->id],
                'style' => 'display:inline'
            ]) !!}
                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-xs',
                        'title' => 'Delete VehicleManage',
                        'onclick'=>'return confirm("Confirm delete?")'
                ))!!}
            {!! Form::close() !!}
            <br/>
            <br/>

            <div class="table-responsive">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th>ID</th><td>{{ $vehiclemanage->id }}</td>
                        </tr>
                        <tr><th> Type </th><td> {{ $vehiclemanage->type }} </td></tr><tr><th> Plate No </th><td> {{ $vehiclemanage->plate_no }} </td></tr><tr><th> Chassis No </th><td> {{ $vehiclemanage->chassis_no }} </td></tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>  
</section>



@stop
@section('js')

<script type="text/javascript">
$(document).ready(function() {
  
});
</script>
@stop




























