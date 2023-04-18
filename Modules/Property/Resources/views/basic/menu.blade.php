<li class="{{ Request::is('admin/property') ? 'active' : '' }} {{ Request::is('admin/property/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/manage-property.png')}}" class="sidebar_icons"> <span>{{trans('property::menu.sidebar.manage')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/property') ? 'active' : '' }}"><a href="{{route('property.index')}}"><i class="fa fa-list"></i> {{trans('property::menu.sidebar.slug')}}</a></li>
  </ul>
</li>
