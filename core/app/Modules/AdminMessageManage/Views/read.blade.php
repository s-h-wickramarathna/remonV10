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
              <h3 class="box-title">Read Mail</h3>
              <div class="box-tools pull-right">
                <!-- /.mailbox-read-info -->
                <div class="mailbox-controls with-border text-center">
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Delete">
                      <i class="fa fa-trash-o"></i></button>
                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Reply">
                      <i class="fa fa-reply"></i></button>
                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Forward">
                      <i class="fa fa-share"></i></button>
                  </div>
                  <!-- /.btn-group -->
                  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="" data-original-title="Print">
                    <i class="fa fa-print"></i></button>
                </div>
                <!-- /.mailbox-controls -->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h3>Message Subject Is Placed Here</h3>
                <h5>From: help@example.com
                  <span class="mailbox-read-time pull-right">15 Feb. 2016 11:03 PM</span></h5>
              </div>
              
              <div class="mailbox-read-message">
                <p>Hello John,</p>

                <p>Keffiyeh blog actually fashion axe vegan, irony biodiesel. Cold-pressed hoodie chillwave put a bird
                  on it aesthetic, bitters brunch meggings vegan iPhone. Dreamcatcher vegan scenester mlkshk. Ethical
                  master cleanse Bushwick, occupy Thundercats banjo cliche ennui farm-to-table mlkshk fanny pack
                  gluten-free. Marfa butcher vegan quinoa, bicycle rights disrupt tofu scenester chillwave 3 wolf moon
                  asymmetrical taxidermy pour-over. Quinoa tote bag fashion axe, Godard disrupt migas church-key tofu
                  blog locavore. Thundercats cronut polaroid Neutra tousled, meh food truck selfies narwhal American
                  Apparel.</p>

                <p>Thanks,<br>Jane</p>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <ul class="mailbox-attachments clearfix">                
                <li>
                  <span class="mailbox-attachment-icon"><i class="fa fa-file-word-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> App Description.docx</a>
                        <span class="mailbox-attachment-size">
                          1,245 KB
                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>

                <li>
                  <span class="mailbox-attachment-icon"><i class="fa fa-picture-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> screenshot.jpg</a>
                        <span class="mailbox-attachment-size">
                          1,245 KB
                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
               
              </ul>
            </div>
            <!-- /.box-footer -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                <button type="button" class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
              </div>
              <button type="button" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
              <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
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
