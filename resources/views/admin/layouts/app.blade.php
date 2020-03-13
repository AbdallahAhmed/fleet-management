<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fleet Management | Dashboard | @yield('title')</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport'/>
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link href="{{asset('admin_assets/css/material-dashboard.css?v=2.1.2')}}" rel="stylesheet"/>
    <link href="{{asset('admin_assets/css/datepicker3.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('admin_assets/demo/demo.css')}}" rel="stylesheet"/>
    <script src="{{asset('admin_assets/js/jquery.js')}}"></script>
    <script src="{{asset('admin_assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('admin_assets/js/core/bootstrap-material-design.min.js')}}"></script>
    @stack('styles')
</head>
<body class="">
<div class="wrapper ">
    @include('admin.layouts.sidebar')
    <div class="main-panel">
    @include('admin.layouts.header')
    @yield('content')
    @include('admin.layouts.footer')
    </div>
</div>
@stack('scripts')
</body>
</html>