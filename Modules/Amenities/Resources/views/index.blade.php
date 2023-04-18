@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.main')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
  <h1><i class="{{trans('amenities::menu.font_icon')}}"></i>
    {{trans('amenities::menu.sidebar.main')}}
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
    <li class="active">{{trans('amenities::menu.sidebar.main')}}</li>
  </ol>
</section>
<section class="content">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">{{trans('amenities::menu.sidebar.manage')}}</h3>
      <div class="box-tools pull-right">
        @if(Auth::user()->hasRole(['admin']) || Auth::user()->hasAnyPermission(['amenities.create']))
        <a href="{{route('amenities.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i>&nbsp;&nbsp;{{trans('amenities::menu.sidebar.add_new')}}</a>
        @endif
      </div>
    </div>
    <div class="box-body" style="display: block;">
      <table class="table table-bordered table-hover" id="data_filter">
        <thead>
          <tr>
            <th>S.No.</th>
            <th>Image</th>
            <th>Name</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</section>
@endsection
@section('uniquePageScript')
<script>
  jQuery(document).ready(function() {
    jQuery('#data_filter').dataTable({
      sPaginationType: "full_numbers",
      processing: true,
      serverSide: true,
      ajax: "{!! route('amenities.index') !!}",
      columns: [{
          data: 'rownum',
          name: 'rownum',
          orderable: false,
          searchable: false
        },
        {
          data: 'image',
          name: 'image',
          orderable: false,
          searchable: false
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'status',
          name: 'status'
        },
        {
          data: 'created_at',
          name: 'created_at'
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        },
      ],
      "drawCallback": function(settings) {
        $(".group1").colorbox({
          rel: 'group1'
        });
      }
    });

  });
</script>
@endsection