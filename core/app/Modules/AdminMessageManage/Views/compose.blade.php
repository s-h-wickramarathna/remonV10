@extends('layouts.back_master') @section('title','Dashboard')
@section('current_title','Dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('assets/dist/summernote/summernote.css')}}">
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
              <h3 class="box-title">Compose New Message</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="form-group">
                <input class="form-control" placeholder="To:">
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Subject:">
              </div>

              <div class="form-group">
                  <textarea rows="5" id="summernote" class="form-control" placeholder="Type in a message" name="message"></textarea>
              </div>

              <div class="form-group">
                <div class="btn btn-default btn-file">
                  <i class="fa fa-paperclip"></i> Attachment
                  <input name="attachment" type="file">
                </div>
                <p class="help-block">Max. 32MB</p>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
              <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
            </div>
            <!-- /.box-footer -->
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
<script src="{{asset('assets/dist/summernote/summernote.js')}}"></script> 
 <script type="text/javascript">
    $(document).ready(function () {
        $('#summernote').summernote({
          height: 200,                 // set editor height
          minHeight: null,             // set minimum height of editor
          maxHeight: null,             // set maximum height of editor
          focus: true,
          toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]]
        });
        $('.dropdown-toggle').dropdown()
    });            
 </script>
@stop
