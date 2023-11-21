@extends('layouts.back_master') @section('title','Dashboard')
@section('current_title','Dashboard')

@section('css')
<style type="text/css">

</style>  
@stop

@section('content')
<!-- Content-->
<section>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Message
      <small>Management</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li>message</li>
      <li class="active">inbox</li>
    </ol>
  </section>
  <!-- !!Content Header (Page header) -->

  
  <section class="content">
  	 <div class="row">
        <div class="col-md-3">
          @include('AdminMessageManage::includes.sidemenu')
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Local Inbox</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input class="form-control input-sm" placeholder="Search message" type="text">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">              
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                    @foreach($messages as $key=>$message)
                      <tr>
                        <td >
                          <input type="checkbox" value="" style="padding: 0;margin:0">
                        </td>
                        <td class="mailbox-name">
                          <a href="{{url('admin/message/read/')}}{{$message['id']}}">
                          {{$message['fromName']}}
                          </a>
                        </td>
                        <td class="mailbox-subject">
                          {{$message['subject']}}
                        </td>
                        <td class="mailbox-attachment"></td>
                        <td class="mailbox-date">{{$message['date']}}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                  <i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                </div>
                

                <div class="pull-right">
                  1-10/200
                  <div class="btn-group">
                     <ul class="pagination pagination-sm" style="margin:0;">
                      <li><a href="#">&laquo;</a></li>
                      <li><a href="#">1</a></li>
                      <li><a href="#">2</a></li>
                      <li><a href="#">3</a></li>
                      <li><a href="#">4</a></li>
                      <li><a href="#">5</a></li>
                      <li><a href="#">&raquo;</a></li>
                    </ul>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
  </section>






  </section>
  <!-- !!Main content -->

</section>
<!-- !!!Content -->

@stop
@section('js')
  <!-- CORE JS -->
  
  <!-- //CORE JS -->
@stop
