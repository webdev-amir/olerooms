<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" style="display: block;">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content= "width=device-width, user-scalable=no">
        <meta name="keywords" content="{{ isset($pageInfo->meta_keyword) ? $pageInfo->meta_keyword : '' }}">
        <meta name="description" content="{{ isset($pageInfo->meta_description) ? $pageInfo->meta_description : '' }}">
        <title>@yield('title', isset($pageInfo->name) ? $pageInfo->name.' -' : '' .config('app.name'))</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/oleroom-loader.gif') }}">
        <link rel="stylesheet" href="{{URL::to('/css/frontend.css')}}">
            <script>
            var bookingCore = {
                url: "{{URL::to('/')}}/",
                url_root: "{{URL::to('/')}}",
                booking_decimals: 0,
                thousand_separator: '.',
                decimal_separator: ',',
                currency_position: 'left',
                currency_symbol: '$',
                currency_rate: '1',
                date_format: 'MM/DD/YYYY',
                space_add_update_date_format: 'DD/MM/YYYY',
                view_end_date_formate: 'DD/MM/YYYY',
                date_time_format: 'MM/DD/YYYY hh:mm A',
                map_provider: 'gmap',
                map_gmap_key: '',
                routes: {
                    login: "{{route('customer.login')}}",
                    register: "{{route('register')}}",
                },
                module: { },
                currentUser: 0,
                isAdmin: 0,
                rtl: 0,
                markAsRead: "{{route('notification.markAsRead')}}",
                markAllAsRead: "{{route('notification.markReadAllNotification')}}",
                loadNotify: '',
                pusher_api_key: '',
                pusher_cluster: '',
            };
             var i18n = {
                 warning:"Warning",
                 success:"Success",
                 confirm_delete:"Do you want to delete?",
                 confirm:"Confirm",
                 cancel:"Cancel",
             };
            var daterangepickerLocale = {
                "applyLabel": "Apply",
                "cancelLabel": "Cancel",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Custom",
                "weekLabel": "W",
                "first_day_of_week": 1,
                "daysOfWeek": ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                "monthNames": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            };
            </script>
            <link href="{{URL::to('theme/libs/carousel-2/owl.carousel.css')}}" rel="stylesheet"> 
    </head>
     <body>
        <section class="bravo_wrap @yield('section_type_dashboard') customer-dashboard bgDark" id="app">
            @include('includes.loader')
            @yield('content')
        </section>
        @yield('modalSection')
        @include('mydashboard::includes.delete_account_model')
        @include('admin.page.message')
        <script src="{{asset('js/frontend.js')}}" defer></script>
        <script src="{{URL::to('js/frontend/jquery-ui.js')}}"></script>
        <script src="{{URL::to('js/frontend/jquery.ui.touch-punch.min.js')}}"></script>
        <script src="{{ asset('js/jquery.form.js') }}" defer></script>
        <script type="text/javascript">
            var site_url = '{{ URL::to("/") }}';
            var _imageUpload = "";
            var _UserImgSrc = "{{URL::to('storage/app/public/users/')}}/";
            var _UserImgThumbSrc = "{{URL::to('storage/app/public/users/thumbnail')}}/";
            var _publicPath = "{{url('/')}}";
            var REQUEST_URL = "{{Request::url()}}";
            var admin_url = "{{ URL::to('/') }}/admin";
            var _enter_same_as_passowed = "{{trans('menu.validiation.please_enter_confirm_password_same_as_password')}}";
            var must_minimum_digit_pwd = "{{trans('menu.validiation.password_must_be_minimum_8_digit')}}";
            var verify_you_are_human = "{{trans('menu.validiation.verify_you_are_human')}}";
            var enter_correct_email = "{{trans('menu.validiation.enter_correct_email')}}";
            var enter_valid_card_number = "{{trans('menu.validiation.enter_valid_card_number')}}";
            var _loaderMsg = "{{ trans('flash.alerts.loading') }}";
            var _currentRname = "@if(request()->route()) {{ request()->route()->getName() }} @else "" @endif ";
            var APP_URL = {!! json_encode(url('/')) !!};
            var APP_NAME = {!! json_encode(config('app.name', 'Ole Rooms')) !!};
            var mapMarkerImage ="{{URL('img/map-marker.png')}}";
        </script>
        @yield('uniquePageScript')
        <script src="{{URL::to('theme/libs/lazy-load/intersection-observer.js')}}"></script>
        <script async src="{{URL::to('theme/libs/lazy-load/lazyload.min.js')}}"></script>
        <script> 
        // Set the options to make LazyLoad self-initialize
        window.lazyLoadOptions = {
            elements_selector: ".lazy",
            // ... more custom settings?
        };
        // Listen to the initialization event and get the instance of LazyLoad
        window.addEventListener('LazyLoad::Initialized', function(event) {
            window.lazyLoadInstance = event.detail.instance;
        }, false);
        </script>
        <script src="{{URL::to('theme/libs/lodash.min.js')}}"></script>
        <script src="{{URL::to('theme/libs/jquery-3.3.1.min.js')}}"></script>
        <script src="{{URL::to('theme/js/animation.js')}}"></script>
        <script src="{{URL::to('theme/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{URL::to('theme/libs/carousel-2/owl.carousel.min.js')}}"></script>
        <script type="text/javascript" src="{{URL::to('theme/libs/daterange/moment.min.js')}}"></script>
        <script type="text/javascript" src="{{URL::to('theme/libs/daterange/daterangepicker.min.js')}}"></script>
        <script>
            window.trans = <?php
            // copy all translations from /resources/lang/CURRENT_LOCALE/* to global JS variable
            $lang_files = File::files(resource_path() . '/lang/' . App::getLocale());
            $trans = [];
            foreach ($lang_files as $f) {
                $filename = pathinfo($f)['filename'];
                $trans[$filename] = trans($filename);
            }
            echo json_encode($trans);
            ?>;
        </script>
        <div class="modal fade login" id="register" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content relative">
                    <div class="modal-header">
                        <h4 class="modal-title">Sign Up</h4>
                        <span class="c-pointer" data-dismiss="modal" aria-label="Close">
                            <i class="input-icon field-icon fa">
                                <img src="{{URL::to('images/ico_close.svg')}}" alt="close">
                            </i>
                        </span>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                </div>
            </div>
        </div>
        <script src="{{URL::to('js/jquery.star-rating-svg.js')}}"></script>
         {!! mapScripts() !!}
         @include('includes.login-modal')
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    </body>
</html>