@extends('layouts.dashboard_app')
@section('title', " ".trans('menu.my_transactions')." ".trans('menu.pipe')." " .app_name())
@section('content')
<section class="dashboard">
   <div class="dasblock">
      @include('includes.dashboard.left_sidebar_menu')
      <div class="layoutSidenav_content">
         <div class="content_wrap">
            @include('includes.dashboard.topbar_header')
            <div class="welcomblock">
               <div class="row secondrow">
                  <div class="col-sm-12 col-md-5">
                      @include('includes.dashboard.my_investments_loans_data')
                  </div>
                  <div class="col-sm-12 col-md-7">
                     <ul class="nav justify-content-end dashboardtabs">
                        @include('includes.dashboard.my_wallet_top_right_menu')
                     </ul>
                  </div>
               </div>
               <div class="row mT70 mB50 titleblock_custom">
                  <div class="col-sm-12">
                     <div class="loanoverview-block">
                        <h1>@lang('menu.my_transactions')</h1>
                     </div>
                  </div>
               </div>
               <div class="tbl_blok mB40">
                  <div class="shdow_block">
                     <div class="welcomblock mB20 blancerow pl-4 pr-4">
                        <div class="row align-items-center">
                           <div class="col-sm-12 col-md-6 col-lg-6">
                              <form class="form-inline form justify-content-end" style="float: left;">
                                <div class="div mR20">
                                   <label class="col-form-label">
                                      <img src="{{asset('img/svg/filter.svg')}}" alt="image not found">
                                   </label>
                                </div>
                                <div class="input-group">
                                    <div id="export_reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                       <i class="fa fa-calendar"></i>&nbsp;
                                       <span>Request Statement </span>&nbsp;&nbsp; <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                                 {{ Form::hidden('rfrom',@$GET['from'], ['id'=>'export_start_date']) }}
                                 {{ Form::hidden('rto',@$GET['to'], ['id'=>'export_end_date']) }}
                              </form>
                           </div>
                           <div class="col-sm-12 col-md-6 col-lg-6">
                              <div class="content text-right">
                                 <p class="subtext fz18 mb-0">@lang('menu.available_balance')</p>
                                 <h4 class="subhead uppercase fz26 mB15">{{numberformatWithCurrency(auth()->user()->UserWalletAmount,2)}}</h4>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="commonshadow p-4">
                        <div class="welcomblock mB20 recentrow">
                           <div class="row">
                              <div class="col-6 col-md-6 d-flex align-items-center">
                                 <div class="dashboardsubtitle">
                                     <h4 class="subhead lowercase mb-0">Recent</h4>
                                 </div>
                              </div>
                              <div class="col-6 col-md-6 filterbl">
                                 <form class="form-inline form justify-content-end">
                                    <div class="div mR20">
                                       <label class="col-form-label">
                                          <img src="{{asset('img/svg/filter.svg')}}" alt="image not found">
                                       </label>
                                    </div>
                                    <div class="input-group">
                                          <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                     <i class="fa fa-calendar"></i>&nbsp;
                                     <span></span> &nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                                    </div>
                                     </div>
                                     {{ Form::hidden('from',@$GET['from'], ['id'=>'start_date']) }}
                                     {{ Form::hidden('to',@$GET['to'], ['id'=>'end_date']) }}
                                 </form>
                              </div>
                           </div>
                        </div>
                        <div class="common_table" id="result">
                          @include('wallet::dashboard.ajax_wallet_payment_summarry_list')
                       </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection
@section('uniquePageScript')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="{!! Module::asset('wallet:js/wallet_summary.js') !!}"></script>
@endsection


