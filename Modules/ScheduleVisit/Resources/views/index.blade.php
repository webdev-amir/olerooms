@extends('admin.layouts.master')
@section('title', "Schedule Visit ".trans('menu.pipe')." " .app_name())
@section('content')
    <section class="content-header">
      <h1><em class="{{trans($model.'::menu.font_icon')}} "></em>
        Schedule Visit Manager
        <small></small>
      </h1>
      <ol class="breadcrumb">
         <li><em class="fa fa-dashboard"></em> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li class="active">Schedule Visit Manager</li>
        <li class="active">Schedule Visit Manager</li>
      </ol>
    </section>
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Schedule Visit </h3>
                <div class="box-tools pull-right">
                </div>
                <br><br>
               <div class="row">
                     <div class='col-md-2 '>
                          <div class="form-group">
                              <div class='input-group date' id='datetimepicker7'>
                                  {{ Form::select('order_by', [''=>'Filter By Status','all'=>'All','active'=>'Today Visit','past_visit'=>'Past Visit','upcoming_visit'=>'Upcoming Visit','cancelled'=>'Cancelled Visit'],isset($_REQUEST['status']) ? $_REQUEST['status'] : NULL , ['class' => 'form-control']) }}
                              </div>
                          </div>
                     </div>
                     {{ Form::hidden('page',@$_GET['page'], []) }}
                    <div class='col-md-4'>
                          <div class="form-group">
                            <button href="javascript:;" class="btn btn-success btn-flat search_trigger" onclick="serach();"><i class="fa fa-search"></i> Search</button>
                           <button href="javascript:;" class="btn btn-warning btn-flat" onclick="reset();">Reset Filter</button>
                          </div>
                    </div>
                </div>
            </div>
            <div class="box-body" id="result" style="display: block;">
               @include('schedulevisit::ajax_schedule_visit_list')
            </div>
        </div>
    </section>  
@endsection
@section('uniquePageScript')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection
