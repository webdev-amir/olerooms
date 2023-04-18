 <div class="modal-header">
   <button type="button" class="close resetclose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="modal-title" id="myModalLabel">Confirmation Redeem</h4>
 </div>
 {!! Form::open(['route' => 'redeemRequest.changeRedeemStatus','id'=>'F_RedeemCreditProceed']) !!}
 {{ Form::hidden('status',$data['status'], ['id'=>'status']) }}
 {{ Form::hidden('id',$data['redeemid'], ['id'=>'redeemid']) }}
 <div class="modal-body">
   <div class="box-body table-responsive no-padding">
     @if($data['status']=='completed')
     <div class="col-sm-12 ermsg">
       {{ Form::text('transactionid',null, ['required','class'=>'form-control','id'=>'transactionid','placeholder'=>'Transaction Id','title'=>'Please enter transaction id','maxlength'=>50]) }}
     </div>
     <br><br>
     @endif
     <div class="col-sm-12 ermsg">
       {{ Form::textarea('comments',null, ['required','class'=>'form-control','id'=>'comments','placeholder'=>'Comments','title'=>'Please enter comments','maxlength'=>300]) }}
     </div>
   </div>
 </div>
 <div class="modal-footer">
   <button type="button" class="btn btn-default resetclose" data-dismiss="modal">Close</button>
   <button type="submit" class="btn btn-primary directSubmit" id="RedeemCreditProceed">Proceed</button>
 </div>
 {!! Form::close() !!}