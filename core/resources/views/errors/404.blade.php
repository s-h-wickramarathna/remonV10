@extends('layouts.master') @section('title','Ops! You\'re in a Wrong Place.')
@section('content')
<div class="eq-col">
  <div class="relative full-height1">
    <div class="display-row">

      <!-- error wrapper -->
      <div class="center-wrapper error-page">
        <div class="center-content text-center">
          <div class="error-number">
            <span>404</span>
          </div>
          <div class="h5">PAGE NOT FOUND</div>
          <p>Sorry, but the page you were trying to view does not exist.</p>
          <a href="{{URL::previous()}}"><i class="fa fa-angle-left"></i> Go Back</a>
        </div>
      </div>
      <!-- /error wrapper -->

    </div>
  </div>
</div>
@stop