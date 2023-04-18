<li class="{{ Request::is('admin/settings') ? 'active' : '' }} {{ Request::is('admin/settings/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/contactus.png')}}" / class="sidebar_icons"><span>{{trans('settings::menu.sidebar.menu_title')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/settings') ? 'active' : '' }}"><a href="{{route('settings.index')}}"><i class="fa fa-list"></i> {{trans('settings::menu.sidebar.slug')}}</a></li>
  </ul>
</li>
{{--<img src="{{URL::to('img/sidebar/lock.png')}}" / class="sidebar_icons">--}}