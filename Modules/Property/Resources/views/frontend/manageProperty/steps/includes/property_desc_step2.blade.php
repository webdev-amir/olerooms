<div class="col-sm-12 col-md-6 col-lg-12">
    <div class="form-group ermsg">
        <label>Property Description</label>
        {{ Form::textarea('property_description',null, ['required','class'=>'form-control height-100','id'=>'ckeditor','data-msg-required'=>'Please enter property description','placeholder'=>"Property Description",'autocomplete'=>'off']) }}
    </div>
</div>
