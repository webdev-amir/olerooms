 <div class="sidebar-user">
    <div class="bravo-close-menu-user"><i class="icofont-scroll-left"></i></div>
    <div class="sidebar-menu">
       <a href="{{route('agent.home')}}" class="dashboard-logo-left">
          <img src="{{URL::to('img/olelogo.png')}}" alt="Ole Rooms">
       </a>
       <ul class="main-menu">
          <li class="{{ setActive('agent/dashboard') }}"><a href="{{route('agent.dashboard')}}"><i class="ri-dashboard-fill"></i>My Dashboard </a></li>
          <li class="{{ setActive('agent/mybooking') }}"><a href="{{route('agent.dashboard.mybookings')}}"><i class="ri-calendar-line"></i>My Bookings </a></li>
          <li class="{{ setActive('agent/myearnings') }}"><a href="{{route('agent.dashboard.myEarnings')}}"><i><img src="{{URL::to('img/rup.svg')}}" alt="image" /> </i>My Earnings</a> </li>
          <li class="{{ setActive('agent/myprofile') }}"><a href="{{route('agent.dashboard.myprofile')}}"><i class="ri-user-fill"></i> My Profile</a></li>
          <li class="{{ setActive('agent/notifications') }}"><a href="{{route('agent.dashboard.notifications')}}"><i class="ri-notification-3-fill"></i> Notifications </a></li>
          <li>
             <form id="logout-form-agent" action="{{ route('agent.logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
             </form>
             <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form-agent').submit();"><i class="ri-logout-circle-r-line"></i> Logout </a>
          </li>
       </ul>
    </div>
 </div>