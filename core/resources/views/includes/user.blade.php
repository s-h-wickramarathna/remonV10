
<style type="text/css">
  .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus {
    color: #D96557;
    text-decoration: none;
    background-color: #f5f5f5;
}

.dropdown-menu > li > a {
    display: block;
    padding: 5px 10px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
}

.dropdown-menu > li > a {
    padding-right: 15px;
    padding-left: 15px;
    color: #616161;
    font-size: 13px;
    
}

  /*.control-sidebar-bg,*/
  /*.control-sidebar {*/
      /*top: 0 !important;*/
      /*right: -250px !important;*/
      /*width: 250px!important;*/
      /*-webkit-transition: right 0.3s ease-in-out!important;*/
      /*-o-transition: right 0.3s ease-in-out!important;*/
      /*transition: right 0.3s ease-in-out!important;*/
  /*}*/
  /*.control-sidebar {*/
      /*position: absolute!important;*/
      /*padding-top: 50px!important;*/
      /*z-index: 1010!important;*/
  /*}*/

  /*.control-sidebar.control-sidebar-open,*/
  /*.control-sidebar.control-sidebar-open + .control-sidebar-bg {*/
      /*right: 0!important;*/
  /*}*/


</style>

<li>
    <button style="position: relative;top: 15px;" type="button" class="btn btn-sm bg-green" data-toggle="layout-small-menu">
        <i class="fa fa-cogs" aria-hidden="true"></i>
    </button>
</li>

<li>

    <a href="javascript:;" data-toggle="dropdown">
      <img src="{{asset('assets/images/avatar.jpg')}}" class="header-avatar img-circle ml10" alt="user" title="user">
      <span class="pull-left">@if(isset($user) && count($user->employee) > 0) {{$user->employee[0]->full_name}} @endif</span>
    </a>
    <ul class="dropdown-menu">

         <li style="padding-top: 5px;padding-left:7px;padding-right: 7px;padding-bottom: 5px;">
          <a href="{{url('user/logout')}}"><i class="fa fa-sign-out" style="width: 30px;"></i>Logout</a>
         </li>
    </ul>
</li>