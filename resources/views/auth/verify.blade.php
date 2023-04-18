@extends('mydashboard::layouts.dashboard_master')
@section('title', "Verify Email".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_user_profile" id="customer_profile">
    <div class="container-fluid">
        <div class="row row-eq-height">
            <div class="col-md-3 slide-menu">
                @include('mydashboard::includes.sidebar_profile_menu')
            </div>
            <div class="col-md-9 top-menu">
                <div class="user-form-settings">
                    <div>
                        <div class="dash_header d-flex justify-content-between">
                            <div aria-label="breadcrumb" class="breadcrumb-page-bar">
                                <ul class="page-breadcrumb p-0">
                                    <li class=" active"> Verify Email </li>
                                </ul>
                                <div class="bravo-more-menu-user"><i class="fa fa-bars"></i></div>
                            </div>
                            @include('mydashboard::includes.sidebar_top_header_menu')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 visits-content">
                <div class="user-form-settings">
                    <div class="selected fadeInUp animated2 delay1">
                        <h2 class="title-bar">
                            <span>
                                Verify Email
                            </span>
                        </h2>
                        <div class="user-profile-lists profile-clone">
                            <div class="inner_content w-100">
                                <div class="card">
                                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                                    <div class="card-body">
                                        @if (session('resent'))
                                        <div class="alert alert-success" role="alert">
                                            {{ __('A fresh verification link has been sent to your email address.') }}
                                        </div>
                                        @endif

                                        {{ __('Before proceeding, please check your email for a verification link.') }}
                                        {{ __('If you did not receive the email') }},
                                        
                                        @if(auth()->user()->hasRole('customer'))
                                            @php $resendRoute = route('verification.resend') @endphp
                                        @elseif(auth()->user()->hasRole('agent'))
                                            @php $resendRoute = route('agent.verification.resend') @endphp
                                        @elseif(auth()->user()->hasRole('company'))
                                            @php $resendRoute = route('company.verification.resend') @endphp
                                        @else
                                            @php $resendRoute = route('verification.resend') @endphp
                                        @endif
                                        <form class="d-inline" method="POST" action="{{ $resendRoute }}">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
