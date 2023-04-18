@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.redeem_credit_requests')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
    <section class="content-header">
      <h1><i class="{{trans($model.'::menu.font_icon')}} "></i>
        {{trans($model.'::menu.sidebar.redeem_credit_requests')}} @lang('menu.manager')
        <small></small>
      </h1>
      <ol class="breadcrumb">
         <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li class="active">@lang($model.'::menu.sidebar.redeem_credit_requests') @lang('menu.manager')</li>
        <li class="active">@lang($model.'::menu.sidebar.redeem_credit_requests')</li>
      </ol>
    </section>
    <section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans($model.'::menu.sidebar.redeem_rrequest')}} </h3>
            <div class="box-tools pull-right">
            </div>
            <br><br>
           @include('wallet::redeemcredit.search_filter')
        </div>
        <div class="box-body" id="result" style="display: block;">
           @include('wallet::redeemcredit.ajax_redeemcredit_list')
        </div>
    </div>
    </section>
@endsection
@section('uniquePageScript')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{!! Module::asset('wallet:js/reedomcredit.js') !!}"></script>
@endsection

