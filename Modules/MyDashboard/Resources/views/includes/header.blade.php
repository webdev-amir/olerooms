<div class="bravo_header">
    <div class="container-fluid">
        <div class="content fadeInUp animated3 delay1 selected">
            <a href="{{URL::to('/')}}" class="bravo-logo">
                <img src="{{URL::to('img/logo.svg')}}" alt="Ole Rooms">
            </a>
            <div class="header-left">
              <div class="bravo-menu">
                 <ul class="main-menu menu-generated">
                    <li class=" depth-0 {{ Request::is('propertyowner') ? 'active' : '' }}">
                       <a href="javascript:;">
                       <span class="icondesign"><img class="" src="{{URL::to('images/call-owner.svg')}}" alt="image"/></span>
                       List Your Property
                       </a>
                    </li>
                    <li class=" depth-0">
                       <a href="javascript:;" class="border-0">
                       <span class="icondesign"><img class="" src="{{URL::to('images/mail-gradient.svg')}}" alt="image"/></span>
                       {!! $configVariables['adminemail']['value'] !!}
                       </a> 
                    </li>
                     @guest
                    <li class=" depth-0">
                        <a href="{{route('vendor.login')}}">
                           <button type="button" class="btn btn-success gradientBtn minw-101">
                           <i class="icofont-ui-user mr-2"></i> Login
                           </button>
                        </a>
                    </li>
                    @else
                    <li class=" depth-0">
                     <button type="button" class="btn btn-success gradientBtn minw-101" href="{{ route('auth.logout') }}"
                          onclick="event.preventDefault(); document.getElementById('logout-form').submit();" alt="@lang('menu.logout')" title="@lang('menu.logout')">@lang('menu.logout')</button>
                       <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                          @csrf
                       </form>
                    </li>
                    @endif
                 </ul>
              </div>
            </div>
            <div class="header-right">
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
                <li class=" depth-0">
                     @if(auth()->user()->hasRole('customer'))
                        <a href="{{ route('customer.dashboard') }}" > Dashboard </a>
                    @endif
                    @if(auth()->user()->hasRole('vendor'))
                        <a href="{{ route('vendor.dashboard') }}" > Dashboard </a>
                    @endif
                </li>
                @endauth
                <li class=" depth-0 {{ Request::is('propertyowner') ? 'active' : '' }}">
                    <a href="{{route('propertyowner.home')}}">
                         List Your Property
                    </a>
                </li>
                <li class="depth-0 {{ Request::is('help') ? '' : '' }}">
                    <a target="" href="{{route('pages.show','help')}}">
                        Help
                    </a>
                </li>
                <li class="depth-0 {{ Request::is('contactus') ? '' : '' }}">
                    <a target="" href="{{route('contactus.create')}}">
                        Contact
                    </a>
                </li>
                @guest
                <li class="{{ Request::is('login') ? '' : '' }}">
                    <a href="{{route('vendor.login')}}" class="login">
                        Login
                    </a>
                </li>
                @endif
                @auth
                    <li class="depth-0">
                        <a class="dropdown-item" href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" > @lang('menu.logout') <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">@csrf </form> </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</div>