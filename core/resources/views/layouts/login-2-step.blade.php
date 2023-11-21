<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title>User Login</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <link rel="icon"  href="{{asset('assets/images/man.png')}}">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

  <!-- page level plugin styles -->
  <!-- /page level plugin styles -->

  <!-- build:css({.tmp,app}) styles/app.min.css -->
  <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/dist/css/bootstrap.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/perfect-scrollbar/css/perfect-scrollbar.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/checkbo/src/0.1.4/css/checkBo.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/styles/roboto.css')}}">
  <link rel="stylesheet" href="{{asset('assets/styles/font-awesome.css')}}">
  <link rel="stylesheet" href="{{asset('assets/styles/panel.css')}}">
  <link rel="stylesheet" href="{{asset('assets/styles/feather.css')}}">
  <link rel="stylesheet" href="{{asset('assets/styles/animate.css')}}">
  <link rel="stylesheet" href="{{asset('assets/styles/urban.css')}}">
  <link rel="stylesheet" href="{{asset('assets/styles/urban.skins.css')}}">

<style type="text/css">
  
  .form-layout {
    margin: 0 auto;
    padding: 15px;
    border: 1px solid #eee;
    background: rgba(245, 245, 245, 0.57);
    border-radius: 30px;
    -webkit-box-shadow: 5px 5px 19px -1px rgba(117,117,117,1);
    -moz-box-shadow: 5px 5px 19px -1px rgba(117,117,117,1);
    box-shadow: 5px 5px 19px -1px rgba(117,117,117,1);
  }

  p {
    color: #000 ;
    font-family:'Raleway' sans-serif;
    font-size: 14px;
}

.bg-white {
    color: #333;
    font-family:'Raleway'sans-serif;
    font-size: 14px;
    
}

.center-wrapper {
  display: table;
  width: 100%;
  height: 100%;
  position: relative;
}
.form-layout input {
    margin-bottom: 10px;
    background: none !important;
}

.btn-success {
    color: white;
    background-color: #595959 !important;
    border-color: #595959 !important;
}

.btn-success:hover, .btn-success:focus, .btn-success.focus, .btn-success:active, .btn-success.active, .open > .dropdown-toggle.btn-success {
    color: white;
    background-color: rgba(128, 128, 128, 0.66) !important;
    border-color: rgba(128, 128, 128, 0.66) !important
}

.form-layout .btn-lg {
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 50px;
}

.btn-success::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
    width: 100%;
    height: 100%;
    background-color: transparent !important;
    opacity: 0;
    transform: scale3d(0.7, 1, 1);
    transition: transform 0.4s, opacity 0.4s;
    border-radius: 50px;
}
.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
    background-image: none;
    border: 1px solid #595959;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .62);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}

.form-layout input:focus {
    border-color: #595959;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
}

</style>
</head>

<body>

  <div class="app layout-fixed-header bg-white usersession">
    <div class="full-height">
      <div class="center-wrapper">
        <div class="center-content">
          <div class="row no-margin">
            <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
              <form role="form" action="{{URL::to('user/login')}}" class="form-layout checkbo" method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="text-center mb15">
                  <!-- <h1 style="font-weight: bold;color: #6A9A28;">WELCOME..!</h1> -->

                  <!-- <img src="{{asset('assets/images/logo-dark22.png')}}" style="width: 54%;
    height: 50%;" /> -->
                </div>
                <p class="text-center mb30">Please sign in to your account</p>
                @if($errors->has('login'))
                  <div class="alert alert-danger">
                    Oh snap! {{$errors->first('login')}}
                  </div>
                @endif
                <div class="form-inputs">
                  <input type="text" name="username" class="form-control input-lg" placeholder="Username" autocomplete="off" value="{{{Input::old('username')}}}" @if(empty(Input::old('username'))) autofocus @endif>
                  <input type="password" name="password" class="form-control input-lg" placeholder="Password" @if(!empty(Input::old('username'))) autofocus @endif>
                </div>
                <label class="cb-checkbox">
                  <input type="checkbox" name="remember" value="{{Input::old('remember')}}" />Keep me signed in
                </label>
                <button class="btn btn-success btn-block btn-lg mb15" type="submit">
                  <span>SIGN IN</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- build:js({.tmp,app}) scripts/app.min.js -->
  <script src="{{asset('assets/scripts/extentions/modernizr.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery/dist/jquery.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/dist/js/bootstrap.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery.easing/jquery.easing.js')}}"></script>
  <script src="{{asset('assets/vendor/fastclick/lib/fastclick.js')}}"></script>
  <script src="{{asset('assets/vendor/onScreen/jquery.onscreen.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery-countTo/jquery.countTo.js')}}"></script>
  <script src="{{asset('assets/vendor/perfect-scrollbar/js/perfect-scrollbar.jquery.js')}}"></script>
  <script src="{{asset('assets/scripts/ui/accordion.js')}}"></script>
  <script src="{{asset('assets/scripts/ui/animate.js')}}"></script>
  <script src="{{asset('assets/scripts/ui/link-transition.js')}}"></script>
  <script src="{{asset('assets/scripts/ui/panel-controls.js')}}"></script>
  <script src="{{asset('assets/scripts/ui/preloader.js')}}"></script>
  <script src="{{asset('assets/scripts/ui/toggle.js')}}"></script>
  <script src="{{asset('assets/scripts/urban-constants.js')}}"></script>
  <script src="{{asset('assets/scripts/extentions/lib.js')}}"></script>

  <script src="{{asset('assets/vendor/chosen_v1.4.0/chosen.jquery.min.js')}}"></script>
  <script src="{{asset('assets/vendor/checkbo/src/0.1.4/js/checkBo.min.js')}}"></script>


  <script type="text/javascript">
    $(document).ready(function(){
      $('.checkbo').checkBo();
    });
  </script>
  <!-- endbuild -->
</body>

</html>
