@extends('admin.layouts.master')
@section('title', " Booking Management ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1><i class="{{trans('$model::menu.font_icon')}}"></i>
        Booking Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">Booking Management</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Booking Management</h3>
            <div class="box-tools pull-right">
            </div>
            <br><br>
            @include('booking::includes.search_filter')
        </div>
        <div class="box-body" style="display: block;">
            <table class="table table-bordered table-hover" id="data_filter">
                <thead>
                    <tr>
                        <th>Booking Id </th>
                        <th>Booking Info </th>
                        <th>Property Info</th>
                        <th>Customer Info</th>
                        <th>Owner</th>
                        <th>Check-In Date</th>
                        <th>Check-out Date</th>
                        <th>Location</th>
                        <th>Amount</th>
                        <th>Txn Id</th>
                        <th>Status</th>
                        <th>Action</th>
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

        /* $(document).on('change','#bookingstatus',function(){
            var thisobject = $(this);
            var data = {};
            data.bookingId = thisobject.attr('booking_id');
            data.status =    thisobject.val();

            $.ajax({
                type: 'POST',
                url: "",
                data: data,
                beforeSend: function() {
                    loaderString
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr) {
                    console.log('error occured',xhr.statusText + xhr.responseText);
                }
            });
        }); */

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
                "url": "{!! route($model.'.ajax') !!}",
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
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'property_name',
                    name: 'property_name'
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
                    data: 'checkIn_date',
                    name: 'checkIn_date'
                },
                {
                    data: 'checkOut_date',
                    name: 'checkOut_date'
                },
                {
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'amount',
                    name: 'amount',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'transaction_id',
                    name: 'transaction_id'
                },
                {
                    data: 'status',
                    name: 'status'
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