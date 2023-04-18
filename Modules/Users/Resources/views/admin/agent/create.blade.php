@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.users.admin.create_new_agent')." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
    <h1><i class="fa fa-users"></i>
        {{trans('menu.role.agent')}} {{trans('menu.manager')}}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('agent.index')}}">{{trans('menu.role.agent')}}</a></li>
        <li class="active">{{trans('menu.sidebar.users.admin.create_new_agent')}}</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans('users::menu.sidebar.add_new_agent')}} </h3>
        </div>
        {!! Form::open(['route' => 'agent.store','class'=>'','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
            @include('users::admin.agent.form')
        </div>
        <div class="box-footer">
            <div class="row pull-right">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">{{trans('menu.sidebar.create')}}</button>
                    <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
</section>
@endsection