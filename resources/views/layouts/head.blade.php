  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="This is testing template">
  <meta name="keywords" content="cozzal admin">
  <meta name="author" content="PIXINVENT">
  <meta name="mainURL" content="{{URL::to('/')}}">
  <title>@yield('site_title') | {{ Config('app.name')}}</title>

  <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('fav.png')}}">

  <link href="{{ URL::asset('css/google_font.css') }}" rel="stylesheet">
  <!-- Bootstrap CSS-->
  <link href="{{URL::asset('assets/vendor/bootstrap-4.1/bootstrap.min.css')}}" rel="stylesheet" media="all">
  <!-- BEGIN VENDOR CSS-->
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/vendors.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/vendors/css/ui/prism.min.css') }}">
  <!-- END VENDOR CSS-->
  <!-- BEGIN ROBUST CSS-->
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/app.css') }}">
  <!-- END ROBUST CSS-->
  <!-- BEGIN Page Level CSS-->
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/core/colors/palette-gradient.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/core/colors/palette-callout.css') }}">

  <!-- END Page Level CSS-->
  <!-- BEGIN Custom CSS-->
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css?').uniqid() }}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/offset-grid.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/plugins/forms/checkboxes-radios.css') }}">
