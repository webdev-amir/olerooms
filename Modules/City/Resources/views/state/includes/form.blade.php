<div class="panel-body">
  <div class="form-group">
     <label class="col-sm-2 control-label">Country Name<span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
         {{ Form::text('country_id',$data->country->name, ['required','class'=>'form-control','id'=>'name','placeholder'=>trans('menu.placeholder.name'),'title'=>trans('menu.validiation.please_enter_name'),'readonly']) }}
     </div>
  </div>  
  <div class="form-group">
     <label class="col-sm-2 control-label">State Name<span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
         {{ Form::text('name',$data->name, ['required','class'=>'form-control','id'=>'name','placeholder'=>trans('menu.placeholder.name'),'title'=>'please enter state name']) }}
     </div>
  </div>  
  <div class="form-group">
     <label class="col-sm-2 control-label">State Code<span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
         {{ Form::text('stateCode',$data->stateCode, ['required','class'=>'form-control','id'=>'name','placeholder'=>'please enter state code','title'=>'please enter state code']) }}
     </div>
  </div>  
  <div class="form-group">
     <label class="col-sm-2 control-label">Status<span class="asterisk">*</span></label>
     <div class="col-sm-8 ermsg">
      {{ Form::select('status',['0'=>'InActive','1'=>'Active'], $data->status,  ['class' => 'form-control', 'title'=>'please enter state status']) }}
     </div>
  </div>  
</div>
@section('uniquePageScript')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection