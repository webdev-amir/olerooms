@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.main')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1><i class="{{trans('$model::menu.font_icon')}}"></i>
        {{trans($model.'::menu.sidebar.main')}}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">{{trans($model.'::menu.sidebar.main')}}</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans($model.'::menu.sidebar.manage')}}</h3>
            <div class="box-tools pull-right">
                @if(Auth::user()->hasRole(['admin']) || Auth::user()->hasAnyPermission([$model.'.create']))
                <a href="{{route($model.'.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i>&nbsp;&nbsp;{{trans($model.'::menu.sidebar.add_new')}}</a>
                @endif
            </div>
        </div>
        <div class="box-body" style="display: block;">
            <table class="table table-bordered table-hover" id="data_filter">
                <thead>
                    <tr>
                        <th>{{trans($model.'::menu.sidebar.form.s_no')}}</th>
                        <th>{{trans($model.'::menu.sidebar.form.title')}}</th>
                        <th>Coupon Code</th>
                        <th>{{trans($model.'::menu.sidebar.form.offer_type')}}</th>
                        <th>Property Type</th>
                        <th>Image</th>
                        <th>{{trans($model.'::menu.sidebar.form.duration')}}</th>
                        <th>Apply Globally</th>
                        <th>{{trans($model.'::menu.sidebar.form.coupon_status')}}</th>
                        <th>{{trans($model.'::menu.sidebar.form.status')}}</th>
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
            ajax: "{!! route($model.'.index') !!}",
            columns: [{
                    data: 'rownum',
                    name: 'rownum',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'coupon_code',
                    name: 'coupon_code',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'offer_type',
                    name: 'offer_type',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'property_type_id',
                    name: 'property_type_id',
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
                    data: 'duration',
                    name: 'duration'
                },
                {
                    data: 'is_global_apply',
                    name: 'is_global_apply'
                },
                {
                    data: 'coupon_status',
                    name: 'coupon_status'
                },
                {
                    data: 'status',
                    name: 'status'
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