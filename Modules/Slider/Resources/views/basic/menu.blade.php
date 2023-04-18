<li class="{{ Request::is('admin/slider') ? 'active' : '' }} {{ Request::is('admin/slider/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/film.png')}}" class="sidebar_icons"> <span>{{trans('Banner Management')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/slider') ? 'active' : '' }}"><a href="{{route('slider.index')}}"><i class="fa fa-list"></i> {{trans('Banner List')}}</a></li>
  </ul>
</li>
