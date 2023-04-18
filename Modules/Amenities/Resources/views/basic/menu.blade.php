<li class="{{ Request::is('admin/amenities') ? 'active' : '' }} {{ Request::is('admin/amenities/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/amenities.png')}}"/ class="sidebar_icons"> <span>{{trans('amenities::menu.sidebar.manage')}}</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    <li class="{{ Request::is('admin/amenities') ? 'active' : '' }}"><a href="{{route('amenities.index')}}"><i class="fa fa-list"></i> {{trans('amenities::menu.sidebar.slug')}}</a></li>
    @if(Auth::user()->hasRole(['admin']) || Auth::user()->hasAnyPermission(['amenities.create']))
    <li class="{{ Request::is('admin/amenities/create') ? 'active' : '' }}"><a href="{{route('amenities.create')}}"><i class="fa fa-plus"></i> {{trans('amenities::menu.sidebar.create')}}</a></li>
    @endif
  </ul>
</li>