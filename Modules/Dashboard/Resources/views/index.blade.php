@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.dashboard')." ".trans('menu.pipe')." " .app_name(). " Admin")
@section('content')
<section class="content-header">
  <h1>Statistic</h1>
  <ol class="breadcrumb">
    <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>
<section class="content" style="min-height: 100px;">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Filter</h3>
      <br><br>
      {!! Form::open(['route' => 'backend.dashboard','method' => 'GET']) !!}
      <div class="row">
        <div class='col-md-2'>
          <div class="form-group">
            <div class='input-group date' id='datetimepicker6'>
              <input type='text' class="form-control from" name="from" id="srart_date" value="{{@$_GET['from']}}" placeholder="From" />
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>
        <div class='col-md-2'>
          <div class="form-group">
            <div class='input-group date' id='datetimepicker7'>
              <input type='text' class="form-control to" name="to" id="end_date" value="{{@$_GET['to']}}" placeholder="To" />
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>
        <div class='col-md-1'>
          <button type="submit" class="btn btn-success btn-flat pull-right btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
            <i class="fa fa-search"></i> {{ trans('users::menu.sidebar.form.search') }}
          </button>
        </div>
        <div class='col-md-2'>
          <a href="{{route('backend.dashboard')}}" class="btn btn-success btn-flat pull-left btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
            <i class="fa fa-refresh"></i> Clear Search
          </a>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{@$usersCount}}</h3>
          <p>Total Users</p>
        </div>
        <div class="icon">
          <i class="ion-person-add"></i>
        </div>
        @can('users.index')
        <a href="{{route('users.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        @else
        <a href="javascript:;" class="small-box-footer">&nbsp;</a>
        @endcan
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue">
        <div class="inner">
          <h3>{{@$vendorsCount}}</h3>
          <p>Total Vendors</p>
        </div>
        <div class="icon">
          <i class="ion-person-add"></i>
        </div>
        @can('users.index')
        <a href="{{route('vendor.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        @else
        <a href="javascript:;" class="small-box-footer">&nbsp;</a>
        @endcan
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{@$bookingsCount}}</h3>
          <p>Total Bookings</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="{{route('booking.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{isset($paymentsCount) ? numberformatWithCurrency($paymentsCount) : ""}}<sup style="font-size: 20px"></sup></h3>
          <p>Earnings</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="{{route('payment.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{$cancelledBookingsCount}}<sup style="font-size: 20px"></sup></h3>
          <p>Cancelled Booking Requests</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="{{route('booking.cancelbooking.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{$unapprovedVendorsCount}}<sup style="font-size: 20px"></sup></h3>
          <p>Vendors(Pending)</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="{{route('vendor.index')}}?verification_status=pending" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

  </div>
</section>
@endsection
@section('uniquePageScript')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
  jQuery(document).ready(function() {
    // Date Picker
    $('#srart_date').datepicker({
      format: 'yyyy-mm-dd',
      onSelect: function(date) {
        var date2 = $('#srart_date').datepicker('getDate');

        date2.setDate(date2.getDate() + 1);
        // $('#end_date').datepicker('setDate', date2);
        //sets minDate to dt1 date + 1
        // $('#end_date').datepicker('option', 'minDate', date2);
      }
    });
    $('#end_date').datepicker({
      format: 'yyyy-mm-dd',
      onClose: function() {
        var dt1 = $('#srart_date').datepicker('getDate');
        var dt2 = $('#end_date').datepicker('getDate');
        if (dt2 <= dt1) {
          var minDate = $('#end_date').datepicker('option', 'minDate');
          $('#end_date').datepicker('setDate', minDate);
        }
      }
    });
  });
</script>
@endsection