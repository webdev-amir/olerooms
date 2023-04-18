<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" style="display: block;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content= "width=device-width, user-scalable=no">
    <meta name="keywords" content="{{ isset($pageInfo->meta_keyword) ? $pageInfo->meta_keyword : '' }}">
    <meta name="description" content="{{ isset($pageInfo->meta_description) ? $pageInfo->meta_description : '' }}">
    <title>@yield('title', isset($pageInfo->name) ? $pageInfo->name.' -' : '' .config('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{URL::to('/css/frontend.css')}}">
        <script type="text/javascript">
            var site_url = '{{ URL::to("/") }}';
            var _imageUpload = "";
            var _UserImgSrc = "{{URL::to('storage/users/')}}/";
            var _UserImgThumbSrc = "{{URL::to('storage/users/thumbnail')}}/";
            var _publicPath = "{{url('/')}}";
            var REQUEST_URL = "{{Request::url()}}"; 
            var _REQUEST_URI_ = "{{Request::getRequestUri()}}";
            var admin_url = "{{ URL::to('/') }}/admin";
            var _enter_same_as_passowed = "{{trans('menu.validiation.please_enter_confirm_password_same_as_password')}}";
            var must_minimum_digit_pwd = "{{trans('menu.validiation.password_must_be_minimum_8_digit')}}";
            var verify_you_are_human = "{{trans('menu.validiation.verify_you_are_human')}}";
            var enter_correct_email = "{{trans('menu.validiation.enter_correct_email')}}";
            var enter_valid_card_number = "{{trans('menu.validiation.enter_valid_card_number')}}";
            var _loaderMsg = "{{ trans('flash.alerts.loading') }}";
            var _currentRname = "@if(request()->route()) {{ request()->route()->getName() }} @else "" @endif ";
            var APP_URL = {!! json_encode(url('/')) !!};
            var APP_NAME = {!! json_encode(config('app.name', 'Laravel')) !!};
            var mapMarkerImage ="{{URL('img/map-marker.png')}}";
        </script>
</head>
<body>
    <section id="app" class="dashboard">
        @include('mydashboard::includes.sidebar_profile_menu') 
        @include('mydashboard::includes.sidebar_top_header_menu')
        @yield('content')
        @include('mydashboard::includes.footer')
    </section>
        @include('admin.page.ajaxloader')
        @include('admin.page.front_message')
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script async src="https://static.addtoany.com/menu/page.js"></script>
        @yield('uniquePageScript') 
</body>
</html>
