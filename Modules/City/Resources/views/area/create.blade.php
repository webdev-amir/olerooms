@extends('admin.layouts.master')
@section('title', " Area Manager ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
   <section class="content-header">
      <h1><i class="fa fa-flag"></i>
        Area Manager
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">Dashboard</a></li>
        <li><a href="{{route('state.index')}}">State Manager</a></li>
        <li><a href="{{route('state.city',$state->id)}}">City Manager</a></li>
        <li><a href="{{route('state.city.areas',[$state->id,$city->id])}}">Area Manager</a></li>
        <li class="active">Add Area</li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Area</h3>
        </div>
          {!! Form::open(['route' => ['state.city.areas.store',[$state->id, $city->id]], 'class'=>'form-horizontal','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Name<span class="asterisk">*</span></label>
                  <div class="col-sm-8 ermsg">
                    {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'name','placeholder'=>trans('menu.placeholder.name'),'title'=>trans('menu.validiation.please_enter_name'),'maxlength'=>50]) }}
                    <input type="hidden" name="city_id" value="{{$city->id}}" >
                  </div>
                </div> 
              </div>
            </div>
          </div>
      </div>
      <div class="box-footer">
         <div class="row pull-right">
            <div class="col-sm-12">
               <button class="btn btn-primary" type="submit">{{trans('menu.sidebar.create')}}</button>
               <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
            </div>
         </div>
      </div>
      {!! Form::close() !!}
    </section>
@endsection
@section('uniquePageScript')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection