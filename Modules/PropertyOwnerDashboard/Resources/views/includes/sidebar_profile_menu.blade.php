 <div class="sidebar-user">
   <div class="bravo-close-menu-user"><i class="icofont-scroll-left"></i></div>
   <div class="sidebar-menu">
      <a href="{{route('propertyowner.home')}}" class="dashboard-logo-left">
         <img src="{{URL::to('img/olelogo.png')}}" alt="Ole Rooms">
      </a>
      <ul class="main-menu">
         <li class="{{ setActive('owner/dashboard') }}"><a href="{{route('vendor.dashboard')}}"><i class="ri-dashboard-fill"></i>My Dashboard </a></li>
         <li class="{{ setActive('owner/myproperty') }}"><a href="{{route('vendor.myproperty')}}"><i class="ri-hotel-fill"></i>My Property </a></li>
         <li class="{{ setActive('owner/mybooking') }}"><a href="{{route('vendor.dashboard.mybookings')}}"><i class="ri-calendar-line"></i>My Bookings </a></li>
         <li class="{{ setActive('owner/myvisit') }}"><a href="{{route('vendor.dashboard.myvisits')}}"><i class="ri-building-4-fill"></i>My Visits</a></li>
         <li class="{{ setActive('owner/myearnings') }}"><a href="{{route('vendor.dashboard.myEarnings')}}"><i><img src="{{URL::to('img/rup.svg')}}" alt="image"/> </i>My Earnings</a> </li>
         <li class="{{ setActive('owner/myreviews') }}"><a href="{{route('vendor.dashboard.myReviews')}}"><i class="ri-star-fill"></i> My Reviews</a> </li>
         <li  class="{{ setActive('owner/myprofile') }}"><a href="{{route('vendor.dashboard.myprofile')}}"><i class="ri-user-fill"></i> My Profile</a></li>
         <li class="{{ setActive('owner/notifications') }}"><a href="{{route('vendor.dashboard.notifications')}}"><i class="ri-notification-3-fill"></i> Notifications </a></li>
         <li>
            <form id="logout-form-vendor" action="{{ route('vendor.logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-vendor').submit();"><i class="ri-logout-circle-r-line"></i> Logout </a>
         </li>
      </ul>
   </div>
</div>
