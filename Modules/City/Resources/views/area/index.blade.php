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
        <li class="active">Area Manager</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">All Areas</h3>
          <div class="box-tools pull-right">
          </div>
          <br>
            @include('city::area.search_filter')
            <a href="{{route('state.city.areas.create',[$state->id,$city->id,])}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i>  Add New Area</a>
        </div>
        <div class="box-body " style="display: block;" id="result">
           @include('city::area.ajax_area_list')
        </div>
      </div>
    </section>
@endsection