<div class="sidebar-user">
   <div class="bravo-close-menu-user"><i class="icofont-scroll-left"></i></div>
   <div class="sidebar-menu">
      <a href="{{route('company.home')}}" class="dashboard-logo-left">
         <img src="{{URL::to('img/olelogo.png')}}" alt="Ole Rooms">
      </a>
      <ul class="main-menu">
         <li class="{{ setActive('company/dashboard') }}"><a href="{{route('company.dashboard')}}"><i class="ri-dashboard-fill"></i>My Dashboard </a></li>
         <li class="{{ setActive('company/my-code-bookings') }}"><a href="{{route('company.dashboard.mycodebookings')}}"><i class="ri-calendar-line"></i>My Code Bookings </a></li>
         <li class="{{ setActive('company/mybooking') }}"><a href="{{route('company.dashboard.mybookings')}}"><i class="ri-calendar-line"></i>My Bookings </a></li>
         <li class="{{ setActive('company/myvisit') }}"><a href="{{route('company.dashboard.myvisits')}}"><i class="ri-building-4-fill"></i>My Visits</a> </li>
         <li class="{{ setActive('company/myearnings') }}"><a href="{{route('company.dashboard.myEarnings')}}"><i><img src="{{URL::to('img/rup.svg')}}" alt="image" /> </i>My Earnings</a> </li>
         <li class="{{ setActive('company/myprofile') }}"><a href="{{route('company.dashboard.myprofile')}}"><i class="ri-user-fill"></i> My Profile</a></li>
         <li class="{{ setActive('company/notifications') }}"><a href="{{route('company.dashboard.notifications')}}"><i class="ri-notification-3-fill"></i> Notifications </a></li>
         <li>
            <form id="logout-form-company" action="{{ route('company.logout') }}" method="POST" style="display: none;">
               {{ csrf_field() }}
            </form>
            <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form-company').submit();"><i class="ri-logout-circle-r-line"></i> Logout </a>
         </li>
      </ul>
   </div>
</div>