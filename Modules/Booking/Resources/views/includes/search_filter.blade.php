<style type="text/css">
    .col-md-2 {
        width: 14.667%;
    }

    .col-md-1 {
        width: 13.333%;
    }
</style>
<div class="row">
    <div class='col-sm-2'>
        <div class="form-group">
            {{ Form::text('username',@$_GET['username'], ['id'=>'username','class'=>'form-control','placeholder'=>'Search by username','autocomplate'=>'off']) }}
        </div>
    </div>
    {{-- <div class='col-sm-2'>
        <div class="form-group">
            {{ Form::text('title',@$_GET['search'], ['id'=>'title','class'=>'form-control','placeholder'=>'Search by Title','autocomplate'=>'off']) }}
</div>
</div> --}}
<div class='col-sm-2'>
    <div class="form-group">
        {{ Form::text('bookingId',@$_GET['search'], ['id'=>'bookingId','class'=>'form-control','placeholder'=>'Search by Booking Id','autocomplate'=>'off']) }}
    </div>
</div>
{{-- <div class='col-sm-2'>
        <div class="form-group">
            {{ Form::text('emailId',@$_GET['search'], ['id'=>'emailId','class'=>'form-control','placeholder'=>'Search by Email Id','autocomplate'=>'off']) }}
</div>
</div> --}}
<!-- <div class='col-sm-2'>
        <div class="form-group">
            {{ Form::text('transactionId',@$_GET['search'], ['id'=>'transactionId','class'=>'form-control','placeholder'=>'Search by Transaction Id','autocomplate'=>'off']) }}
        </div>
    </div> -->
<div class='col-sm-2'>
    <div class="form-group">
        <div class='input-group date' id='datetimepicker6'>
            {{ Form::text('from',@$_GET['from'], ['class'=>'form-control from ','placeholder'=>'Start Date','id'=>'srart_date','autocomplate'=>'off']) }}
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
</div>


<div class='col-sm-2'>
    <div class="form-group">
        <div class='input-group date' id='datetimepicker7'>
            {{ Form::text('to',@$_GET['to'], ['class'=>'form-control to','placeholder'=>'End Date','id'=>'end_date','autocomplate'=>'off']) }}
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
</div>

@if(Route::current()->getName() != 'booking.cancelschedulevisit.index')
<div class='col-sm-2'>
    <div class="form-group">
        {{ Form::select('statusBooking', [''=>'Select status']+Config::get('custom.filter_booking_status'),isset($_REQUEST['status']) ? $_REQUEST['status'] : NULL , ['id'=>'statusBooking','class' => 'form-control']) }}
    </div>
</div>
<div class='col-sm-2'>
    <div class="form-group">
        {{ Form::select('vendorId', $vendors,isset($_REQUEST['vendorId']) ? $_REQUEST['vendorId'] : NULL , ['id'=>'vendor','placeholder'=>'Select vendor', 'class' => 'form-control']) }}
    </div>
</div>
<div class='col-sm-2'>
    <div class="form-group">
        {{ Form::select('proType', $propertyTypes,isset($_REQUEST['proType']) ? $_REQUEST['proType'] : NULL , ['id'=>'proType','placeholder'=>'Select Property Type', 'class' => 'form-control']) }}
    </div>
</div>
@else
<div class='col-sm-2'>
    <div class="form-group">
        {{ Form::select('statusBooking', [''=>'Select status']+Config::get('custom.filter_schedulevisit_status'),isset($_REQUEST['status']) ? $_REQUEST['status'] : NULL , ['id'=>'statusBooking','class' => 'form-control']) }}
    </div>
</div>
@endif
</div>
{{ Form::hidden('page',@$_GET['page'], []) }}
<div class="row" style="display:flex; justify-content: space-between;float: right;padding-right: 8px;">
    <div class=''>
        <div class="form-group" style="width: 191px;">
            <button href="javascript:;" class="btn btn-success btn-flat search_trigger" onclick="loadTable();"><i class="fa fa-search"></i> Search</button>
            <button href="javascript:;" class="btn btn-warning btn-flat" onclick="resetBookingFilter();">Reset Filter</button>
        </div>
    </div>
</div>