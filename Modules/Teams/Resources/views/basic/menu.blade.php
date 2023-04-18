<li class="{{ Request::is('admin/teams') ? 'active' : '' }} {{ Request::is('admin/teams/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/teams.png')}}"/ class="sidebar_icons">  <span>{{trans('teams::menu.sidebar.manage')}}</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    <li class="{{ Request::is('admin/teams') ? 'active' : '' }}"><a href="{{route('teams.index')}}"><i class="fa fa-list"></i> {{trans('teams::menu.sidebar.slug')}}</a></li>
    @if(Auth::user()->hasRole(['admin']) || Auth::user()->hasAnyPermission(['teams.create']))
    <li class="{{ Request::is('admin/teams/create') ? 'active' : '' }}"><a href="{{route('teams.create')}}"><i class="fa fa-plus"></i> {{trans('teams::menu.sidebar.create')}}</a></li>
    @endif
  </ul>
</li>