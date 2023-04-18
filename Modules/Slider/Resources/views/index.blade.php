@extends('admin.layouts.master')
@section('title', " Banner Management ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<link rel="stylesheet" href="{{ asset('public/css/lightbox.min.css') }}">
<script src="{{ asset('public/js/lightbox-plus-jquery.min.js') }}"></script>
    <section class="content-header">
      <h1><i class="{{trans('slider::menu.font_icon')}}"></i>
        {{trans('Banner Management')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li class="active">{{trans('slider::menu.sidebar.main')}}</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('slider::menu.sidebar.manage')}}</h3>
          <div class="box-tools pull-right">
            @if(Auth::user()->hasRole(['admin']) || Auth::user()->hasAnyPermission(['slider.create']))
             <!--  <a href="{{route('slider.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i>&nbsp;&nbsp;{{trans('slider::menu.sidebar.add_new')}}</a> -->
            @endif
          </div>
        </div>
        <div class="box-body" style="display: block;">
            <table class="table table-bordered table-hover" id="data_filter">
              <thead>
                <tr>
                    <th>{{trans($model.'::menu.sidebar.form.s_no')}}</th>
                    <th>{{trans($model.'::menu.sidebar.form.banner_image')}}</th>
                    <th>{{trans($model.'::menu.sidebar.form.url')}}</th>                    
                    <th>{{trans($model.'::menu.sidebar.form.created')}}</th>
                    <th>{{trans($model.'::menu.sidebar.form.action')}}</th>
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
        ajax: "{!! route('slider.index') !!}",
        columns: [
            { data: 'rownum', name: 'rownum',orderable:false, searchable:false },
            { data: 'banner_image', name: 'banner_image',orderable:false, searchable:false },            
            { data: 'url', name: 'url' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable:false, searchable:false  },
        ],
        "drawCallback": function( settings ) {
            $(".group1").colorbox({rel:'group1'});
        }
    });
  // Chosen Select
    jQuery("select").chosen({
      'width': '70px',
      'white-space': 'nowrap',
      disable_search_threshold: 10
    }); 
  });
</script>
@endsection