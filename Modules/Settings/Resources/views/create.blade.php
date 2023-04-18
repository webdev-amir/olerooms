@extends('admin.layouts.master')
@section('title', " ".trans('settings::menu.sidebar.create')." ".trans('menu.pipe')." " .app_name(). " :: Admin")
@section('content')
   <section class="content-header">
      <h1><i class="{{trans('settings::menu.font_icon')}}"></i>
        {{trans('settings::menu.sidebar.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route('settings.index')}}">@lang('settings::menu.sidebar.main') @lang('menu.manager')</a></li>
         <li class="active">{{trans('settings::menu.sidebar.create')}}</li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('settings::menu.sidebar.create')}}</h3>
        </div>
          {!! Form::open(['route' => 'settings.store','class'=>'form-horizontal','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                 @include('settings::basic.form')
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
