

@extends('layouts.back_master') @section('title','Add Menu')
@section('css')
<style type="text/css">
  
    
</style>
@stop
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    Devicemanage 
    <small> Management</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a></li>
        <li><a href="{{ url('/device/device-manage/') }}">Devicemanage list</a></li>
        <li class="active">Devicemanage</li>
    </ol>
</section>


<!-- SEARCH -->
<section class="content">



    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">DeviceManage {{ $devicemanage->id }}</h3>
            <div class="box-tools pull-right">
                <a href="{{ url('/device/device-manage') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                <a href="{{ url('/device/device-manage/' . $devicemanage->id . '/edit') }}" title="Edit DeviceManage"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
            </div>
        </div>
        <div class="box-body">

            {!! Form::open([
                'method'=>'DELETE',
                'url' => ['device/devicemanage', $devicemanage->id],
                'style' => 'display:inline'
            ]) !!}
                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-xs',
                        'title' => 'Delete DeviceManage',
                        'onclick'=>'return confirm("Confirm delete?")'
                ))!!}
            {!! Form::close() !!}
            <br/>
            <br/>

            <div class="table-responsive">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th>ID</th><td>{{ $devicemanage->id }}</td>
                        </tr>
                        <tr><th> Type </th><td> {{ $devicemanage->type }} </td></tr><tr><th> Imei No </th><td> {{ $devicemanage->imei_no }} </td></tr><tr><th> Mobile No </th><td> {{ $devicemanage->mobile_no }} </td></tr>
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




























