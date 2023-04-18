@extends('admin.layouts.master')
@section('title', " Edit Area ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
   <section class="content-header">
      <h1><i class="{{trans('settings::menu.font_icon')}}"></i>
         Area Manager
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('state.index')}}">State Manager</a></li>  
        <li><a href="{{route('state.city',$state->id)}}">City Manager</a></li>  
        <li> <a href="{{url('admin/state/'.$state->id.'/'.'city/'.$city->id.'/areas')}}">Area Manager</a></li>
        <li class="active">{{$data->name}}</li>


      </ol>
   </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Edit Area</h3>
        </div>
        {!! Form::model($data, ['method' => 'PATCH','route' => ['area.update',$state->id,$id, $data->id],'class'=>'form-horizontal validate','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
         <div class="row">
              <div class="col-md-12">
               {{ Form::hidden('id',null, []) }}
               <div class="panel-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Name<span class="asterisk">*</span></label>
                    <div class="col-sm-8 ermsg">
                      {{ Form::text('name',$data->name, ['required','class'=>'form-control','id'=>'name','placeholder'=>trans('menu.placeholder.name'),'title'=>trans('menu.validiation.please_enter_name'),'maxlength'=>50]) }}
                      <input type="hidden" name="city_id" value="{{$id}}" >
                    </div>
                  </div> 
                </div>
              </div>
           </div>
      </div>
      <div class="box-footer">
         <div class="row pull-right">
            <div class="col-sm-12">
               <button class="btn btn-primary" type="submit">{{trans('menu.sidebar.update')}}</button>
               <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
            </div>
         </div>
      </div>
      {!! Form::close() !!}
    </section>
@endsection
