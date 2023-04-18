@extends('admin.layouts.master')
@section('title', " ".trans('settings::menu.sidebar.edit')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))

@section('content')
   <section class="content-header">
      <h1><i class="{{trans('settings::menu.font_icon')}}"></i>
        {{trans('settings::menu.sidebar.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
         <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route('settings.index')}}">@lang('settings::menu.sidebar.main') @lang('menu.manager')</a></li>
         <li class="active">{{trans('settings::menu.sidebar.edit')}}</li>
      </ol>
   </section>
   <section class="content">
      <div class="box box-success">
         <div class="box-header with-border">
            <h3 class="box-title">{{trans($model.'::menu.sidebar.edit')}}</h3>
         </div>
        {!! Form::model($data, ['method' => 'PATCH','route' => [$model.'.update', $data->id],'class'=>'form-horizontal validate','id'=>'validateForm','files'=>true]) !!}
          {{ Form::hidden('id',null, []) }}
         <div class="box-body">
            <div class="row">
               <div class="col-md-12">
                  @include($model.'::basic.form')
               </div>
            </div>
         </div>
         <div class="box-footer">
            <div class="row pull-right">
               <div class="col-sm-12">
                 <button class="btn btn-primary" type="submit">{{trans('menu.sidebar.update')}}</button>
                 <a href="{{route($model.'.index')}}" class="btn btn-default">{{trans('menu.sidebar.cancel')}}</a>
               </div>
            </div>
         </div>
      {!! Form::close() !!}
    </section>
@endsection