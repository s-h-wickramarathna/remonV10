<!doctype html>
<html class="no-js">

<head>
  <meta charset="utf-8">
  <title>Ramon | @yield('title')</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- <link rel="shortcut icon" href="/favicon.ico"> -->
  <link rel="icon"  href="{{asset('assets/images/man.png')}}">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

  <!-- page level plugin styles -->
  <link rel="stylesheet" href="{{asset('assets/css/AdminLTE.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/chosen_v1.4.0/chosen.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/checkbo/src/0.1.4/css/checkBo.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/sweetalert/lib/sweet-alert.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/jquery-confirm-master/dist/jquery-confirm.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/datatables/media/css/jquery.dataTables.css')}}">
  <!-- /page level plugin styles -->

  <!--bootstrap-->
  <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/dist/css/bootstrap.css')}}">

  <!--fonts-->
  <link rel="stylesheet" href="{{asset('assets/styles/roboto.css')}}">
  <link rel="stylesheet" href="{{asset('assets/styles/font-awesome.css')}}">
  <!--fonts-->

  <!--template-->
  <link rel="stylesheet" href="{{asset('assets/styles/urban.css')}}">
  <!--common-->
  <link rel="stylesheet" href="{{asset('assets/vendor/jquery.multiselect/css/multi-select.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')}}">
  <!--common-->

  <!--scrollbar-->
  <link rel="stylesheet" href="{{asset('assets/vendor/perfect-scrollbar/css/perfect-scrollbar.css')}}">
  <link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css">
  <link href="{{asset('assets/vendor/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css')}}" rel="stylesheet">


  <!-- endbuild -->
  <style type="text/css">
    .vertical-align-middle{
      vertical-align:middle !important;
    }

    .datatable a{
      font-size: 16px;
    }

    .datatable a:hover{
      font-size: 16px;
      color: #3B3B3B;
      transition: all .3s;
      -webkit-transition: all .3s;
      -moz-transition: all .3s;
    }

    .datatable a.blue{
      color: #1E88E5;
    }

    .datatable a.blue:hover{
      color: #0D47A1;
    }

    .datatable a.red{
      color: #E53935;
    }

    .datatable a.red:hover{
      color: #B71C1C;
    }

    .datatable a.green{
      color: #00C853;
    }

    .datatable a.green:hover{
      color: #2E7D32;
    }

    .datatable a.disabled{
      color: #565656;
    }

    .table.table-condensed > thead > tr > th,
    .table.table-condensed > tbody > tr > th,
    .table.table-condensed > tfoot > tr > th,
    .table.table-condensed > thead > tr > td,
    .table.table-condensed > tbody > tr > td,
    .table.table-condensed > tfoot > tr > td {
      padding: 6px 10px;
    }

    .sweet-alert{
      border-radius: 0;
    }

    .sweet-alert button{
      border-radius: 0;
    }

    .sweet-alert button.cancel{
      background-color: #E05A5A;
      transition: all .4s;
      -webkit-transition: all .4s;
      -moz-transition: all .4s;
    }

    .sweet-alert button.cancel:hover{
      background-color: #CC4D4D;
    }

    .sidebar-panel {
      background-color: #37444e;
    }

    .sidebar-panel > .brand {
          background-color: #37444e;
          border-bottom: 1px solid rgba(204, 204, 204, 0.17);
    }

    .body{
      font-size: 16px;
    }

    .main-panel > .header {
      background-color: #37444e;
     /* border-bottom: 1px solid #5f5f5e;*/
    } 

    .main-panel > .header .navbar-text {
      color: #556B8D;
    }

    .main-panel > .header .nav > li > a {
      color: #FFFFFF;
    }

    .border-danger {
      border-color: #D96557;
    }

    .bg-danger {
      color: white;
      background-color: #D96557;
    }

    .breadcrumb > li{
      display: inline-block;
      color: #292929;
    }

    .breadcrumb > li:hover{
      color: #D96557;
    }

    .main-panel > .header .navbar-nav .dropdown-menu {
      margin-top: 2px;
      padding: 0;
      border-color: rgba(0, 0, 0, 0.1);
      border-top: 0;
      background-color: white;
      -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      -moz-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      -webkit-border-radius: 0;
      -moz-border-radius: 0;
      border-radius: 0;
      width: 100%;
    }

    b, strong {
      font-weight: normal;
    }

    .breadcrumb > li + li:before{
      color: #4A4A4A;
    }

    .bootstrap-tagsinput{
      display: block;
      width: 100%;
      padding: 6px 12px;
      line-height: 1.42857143;
      color: #555;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
      border-color: #e4e4e4;
      font-weight: 400;
      font-size: 13px;
      -webkit-font-smoothing: antialiased;
      -webkit-border-radius: 2px;
      -moz-border-radius: 2px;
      border-radius: 2px;
      -webkit-transition: border 300ms linear;
      -moz-transition: border 300ms linear;
      -o-transition: border 300ms linear;
      transition: border 300ms linear;
      -webkit-box-shadow: none;
      -moz-box-shadow: none;
      box-shadow: none;
    }

    .bootstrap-tagsinput .tag{
      padding: 3px 6px 4px;
      font-size: 14px;
      font-weight: 100;
      line-height: 2.2;
      border-radius: 2px;
    }

    .sidebar-panel > nav li ul li a{
   		padding: 10px 25px 10px 50px;
      background: #495469;
    }

    .sidebar-panel > nav > ul > li a{
    	padding: 10px 20px;
    }

    .sidebar-panel > nav ul > li > a .fa{
    	width: 25px;
    }

    .sidebar-panel > nav .open > ul{
      max-height: none;
    }

    .form-control{
      height: 30px;
      line-height: 1.5;
      padding: 5px 10px;
    }

    .chosen-container .chosen-single, .chosen-container .chosen-choices{
      min-height: 30px;
      padding: 5px 10px;
    }

    .chosen-container{
      width: 100%!important;
    }

    .btn{
      border-radius: 2px;
      font-size: 12px;
      line-height: 1.5;
      padding: 5px 10px;
    }

    .pagination{
      font-size: 12px;
    }

    form .control-label.required::after{
      position: absolute;
      margin-left: 1px;
    }

    .ms-container{
      width: 100%;
    }

    body {
      color: #333;
       padding: 0!important;
       margin: 0!important;
       direction: "ltr";
       font-size: 12px;
    }

    .brand-logo{
      margin-top: 0px !important;
      font-size: 30px;
      color: #fff;
      font-family: 'Source Sans Pro', sans-serif;
      margin-left: 0%;
    }

	.ever{
  		font-family: 'Lobster', cursive;
      margin-left: 40px;
	}

    .sidebar-panel > nav {
      position: absolute;
      top: 55px;
      bottom: 0;
      width: 100%;
      overflow-x: hidden;
      overflow-y: scroll;
      padding-bottom: 50px;
    }
    

  </style>

  @yield('css')

</head>

<body>

  <div class="app layout-fixed-header">

    <!-- sidebar panel -->
    <div class="sidebar-panel offscreen-left">

      <div class="brand">
        <!-- logo -->
        <div class="brand-logo" >
          <!-- <span class="ever">4ever </span> -->
          <a href="{{url('/')}}"><img src="{{asset('assets/images/logo-dark.png')}}" style="height: auto; width: 100px;overflow: hidden;margin-left: 25px;"></a>
        </div>
        <!-- /logo -->
        <!-- toggle small sidebar menu -->
        <a href="javascript:;" class="toggle-sidebar hidden-xs hamburger-icon v3" data-toggle="layout-small-menu">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </a>
        <!-- /toggle small sidebar menu -->
      </div>

      <!-- main navigation -->
      <nav role="navigation">
        @include('includes.menu')
      </nav>
      <!-- /main navigation -->

    </div>
    <!-- /sidebar panel -->

    <!-- content panel -->
    <div class="main-panel">

      <!-- top header -->
      <header class="header navbar">

        <div class="brand visible-xs">
          <!-- toggle offscreen menu -->
          <div class="toggle-offscreen">
            <a href="#" class="hamburger-icon visible-xs" data-toggle="offscreen" data-move="ltr">
              <span></span>
              <span></span>
              <span></span>
            </a>
          </div>
          <!-- /toggle offscreen menu -->

          <!-- logo -->
          <div class="brand-logo">
            <img src="{{asset('assets/images/logo-larg.png')}}" style="height: auto; width: 150px;">
          </div>
          <!-- /logo -->
        </div>

        <ul class="nav navbar-nav hidden-xs">
          <li>
            <p class="navbar-text" >
              <a href="{{url('/')}}"><i class="fa fa-tachometer " aria-hidden="true" style="font-size: 20px;color: #fff;margin-top: 2px"></i></a>
            </p>
          </li>
          <li>
            <p class="navbar-text" >
              @yield('current_title')
            </p>
          </li>
        </ul>

        <ul class="nav navbar-nav navbar-right hidden-xs">
          @include('includes.user')
        </ul>
      </header>
      <!-- /top header -->

      <!-- main area -->
      <div class="main-content">
        @yield('content')
      </div>
      <!-- /main area -->
    </div>
    <!-- /content panel -->

    <!-- bottom footer -->
    <footer class="content-footer">
      <nav class="footer-left">
         <a href="javascript:;" style="color: #616161">Copyright <i class="fa fa-copyright"></i> <span>Hynax</span> 2015. All rights reserved</a>
       </nav>
    </footer>
    <!-- /bottom footer -->
  </div>

  <script src="{{asset('assets/vendor/jquery/dist/jquery-2.0.1.js')}}"></script>
  <script src="{{asset('assets/scripts/extentions/modernizr.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/dist/js/bootstrap.js')}}"></script>



  <script src="{{asset('assets/vendor/perfect-scrollbar/js/perfect-scrollbar.jquery.js')}}"></script>
  <script src="{{asset('assets/scripts/ui/accordion.js')}}"></script>

  <script src="{{asset('assets/scripts/ui/preloader.js')}}"></script>
  <script src="{{asset('assets/scripts/ui/toggle.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery.multiselect/js/jquery.multi-select.js')}}"></script>
  <!-- endbuild -->

  <script src="{{asset('/assets/vendor/chosen_v1.4.0/chosen.jquery.js')}}"></script>
  <script src="{{asset('assets/vendor/checkbo/src/0.1.4/js/checkBo.min.js')}}"></script>
  <script src="{{asset('assets/vendor/sweetalert/lib/sweet-alert.min.js')}}"></script>

  <script src="{{asset('assets/vendor/jquery-confirm-master/dist/jquery-confirm.min.js')}}"></script>

  <script src="{{asset('assets/vendor/datatables/media/js/jquery.dataTables.js')}}"></script>
  <script src="{{asset('assets/scripts/extentions/bootstrap-datatables.js')}}"></script>
  <script src="https://cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js"></script>
  <script src="{{asset('assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{asset('assets/vendor/typehead/typehead.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap-switch-master/dist/js/bootstrap-switch.min.js')}}"></script>


  <!-- Custom DataTable Generator -->
  <script src="{{asset('assets/scripts/custom/custom_functions.js')}}"></script>

  <script type="text/javascript">


    $(document).ready(function(){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $('form').checkBo();

      $('.chosen').chosen();

      @if(session('success'))
        sweetAlert('{{session('success.title')}}', '{{session('success.message')}}',0);
      @elseif(session('error'))
        sweetAlert('{{session('error.title')}}','{{session('error.message')}}',2);
      @elseif(session('warning'))
        sweetAlert('{{session('warning.title')}}','{{session('warning.message')}}',3);
      @elseif(session('info'))
        sweetAlert('{{session('info.title')}}','{{session('info.message')}}',1);
      @endif
    });
  </script>

  @yield('js')
</body>

</html>

