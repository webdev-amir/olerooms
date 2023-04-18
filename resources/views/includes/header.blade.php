<div class="bravo_header">
    <div class="container-fluid">
        <div class="content fadeInUp animated3 delay1 selected">
            <a href="{{URL::to('/')}}" class="bravo-logo">
                <img src="{{URL::to('img/logo.svg')}}" alt="Ole Rooms" title="{{env('APP_NAME', 'Ole Rooms')}}">
            </a>
            <div class="innerSearch_row header-left {{(Route::currentRouteName() == 'search')?'listing-menu mr-2':''}}">
                @if(Route::currentRouteName() == 'search' &&  request()->map_value!='show_map')
                <a href="#" class="btn customBtn btn-success searchproperty_btn d-none ml-2"> <i class="ri-search-line"></i> </a>
                @endif
                <div class="bravo-menu" id="searchinner_menu">
                    @if(Route::currentRouteName() == 'search' && request()->map_value!='show_map')
                    @php
                    $property_type = request()->get('property_type')?request()->get('property_type'):'';
                    $orderby = request()->get('orderby')?request()->get('orderby'):'';
                    $searchLayout = request()->get('searchLayout')?request()->get('searchLayout'):'';
                    @endphp 
                    <div class="">
                        <div role="" class="" id="bravo_hotel">
                            <form action="" class="form bravo_form searchtop-filter-form" method="get">
                                <div class="g-field-search">
                                    <div class="row list-cal">
                                        <div class="col-md-5 border-right">
                                            @include('frontend.includes.listsearch.searchKey')
                                        </div>
                                        <div class="col border-right">
                                            @include('frontend.includes.listsearch.checkin_date')
                                        </div>
                                        @if(request()->get('property_type')=='1')
                                        <div class="col border-right-0 pr-0">
                                            @include('frontend.includes.listsearch.guests')
                                        </div>
                                        @elseif(request()->get('property_type')=='2')
                                        <div class="col border-right pr-0">
                                            @include('frontend.includes.listsearch.flatbhk')
                                        </div>
                                        <div class="col border-right-0 pr-0">
                                            @include('frontend.includes.listsearch.flat_adults')
                                        </div>
                                        @elseif(request()->get('property_type')=='3')
                                        <div class="col border-right">
                                            @include('frontend.includes.listsearch.checkout_date')
                                        </div>
                                        <div class="col border-right">
                                            @include('frontend.includes.listsearch.hotel_adults')
                                        </div>
                                        @else
                                        <div class="col border-right">
                                            @include('frontend.includes.listsearch.checkout_date')
                                        </div>
                                        <div class="col border-right">
                                            @include('frontend.includes.listsearch.guests')
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="g-button-submit">
                                    <input type="hidden" value="{{$property_type}}" name="property_type" />
                                    <input type="hidden" value="{{$orderby}}" name="orderby" />
                                    <input type="hidden" value="{{$searchLayout}}" name="searchLayout" />
                                    <button class="btn btn-success btn-search" id="searchFilterButton" type="button">
                                        <i class="ri-search-line mr-3"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                    <ul class="main-menu menu-generated">
                        <div class="d-flex download_app align-items-center">
							<a href="{!! $configVariables['android-app-url']['value'] !!}" target="_blank"><img src="{{URL::to('images/android.svg')}}" alt="Android App"></a>Download App
						</div>
                        <li class=" depth-0 {{ Request::is('propertyowner') ? 'active' : '' }}">
                            @auth
                            @if(auth()->user()->hasRole('vendor'))
                            <a href="{{route('vendor.myproperty')}}">
                                <i class="icondesign ri-building-line"></i> List Your Property <span class="css-1l29bq6">Free</span>
                            </a>
                            @else
                            <a href="{{route('propertyowner.home')}}">
                                <i class="icondesign ri-building-line"></i> List Your Property <span class="css-1l29bq6">Free</span>
                            </a>
                            @endif
                            @else
                            <a href="{{route('propertyowner.home')}}">
                                <i class="icondesign ri-building-line"></i> List Your Property <span class="css-1l29bq6">Free</span>
                            </a>
                            @endauth
                        </li>
                        <li class="depth-0 {{ Request::is('company') ? 'active' : '' }}">
                            <a href="{{route('company.home')}}" title="OLE Corporate">
                                <i class="icondesign ri-briefcase-3-line"></i> OLE Corporate
                            </a>
                        </li>
                        <li class=" depth-0">
                            <a href="{{route('agent.home')}}" class="border-0" title="Become an Agent">
                                <i class="icondesign ri-user-received-2-line"></i> Become an Agent
                            </a>
                        </li>
                        @guest
                        <li class=" depth-0">
                            <a href="{{route('customer.login')}}" title="SignUp/Login">
                                <button type="button" class="btn btn-success">
                                    <i class="icofont-ui-user mr-2"></i> SignUp/Login
                                </button>
                            </a>
                        </li>
                        @else
                        @auth
                        <li class="depth-0">
                            <div class="dropdown afterloginMenu ml-3 position-relative ">
                                <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <figure class="mb-0" style="background:url('{{ auth()->user()->getThumbPicturePathAttribute() }}'),url('{{onerrorProImage()}}');"></figure>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @if(auth()->user()->hasRole('customer'))
                                    <a class="dropdown-item" href="{{ route('customer.dashboard.myprofile') }}"> My Profile </a>
                                    @endif
                                    @if(auth()->user()->hasRole('vendor'))
                                    <a class="dropdown-item" href="{{ route('vendor.dashboard') }}"> Dashboard </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> @lang('menu.logout') <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">@csrf </form> </a>
                                </div>
                            </div>
                        </li>
                        @endauth
                        @endif
                    </ul>
                    @endif
                </div>
            </div>
            <div class="header-right d-flex d-lg-none">
                    <div class="download_app align-items-center">
						<a href="{!! $configVariables['android-app-url']['value'] !!}" target="_blank" class="mr-0"><img src="{{URL::to('images/android.svg')}}" alt="Android App"></a>
                    </div>
                    <button class="bravo-more-menu">
                        <i class="ri-menu-3-line"></i>
                    </button>
            </div>
        </div>
    </div>
    <div class="bravo-menu-mobile fadeInUp animated3 delay1 selected" style="display:none;">
        <div class="user-profile">
            <div class="b-close">
                <i class="icofont-scroll-left"></i>
            </div>
            <div class="avatar"></div>
            <ul>
            </ul>
        </div>
        <div class="g-menu">
            <ul class="main-menu menu-generated">
                @auth
                <li class="depth-0">
                    @if(auth()->user()->hasRole('customer'))
                    <a href="{{ route('customer.dashboard.myprofile') }}"> My Profile </a>
                    @endif
                    @if(auth()->user()->hasRole('vendor'))
                    <a href="{{ route('vendor.dashboard') }}"> Dashboard </a>
                    @endif
                </li>
                @endauth
                @auth
                @if(auth()->user()->hasRole('vendor'))
                <li class=" depth-0 {{ Request::is('propertyowner') ? 'active' : '' }}">
                    <a href="{{route('vendor.myproperty')}}">
                        List Your Property <span class="css-1l29bq6">Free</span>
                    </a>
                </li>
                @else
                <li class=" depth-0 {{ Request::is('propertyowner') ? 'active' : '' }}">
                    <a href="{{route('propertyowner.home')}}">
                        List Your Property <span class="css-1l29bq6">Free</span>
                    </a>
                </li>
                @endif
                @else
                <li class=" depth-0 {{ Request::is('propertyowner') ? 'active' : '' }}">
                    <a href="{{route('propertyowner.home')}}">
                        List Your Property <span class="css-1l29bq6">Free</span>
                    </a>
                </li>
                @endauth
                <li class="depth-0 {{ Request::is('company') ? 'active' : '' }}">
                    <a href="{{route('company.home')}}" title="OLE Corporate">
                        OLE Corporate
                    </a>
                </li>
                <li class=" depth-0 {{ Request::is('agent') ? 'active' : '' }}">
                    <a href="{{route('agent.home')}}" title="Become an Agent">
                        Become an Agent
                    </a>
                </li>
                <li class="depth-0 {{ Request::is('help') ? 'active' : '' }}">
                    <a target="" href="{{route('pages.show','help')}}">
                        Help
                    </a>
                </li>
                <li class="depth-0 {{ Request::is('contactus') ? 'active' : '' }}">
                    <a target="" href="{{route('contactus.create')}}">
                        Contact
                    </a>
                </li>
                @guest
                <li class="{{ Request::is('login') ? 'active' : '' }}">
                    <a href="{{route('customer.login')}}" class="login">
                        Login
                    </a>
                </li>
                @endif
                @auth
                <li class="depth-0">
                    <a class="dropdown-item" href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> @lang('menu.logout') <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">@csrf </form> </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</div>