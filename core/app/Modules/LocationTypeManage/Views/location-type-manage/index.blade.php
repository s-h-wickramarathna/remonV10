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
    <small> List</small>
    </h1> -->
    <ol class="breadcrumb">
        <li><a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a></li>
        <li><a href="#">Location management</a></li>
        <li class="active"><a href="{{ route('admin.location.type.index') }}">List</a></li>
    </ol>
    <br>
</section>


<!-- SEARCH -->
<section class="content">

    
    <!-- Default box -->
    <div class="box">        
        <div class="box-body">
           <form action="{{ url('/admin/location/type') }}" method="get">
            <div class="row">
                <div class="col-lg-10 col-md-10 col-ms-10 col-xs-8">
                    <!-- <label class="control-label">search</label> -->
                    <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ Input::get('search') }}">  
                </div>
                <div class="col-lg-1 col-md-2 col-ms-2 col-xs-4  col-lg-offset-1 pull-right">
                    <button class="btn btn-default btn-block btn-sm pull-right" type="submit">
                        Find
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div> 



    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Location Type List</h3>
            <div class="box-tools pull-right">
                <a href="{{ route('admin.location.type.create') }}" class="btn btn-success btn-sm" title="Add New LocationTypeManage">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                </a>
                <button class="btn btn-warning btn-sm" onclick="window.history.back()">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Name</th>
                            <th width="70%">Description</th>
                            <th width="15%" colspan="3" style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    @if(count($locations) > 0)
                        <tbody>
                        @foreach($locations as $key => $item)
                            <tr>
                                <td>{{ (++$key) }}</td>
                                <td>{{ $item->name?:'-' }}</td>
                                <td>{{ $item->description?:'-' }}</td>
                                <td>
                                    <a href="{{ route('admin.location.type.view', $item->id) }}" title="View LocationTypeManage"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.location.type.edit', $item->id) }}" title="Edit LocationTypeManage"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.location.type.delete', $item->id) }}" method="get" name="form_action">
                                        @if($item->status == 1)
                                            <button class="btn btn-xs btn-danger action">
                                                <input type="hidden" name="type_status" value="0">
                                                <span class="fa fa-times"></span> Disable?
                                            </button>
                                        @elseif($item->status == 0)
                                            <button class="btn btn-xs btn-success action">
                                                <input type="hidden" name="type_status" value="1">
                                                <span class="fa fa-check"></span> Enable?
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>                    
                    @else
                        <tbody>
                            <tr>
                                <td colspan="6" align="center">
                                    <div style="font-size: 30px;color:#999;padding-top: 20px;">Data not Found!.</div>
                                </td>
                            </tr>
                        </tbody>
                    @endif
                </table>
                <div class="pagination-wrapper"> {!! $locations->appends(['search' => Request::get('search')])->render() !!} </div>
            </div>            
        </div>
    </div>  
</section>



@stop
@section('js')

<script type="text/javascript">
$(document).ready(function() {

    $('.action').on('click',function(e){
        e.preventDefault();
        var status = $(this).find('input[name=type_status]').val();
        var form = $(this).parents('form[name=form_action]');
        console.log(status);
        if(status == 1)
        {
            var color = '#00a65a';
            var txt = 'enable';
        }
        else if(status == 0)
        {
            var color = '#d9534f';
            var txt = 'disable';
        }
        else
        {
            var color = '#d9534f';
            var txt = '-';
        }

        swal({
            title: "Are you sure?",
            text: "You want to "+txt+" this location type?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: color,
            confirmButtonText: "Yes, "+txt+" it!",
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


































