@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.users.customer_main')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1><i class="{{trans('users::menu.font_icon')}} "></i>
        {{trans('menu.sidebar.users.customer_main')}}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">{{trans('menu.sidebar.users.customer_main')}}</li>
        <li class="active">Customers</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('Customers')</h3>
            <div class="box-tools pull-right">
                <a href="{{route('users.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> Add Customer</a>
                <br/>
            </div>
            <br><br>
            {!! Form::open(['route' => 'users.index','method' => 'GET']) !!}
            <div class="row">
                <div class='col-md-2'>
                    <div class="form-group">
                        {{ Form::text('name',@$_GET['name'], ['class'=>'form-control','placeholder'=>trans('users::menu.sidebar.form.search_by_name')]) }}
                    </div>
                </div>
                <div class='col-md-2'>
                    <div class="form-group">
                        {{ Form::text('email',@$_GET['email'], ['class'=>'form-control','placeholder'=>trans('users::menu.sidebar.form.email')]) }}
                    </div>
                </div>

                <div class='col-md-2'>
                    <div class="form-group">
                        {{ Form::text('phone',@$_GET['phone'], ['class'=>'form-control','placeholder'=>trans('users::menu.sidebar.form.phone')]) }}
                    </div>
                </div>


                <div class='col-md-2'>
                    <div class="form-group">
                        {!! Form::select('status',[''=>'Select Status','active'=>'Active','inactive'=>'In-Active'],@$_GET['status'], array('class' => 'form-control')) !!}
                    </div>
                </div>
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
                            <input type='text' class="form-control to" name="to" id="end_date" value="{{@$_GET['to']}}"  placeholder="To"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row" style="float:right;">
                <div class='col-md-1'>
                    <button type="submit" class="btn btn-success btn-flat pull-right btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
                        <i class="fa fa-search"></i> {{ trans('users::menu.sidebar.form.search') }}
                    </button>
                </div>
                <div class='col-md-2'>
                    <a href="{{route('users.index')}}" class="btn btn-success btn-flat pull-left btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
                        <i class="fa fa-refresh"></i> {{ trans('Clear Search') }}
                    </a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>@sortablelink('name', trans('users::menu.sidebar.form.name'))</th>
                        <th>@sortablelink('email',trans('users::menu.sidebar.form.email'))</th>
                        <th>@lang('users::menu.sidebar.form.mob_number')</th>
                        <th>@lang('users::menu.sidebar.form.reg_date')</th>
                        <th>@lang('users::menu.sidebar.form.status')</th>
                        <th>@lang('users::menu.sidebar.form.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($users)>0)
                    @php $i=0; @endphp
                    @foreach($users as $user)
                    @php $i++; @endphp
                    <tr>
                        <td>{{ $user->fullName }}</td>
                        <td><a href="mailto:{{ $user->email }}" class="tooltips" data-original-title="Send Email">{{ $user->email }}</a></td>
                        <td>{{$user->NotificationNumber}}</td>
                        <td>{{ $user->created_at->format(\Config::get('custom.default_date_formate')) }}</td>
                        <td>
                            @if($user->deleted_at)
                            <span class="label label-danger">Deleted</span>
                            @else
                            @if($user->status == 1)
                            <span class="label label-success">@lang('users::menu.sidebar.form.active')</span>
                            @else
                            <span class="label label-danger">@lang('users::menu.sidebar.form.inactive')</span>
                            @endif
                            @endif
                        </td>
                        <td class="">
                            <a href="{{route('users.show',$user->slug)}}" class="bookingAction" data-toggle="tooltip" data-placement="top" title="View Details">
                                <i class="fa fa-eye"></i>
                            </a>
                            @if(!$user->deleted_at)
                            @if($user->status==0) 
                            <a data-placement="top" data-toggle="tooltip" class="danger tooltips" title="Active" rel="Active" name="{{route('users.status',$user->slug)}}" href="javascript:;" onClick="return AjaxActionTableDrow(this);" data-title="Active" data-action="{{route('users.status',$user->slug)}}" data-refresh="no" data-reload="yes"><i class="fa fa-check" aria-hidden="true"></i></a>
                            @else
                            <a data-toggle="tooltip" class="success tooltips"  title="Inactive"  rel="Inactive" name="{{route('users.status',$user->slug)}}" href="javascript:;" data-placement="top" onClick="return AjaxActionTableDrow(this);" data-title="Inative" data-action="{{route('users.status',$user->slug)}}" data-refresh="no" data-reload="yes"><i class="fa fa-ban" aria-hidden="true"></i></a>
                            @endif


                            <a href="{{ route('users.edit',[$user->slug]) }}" class="" data-toggle="tooltip" data-placement="top" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <a data-toggle="tooltip" class="changestatus" title="Delete" href="javascript:;" data-default="Delete" data-title="Delete" data-url="{{ route('users.delete', $user->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a> 
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr><td colspan="9" align="center">@lang('menu.no_record_found')</td></tr>
                    @endif
                </tbody>
            </table>
            <div class="pull-right">
                {{ $users->appends($_GET)->links("pagination::bootstrap-4") }}
            </div>
        </div>
    </div>
</section>
@endsection
@section('uniquePageScript')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
                                jQuery(document).ready(function () {
                                    // Date Picker
                                    $('#srart_date').datepicker({
                                        format: 'yyyy-mm-dd',
                                        onSelect: function (date) {
                                            var date2 = $('#srart_date').datepicker('getDate');

                                            date2.setDate(date2.getDate() + 1);
                                            $('#end_date').datepicker('setDate', date2);
                                            //sets minDate to dt1 date + 1
                                            $('#end_date').datepicker('option', 'minDate', date2);
                                        }
                                    });
                                    $('#end_date').datepicker({
                                        format: 'yyyy-mm-dd',
                                        onClose: function () {
                                            var dt1 = $('#srart_date').datepicker('getDate');
                                            var dt2 = $('#end_date').datepicker('getDate');
                                            if (dt2 <= dt1) {
                                                var minDate = $('#end_date').datepicker('option', 'minDate');
                                                $('#end_date').datepicker('setDate', minDate);
                                            }
                                        }
                                    });

                                    $(document).on('click', '.changestatus', function () {
                                        var id = $(this).attr('data-id');
                                        var status = $(this).attr('data-default');
                                        var title = $(this).attr('data-title');
                                        var url = $(this).attr('data-url');
                                        Lobibox.confirm({
                                            draggable: false,
                                            closeButton: false,
                                            closeOnEsc: false,
                                            title: title + ' Confirmation',
                                            msg: 'Are you sure you, want to ' + status + '?',
                                            callback: function ($this, type, ev) {
                                                if (type === 'yes') {
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: url,
                                                        dataType: 'json',
                                                        data: {
                                                            'id': id,
                                                            'status': status
                                                        },
                                                        success: (data) => {
                                                            // getAjax();
                                                            if (data['status']) {
                                                                Lobibox.notify('success', {
                                                                    position: "top right",
                                                                    msg: data['message']
                                                                });
                                                            }
                                                            setTimeout(function () {
                                                                window.location.reload();
                                                            }, 5000);
                                                        },
                                                        error: function (data) {
                                                            console.log(data);
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    });
                                });
</script>
@endsection
