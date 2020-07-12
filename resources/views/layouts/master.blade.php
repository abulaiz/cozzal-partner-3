<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
  @include('layouts.head')
</head>
<body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar"
data-open="click" data-menu="vertical-menu" data-col="2-columns">

  @include('layouts.navbar')

  @if(Auth::user()->hasRole('owner'))
    @include('layouts.sidebar.owner')
  @else
    @include('layouts.sidebar.general')
  @endif
  

  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
          <h3 class="content-header-title mb-0 d-inline-block">@yield('content_title')</h3>
          <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb">
                @yield('breadcumb_nav')
              </ol>
            </div>
          </div>
        </div>
        <div class="content-header-right col-md-4 col-12">
          <div class="btn-group float-md-right">
          @yield('button_header')
          </div>
        </div>
      </div>

      <div class="op-0 bounced" id="message-bar"></div>

      <div class="content-body">
        @yield('content')
      </div>
    </div>
  </div>

  <footer class="footer static-bottom footer-dark navbar-border">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
      <span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2018 <a class="text-bold-800 grey darken-2" href="https://themeforest.net/user/pixinvent/portfolio?ref=pixinvent"
        target="_blank">Cozzal IT </a>, All rights reserved. </span>
      <span class="float-md-right d-block d-md-inline-blockd-none d-lg-block">Hand-crafted & Made with <i class="ft-heart pink"></i></span>
    </p>
  </footer>

  <script src="{{ URL::asset('app-assets/vendors/js/vendors.min.js') }}" type="text/javascript"></script>
  
  <script src="{{ URL::asset('app-assets/js/core/app-menu.js?').uniqid() }}" type="text/javascript"></script>
  <script src="{{ URL::asset('app-assets/js/core/app.js') }}" type="text/javascript"></script>
  <script src="{{ URL::asset('app-assets/js/scripts/customizer.js') }}" type="text/javascript"></script>

  <link rel="stylesheet" type="text/css" href="{{URL::asset('app-assets/vendors/css/extensions/toastr.css')}}">
  <script src="{{URL::asset('app-assets/vendors/js/extensions/toastr.min.js')}}" type="text/javascript"></script>
  <script src="{{URL::asset('app-assets/vendors/js/extensions/sweetalert.min.js')}}" type="text/javascript"></script>
  <script src="{{URL::asset('js/lib/confirmDialog.js?'.uniqid())}}" type="text/javascript"></script> 
  <script src="{{URL::asset('js/lib/leftToastr.js')}}" type="text/javascript"></script> 
    <script type="text/javascript" src="{{ URL::asset('js/lib/message.js?').uniqid() }}"></script>

  @yield('pageJs')

  <script type="text/javascript" src="{{ URL::asset('js/view/sidebar.js?').uniqid() }}"></script>

  <style type="text/css">
    .pade-enter-active, .pade-leave-active {
      transition: transform .5s;
      transform: scale(1);
    }

    .pade-enter, .pade-leave-to {
      opacity: 0;
      transform: scale(0);
    }  
  </style>  

</body>
</html>
