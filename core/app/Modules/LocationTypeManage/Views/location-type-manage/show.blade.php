

@extends('layouts.back_master') @section('title','Add Menu')
@section('css')
<style type="text/css">
  
    
</style>
@stop
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- <h1>
    Location Management
    <small> Show</small>
    </h1> -->
    <ol class="breadcrumb">
        <li><a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a></li>
        <li>Location Management</li>
        <li class="active"><a href="{{ route('admin.location.type.view') }}">Show</a></li>
    </ol>
    <br>
</section>


<!-- SEARCH -->
<section class="content">



    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Location Type : {{ $type->id }}</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-warning btn-xs" onclick="window.history.back()">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                </button>
                <a href="{{ route('admin.location.type.edit', $type->id) }}" title="Edit LocationTypeManage"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="20%">ID</th>
                                    <th>:</th>
                                    <td>{{ $type->id }}</td>
                                </tr>
                                <tr>
                                    <th width="20%"> Name </th>
                                    <th>:</th>
                                    <td> {{ $type->name?:'-' }} </td>
                                </tr>
                                <tr>
                                    <th width="20%"> Description </th>
                                    <th>:</th>
                                    <td> {{ $type->description?:'-' }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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




























