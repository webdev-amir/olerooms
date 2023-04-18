@extends('admin.layouts.master')
@section('title', " ".trans('users::menu.sidebar.form.user_details')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1>@lang('users::menu.sidebar.form.user_profile')</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('users.index')}}">Users</a></li>
        <li class="active">@lang('users::menu.sidebar.form.user_profile')</li>
        <li class="active">{{ucfirst($user->fullName)}}</li>
    </ol>
</section> 
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{$user->ThumbPicturePath}}" alt="User profile picture" id="dash_PImage" onerror="this.src='{{onerrorProImage()}}'">
                      <!-- <span class="upload-icon"> <i class="fa fa-camera"></i>
                      <input type="file" id="PImage" name="PImage" accept="image/*" class="onlyimageupload" data-userid="{{$user->id}}" data-uploadurl="{{route('users.uploadProfile')}}">
                      </span> -->
                    <h3 class="profile-username text-center">{{ucfirst($user->fullName)}}</h3>
                    <p class="text-muted text-center">
                        @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $v)
                        <label class="badge badge-success"> {{ $v }}</label>
                        @endforeach
                        @endif
                    </p>
                    <ul class="list-group list-group-unbordered">
                        <!--  <li class="list-group-item">
                           <b>@lang('users::menu.sidebar.form.total_project')</b> <a class="pull-right">0</a>
                         </li> -->
                    </ul>
                    @if(Auth::guard('admin')->user()->hasRole('admin') && $user->hasRole('admin'))
                    <a href="{{route('subadmin.edit',$user->slug)}}" class="btn btn-primary btn-block"><i class="fa fa-pencil"></i> &nbsp;&nbsp;@lang('users::menu.sidebar.form.edit')</a>
                    @endif
                </div>
            </div>
            {{--
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">@lang('users::menu.sidebar.form.address')</h3>
            </div>
            <div class="box-body">
                    <strong><i class="fa fa-map-marker margin-r-5"></i>@lang('users::menu.sidebar.form.company_name')                            </strong>
                                                           @if(isset($user->company))
                                <p class="text-muted">
                {{ucfirst($user->company)}} 
                                    </p>
                                    @else
                                    N/A
                                    @endif
                                </div>
                            </div>
                            --}}
                        </div>
                        <div class="col-md-9">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#userDetails" data-toggle="tab">@lang('users::menu.sidebar.form.user_details')</a></li>
                                    <!--  <li><a href="#changePassword" data-toggle="tab">@lang('users::menu.sidebar.form.change_password')</a></li> -->
                                </ul>
                                <div class="tab-content">
                                    <div class="active tab-pane" id="userDetails">
                                        <form class="form-horizontal">
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-3 control-label">@lang('Full Name'):</label>
                                                <div class="col-sm-9 paddt7">
                                                    {{ucfirst($user->fullName)}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.email'):</label>
                                                <div class="col-sm-9 paddt7">
                                                    {{$user->email}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.mob_number'):</label>
                                                <div class="col-sm-9 paddt7">
                                                    {{$user->NotificationNumber}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail" class="col-sm-3 control-label">Address:</label>
                                                <div class="col-sm-9 paddt7">
                                                    {{$user->address ? $user->address : 'N/A'}}
                                                    @if($user->address2)
                                                    <br>{{ $user->address2 }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail" class="col-sm-3 control-label">Gender :</label>
                                                <div class="col-sm-9 paddt7">
                                                    {{$user->gender ? ucfirst($user->gender) : 'N/A'}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail" class="col-sm-3 control-label">Marital Status :</label>
                                                <div class="col-sm-9 paddt7">
                                                    {{$user->marital_status ? ucfirst($user->marital_status) : 'N/A'}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputCountry" class="col-sm-3 control-label"> @lang('users::menu.sidebar.form.status'):</label>
                                                <div class="col-sm-9 paddt7">
                                                    @if($user->deleted_at)
                                                    <span class="label label-danger">Deleted</span>
                                                    @else
                                                    @if($user->status==1)
                                                    <span class="label label-success">Active</span>
                                                    @else
                                                    <span class="label label-danger">InActive</span>
                                                    @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputCountry" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.email_verify'):</label>
                                                <div class="col-sm-9 paddt7">
                                                    @if($user->email_verified_at)
                                                    <span class="label label-success">Verified</span>
                                                    @else
                                                    <span class="label label-danger">Not Verified</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputCountry" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.date_of_join'):</label>
                                                <div class="col-sm-9 paddt7">
                                                    {{$user->created_at->format(\Config::get('custom.default_date_time_formate'))}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputCountry" class="col-sm-3 control-label">Date of Birth:</label>
                                                <div class="col-sm-9 paddt7">
                                                    {{$user->dob ? $user->dob : 'N/A'}}
                                                </div>
                                            </div> 
                                            {{--
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.documents_uploaded'):</label>
                     <div class="col-sm-9 paddt7">
                        <div class="timeline-item">
                          <div class="timeline-body">
                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                          </div>
                        </div>
                      </div>
                  </div>
                  --}}
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="changePassword">
                                        @include('users::change_password')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </section>
                    @endsection
