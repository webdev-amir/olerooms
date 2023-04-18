{!! Form::open(['route' => 'vendor.registered','class'=>'form bravo-form-register form-loader','id'=>'F_vregister','autocomplete'=> 'off']) !!}
 <h4 class="text-center">Get Started</h4>
 <div class="form-group ermsg">
   {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'name','placeholder'=>'Property Owner’s Name','title'=>'Please enter property owner name','maxlength'=>'50']) }}
 </div>
 <div class="form-group ermsg">
   {{ Form::email('email',null, ['required','class'=>'form-control','id'=>'email','placeholder'=>'Email Address','title'=>'Please enter email address','maxlength'=>'150']) }}
 </div>
 <div class="form-group ermsg">
   {{ Form::text('phone',null, ['maxlength'=>'10','required','class'=>'form-control isinteger','id'=>'phone','placeholder'=>'Mobile Number','title'=>'Please enter phone number']) }}
 </div>
 <div class="form-group mt-4 ermsg">
    <div class="d-flex justify-content-between">
       <label for="term" class="mb0 remembertext">
         <input required type="checkbox" id="term" value="1" name="term" title="Please accept Terms & Conditions"> I have read & agreed <a href="{{route('pages.show','terms-and-conditions')}}" target='_blank' title="Terms & Conditions">Terms & Conditions.</a> 
       <span class="checkmark fcheckbox"></span>
       </label>
    </div>
 </div>
 <div class="error message-error invalid-feedback"></div>
 <div class="form-group text-right mb-0  mt-5">
    <button type="submit" id="vregister" class="btn customBtn gradientBtn minw-184 form-submit directSubmit" data-loader="Please wait registering...">
    Register
    <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span>
    </button>
 </div>
</form>