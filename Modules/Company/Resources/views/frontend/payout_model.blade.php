<div class="modal payment_modal " tabindex="-1" role="dialog" id="paymentModal">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-body p-0">
            {!! Form::open(['route' =>'company.dashboard.sendRedeemCreditRequest','id'=>'F_addMoney']) !!}
            <div class="iconwrap"><img src="{{URL::to('images/home-agent.svg')}}" alt="img not found" /></div>
            <p class="mb-0 medium font24 black">Are you sure you want to <br>reedem amount from your wallet ? </p>
            <div class="btn_row mt-4">
               <button type="button" class="btn outlineBtn_gradient minw-170 mr-3" data-dismiss="modal"> No, Cancel it </button>
               <button type="submit" id="addMoney" class="btn customBtn btn-success minw-170 directSubmit " data-loader="@lang('Please wait!! sending your redeem request')" data-submit="yes">@lang('Yes, Continue') </button>
            </div>
            {!! Form::close() !!}
         </div>
      </div>
   </div>
</div>