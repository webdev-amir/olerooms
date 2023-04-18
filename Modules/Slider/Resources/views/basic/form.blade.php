 <div class="panel-body">
     <div class="form-group">
         <label class="col-sm-2 control-label">Url <span class="asterisk"></span></label>
         <div class="col-sm-10 ermsg">
             {{ Form::url('url',null, ['class'=>'form-control','id'=>'url','placeholder'=>'Url','title'=>'Please enter Url','maxlength'=>100]) }}
         </div>
     </div>
     <div class="form-group">
         <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.banner_image')}} <span class="asterisk">*</span></label>
         <div class="col-sm-10 ermsg">
             {{ Form::hidden('banner_image',null, ['required','id'=>'f_mediaId']) }}
             <input type="file" name="files" id="mediaId" accept="image/*" @if(isset($data)) value="{{$data->picture_path}}" @endif onchange="after_logo_select(this.id)" style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('slider.mediaStore')}}">
             <div class="input-group logo-duplicate_valid_msg">
                 <input type="text" value="@if(isset($data->banner_image)) {{$data->banner_image}} @endif" readonly="" id="logo-duplicate" aria-describedby="basic-addon2" class="form-control" name="logo-duplicate">
                 <span id="toggle_popover_mediaId" rel="popover" class="input-group-addon btn @if(!(isset($data) && $data->picture_path))  disabled_advanced @endif"><i class="fa fa-eye"></i></span>
                 <span onclick="document.getElementById('mediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
             </div>
             <div class="description">
                 <small>Max 5Mb image size allowed. Allowed types : jpeg, jpg.</small>
                 <br>
                 <small>Upload 545x190 px image for better resolution.</small>

             </div>
         </div>
         <!-- Html use for Gift Images logo -->
         <div id="logo_popover_mediaId" style="display:none">
             <div id="logo_popover_content">
                 @if(isset($data->banner_image))
                 <img src="{{$data->picture_path}}" class="img-thumbnail tool-img" alt="" width="304" height="236" id="logo_popover_img_mediaId">
                 @else
                 <p id="logo_popover_placeholder">No media has been selected yet</p>
                 @endif
             </div>
         </div>
     </div>
 </div>
 @section('uniquePageScript')
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
             title: 'Slider Image',
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