<li class="{{ Request::is('admin/email-templates') ? 'active' : '' }} {{ Request::is('admin/email-templates/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/email.png')}}" class="sidebar_icons"> <span>{{trans('emailtemplates::menu.sidebar.manage')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/email-templates') ? 'active' : '' }}"><a href="{{route('email-templates.index')}}"><i class="fa fa-list"></i> {{trans('emailtemplates::menu.sidebar.slug')}}</a></li>
    <?php /*<li class="{{ Request::is('admin/email-templates/create') ? 'active' : '' }}"><a href="{{route('email-templates.create')}}"><i class="fa fa-plus"></i> {{trans('emailtemplates::menu.sidebar.create')}}</a></li> */ ?>
  </ul>
</li>
