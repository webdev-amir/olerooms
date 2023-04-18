<style type="text/css">
    .col-md-2 {
        width: 14.667%;
    }

    .col-md-1 {
        width: 13.333%;
    }
</style>
<div class="row">
    {{-- Form::open(array('url'=>'', 'id'=>'filterData')) --}}
    <div class='col-sm-2'>
        <div class="form-group">
            {{ Form::text('username',@$_GET['username'], ['id'=>'username','class'=>'form-control','placeholder'=>'Search by username','autocomplate'=>'off']) }}
        </div>
    </div>
    <div class='col-sm-2'>
        <div class="form-group">
            {{ Form::text('bookingId',@$_GET['search'], ['id'=>'bookingId','class'=>'form-control','placeholder'=>'Search by Booking Id','autocomplate'=>'off']) }}
        </div>
    </div>
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
    
    {{-- Form::close() --}}
</div>
<div class="row" style="display:flex; justify-content: space-between;float: right;padding-right: 8px;">
    <div class=''>
        <div class="form-group" style="width: 191px;">
            <button href="javascript:;" class="btn btn-success btn-flat search_trigger" onclick="loadTable();"><i class="fa fa-search"></i> Search</button>
            <button href="javascript:;" class="btn btn-warning btn-flat" onclick="resetBookingFilter();">Reset Filter</button>
        </div>
    </div>
</div>