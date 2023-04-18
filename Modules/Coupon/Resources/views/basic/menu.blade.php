<li class="{{ Request::is('admin/coupon') ? 'active' : '' }} {{ Request::is('admin/coupon/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/coupon.png')}}" class="sidebar_icons"> <span>{{trans('coupon::menu.sidebar.manage')}}</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    <li class="{{ Request::is('admin/coupon') ? 'active' : '' }}"><a href="{{route('coupon.index')}}"><i class="fa fa-list"></i> {{trans('coupon::menu.sidebar.slug')}}</a></li>
    @if(Auth::user()->hasRole(['admin']) || Auth::user()->hasAnyPermission(['coupon.create']))
    <li class="{{ Request::is('admin/coupon/create') ? 'active' : '' }}"><a href="{{route('coupon.create')}}"><i class="fa fa-plus"></i> {{trans('coupon::menu.sidebar.create')}}</a></li>
    @endif
  </ul>
</li>