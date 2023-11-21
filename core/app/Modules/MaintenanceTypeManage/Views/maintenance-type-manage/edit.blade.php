
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
            <h3 class="box-title">Maintenancetypemanage</h3>
            <div class="box-tools pull-right">
                <a href="{{ url('/admin/maintenance/type/maintenance-type-manage') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
            </div>
        </div>
        <div class="box-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            {!! Form::model($maintenancetypemanage, [
                'method' => 'PATCH',
                'class' => 'form-horizontal',
                'files' => true
            ]) !!}

            @include ('MaintenanceTypeManage::maintenance-type-manage.form', ['submitButtonText' => 'Update'])

            {!! Form::close() !!}



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




































