<div class="sidebar-user">
   <div class="bravo-close-menu-user"><i class="icofont-scroll-left"></i></div>
   <div class="sidebar-menu">
      <a href="{{URL::to('/')}}" class="dashboard-logo-left">
         <img src="{{URL::to('img/olelogo.png')}}" alt="Ole Rooms">
      </a>
      <ul class="main-menu">
         <li class="{{ setActive('customer/mybooking') }}"><a href="{{route('customer.dashboard.mybooking')}}"><i class="ri-calendar-line"></i>My Bookings </a></li>
         <li class="{{ setActive('customer/myvisit') }}"><a href="{{route('customer.dashboard.myvisit')}}"><i class="ri-building-4-fill"></i>My Visits</a></li>
         <li class="{{ setActive('customer/wishlist') }}"><a href="{{route('dashboard.wishlist')}}"><i class="ri-heart-fill"></i> My Wishlist</a> </li>
         <li class="{{ setActive('customer/myprofile') }}">
            <a href="{{route('customer.dashboard.myprofile')}}">
               <i class="ri-user-fill"></i> My Profile
            </a>
         </li>
         <li class="{{ setActive('customer/notifications') }}"><a href="{{route('customer.dashboard.notifications')}}"><i class="ri-notification-3-fill"></i> Notifications </a></li>
         <li>
            @if(auth()->user()->hasRole('customer'))
                 @php $logOutRoute = route('customer.logout') @endphp
            @elseif(auth()->user()->hasRole('agent'))
                 @php $logOutRoute = route('agent.logout') @endphp
            @elseif(auth()->user()->hasRole('company'))
                 @php $logOutRoute = route('company.logout') @endphp
            @elseif(auth()->user()->hasRole('owner'))
                 @php $logOutRoute = route('owner.logout') @endphp
            @else
                 @php $logOutRoute = route('auth.logout') @endphp
            @endif
            <form id="logout-form-customer" action="{{ $logOutRoute }}" method="POST" style="display: none;">
               {{ csrf_field() }}
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-customer').submit();"><i class="ri-logout-circle-r-line"></i> Logout </a>
         </li>
      </ul>
   </div>
</div>