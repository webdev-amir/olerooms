@extends('layouts.dashboard_app')
@section('title', " ".trans('menu.wallet_payment')." ".trans('menu.pipe')." " .app_name())
@section('content')
<section class="dashboard">
   <div class="dasblock">
      @include('includes.dashboard.left_sidebar_menu')
      <div class="layoutSidenav_content">
         <div class="content_wrap">
            @include('includes.dashboard.topbar_header')
            <div class="welcomblock">
               <div class="row secondrow">
                  <div class="col-sm-12 col-md-5">
                     @if(auth()->user()->hasRole('investors'))
                         @include('includes.dashboard.my_investments_loans_data')
                     @endif
                     @if(auth()->user()->hasRole('borrowers'))
                        @include('includes.dashboard.borrowers_my_investments_loans_data')
                     @endif
                  </div>
                  <div class="col-sm-12 col-md-7">
                     <ul class="nav justify-content-end dashboardtabs">
                        @if(auth()->user()->hasRole('investors'))
                        <li class="nav-item">
                           <a class="nav-link" href="{{route('investor.myWallet')}}" title="@lang('menu.add_money')"><img
                              src="{{asset('img/dashboard/add-money.svg')}}">@lang('menu.add_money')</a>
                        </li>
                        @else
                        <li class="nav-item">
                           <a class="nav-link" href="{{route('borrower.myWallet')}}" title="@lang('menu.add_money')"><img
                              src="{{asset('img/dashboard/add-money.svg')}}">@lang('menu.add_money')</a>
                        </li>
                        @endif
                        <li class="nav-item">
                           <a class="nav-link active" href="javascript:;" title="@lang('menu.payment_&_transaction')"><img
                              src="{{asset('img/dashboard/payment-icon.svg')}}">@lang('menu.payment_&_transaction')</a>
                        </li>
                         @if(auth()->user()->hasRole('investors'))
                        <li class="nav-item">
                           <a class="nav-link {{ setActiveMainMenu('investor/redeem-credit') }}" href="{{route('investor.redeemCredit')}}" title="@lang('menu.redeem_credit')"><img src="{{asset('img/dashboard/statement-icon.svg')}}" >@lang('menu.redeem_credit')</a>
                        </li>
                        @else
                        <li class="nav-item">
                           <a class="nav-link {{ setActiveMainMenu('borrower/redeem-credit') }}" href="{{route('borrower.redeemCredit')}}" title="@lang('menu.redeem_credit')"><img src="{{asset('img/dashboard/statement-icon.svg')}}" >@lang('menu.redeem_credit')</a>
                        </li>
                        @endif
                     </ul>
                  </div>
               </div>
            </div>
            <div class="row mT70 mB90">
               <div class="col-sm-12">
                  <div class="loanoverview-block">
                     <h1>@lang('menu.payment')</h1>
                  </div>
               </div>
            </div>
            <div class="sign_up mL90 mR90 loan_payment">
                  <div class="row">
                     <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="form_block">
                           <div class="section_title mB20 text-left">
                              <h2>@lang('menu.payment_method')</h2>
                           </div>
                            <div class="img_wrap card-wrapper-block">
                              <div class="card-wrapper"></div>
                           </div> 
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="form_block">
                           <div class="tabsec">
                              <ul class="nav nav-tabs mB20" id="myTab" role="tablist">
                                 <li class="nav-item">
                                    <a class="nav-link active" id="debit_card" data-toggle="tab" href="#paymentform"
                                       role="tab" aria-controls="home" aria-selected="true"> <img
                                          src="{{asset('img/dashboard/creditcard.svg')}}">@lang('menu.debit_card')</a>
                                 </li>
                                 <li class="nav-item">
                                    <a class="nav-link" id="credit_card" data-toggle="tab"
                                       href="#paymentform" role="tab" aria-controls="profile"
                                       aria-selected="false"><img src="{{asset('img/dashboard/creditcard.svg')}}">
                                       @lang('menu.credit_card')</a>
                                 </li>
                              </ul>
                              <div class="tab-content" id="myTabContent">
                                 <div class="tab-pane  show active" id="paymentform" role="tabpanel"
                                    aria-labelledby="debit_card">
                                    {!! Form::open(['route' =>'dashboard.makeWalletPayment','id'=>'F_makePayment','class'=>'payment-form']) !!}
                                       {!! Form::hidden('id', $record->id, array('id'=>'id')) !!}
                                       {!! Form::hidden('payment_mode', 'debitcard', array('id'=>'payment_mode')) !!}
                                       {{ Form::hidden('status','charge', []) }}
                                       <div class="form-item ermsg">
                                          <p class="formLabel">@lang('menu.card_number')</p>
                                          {!! Form::text('number', null, array('placeholder' => '','id'=>'number','class'=>'form-style form-control','required','type'=>'tel','autocomplete'=>'off')) !!}
                                       </div>
                                       <div class="form-item ermsg">
                                          <p class="formLabel">@lang('menu.card_holder_name')</p>
                                          {!! Form::text('name', null, array('placeholder' => '','id'=>'name','class'=>'form-style form-control','required','autocomplete'=>'off','maxlength'=>'50')) !!}
                                       </div>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-item ermsg">
                                                <p class="formLabel">@lang('menu.expiry_mm_yy')</p>
                                                {!! Form::text('expiry', null, array('placeholder' => '','id'=>'expiry','class'=>'form-style form-control','required','autocomplete'=>'off')) !!}
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-item ermsg">
                                                <p class="formLabel">@lang('menu.cvv')</p>
                                               {!! Form::text('cvc', null, array('placeholder' => '','id'=>'cvc','class'=>'form-style form-control','required','autocomplete'=>'off')) !!}
                                             </div>
                                          </div>
                                       </div>
                                       <button type="submit" id="makePayment" class="w-100 mT10 mB20 btn btn-default btn_gold directSubmit" data-loader="@lang('flash.loader.please_wait')">@lang('menu.pay') {{numberformatWithCurrency($record->amount,2)}}</button>
                                    {!! Form::close() !!}
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
         </div>
      </div>
   </div>
</section>
@endsection
@section('uniquePageScript')
<script src="{{URL::to('js/card.js')}}"></script>
<script>
new Card({
   form: document.querySelector('.payment-form'),
   container: '.card-wrapper'
});

/* To Disable Inspect Element */
$(document).bind("contextmenu",function(e) {
 e.preventDefault();
});
$(document).keydown(function(e){
    if(e.which === 123){
       return false;
    }
});
document.onkeydown = function(e) {
if(event.keyCode == 123) {
return false;
}
if(e.ctrlKey && e.keyCode == 'E'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'H'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'A'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'F'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'E'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'V'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'C'.charCodeAt(0)){
return false;
}
}
</script>
@endsection



