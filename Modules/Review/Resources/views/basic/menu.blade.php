<li class="{{ Request::is('admin/review') ? 'active' : '' }} {{ Request::is('admin/review/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/sitemang.png')}}" / class="sidebar_icons"> <span>Review Manager</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    <li class="{{ Request::is('admin/review') ? 'active' : '' }}"><a href="{{route('review.index')}}"><i class="fa fa-list"></i> Review List</a></li>
  </ul>
</li>