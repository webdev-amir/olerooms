<li class="{{ Request::is('admin/trustedcustomers') ? 'active' : '' }} {{ Request::is('admin/trustedcustomers/*') ? 'active' : '' }} treeview">
    <a href="javascript:;"><img src="{{URL::to('img/sidebar/trustedcustomer.png')}}" / class="sidebar_icons">  <span>{{trans('trustedcustomers::menu.sidebar.menu_title')}}</span>
     <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
       <li class="{{ Request::is('admin/trustedcustomers') ? 'active' : '' }}"><a href="{{route('trustedcustomers.index')}}"><i class="fa fa-list"></i> {{trans('trustedcustomers::menu.sidebar.slug')}}</a></li>
        <li class="{{ Request::is('admin/trustedcustomers/create') ? 'active' : '' }}"><a href="{{route('trustedcustomers.create')}}"><i class="fa fa-plus"></i> {{trans('trustedcustomers::menu.sidebar.create')}}</a></li>
      </ul>
</li>
