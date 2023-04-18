<div class="panel-body">
  <div class="form-group">
     <label class="col-sm-2 control-label">Name<span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
         {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'name','placeholder'=>trans('menu.placeholder.name'),'title'=>trans('menu.validiation.please_enter_name')]) }}
     </div>
  </div>  
  <div class="form-group">
     <label class="col-sm-2 control-label">Status<span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
      {{ Form::select('status',['0'=>'InActive','1'=>'Active','2'=>'Coming Soon'], null,  ['class' => 'form-control']) }}
     </div>
  </div>  
  <div class="form-group">
    <label class="col-sm-2 control-label">Image<span class="asterisk">*</span></label>
    <div class="col-sm-8 ermsg">
      {{ Form::hidden('image',null, ['id'=>'f_mediaId']) }}
      <input type="file" name="files" id="mediaId" accept="image/*"  onchange="after_logo_select(this.id)" style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('city.mediaStore')}}">
      <div class="input-group logo-duplicate_valid_msg">
        <input type="text" value="" readonly="" id="logo-duplicate" aria-describedby="basic-addon2" class="form-control" name="logo-duplicate">
        <span id="toggle_popover_mediaId" rel="popover" class="input-group-addon btn  disabled_advanced "><i class="fa fa-eye"></i></span>
        <span onclick="document.getElementById('mediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
      </div>
      <div class="description"><small>Max 5Mb image size allowed. Allowed types : jpeg, jpg.</small></div>
    </div>
    <div id="logo_popover_mediaId" style="display:none">
      <div id="logo_popover_content">
        <p id="logo_popover_placeholder">No media has been selected yet</p>
      </div>
    </div>
  </div>
@section('uniquePageScript')
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script type="text/javascript">
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
       title: 'Image',
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