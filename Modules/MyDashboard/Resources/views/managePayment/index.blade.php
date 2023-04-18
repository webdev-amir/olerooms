@extends('layouts.dashboard_master')
@section('title', "Payment History ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="dashboardrightPanel">
   <div class="inner_content w-100">
      <div class="mB35 aos-animate" data-aos="fade-up" data-aos-duration="3000">
         <h2 class="sec-title fz40 extraBold nunito black" id="offcanvasRightLabel"><span> Payment History </span></h2>
      </div>
      <div id="result_all_payments">
         @include('mydashboard::managePayment.ajax_all_payments')
      </div>
   </div>
</div>
<?php /*
<div class="dashboardrightPanel">
   <my-payments-component 
      auth-userid="{{ Auth::user()->id }}" 
      action-route="{{route('dashboard.updatePassword')}}"
   > 
</div> */ ?>
@endsection