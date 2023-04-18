<div
   class="modal fade resetpassword_success"
   id="deactivateAccount"
   tabindex="-1"
   role="dialog"
   aria-hidden="true"
   >
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content relative modal_design">
         <div class="modal-body text-center p-0">
            <figure class="modalicon_wrap">
               <img
                  src="{{URL::to('images/deactivateaccount.svg')}}"
                  alt="image  not found"
                  />
            </figure>
            <h4 class="font24 black medium mb-3">Deactivate Account</h4>
            <p class="font16 grey regular">
               Do you wish to Deactivate Your Account
            </p>
            <div class="d-flex justify-content-center mt-3">
               {!! Form::model(Auth::user(),['method'=>'post', 'route' => ['company.dashboard.deactivateAccount'], 'class'=>'editProfile','id'=>'F_deactivate_user']) !!}
                <button type="submit" id="deactivate_user" class="outlineBtn_gradient minw-184 mr-3 form-submit directSubmit"  data-loader="Please wait deactivating your account..">Yes , Deactivate it</button>
               <a href="javascript:;" class="outlineBtn_green minw-101" data-dismiss="modal" aria-label="Close">No</a>
               {!! Form::close() !!}
            </div>
         </div>
      </div>
   </div>
</div>