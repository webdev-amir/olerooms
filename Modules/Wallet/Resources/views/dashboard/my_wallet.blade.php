@extends('layouts.dashboard_app')
@section('title', " ".trans('menu.my_wallet')." ".trans('menu.pipe')." " .app_name())
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
                        @include('includes.dashboard.my_wallet_top_right_menu')
                     </ul>
                  </div>
               </div>
            </div>
            @if(auth()->user()->hasRole('investors') || auth()->user() && auth()->user()->is_kycCompleted())
            <div class="row mT70 mB90">
               <div class="col-sm-12">
                  <div class="loanoverview-block">
                     <h1>@lang('menu.add_money_to_account')</h1>
                  </div>
               </div>
            </div>
            <div class="nrsection">
                  <div class="loanoverview-block mB20">
                     <p class="customerid">@lang('menu.available_balance') : <span class="badge bg-transparent"> {{numberformatWithCurrency(auth()->user()->UserWalletAmount,2)}} </span></p>
                  </div>
                  {!! Form::open(['route' =>'dashboard.proceedToAddWalletMoney','id'=>'validateForm']) !!}
                  <div class="row justify-content-center">
                     <div class="col-sm-12 col-md-6 col-lg-4 mB15">
                           <div class="form-item ermsg">
                              <p class="formLabel formTop">@lang('menu.amount')</p>
                              {{ Form::text('amount',null, ['class'=>'form-style form-control numberonly','required','id'=>'amount','title'=>trans('menu.validiation.please_enter_amount'),'maxlength'=>'8','autocomplete'=>'off','min'=>1,'number'=>true,'data-msg-min'=>'Please enter a value greater than or equal to 1']) }}
                           </div>
                     </div>
                     <div class="col-sm-12 col-md-4 col-lg-2 mB15">
                         <button type="submit" id="addMoney" class="w-100 btn btn_gold" data-loader="@lang('flash.loader.please_wait')">@lang('menu.proceed') </button>
                     </div>
                     <div class="col-sm-12 text-center">
                           <span class="subtext fz20 mL25"> @lang('menu.money_will_be_added_to_credence_wallet') </span>
                     </div>
                  </div>
                  {!! Form::close() !!}
            </div>
            @else
            <div class="row mT70 mB60">
               <div class="col-sm-12">
                  <div class="loanoverview-block">
                     <h1>@lang('menu.my_wallet')</h1>
                     <p class="customerid">@lang('menu.complete_your_kyc_upload_your_valid_id_proof')</p>
                  </div>
               </div>
            </div>
            <div class="nrsection">
                 <p class="mB60 text-center grColor pending_kyc_status" id="result">
                  @if(auth()->user()->walletVerification && auth()->user()->walletVerification->status=='pending')
                     @lang('flash.error.your_kyc_verification_already_inprogress') 
                  @endif
                 </p>
                 {!! Form::open(['route' =>'dashboard.sendKycVerificationRequest','id'=>'F_kycVerification']) !!}
                  <div class="row justify-content-center">
                     <div class="col-sm-12 col-md-6 col-lg-4 mB20">
                        <div class="form-item ermsg">
                           <a href="javascript:;" class="w-100 btn btn_gold upbtn">
                           <i class="zmdi zmdi-cloud-upload"></i>@lang('menu.drang-or-opload-document')
                           <input type='file' id="docName" accept="application/pdf" class="imageanddocupload" data-uploadurl="{{route('dashboard.uploadKycVerificationDocument')}}" />
                           </a>
                           {{ Form::hidden('document',null, ['required','class'=>'hidden-field','id'=>'f_docName','title'=>trans('menu.validiation.please_seletc_kyc_document')]) }}
                           <label id="n_docName"></label>
                          </div>  
                     </div>

                     <div class="col-sm-12 col-md-4 col-lg-3">
                        <button type="submit" id="kycVerification" class="w-100 btn btn_gold directSubmit" data-loader="@lang('flash.loader.submitting_you_kyc_request')">@lang('menu.submit_document') </button>
                     </div>
                  </div>
                {!! Form::close() !!}
            </div>
            @endif
         </div>
      </div>
   </div>
</section>
@endsection
@section('uniquePageScript')
<script>
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