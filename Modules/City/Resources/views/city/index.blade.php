@extends('admin.layouts.master')
@section('title', " City Manager ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
  <h1><i class="fa fa-map-marker" aria-hidden="true"></i>
    City Manager
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{route('backend.dashboard')}}">Dashboard</a></li>
    <li><a href="{{route('state.index')}}">State Manager</a></li>
    <li class="active">City Manager</li>
  </ol>
</section>
<section class="content">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">All Cities</h3>
      <div class="box-tools pull-right">
      </div>
      <br>
      @include('city::includes.search_filter')
      <a href="{{route('city.create',[$state->id])}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> Add City</a>
    </div>
    <div class="box-body" id="result" style="display: block;">
      @include('city::includes.ajax_city_list')
    </div>
  </div>
</section>
@endsection