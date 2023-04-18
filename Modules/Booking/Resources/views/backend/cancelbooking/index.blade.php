@extends('admin.layouts.master')
@section('title', " Booking Cancel Manager ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1><i class="{{trans('$model::menu.font_icon')}}"></i>
        Booking Cancel Manager
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">Booking Cancel Manager</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Booking Cancel Manager</h3>
            <div class="box-tools pull-right">
            </div>
            <br><br>
            @include('booking::includes.search_filter')
        </div>
        <div class="box-body" style="display: block;">
            <table class="table table-bordered table-hover" id="data_filter">
                <thead>
                    <tr>
                        <th>Booking Id</th>
                        <th>Customer Info</th>
                        <th>Owner Info</th>
                        <th>Property Type</th>
                        <th>Booking Date</th>
                        <th>Check-In Date</th>
                        <th>Check-out Date</th>
                        <!-- <th>Location</th> -->
                        <th>Amount</th>
                        <!-- <th>Txn Id</th> -->
                        <th>Cancellation Reason</th>
                        <th>Booking Status</th>
                        <th>Cancellation Status</th>
                        <th>Cancellation Status Date</th>
                        <th width="8%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
@endsection
@section('uniquePageScript')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        loadTable();
    });

    function loadTable() {
        var loaderString = '<div class="ajaxloader" id="AjaxLoader"><div class="strip-holder"><div class="strip-1"></div><div class="strip-2">';
        loaderString += '</div><div class="strip-3"></div></div></div>';
        $('#data_filter').dataTable({
            sPaginationType: "full_numbers",
            processing: true,
            serverSide: true,
            destroy: true,
            order: [0, 'desc'],
            ajax: {
                "url": "{!! route($model.'.cancelbooking.ajax') !!}",
                "type": "POST",
                "data": function(d) {
                    if ($('#username').val() != "") {
                        d.username = $('#username').val();
                    }
                    if ($('#title').val() != "") {
                        d.title = $('#title').val();
                    }
                    if ($('#bookingId').val() != "") {
                        d.bookingId = $('#bookingId').val();
                    }
                    if ($('#emailId').val() != "") {
                        d.emailId = $('#emailId').val();
                    }
                    if ($('#transactionId').val() != "") {
                        d.transactionId = $('#transactionId').val();
                    }
                    if ($('#srart_date').val() != "") {
                        d.srart_date = $('#srart_date').val();
                    }
                    if ($('#end_date').val() != "") {
                        d.end_date = $('#end_date').val();
                    }
                    if ($('#statusBooking').val() != "") {
                        d.statusBooking = $('#statusBooking').val();
                    }
                    if ($('#vendor').val() != "") {
                        d.vendor = $('#vendor').val();
                    }
                    if ($('#proType').val() != "") {
                        d.proType = $('#proType').val();
                    }
                }
            },
            language: {
                "processing": loaderString,
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'pro_owner',
                    name: 'pro_owner'
                },
                {
                    data: 'booking_date',
                    name: 'booking_date'
                },
                {
                    data: 'checkIn_date',
                    name: 'checkIn_date'
                },
                {
                    data: 'checkOut_date',
                    name: 'checkOut_date'
                },
                // {
                //     data: 'location',
                //     name: 'location'
                // },
                {
                    data: 'amount',
                    name: 'amount',
                    orderable: false,
                    searchable: false
                },
                // {
                //     data: 'transaction_id',
                //     name: 'transaction_id'
                // },
                {
                    data: 'cancellation_reason',
                    name: 'cancellation_reason'
                },
                {
                    data: 'booking_status',
                    name: 'booking_status'
                },
                {
                    data: 'cancellation_status',
                    name: 'cancellation_status'
                },
                {
                    data: 'cancellation_status_date',
                    name: 'cancellation_status_date'
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
    }

    function resetBookingFilter() {
        $('#username, #title , #bookingId, #emailId, #transactionId, #srart_date, #end_date').val("");
        $('#statusBooking, #vendor, #proType').prop('selectedIndex', 0);
        $('#srart_date, #end_date').datepicker('setDate', null);
        if (document.getElementById("end_date")) {
            $('#end_date').datepicker("option", "minDate", null)
        }
        loadTable();
    }
</script>
@endsection