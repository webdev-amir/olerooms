@extends('admin.layouts.master')
@section('title', " Review Management ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1><i class="{{trans('$model::menu.font_icon')}}"></i>
        Review Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">Review Management</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Review Management</h3>
            <div class="box-tools pull-right">
            </div>
            <br><br>
            @include('review::includes.search_filter')
        </div>
        <div class="box-body" style="display: block;">
            <table class="table table-bordered table-hover" id="data_filter">
                <thead>
                    <tr>
                        <th>Booking Id</th>
                        <th>Username</th>
                        <th>Ownername</th>  
                        <th>Property Name</th>  
                        <th>Booking Date</th>
                        <th>Rating</th>
                        <th>Written Review</th>
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
    });

    function loadTable(){
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
                    "data": function(d){
                                if($('#username').val() != ""){
                                    d.username = $('#username').val();
                                }
                               
                                if($('#bookingId').val() != ""){
                                    d.bookingId = $('#bookingId').val();
                                }
                                
                                if($('#srart_date').val() != ""){
                                    d.srart_date = $('#srart_date').val();
                                }
                                if($('#end_date').val() != ""){
                                    d.end_date = $('#end_date').val();
                                }
                                if($('#vendor').val() != ""){
                                    d.vendor = $('#vendor').val();
                                }
                            }
                },
                language: {          
                    "processing": loaderString,
                },
                columns: [
                    {
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
                        data: 'property_name',
                        name: 'property_name'
                    },
                   
                    {
                        data: 'booking_date',
                        name: 'booking_date'
                    },
                    {
                        data: 'rating_number',
                        name: 'rating_number'
                    },
                    {
                        data: 'review_content',
                        name: 'review_content'
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
    function resetBookingFilter(){
      $('#username, #title , #bookingId, #emailId, #transactionId, #srart_date, #end_date').val("");
      $('#statusBooking, #vendor, #proType').prop('selectedIndex',0);
      $('#srart_date, #end_date').datepicker('setDate', null);
        if (document.getElementById("end_date")) {
            $('#end_date').datepicker( "option", "minDate", null )
        }
      loadTable();
    }
</script>
@endsection