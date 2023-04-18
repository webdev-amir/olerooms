<div class="bravo_header">
    <div class="container-fluid">
        <div class="content fadeInUp animated3 delay1 selected">
            <a href="{{URL::to('/')}}" class="bravo-logo">
                <img src="{{URL::to('img/logo.svg')}}" alt="Ole Rooms">
            </a>
            <div class="header-left">
              <div class="bravo-menu">
                 <ul class="main-menu menu-generated">
                    <li class=" depth-0">
                       <a href="tel:{!! $configVariables['admincontact']['value'] !!}" class="border-0">
                       <span class="icondesign"><img class="" src="{{URL::to('images/support-grey.svg')}}" alt="image"/></span>
                       {!! $configVariables['admincontact']['value'] !!}
                       </a> 
                    </li>
                    <li class=" depth-0">
                       <a href="mailto:{!! $configVariables['adminemail']['value'] !!}" class="border-0" style="text-transform:none ;">
                       <i class="ri-mail-line icondesign"></i>
                       <!-- <span class="icondesign"><img class="" src="{{URL::to('images/mail.svg')}}" alt="image"/></span> -->
                       {!! $configVariables['adminemail']['value'] !!}
                       </a> 
                    </li>
                    @guest
                    <li class=" depth-0">
                        <a href="{{route('company.login')}}">
                           <button type="button" class="btn btn-success minw-101">
                           <i class="icofont-ui-user mr-2"></i> Login
                           </button>
                        </a>
                    </li>
                    @else
                     @auth
                        <li class="depth-0">
                            <div class="dropdown afterloginMenu ml-3 position-relative ">
                                <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <figure class="mb-0" style="background:url('{{ auth()->user()->ThumbPicturePath }}'),url('{{onerrorProImage()}}');"></figure>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @if(auth()->user()->hasRole('customer'))
                                    <a class="dropdown-item" href="{{ route('customer.dashboard.myprofile') }}"> My Profile </a>
                                    @endif
                                    @if(auth()->user()->hasRole('vendor'))
                                    <a class="dropdown-item" href="{{ route('vendor.dashboard') }}"> Dashboard </a>
                                    @endif
                                    @if(auth()->user()->hasRole('agent'))
                                    <a class="dropdown-item" href="{{ route('agent.dashboard') }}"> Dashboard </a>
                                    @endif
                                    @if(auth()->user()->hasRole('company'))
                                    <a class="dropdown-item" href="{{ route('company.dashboard') }}"> Dashboard </a>
                                    @endif
                                    <a class="dropdown-item" href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> @lang('menu.logout') <form id="logout-form" action="{{ route('company.logout') }}" method="POST" style="display: none;">@csrf </form> </a>
                                </div>
                            </div>
                        </li>
                        @endauth
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
                    <a href="{{ route('customer.dashboard.myprofile') }}"> My Profile </a>
                    @endif
                    @if(auth()->user()->hasRole('vendor'))
                    <a href="{{ route('vendor.dashboard') }}"> Dashboard </a>
                    @endif
                    @if(auth()->user()->hasRole('agent'))
                    <a href="{{ route('agent.dashboard') }}"> Dashboard </a>
                    @endif
                    @if(auth()->user()->hasRole('company'))
                    <a href="{{ route('company.dashboard') }}"> Dashboard </a>
                    @endif
                    @if(auth()->user()->hasRole('company'))
                        <a href="{{ route('company.dashboard') }}" > Dashboard </a>
                    @endif
                </li>
                @endauth
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
                <li class="{{ Request::is('company/login') ? 'active' : '' }}">
                    <a href="{{route('company.login')}}" class="login">
                        Login
                    </a>
                </li>
                @endif
                @auth
                <li class="depth-0">
                    <a class="dropdown-item" href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> @lang('menu.logout') <form id="logout-form" action="{{ route('company.logout') }}" method="POST" style="display: none;">@csrf </form> </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</div>