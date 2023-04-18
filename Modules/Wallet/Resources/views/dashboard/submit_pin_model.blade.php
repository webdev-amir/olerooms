<div class="modal-header">
   <h5 class="modal-title" id="exampleModalLabel">Verify Pin</h5>
   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
   </button>
 </div>
{!! Form::open(['route' =>'dashboard.makeWalletPayment','id'=>'F_VerifyPin']) !!}
{{ Form::hidden('id',$tempsessionid, ['id'=>'tempsessionid']) }}
{{ Form::hidden('status',$data['status'], ['id'=>'status']) }}
{{ Form::hidden('reference',$data['reference'], ['id'=>'reference']) }}
   <div class="modal-body">
        <div class="cardlayout shadow-none p-0">
          <div class="col-sm-12 ermsg">
            <div class="form-item ermsg">
              {{ Form::text('pin',NULL, ['required','class'=>'form-style form-control','id'=>'pin','placeholder'=>'Enter Pin','title'=>'Please enter pin','maxlength'=>4,'autocomplete'=>'off']) }}
            </div>
          </div>
       </div>
   </div>
   <div class="modal-footer border-0">
      <button type="submit" id="VerifyPin" class="btn btn-default btn_gold directSubmit" data-loader="Please wait.. verifing your pin">Verify </button>
   </div>
 {!! Form::close() !!}