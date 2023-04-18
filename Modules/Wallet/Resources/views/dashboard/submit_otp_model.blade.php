<div class="modal-header">
   <h5 class="modal-title" id="exampleModalLabel">Verify OTP</h5>
   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
   </button>
 </div>
{!! Form::open(['route' =>'dashboard.makeWalletPayment','id'=>'F_VerifyOTP']) !!}
{{ Form::hidden('id',$tempsessionid, ['id'=>'tempsessionid']) }}
{{ Form::hidden('status',$data['status'], ['id'=>'status']) }}
{{ Form::hidden('reference',$data['reference'], ['id'=>'reference']) }}
   <div class="modal-body">
        <div class="cardlayout shadow-none p-0">
          <div class="col-sm-12 ermsg">
            <div class="form-item ermsg">
              {{ Form::text('otp',NULL, ['required','class'=>'form-style form-control','id'=>'pin','placeholder'=>'Enter OTP','title'=>'Please enter otp','maxlength'=>8,'autocomplete'=>'off']) }}
            </div>
          </div>
       </div>
   </div>
   <div class="modal-footer border-0">
      <button type="submit" id="VerifyOTP" class="btn btn-default btn_gold directSubmit" data-loader="Please wait.. verifing your otp">Verify </button>
   </div>
 {!! Form::close() !!}