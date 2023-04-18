<li class="{{ Request::is('admin/schedulevisit') ? 'active' : '' }} {{ Request::is('admin/schedulevisit/*') ? 'active' : '' }} {{ Request::is('admin/cancelschedulevisit') ? 'active' : '' }} {{ Request::is('admin/cancelschedulevisit/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/sitemang.png')}}" / class="sidebar_icons"> <span>Schedule Visit Manager</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    <li class="{{ Request::is('admin/schedulevisit') ? 'active' : '' }} {{ Request::is('admin/schedulevisit/*') ? 'active' : '' }}"><a href="{{route('adminschedulevisit.index')}}"><i class="fa fa-list"></i> All Schedule Visits</a></li>
    <li class="{{ Request::is('admin/cancelschedulevisit') ? 'active' : '' }} {{ Request::is('admin/cancelschedulevisit/*') ? 'active' : '' }}"><a href="{{route('booking.cancelschedulevisit.index')}}"><i class="fa fa-list"></i> Schedule Cancellation</a></li>
  </ul>
</li>