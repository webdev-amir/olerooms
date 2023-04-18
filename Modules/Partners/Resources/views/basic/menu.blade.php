<li class="{{ Request::is('admin/partners') ? 'active' : '' }} {{ Request::is('admin/partners/*') ? 'active' : '' }} treeview">
    <a href="javascript:;"><img src="{{URL::to('img/sidebar/partners.png')}}" / class="sidebar_icons">  <span>{{trans('partners::menu.sidebar.menu_title')}}</span>
     <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
       <li class="{{ Request::is('admin/partners') ? 'active' : '' }}"><a href="{{route('partners.index')}}"><i class="fa fa-list"></i> {{trans('partners::menu.sidebar.slug')}}</a></li>
        <li class="{{ Request::is('admin/partners/create') ? 'active' : '' }}"><a href="{{route('partners.create')}}"><i class="fa fa-plus"></i> {{trans('partners::menu.sidebar.create')}}</a></li>
      </ul>
</li>
 