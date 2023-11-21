
@extends('layouts.back_master') @section('title','Add Menu')
@section('css')
<style type="text/css">
    .form-group.required .control-label:after {
      content:" *";
      color:red;
    }
    #description{
        resize: none;
    }
</style>
@stop
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- <h1>
    Locationtypemanage 
    <small> Management</small>
    </h1> -->
    <ol class="breadcrumb">
        <li><a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a></li>
        <li>Location Management</li>
        <li class="active"><a href="{{ route('admin.location.type.create') }}">Create</a></li>
    </ol>
    <br>
</section>


<!-- SEARCH -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Locationtypemanage</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-warning btn-xs" onclick="window.history.back()">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                </button>
            </div>
        </div>
        <div class="box-body">

           <!--  @if ($errors->any())
               <ul class="alert alert-danger">
                   @foreach ($errors->all() as $error)
                       <li>{{ $error }}</li>
                   @endforeach
               </ul>
           @endif -->

            {!! Form::open(['url' => '/admin/location/type/store', 'class' => 'form-horizontal', 'files' => true]) !!}

              @include('LocationTypeManage::location-type-manage.form')

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




































