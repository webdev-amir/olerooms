<li class="{{ Request::is('admin/newsupdates') ? 'active' : '' }} {{ Request::is('admin/newsupdates/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/newsupdates.png')}}" / class="sidebar_icons"> <span>{{trans('newsupdates::menu.sidebar.manage')}}</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    <li class="{{ Request::is('admin/newsupdates') ? 'active' : '' }}"><a href="{{route('newsupdates.index')}}"><i class="fa fa-list"></i> {{trans('newsupdates::menu.sidebar.slug')}}</a></li>
    @if(Auth::user()->hasRole(['admin']) || Auth::user()->hasAnyPermission(['newsupdates.create']))
    <li class="{{ Request::is('admin/newsupdates/create') ? 'active' : '' }}"><a href="{{route('newsupdates.create')}}"><i class="fa fa-plus"></i> {{trans('newsupdates::menu.sidebar.create')}}</a></li>
    @endif
  </ul>
</li>