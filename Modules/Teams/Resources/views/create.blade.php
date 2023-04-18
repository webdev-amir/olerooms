@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.add_new')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
  <h1><i class="{{trans('teams::menu.font_icon')}}"></i>
    {{trans($model.'::menu.sidebar.main')}}
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
    <li><a href="{{route('teams.index')}}">All Teams</a></li>
    <li class="active">Add Teams</li>
  </ol>
</section>
<section class="content">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Add Teams</h3>
    </div>
    {!! Form::open(['route' => 'teams.store','class'=>'form-horizontal','id'=>'validateForm']) !!}
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          @include('teams::basic.form')
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