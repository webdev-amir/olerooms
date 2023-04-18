@extends('admin.layouts.master')
@section('title', " State Manager ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
  <h1><i class="fa fa-map-marker" aria-hidden="true"></i>
    State Manager
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{route('backend.dashboard')}}">Dashboard</a></li>
    <li class="active">State Manager</li>
  </ol>
</section>
<section class="content">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">All States</h3>
        <div class="box-tools pull-right">
        </div>
        <br>
        @include('city::state.search_filter')
      </div>
      <div class="box-body" id="result" style="display: block;">
          @include('city::state.ajax_state_list')
      </div>
    </div>
</section>
@endsection