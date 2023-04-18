@extends('admin.layouts.master')
@section('title', " ".trans('settings::menu.sidebar.slug')." - ".app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
    <section class="content-header">
      <h1><i class="fa fa-cog"></i>
        {{trans('settings::menu.sidebar.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li class="active">{{trans('settings::menu.sidebar.slug')}}</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('settings::menu.sidebar.slug')}}</h3>

          {{-- <div class="box-tools pull-right">
             <a href="{{route('settings.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> {{trans('settings::menu.sidebar.add_new')}}</a>
          </div> --}}
        </div>
        <div class="box-body " style="display: block;">
            <table class="table table-bordered table-hover"  id="data_filter">
              <thead>
              <tr>
                    <th>S.No.</th>
                    <th>Setting Name</th>
                    <th>Setting Value</th>
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
      var loaderString = '<div class="ajaxloader" id="AjaxLoader"><div class="strip-holder"><div class="strip-1"></div><div class="strip-2">';
      loaderString += '</div><div class="strip-3"></div></div></div>';
      jQuery('#data_filter').dataTable({
          sPaginationType: "full_numbers",
          processing: true,
          serverSide: false,
          ajax: "{!! route($model.'.index') !!}",
          language: {          
            //"processing": loaderString,
          },
          columns: [
              { data: 'rownum', name: 'rownum',orderable:false, searchable:false },
              { data: 'name', name: 'name' },
              { data: 'val', name: 'val' },
              { data: 'created_at', name: 'created_at' },
              { data: 'action', name: 'action', orderable:false, searchable:false },
          ],
          "drawCallback": function( settings ) {
              $(".group1").colorbox({rel:'group1'}); //this use for image preview if nay else
          }
      });
      //Chosen Select
      jQuery("select").chosen({
        'min-width': '100px',
        'white-space': 'nowrap',
        disable_search_threshold: 10
      }); 
    });
  </script>
@endsection
