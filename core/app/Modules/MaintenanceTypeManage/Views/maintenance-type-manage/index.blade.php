@extends('layouts.back_master') @section('title','Add Menu')
@section('css')
<style type="text/css">
  
    
</style>
@stop
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    Maintenance Type 
    <small>Management</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a></li>
        <li>Maintenance Type Management</li>
        <li class="active">list</li>
    </ol>
</section>


<!-- SEARCH -->
<section class="content">

    
    <!-- Default box -->
    <div class="box">        
        <div class="box-body">
           {!! Form::open(['method' => 'GET', 'role' => 'search'])  !!}
            <div class="row">
                <div class="col-md-6">
                    <input data-toggle="tooltip" data-placement="top" title="Search" type="text" class="form-control" name="search" placeholder="Search...">  
                </div>
                <div class="col-md-6 text-right">
                    <div class="filter-tools">
                        <a class="btn btn-info pull-right btn-sm" href="{{ url('admin//employee/add') }}">
                            <i class="fa fa-search"></i>  
                        </a>
                        <button type="submit" class="btn btn-default btn-sm pull-right"><i class="fa fa-refresh"></i></button>
                    </div>
                </div>            
            </div>
            {!! Form::close() !!}
        </div>
    </div> 



    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Types</h3>
            <div class="box-tools pull-right">
                <a href="{{ url('/admin/maintenance/type/maintenance-type-manage/create') }}" class="btn btn-success btn-sm" title="Add New MaintenanceTypeManage">
                    <i class="fa fa-plus" aria-hidden="true"></i> New
                </a>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Remarks</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($maintenancetypemanage as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->remark }}</td>
                            <td>
                                <a href="{{ url('/admin/maintenance/type/show/' . $item->id) }}" title="View MaintenanceTypeManage"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                <a href="{{ url('/admin/maintenance/type/edit/' . $item->id ) }}" title="Edit MaintenanceTypeManage"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['/admin/maintenance/type/delete', $item->id],
                                    'style' => 'display:inline'
                                ]) !!}
                                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger btn-xs',
                                            'title' => 'Delete MaintenanceTypeManage',
                                            'onclick'=>'return confirm("Confirm delete?")'
                                    )) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper"> 
                    {!! $maintenancetypemanage->appends(['search' => Request::get('search')])->render() !!}
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


































