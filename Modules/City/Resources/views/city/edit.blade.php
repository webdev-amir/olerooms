@extends('admin.layouts.master')
@section('title', " Edit City ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
   <section class="content-header">
      <h1><i class="fa fa-map-marker" aria-hidden="true"></i>
         City Manager 
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('state.index')}}">State Manager</a></li>
        <li>City Manager</li>
        <li class="active"><a href="{{route('state.city',$state->id)}}">{{$data->name}}</a></li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Edit City</h3>
        </div>
        {!! Form::model($data, ['method' => 'PATCH','route' => ['state.city.update',$state->id, $data->id],'class'=>'form-horizontal validate','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
         <div class="row">
            <div class="col-md-12">
            {{ Form::hidden('id',null, []) }}
               @include('city::basic.form')
            </div>
         </div>
      </div>
      <div class="box-footer">
         <div class="row pull-right">
            <div class="col-sm-12">
               <button class="btn btn-primary" type="submit">{{trans('menu.sidebar.update')}}</button>
               <button type="reset" class="btn btn-default">{{trans('menu.sidebar.cancel')}}</button>
            </div>
         </div>
      </div>
      {!! Form::close() !!}
    </section>
@endsection
