@extends('admin.layouts.master')
@section('title', " Cancel Booking Details ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1><i class="{{trans('$model::menu.font_icon')}}"></i>
        Cancel Booking Details
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('booking.index')}}">Cancel Booking Management</a></li>
        <li class="active">Cancel Booking Details</li>
    </ol>
</section>
<section class="commonbg sec_pd2 bg_light animated7 fadeInLeft loan_preview content">
    <div class="box box-primary">
        <div class="box-body box-profile">
            <div class="container">
                <div class="shdow_block">
                    <div class="leftabstract"></div>
                    <div class="stepblock mB20">
                        <div class="profileblock">
                            <div class="imgblock">
                                <img src="{{ $booking->property->CoverImgThunbnail }}" alt="proimg" id="v_UImage" onerror="imgError(this);" />
                            </div>
                            <div class="profile_name">
                                <span class="subtext">Booking ID</span>
                                <h4 class="subhead lowercase">#{{$booking->id }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="stepblock mB20">
                        <div class="card">
                            <div class="steptitle mB20">
                                <h4 class="subtext"> <i class="zmdi zmdi-account-circle mR10"></i> Property Details</h4>
                            </div>
                            <div class="row">

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Property Name</h4>
                                        <span class="subtext"> {{$booking->property->property_name}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Property Type</h4>
                                        <span class="subtext">{{$booking->property->propertyType->name}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">City </h4>
                                        <span class="subtext">{{$booking->property->city->name}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Full Address </h4>
                                        <span class="subtext">{{$booking->property->full_address}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Booking Amount </h4>
                                        <span class="subtext">{{ numberformatWithCurrency($booking->total)}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Booking Date </h4>
                                        <span class="subtext">{{ get_date_week_month_name($booking->created_at)}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Check In Date </h4>
                                        <span class="subtext">{{ get_date_week_month_name($booking->check_in_date)}}</span>
                                    </div>
                                </div>
                                @if($booking->check_out_date)
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Check Out Date </h4>
                                        <span class="subtext">{{ get_date_week_month_name($booking->check_out_date)}}</span>
                                    </div>
                                </div>
                                @endif

                                @if(@$jsonData->discount)
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Discount Amount </h4>
                                        <span class="subtext">{{ numberformatWithCurrency($jsonData->discount)}}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="stepblock mB20">
                        <div class="card">
                            <div class="steptitle mB20">
                                <h4 class="subtext"> <i class="zmdi zmdi-info mR10"></i> Customer Details </h4>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Customer Name</h4>
                                        <span class="subtext"> {{$booking->name}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Customer Email</h4>
                                        <span class="subtext"> {{$booking->email}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Customer Contact No.</h4>
                                        <span class="subtext"> {{$booking->phone}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Check In Date</h4>
                                        <span class="subtext"> {{get_date_week_month_name($booking->check_in_date) ?? ''}}</span>
                                    </div>
                                </div>

                                @if(in_array($booking->Property->propertyType->slug,['hostel-pg','hostel-pg-one-day','homestay']))
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Guests</h4>
                                        <span class="subtext"> {{$jsonData->guests}}</span>
                                    </div>
                                </div>
                                @else
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Adults</h4>
                                        <span class="subtext"> {{$jsonData->adult}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Children</h4>
                                        <span class="subtext"> {{$jsonData->children}}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($booking->payment)
                    <div class="stepblock mB20">
                        <div class="card">
                            <div class="steptitle mB20">
                                <h4 class="subtext"> <i class="zmdi zmdi-info mR10"></i> Transaction Details </h4>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Transaction ID</h4>
                                        <span class="subtext"> {{$booking->payment->transaction_id ?? ''}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Amount Paid</h4>
                                        <span class="subtext"> {{numberformatWithCurrency($booking->payment->amount)??''}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Payment Mode</h4>
                                        <span class="subtext"> {{$booking->payment->method ??''}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Payment Date</h4>
                                        <span class="subtext"> {{get_date_week_month_name($booking->payment->created_at) ?? ''}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection