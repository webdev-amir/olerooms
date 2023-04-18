<div class="modal fade resetpassword_success" id="deleteAcoount" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content relative modal_design">
         <div class="modal-body text-center p-0">
            <figure class="modalicon_wrap">
               <i class="ri-delete-bin-line"></i>
            </figure>
            <h4 class="font24 black medium mb-3">Delete Account</h4>
            <p class="font16 grey regular">
               Do you wish to Delete Your Account
            </p>
            <div class="d-flex justify-content-center mt-3">
               {!! Form::model(Auth::user(),['method'=>'post', 'route' => ['vendor.dashboard.deleteAccount'], 'class'=>'editProfile','id'=>'F_delete_account']) !!}
               <button type="submit" id="delete_account" class="outlineBtn_gradient minw-184 mr-3 form-submit directSubmit" data-loader="Please wait deleting your account..">Yes , Delete it</button>
               <a href="javascript:;" class="outlineBtn_green minw-101" data-dismiss="modal" aria-label="Close">No</a>
               {!! Form::close() !!}
            </div>
         </div>
      </div>
   </div>
</div>