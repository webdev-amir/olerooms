<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{Auth::guard('admin')->user()->PicturePath}}" class="img-circle" alt="User Image" style="height: 35px;width: 35px;" />
            </div>
            <div class="pull-left info">
                <p>{{ucfirst(Auth::guard('admin')->user()->FullName)}} <a href="javascript:;"><i class="fa fa-circle text-success"></i></a></p>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">Navigation Menu</li>
            @can('backend.dashboard')
            <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}"><a href="{{route('backend.dashboard')}}"><img src="{{URL::to('img/sidebar/dashboard.png')}}" / class="sidebar_icons"> <span>Dashboard</span></a></li>
            @endcan

            @if(Auth::user()->hasAnyPermissionCustom(['users.index','subadmin.index'],'admin'))
            <li class="{{ Request::is('admin/customer*') || Request::is('admin/vendor*') ? 'active' : '' }}  {{ Request::is('admin/admin*') ? 'active' : '' }} treeview">
                <a href="javascript:;">
                    <img src="{{URL::to('img/sidebar/users.png')}}" / class="sidebar_icons"> <span>{{trans('menu.sidebar.users.main')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @can('users.index')
                    <li class="{{ Request::is('admin/customer*') ? 'active' : '' }}"><a href="{{route('users.index')}}" alt="Users"><i class="fa fa-circle-o"></i>Customers</a></li>
                    @endcan
                    <li class="{{ Request::is('admin/vendor*') ? 'active' : '' }}"><a href="{{route('vendor.index')}}" alt="Vendors"><i class="fa fa-circle-o"></i>{{trans('menu.sidebar.vendors.main')}}</a></li>

                    <li class="{{ Request::is('admin/company*') ? 'active' : '' }}"><a href="{{route('company.index')}}" alt="Company"><i class="fa fa-circle-o"></i>{{trans('menu.sidebar.company.main')}}</a></li>

                    <li class="{{ Request::is('admin/agent*') ? 'active' : '' }}"><a href="{{route('agent.index')}}" alt="Agents"><i class="fa fa-circle-o"></i>{{trans('menu.sidebar.agent.main')}}</a></li>
                </ul>
            </li>
            @endcan

            @if(Auth::user()->hasAnyPermissionCustom(['configuration.index','email-templates.index','roles.index','permissions.index','staticpages.index'],'admin'))
            <li class="{{ Request::is('admin/configuration*') ? 'active' : '' }} {{ Request::is('admin/staticpages*') ? 'active' : '' }} {{ Request::is('admin/aboutus*') ? 'active' : '' }} {{ Request::is('admin/roles*') ? 'active' : '' }} {{ Request::is('admin/permission*') ? 'active' : '' }} {{ Request::is('admin/email-templates*') ? 'active' : '' }} {{ Request::is('admin/slider*') ? 'active' : '' }} {{ Request::is('admin/amenities*') ? 'active' : '' }} {{ Request::is('admin/teams*') ? 'active' : '' }} {{ Request::is('admin/partners*') ? 'active' : '' }} {{ Request::is('admin/propertytype*') ? 'active' : '' }} {{ Request::is('admin/trustedcustomers*') ? 'active' : '' }} treeview">

                <a href="javascript:;"><img src="{{URL::to('img/sidebar/sitemang.png')}}" / class="sidebar_icons"> <span>Site Managment</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @can('configuration.index')
                    @if(\Module::collections()->has('Configuration'))
                    @include('configuration::basic.menu')
                    @endif
                    @endcan

                    @can('email-templates.index')
                    @if(\Module::collections()->has('EmailTemplates'))
                    @include('emailtemplates::basic.menu')
                    @endif
                    @endcan
                  
                        @can('roles.index1')
                        @if(\Module::collections()->has('Roles'))
                        @include('roles::basic.menu')
                        @endif
                        @endcan

                        @can('staticpages.index')
                        @if(\Module::collections()->has('StaticPages'))
                        @include('staticpages::basic.menu')
                        @endif
                        @endcan

                        @can('slider.index')
                        @if(\Module::collections()->has('Slider'))
                        @include('slider::basic.menu')
                        @endif
                        @endcan

                        @can('amenities.index')
                        @if(\Module::collections()->has('Amenities'))
                        @include('amenities::basic.menu')
                        @endif
                        @endcan

                        @can('teams.index')
                        @if(\Module::collections()->has('Teams'))
                        @include('teams::basic.menu')
                        @endif
                        @endcan

                        @can('partners.index')
                        @if(\Module::collections()->has('Partners'))
                        @include('partners::basic.menu')
                        @endif
                        @endcan

                        @can('propertytype.index1')
                        @if(\Module::collections()->has('PropertyType'))
                        @include('propertytype::basic.menu')
                        @endif
                        @endcan

                        @can('trustedcustomers.index')
                        @if(\Module::collections()->has('TrustedCustomers'))
                        @include('trustedcustomers::basic.menu')
                        @endif
                        @endcan
                </ul>
            </li>
            @endif
      
                @if(\Module::collections()->has('Property'))
                @include('property::basic.menu')
                @endif
                @can('faq.index')
                @if(\Module::collections()->has('Faq'))
                @include('faq::basic.menu')
                @endif
                @endcan
                @can('newsupdates.index')
                @if(\Module::collections()->has('NewsUpdates'))
                @include('newsupdates::basic.menu')
                @endif
                @endcan
                @can('coupon.index')
                @if(\Module::collections()->has('Coupon'))
                @include('coupon::basic.menu')
                @endif
                @endcan

                @can('contactus.index')
                @if(\Module::collections()->has('Contactus'))
                @include('contactus::basic.menu')
                @endif
                @endcan

                @if(\Module::collections()->has('Settings'))
                @include('settings::basic.menu')
                @endif

                @if(\Module::collections()->has('City'))
                @include('city::basic.menu')
                @endif

                {{-- @if(\Module::collections()->has('Booking'))
                @include('booking::basic.menu')
            @endif --}}

                @can('payment.index')
                @if(\Module::collections()->has('Payment'))
                @include('payment::basic.menu')
                @endif
                @endcan

                @if(\Module::collections()->has('Booking'))
                @include('booking::basic.menu')
                @endif
                @if(\Module::collections()->has('ScheduleVisit'))
                @include('schedulevisit::basic.menu')
                @endif

                @if(\Module::collections()->has('Review'))
                @include('review::basic.menu')
                @endif
        </ul>
    </section>
</aside>
<!-- Global model for ajax -->
<div class="modal fade" id="globalModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modelContent">
            <!-- dynamic content goes here  -->
        </div>
    </div>
</div>