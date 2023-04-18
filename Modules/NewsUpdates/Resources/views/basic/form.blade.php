 <div class="panel-body">
   <div class="form-group">
     <label class="col-sm-2 control-label">Post type<span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {!!Form::select('post_type',['News'=>'News','Article'=> 'Article'] , null, ['placeholder'=>'Select post type', 'title'=>'Please select post type', 'class' => 'form-control', 'required' ])!!}
     </div>
   </div>
   <div class="form-group">
     <label class="col-sm-2 control-label">Title <span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {{ Form::text('title',null, ['required','class'=>'form-control','id'=>'title','placeholder'=>'Title','title'=>'Please enter title','maxlength'=>150]) }}
     </div>
   </div>

   <div class="form-group">
     <label class="col-sm-2 control-label">Author </label>
     <div class="col-sm-8 ermsg">
       {{ Form::text('author',null, ['class'=>'form-control','id'=>'author','placeholder'=>'Author','title'=>'Please enter author','maxlength'=>50]) }}
     </div>
   </div>

   <div class="form-group">
     <label class="col-sm-2 control-label">Description </label>
     <div class="col-sm-8 ermsg">
       {{ Form::textarea('description',null, ['class'=>'form-control','id'=>'description','placeholder'=>'Description','title'=>'Please enter description']) }}
     </div>
   </div>


   <div class="form-group">
     <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.image')}} <span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {{ Form::hidden('image',null, ['required','id'=>'f_mediaId']) }}
       <input type="file" name="files" id="mediaId" accept="image/*" @if(isset($data)) value="{{$data->picture_path}}" @endif onchange="after_logo_select(this.id)" style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('newsupdates.mediaStore')}}">
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

   {{--
    <div class="form-group">
      <label class="col-sm-2 control-label">Publish Date</label>
      <div class="col-sm-8 ermsg">
        {{ Form::date('published_at',null, ['class'=>'form-control','id'=>'published_at','placeholder'=>'Publish Date','title'=>'Please enter published date']) }}
      </div>
    </div>
    --}}

 </div>
@section('uniquePageScript')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>
<script type="text/javascript">
  jQuery(document).ready(function () {
    $('#description').summernote({
        height:300,
    });
  });

  $('form').on('reset', function(e) {
    $('#description').summernote('code','<br>');
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
      title: 'News & Updates Image',
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