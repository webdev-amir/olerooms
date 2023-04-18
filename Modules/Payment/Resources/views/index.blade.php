@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.main')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
    <section class="content-header">
      <h1><em class="{{trans($model.'::menu.font_icon')}} "></em>
        {{trans($model.'::menu.sidebar.menu_title')}} @lang('menu.manager')
        <small></small>
      </h1>
      <ol class="breadcrumb">
         <li><em class="fa fa-dashboard"></em> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li class="active">@lang($model.'::menu.sidebar.menu_title') @lang('menu.manager')</li>
        <li class="active">@lang($model.'::menu.sidebar.slug')</li>
      </ol>
    </section>
    <?php /*
    @can('payment.show_payment_statistic')
      <section class="content" style="min-height: 100px;">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Payment Statistic</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
                <section class="content" style="min-height: 100px;">
                  <div class="row">
                    <div class="col-lg-3 col-xs-6">
                      <div class="small-box bg-aqua">
                        <div class="inner">
                          <div class="icon dash-count-icon">
                            <img src="{{asset('img/svg/money-bag01.svg')}}" alt="img">
                          </div>
                          <h3>{{$statictsData['totalAmount']}}<sup style="font-size: 20px"></sup></h3>
                          <p>Total Amount</p>
                        </div>
                      </div>
                    </div>
                </section>
            </div>
          </div>
      </section>
    @endcan
    */ ?>
     <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{trans($model.'::menu.sidebar.payment_record')}} </h3>
                <div class="box-tools pull-right">
                </div>
                <br><br>
               @include('payment::search_filter')
            </div>
            <div class="box-body" id="result" style="display: block;">
               @include('payment::ajax_payment_list')
            </div>
        </div>
    </section>  
@endsection
@section('uniquePageScript')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{!! Module::asset('payment:js/payment.js') !!}"></script>
@endsection
 