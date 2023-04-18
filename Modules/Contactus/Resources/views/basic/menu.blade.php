<li class="{{ Request::is('admin/contactus') ? 'active' : '' }} {{ Request::is('admin/contactus/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/contactus.png')}}" / class="sidebar_icons"> <span>{{trans('contactus::menu.sidebar.menu_title')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/contactus') ? 'active' : '' }}"><a href="{{route('contactus.index')}}"><i class="fa fa-list"></i> {{trans('contactus::menu.sidebar.slug')}}</a></li>
  </ul>
</li>