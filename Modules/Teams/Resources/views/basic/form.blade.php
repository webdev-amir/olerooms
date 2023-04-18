 <div class="panel-body">
   <div class="form-group">
     <label class="col-sm-2 control-label">Team type<span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {!!Form::select('team_type',['Executive'=>'Executive','Core'=> 'Core'] , null, ['placeholder'=>'Select team type', 'title'=>'Please select team type', 'class' => 'form-control', 'required' ])!!}
     </div>
   </div>
   <div class="form-group">
     <label class="col-sm-2 control-label">Name <span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'title','placeholder'=>'Name','title'=>'Please enter name','maxlength'=>50]) }}
     </div>
   </div>
   <div class="form-group">
     <label class="col-sm-2 control-label">Designation <span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {{ Form::text('designation',null, ['required','class'=>'form-control','id'=>'designation','placeholder'=>'Designation','title'=>'Please enter designation']) }}
     </div>
   </div>
   <div class="form-group">
     <label class="col-sm-2 control-label">Linkdin Profile <span class="asterisk"></span></label>
     <div class="col-sm-8 ermsg">
       {{ Form::text('linkedin_url',null, ['class'=>'form-control','id'=>'linkedin_url','placeholder'=>'Linkedin Profile Url','title'=>'Please enter linkedin profile Url']) }}
     </div>
   </div>
   <div class="form-group">
     <label class="col-sm-2 control-label">Order Number <span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {{ Form::text('order_number',null, ['required','class'=>'form-control isinteger','id'=>'order_number','placeholder'=>'Order Number','title'=>'Please enter order number','maxlength'=>2]) }}
     </div>
   </div>
   <div class="form-group">
     <label class="col-sm-2 control-label">Description</label>
     <div class="col-sm-8 ermsg">
       {{ Form::textarea('description',null, ['class'=>'form-control','id'=>'ckeditor','placeholder'=>'Description','title'=>'Please enter description']) }}
     </div>
   </div>
   <div class="form-group">
     <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.image')}} <span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {{ Form::hidden('image',null, ['required','id'=>'f_mediaId']) }}
       <input type="file" name="files" id="mediaId" accept="image/*" @if(isset($data)) value="{{$data->picture_path}}" @endif onchange="after_logo_select(this.id)" style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('teams.mediaStore')}}">
       <div class="input-group logo-duplicate_valid_msg">
         <input type="text" value="@if(isset($data->image)) {{$data->image}} @endif" readonly="" id="logo-duplicate" aria-describedby="basic-addon2" class="form-control" name="logo-duplicate">
         <span id="toggle_popover_mediaId" rel="popover" class="input-group-addon btn @if(!(isset($data) && $data->picture_path))  disabled_advanced @endif"><i class="fa fa-eye"></i></span>
         <span onclick="document.getElementById('mediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
       </div>
       <div class="description"><small>Max 5Mb image size allowed. Allowed types : jpeg, jpg.</small></div>
     </div>
     <div id="logo_popover_mediaId" style="display:none">
       <div id="logo_popover_content">
         @if(isset($data->image))
         <img src="{{$data->picture_path}}" class="img-thumbnail tool-img" alt="" width="304" height="236" id="logo_popover_img_mediaId">
         @else
         <p id="logo_popover_placeholder">No media has been selected yet</p>
         @endif
       </div>
     </div>
   </div>
 </div>
 @section('uniquePageScript')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
 <script src="{{ asset('js/rating.js') }}" defer></script>
 <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
 <script type="text/javascript">
   jQuery(document).ready(function() {
     CKEDITOR.replace('ckeditor');
   });
   $('form').on('reset', function(e) {
     CKEDITOR.instances.ckeditor.setData(" ");
   });
   //Function to insert filename in fake upload box
   function after_logo_select(id) {
     var uploadedFile = jQuery('#' + id)[0].files[0];
     jQuery('#logo-duplicate').val(uploadedFile.name);
     jQuery('#logo_popover_' + id + ' #logo_popover_content').html('<img class="img-thumbnail tool-img" alt="" width="304" height="236" id="logo_popover_img_' + id + '" >');
     document.getElementById('logo_popover_img_' + id).src = URL.createObjectURL(uploadedFile);
     jQuery('#logo_popover_' + id).removeClass('disabled disabled_advanced');
   };

   jQuery(document).ready(function() {
     jQuery('#toggle_popover_mediaId').popover({
       html: true,
       title: 'Amenity Image',
       container: 'body',
       placement: 'top',
       trigger: 'click',
       content: function() {
         return $('#logo_popover_mediaId').html();
       }
     }).click(function() {
       jQuery(this).children('i').toggleClass('fa-eye fa-eye-slash');
     });
   });
 </script>
 @endsection