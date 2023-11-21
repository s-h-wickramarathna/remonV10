

@extends('layouts.back_master') @section('title','Add Menu')
@section('css')
<style type="text/css">
  
    
</style>
@stop
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    Maintenancetypemanage 
    <small> Management</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a></li>
        <li><a href="{{ url('/admin/maintenance/type/maintenance-type-manage/') }}">Maintenancetypemanage list</a></li>
        <li class="active">Maintenancetypemanage</li>
    </ol>
</section>


<!-- SEARCH -->
<section class="content">



    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">MaintenanceTypeManage {{ $maintenancetypemanage->id }}</h3>
            <div class="box-tools pull-right">
                <a href="{{ url('/admin/maintenance/type/maintenance-type-manage') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                <a href="{{ url('/admin/maintenance/type/maintenance-type-manage/' . $maintenancetypemanage->id . '/edit') }}" title="Edit MaintenanceTypeManage"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
            </div>
        </div>
        <div class="box-body">

            {!! Form::open([
                'method'=>'DELETE',
                'url' => ['admin/maintenance/type/maintenancetypemanage', $maintenancetypemanage->id],
                'style' => 'display:inline'
            ]) !!}
                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-xs',
                        'title' => 'Delete MaintenanceTypeManage',
                        'onclick'=>'return confirm("Confirm delete?")'
                ))!!}
            {!! Form::close() !!}
            <br/>
            <br/>

            <div class="table-responsive">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th>ID</th><td>{{ $maintenancetypemanage->id }}</td>
                        </tr>
                        <tr><th> Name </th><td> {{ $maintenancetypemanage->name }} </td></tr><tr><th> Content </th><td> {{ $maintenancetypemanage->content }} </td></tr>
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




























