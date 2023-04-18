<li class="{{ Request::is('admin/state') ? 'active' : '' }} {{ Request::is('admin/state/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/city.png')}}" class="sidebar_icons"><span>State Manager </span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/city') ? 'active' : '' }} {{ Request::is('admin/state') ? 'active' : '' }}"><a href="{{route('state.index')}}"><i class="fa fa-list"></i> States </a></li>
  </ul>
</li>