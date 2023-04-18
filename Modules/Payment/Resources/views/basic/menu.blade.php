<li class="{{ Request::is('admin/payments') ? 'active' : '' }} {{ Request::is('admin/payments/*') ? 'active' : '' }}  {{ Request::is('admin/credit-redeem-requests') ? 'active' : '' }} treeview">
  <a href="javascript:;"><img src="{{URL::to('img/sidebar/payment.png')}}"/ class="sidebar_icons"> <span>{{trans('payment::menu.sidebar.menu_title')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    @can('payment.index')
      <li class="{{ Request::is('admin/payments') ? 'active' : '' }}"><a href="{{route('payment.index')}}"><i class="fa fa-list"></i> {{trans('payment::menu.sidebar.slug')}}</a></li>
   @endcan
   <li class="{{ Request::is('admin/credit-redeem-requests') ? 'active' : '' }}"><a href="{{route('redeemRequest.index')}}"><i class="fa fa-list"></i> {{trans('wallet::menu.sidebar.redeem_credit_requests')}}</a></li>
  </ul>
</li>