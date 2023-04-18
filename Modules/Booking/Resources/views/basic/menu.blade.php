<li class="{{ Request::is('admin/booking') ? 'active' : '' }} {{ Request::is('admin/booking/*') ? 'active' : '' }} {{ Request::is('admin/cancelbooking') ? 'active' : '' }} {{ Request::is('admin/cancelbooking/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/sitemang.png')}}" / class="sidebar_icons"> <span>Booking Manager</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    <li class="{{ Request::is('admin/booking') ? 'active' : '' }}"><a href="{{route('booking.index')}}"><i class="fa fa-list"></i> All Bookings</a></li>
    <li class="{{ Request::is('admin/cancelbooking') ? 'active' : '' }}"><a href="{{route('booking.cancelbooking.index')}}"><i class="fa fa-list"></i> Booking Cancellation</a></li>
  </ul>
</li>