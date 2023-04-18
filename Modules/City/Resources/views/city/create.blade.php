@extends('admin.layouts.master')
@section('title', " City Manager ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
  <h1>
    <i class="fa fa-map-marker" aria-hidden="true">
    </i>
    <small>City Manager</small>
  </h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
    <li><a href="{{route('city.index')}}">City @lang('menu.manager')</a></li>
    <li class="active">Add City</li>
  </ol>
</section>
<section class="content">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Add City</h3>
    </div>
    {!! Form::open(['route' => ['city.store',$stateId],'class'=>'form-horizontal','id'=>'validateForm','files'=>true]) !!}
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          @include('city::basic.form-add-city')
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