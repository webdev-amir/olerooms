@extends('admin.layouts.master')
@section('title', " Update Company ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1><i class="fa fa-users"></i>
        {{trans('menu.role.company')}} {{trans('menu.manager')}}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('company.index')}}">{{trans('menu.role.company')}}</a></li>
        <li class="active">{{trans('menu.sidebar.users.admin.update_company')}}</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans('menu.sidebar.users.admin.update_company')}}</h3>
        </div>
         {!! Form::model($user,['method'=>'PATCH', 'route' => ['company.update',$user->id],'class'=>'','id'=>'validateForm']) !!}
        {{ Form::hidden('id',null, []) }}
        <div class="box-body">
            @include('users::admin.company.form')
        </div>
        <div class="box-footer">
            <div class="row pull-right">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">{{trans('menu.sidebar.update')}}</button>
                    <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
</section>
@endsection