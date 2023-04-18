 <div class="panel-body">
  <div class="form-group">
        <label class="col-sm-2 control-label">Apply Global  <span class="asterisk">(If checked, then vendor can't apply this code, and code will be used globally on OlE Rooms)</span></label>
        <div class="col-sm-1" style="padding-top: 5px;width: 3%;">
            <span class="err_banner">
                {{ Form::checkbox('is_global_apply',1, NULL,['class'=>'minimal','id'=>'is_global_apply']) }}
            </span>
        </div>
    </div>
   <div class="form-group">
     <label class="col-sm-2 control-label">Offer Type<span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {!!Form::select('offer_type',['Flatrate'=>'Flatrate','Percentage'=> 'Percentage'] , null, ['placeholder'=>'Select offer type', 'title'=>'Please select offer type', 'class' => 'form-control offer_type', 'required' ])!!}
     </div>
   </div>
   @if(\Route::currentRouteName()=='coupon.edit')
   <div class="form-group">
     <label class="col-sm-2 control-label">Property Type<span class="asterisk"></span></label>
     <div class="col-sm-8 ermsg">
       {!!Form::select('property_type_id',$propertyType,null, ['placeholder'=>'Select Property type', 'title'=>'Please select property  type', 'class' => 'form-control property_type_id','disabled','readonly' ])!!}
     </div>
   </div>
   @else
    <div class="form-group">
     <label class="col-sm-2 control-label">Property Type<span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {!!Form::select('property_type_id',$propertyType,null, ['placeholder'=>'Select Property type', 'title'=>'Please select property  type', 'class' => 'form-control property_type_id', 'required' ])!!}
     </div>
   </div>
   @endif
   <div class="form-group">
     <label class="col-sm-2 control-label">Title <span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {{ Form::text('title',null, ['required','class'=>'form-control','id'=>'title','placeholder'=>'Title','title'=>'Please enter title','maxlength'=>50]) }}
     </div>
   </div>
   <div class="form-group">
     <label class="col-sm-2 control-label">Coupon Code <span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {{ Form::text('coupon_code',null, ['required','class'=>'form-control','id'=>'coupon_code','placeholder'=>'Coupon Code','title'=>'Please enter Coupon Code','maxlength'=>10]) }}
     </div>
   </div>
   <div class="form-group">
     <label class="col-sm-2 control-label">Discount <span id="symbol"></span><span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
       {{ Form::text('amount',null, ['required','class'=>'form-control isinteger','id'=>'amount','placeholder'=>'Discount','title'=>'Please enter discount','min'=>0,'step'=>'any', 'maxlength'=>10]) }}
     </div>
   </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Start Date <span class="asterisk">*</span></label>
      <div class="col-sm-8 ermsg">
        <div class='input-group date' id='datetimepicker6'>
          {{ Form::text('start_date',null, ['required','class'=>'form-control start_date','id'=>'start_date','placeholder'=>'Start Date','title'=>'Please select start date']) }}
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">End Date <span class="asterisk">*</span></label>
      <div class="col-sm-8 ermsg">
        <div class='input-group date' id='datetimepicker7'>
          {{ Form::text('end_date',null, ['required','class'=>'form-control end_date','id'=>'end_date','placeholder'=>'End Date','title'=>'Please select end date']) }}
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Description<span class="asterisk">*</span> </label>
      <div class="col-sm-8 ermsg">
        {{ Form::textarea('description',null, ['required','class'=>'form-control','id'=>'description','placeholder'=>'Description','title'=>'Please enter description']) }}
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Image <span class="asterisk">*</span></label>
      <div class="col-sm-8 ermsg">
        {{ Form::hidden('image',null, ['required','id'=>'f_mediaId']) }}
        <input type="file" name="files" id="mediaId" accept="image/*" @if(isset($data)) value="{{$data->picture_path}}" @endif onchange="after_logo_select(this.id)" style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('coupon.mediaStore')}}">
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    // Date Picker
    $('#start_date').datepicker({
       dateFormat: 'yy-mm-dd',
       onSelect: function (date) {
        var date2 = $('#start_date').datepicker('getDate');

        date2.setDate(date2.getDate() + 1);
        $('#end_date').datepicker('setDate', date2);
        //sets minDate to dt1 date + 1
        $('#end_date').datepicker('option', 'minDate', date2);
      }
    });
    $('#end_date').datepicker({
      dateFormat: 'yy-mm-dd',
      onClose: function () {
        var dt1 = $('#start_date').datepicker('getDate');
        var dt2 = $('#end_date').datepicker('getDate');
        if (dt2 <= dt1) {
          var minDate = $('#end_date').datepicker('option', 'minDate');
          $('#end_date').datepicker('setDate', minDate);
        }
      }   
    });
});

$(document).on('change', '.offer_type', function (e){
    var type = $(this).val();
    if(type=='Percentage'){
      e.preventDefault(); 
      $('#amount').attr('max',99);
      $('#amount').attr('title','Please enter amount less than 100, in case of Percentage');
      $('#symbol').html('%');
    }else{
      $('#amount').removeAttr('max');
      $('#amount').attr('title','Please enter amount');
      $('#symbol').html('₹');
    }
});

$('form').on('reset', function(e) {
    if (document.getElementById("end_date")) {
      $('#end_date').datepicker( "option", "minDate", null )
    }
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
      title: 'Coupon Image',
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

     $(function () {
        $('#is_global_apply').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        }).on('ifChanged', function(e) {
            //
        });
    });
</script>
@endsection