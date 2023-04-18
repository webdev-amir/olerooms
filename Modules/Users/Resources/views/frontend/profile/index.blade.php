@extends('layouts.app')
@section('content')
<link href="{{URL::to('module/user/css/profile.css')}}" rel="stylesheet">
<div class="page-profile-content page-template-content">
    <div class="container">
        <div class="">
            <div class="row">
                <div class="col-md-3">
                    @include('users::frontend.profile.sidebar')
                </div>
                <div class="col-md-9">
                    <h3 class="profile-name">{{__("Hi, I'm :name",['name'=>$user->getDisplayName()])}}</h3>
                    <div class="profile-bio">{!! $user->bio !!}</div>
                    @include('users::frontend.profile.spaces')
                    <div class="div" style="margin-top: 40px;">
                       @include('users::frontend.profile.reviews') 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
