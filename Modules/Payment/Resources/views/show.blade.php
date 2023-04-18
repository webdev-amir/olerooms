@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.details')." ".trans('menu.pipe')." " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
   <h1><i class="{{trans($model.'::menu.font_icon')}} "></i>
      Payment Details
      <small></small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
      <li><a href="{{route($model.'.index')}}">Payment Managment</a></li>
      <li class="active">Payment Detail</li>
   </ol>
</section>
<section class="content">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Date: {{$payment->created_at->format(Config::get('custom.default_date_formate'))}} </h3>
      <div class="box-tools pull-right">
      </div>
    </div>
    <div class="box-body">
      <div class="row">
         <div class="col-md-12">
            <div class="panel-body">
                <div class="row invoice-info">
                  <div class="col-sm-4 invoice-col">
                    From
                    <address>
                      <strong>@if($payment->user) {{ucfirst($payment->user->FullName)}} @else N/A @endif</strong><br>
                     <?php /*  @if($payment->user->state) {{$payment->user->StateName}},<br>@endif
                       @if($payment->user->zipcode) {{$payment->user->zipcode}},<br>@endif 
                      Phone: {{$payment->user->NotificationNumber}}<br>*/?>
                      Email: {{@$payment->user->email}}
                    </address>
                  </div>
                  <div class="col-sm-4 invoice-col">
                    To
                    <address>
                      {!! getConfig('address') !!}<br>
                      Phone: {!! getConfig('admincontact') !!}<br>
                      Email: {!! getConfig('adminemail') !!}
                    </address>
                  </div>
                  <div class="col-sm-4 invoice-col" style="float: left;">
                    <b>{{trans($model.'::menu.sidebar.invoice')}} #{{$payment->id}}</b><br>
                    <b>@lang($model.'::menu.sidebar.form.transection-id'):</b> {{$payment->transaction_id}}<br>
                    <b>@lang($model.'::menu.sidebar.form.transection_date'):</b> {{$payment->FullTranDate}}<br>
                    <b>{{trans($model.'::menu.sidebar.form.status')}}:</b> {{ucfirst($payment->status)}}
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                      <thead>
                      <tr>
                        <th>@lang($model.'::menu.sidebar.form.username')</th>
                        <th>@lang($model.'::menu.sidebar.form.transection-id')</th>
                        <th>@lang($model.'::menu.sidebar.form.ip_address')</th>
                         <th>{{trans($model.'::menu.sidebar.form.status')}}</th>
                        <th>{{trans($model.'::menu.sidebar.form.amount')}}</th>
                        <th>{{trans($model.'::menu.sidebar.form.tax')}}</th>
                        <th>{{trans($model.'::menu.sidebar.form.total')}}</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td>@if($payment->user) {{$payment->user->FullName}} @else N/A @endif</td>
                        <td>{{$payment->transaction_id}}</td>
                        <td>{{$payment->ip_address}}</td>
                        <td>{{ucfirst($payment->status)}}</td>
                        <td>{{numberformatWithCurrency($payment->amount,2)}}</td>
                        <td>{{numberformatWithCurrency($payment->tax,2)}}</td>
                        <td>{{numberformatWithCurrency($payment->amount,2)}}</td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-6">
                    <p class="lead">@lang($model.'::menu.sidebar.form.payment_method'):</p>
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                       {{$payment->method}} 
                    </p>
                  </div>
                  <div class="col-xs-6">
                    <p class="lead">@lang($model.'::menu.sidebar.form.payment_date'): {{$payment->created_at->format(Config::get('custom.default_date_formate'))}}</p>
                    <div class="table-responsive">
                      <table class="table">
                        <tr>
                          <th style="width:50%">{{trans($model.'::menu.sidebar.form.subtotal')}}:</th>
                          <td>{{numberformatWithCurrency($payment->amount,2)}}</td>
                        </tr>
                        <tr>
                          <th>{{trans($model.'::menu.sidebar.form.tax')}}</th>
                          <td>{{numberformatWithCurrency($payment->tax,2)}}</td>
                        </tr>
                        <tr>
                          <th>{{trans($model.'::menu.sidebar.form.total')}}:</th>
                          <td>{{numberformatWithCurrency($payment->amount,2)}}</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- this row will not appear when printing -->
                <div class="row no-print">
                  <div class="col-xs-12">
                    <?php /*
                      <a href="javascript:;" type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                      <i class="fa fa-download"></i> Generate Invoice
                      </a>
                      */ ?>
                  </div>
                </div>
            </div>
         </div>
      </div>
    </div>
  </div>
</section>

@endsection