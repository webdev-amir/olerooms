@extends('admin.layouts.master')
@section('title', " ScheduleVisit Cancel Details ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1><i class=""></i>
        ScheduleVisit Cancel Details
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('booking.cancelschedulevisit.index')}}"> ScheduleVisit Cancel Manager</a></li>
        <li class="active"> ScheduleVisit Cancel Details</li>
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
                                <img src="{{$data->customer->PicturePath}}" alt="proimg" id="v_UImage" onerror="this.src='{{$data->customer->ErrorPicturePath}}'" />
                            </div>
                            <div class="profile_name">
                                <span class="subtext">#{{$data->schedule_code}}</span>
                                <h4 class="subhead lowercase"><span class="label {{$data->status == 'confirmed'?'label-success':'label-danger'}}">{{ucfirst($data->status)}}</span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="stepblock mB20">
                        <div class="card">
                            <div class="steptitle mB20">
                                <h4 class="subtext"> <i class="zmdi zmdi-account-circle mR10"></i>Customer Details</h4>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Name</h4>
                                        <span class="subtext"> {{ucfirst($data->customer->name)}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Email</h4>
                                        <span class="subtext">{{$data->customer->email}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Customer Contact No.</h4>
                                        <span class="subtext"> {{$data->customer->phone}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($data->scheduleVisitProperty)
                        @foreach($data->scheduleVisitProperty as $key => $propertyList)
                        <div class="stepblock mB20">
                            <div class="card">
                                <div class="steptitle mB20">
                                    <h4 class="subtext"> <i class="zmdi zmdi-account-circle mR10"></i>{{$key+1}}. Property Details</h4>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form_group mB20">
                                            <h4 class="subhead lowercase">Property Name</h4>
                                            <span class="subtext"> {{$propertyList->property->property_name}} ({{$propertyList->property->property_code}})</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form_group mB20">
                                            <h4 class="subhead lowercase">Vititing Date </h4>
                                            <div class="date-in">
                                              <p>Visiting Date</p>
                                              <p class="book-confrom">
                                                 {{ get_date_week_month_name($propertyList->visit_date) }}
                                              </p>
                                           </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form_group mB20">
                                            <h4 class="subhead lowercase">Vititing Time</h4>
                                            <div class="checking col-5">
                                               <div class="date-in">
                                                  <p>Visiting Time</p>
                                                  <p class="book-confrom">
                                                     {{display_time($propertyList->visit_time)}}
                                                  </p>
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form_group mB20">
                                            <h4 class="subhead lowercase">Full Address</h4>
                                            <p class="mb-2"><img class="mr-2" src="{{URL::to('images/map-icon.svg')}}" alt="grid"> {{$propertyList->property->full_address}}, {{$propertyList->property->city->name}}, {{$propertyList->property->state->name}}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form_group mB20">
                                            <h4 class="subhead lowercase">Convinient time to visit property:</h4>
                                            <div class="col-8 p-0">
                                               <div class="owner-confilg">
                                                  <p>
                                                     @if($propertyList->property->convenient_time)
                                                     Owner will be available on {{$propertyList->property->convenient_time}}
                                                     @else
                                                     N/A
                                                     @endif
                                                  </p>
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form_group mB20">
                                            <h4 class="subhead lowercase">Image</h4>
                                             <img src="{{$propertyList->property->CoverImgThunbnail}}" class="detail-image" style="height: 175px;width: 295px;" onerror="this.src='{{onerrorReturnImage()}}'">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        @endforeach
                    @endif

                    @if($data->payment)
                    <div class="stepblock mB20">
                        <div class="card">
                            <div class="steptitle mB20">
                                <h4 class="subtext"> <i class="zmdi zmdi-info mR10"></i> Transaction Details </h4>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Transaction ID</h4>
                                        <span class="subtext"> {{$data->payment->transaction_id ?? ''}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Amount Paid</h4>
                                        <span class="subtext"> {{numberformatWithCurrency($data->payment->amount)??''}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Payment Mode</h4>
                                        <span class="subtext"> {{$data->payment->method ??''}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Payment Date</h4>
                                        <span class="subtext"> {{get_date_week_month_name($data->payment->created_at) ?? ''}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($data->status == 'cancelled' || (!empty($data->cancellation_reason)))
                    <div class="stepblock mB20">
                        <div class="card">
                            <div class="steptitle mB20">
                                <h4 class="subtext"> <i class="zmdi zmdi-info mR10"></i> Cancellation Details </h4>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Cancellation Reason</h4>
                                        <span class="subtext"> {{$data->cancellation_reason?$data->cancellation_reason:"-"}}</span>
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