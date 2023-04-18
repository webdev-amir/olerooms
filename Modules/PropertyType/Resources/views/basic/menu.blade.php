<li class="{{ Request::is('admin/propertytype') ? 'active' : '' }} {{ Request::is('admin/propertytype/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/property.png')}}" / class="sidebar_icons"> <span>{{trans('propertytype::menu.sidebar.manage')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/propertytype') ? 'active' : '' }}"><a href="{{route('propertytype.index')}}"><i class="fa fa-list"></i> {{trans('propertytype::menu.sidebar.slug')}}</a></li>
    @if(Auth::user()->hasRole(['admin']) || Auth::user()->hasAnyPermission(['propertytype.create']))
    <li class="{{ Request::is('admin/propertytype/create') ? 'active' : '' }}"><a href="{{route('propertytype.create')}}"><i class="fa fa-plus"></i> {{trans('propertytype::menu.sidebar.create')}}</a></li>
    @endif
  </ul>
</li>
