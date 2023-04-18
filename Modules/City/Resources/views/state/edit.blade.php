@extends('admin.layouts.master')
@section('title', " Edit State ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
   <section class="content-header">
      <h1><i class="fa fa-map-marker" aria-hidden="true"></i>
         Edit State
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route('state.index')}}">State Manager</a></li>
         <li class="active">Edit State</li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Edit State</h3>
        </div>
        {!! Form::model($data, ['method' => 'PATCH','route' => ['state.update', $data->id],'class'=>'form-horizontal validate','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
         <div class="row">
              <div class="col-md-12">
              {{ Form::hidden('id',null, []) }}
                  @include('city::state.includes.form')
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
