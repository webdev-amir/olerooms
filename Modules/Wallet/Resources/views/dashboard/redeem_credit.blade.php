@extends('layouts.dashboard_app')
@section('title', " ".trans('menu.reedem_credit')." ".trans('menu.pipe')." " .app_name())
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
                     @include('includes.dashboard.my_investments_loans_data')
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
                     <h1>@lang('menu.send_money_from_wallet_to_bank')</h1>
                  </div>
               </div>
            </div>
            <div class="nrsection">
                  <div class="loanoverview-block mB20">
                     <p class="customerid">@lang('menu.available_balance') : <span class="badge bg-transparent updateWalletAmount"> {{numberformatWithCurrency(auth()->user()->UserWalletAmount,2)}} </span></p>
                  </div>
                  {!! Form::open(['route' =>'dashboard.sendRedeemCreditRequest','id'=>'F_addMoney']) !!}
                  <div class="row justify-content-center">
                     <div class="col-sm-12 col-md-6 col-lg-4 mB15">
                           <div class="form-item ermsg">
                              <p class="formLabel formTop">@lang('menu.amount')</p>
                              {{ Form::text('amount',null, ['required','class'=>'form-style form-control numberonly','id'=>'amount','title'=>trans('menu.validiation.please_enter_amount'),'maxlength'=>8,'autocomplete'=>'off','min'=>1,'number'=>true,'data-msg-min'=>'Please enter a value greater than or equal to 1']) }}
                           </div>
                     </div>
                     <div class="col-sm-12 col-md-4 col-lg-2 mB15">
                         <button type="submit" id="addMoney" class="btn btn-default btn_gold directSubmitFund" data-loader="@lang('flash.loader.please_wait')" data-submit="no">@lang('menu.proceed') </button>
                     </div>
                     <div class="col-sm-12 text-center">
                          <span class="subtext fz20 mL25"> @lang('menu.money_will_be_added_to_personal_bank_account') </span>
                     </div>
                  </div>
                  <div id="fundConfirmModel" class="modal" role="dialog">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                           <div class="modal-header">
                             <h5 class="modal-title" id="exampleModalLabel">@lang('menu.are_you_sure_you_want_to_proceed')</h5>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                             </button>
                           </div>
                           <div class="modal-body">
                                <div class="cardlayout shadow-none p-0">
                                 <div class="cardinfo">
                                    <div class="cardinfo_leftsect">@lang('menu.bank_account_name')</div>
                                    <div class="cardinfo_rightsect">@lang('menu.credence_bank')</div>
                                 </div>
                                 <div class="cardinfo">
                                    <div class="cardinfo_leftsect">@lang('menu.wallat_amount')</div>
                                    <div class="cardinfo_rightsect updateWalletAmount">{{numberformatWithCurrency(auth()->user()->UserWalletAmount,2)}}</div>
                                 </div>
                               </div>
                           </div>
                           <div class="modal-footer border-0">
                              <button type="submit" id="FundLoanConfirm" class="btn btn-default btn_gold  confirmSubmit " data-loader="@lang('flash.loader.investing_your_fund')" data-submit="yes">@lang('menu.proceed') </button>
                           </div>
                          </div>
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
                           {{ Form::hidden('document',null, ['required','id'=>'f_docName','title'=>trans('menu.validiation.please_seletc_kyc_document')]) }}
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
<script type="text/javascript">
 $('.directSubmitFund,.confirmSubmit').click(function() { 
      var submitFormId = 'F_' + this.id;
       _submit = 'yes';
      if(submitFormId == 'F_FundLoanConfirm'){
         var submitFormId = 'F_addMoney';
          _submit = 'yes';
      }else{
          _submit = 'no';
      }
      var action = $("#" + submitFormId).attr("action");
      if(typeof($(this).data("loader")) != "undefined"){
         var _loaderMsg = $(this).data("loader");
      }
      $("#" + submitFormId).validate({
         ignore: [],
         cache: false,
          rules: {
          },
          messages: {
          },
          errorPlacement: function(e, r) {
             e.appendTo(r.closest('.ermsg')); 
          },
          submitHandler: function(form) {
              if(_submit=='no'){
                $('#fundConfirmModel').modal('show');
              }else{
                $(form).ajaxSubmit({
                    url: action,
                    type: "POST",
                    cache: false,
                    dataType: 'json',
                    beforeSend: function (){
                      $("#loader_msg").html(_loaderMsg);
                      $("#loader").show();
                    },
                    success: function(data) { 
                            $('.directSubmit').prop("disabled", false);
                            //if condition used for apply for Loan form
                            $("#loader").hide();
                            if (data['reset']) {
                                document.getElementById(submitFormId).reset();
                            }
                            Lobibox.notify(data['type'], {
                                position: "top right",
                                msg: data['message']
                            });
                            if (data['status_code'] == 200) {
                                $('#fundConfirmModel').modal('show');
                                if (data['html']) {
                                    $("#result").html('');
                                    $("#result").html(JSON.parse(data['body']));
                                }
                            }
                            if(data['walletAmount']){ 
                                $(".updateWalletAmount").html(data['walletAmount']);
                                $('#fundConfirmModel').modal('hide');
                            }
                            if(data['url']){ 
                                location.href = data['url'];
                            }
                    },
                    error: function(e) {
                        $("#loader").hide();
                        $('.directSubmit').prop("disabled", false);
                        var Arry = e.responseText;
                        var error = "";
                        JSON.parse(Arry, function(k, v) {
                            if (typeof v != 'object') {
                                error += v + "<br>"
                            }
                        })
                        Lobibox.notify('error', {
                            rounded: false,
                            delay: 5000,
                            delayIndicator: true,
                            position: "top right",
                            msg: error
                        });
                    }
                });
              }
              $(".directSubmit").prop("disabled", true);
              $(".lobibox-close").trigger('click');
          },
      });
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