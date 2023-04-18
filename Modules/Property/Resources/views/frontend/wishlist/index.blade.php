@extends('mydashboard::layouts.dashboard_master')
@section('title', "Wishlist ".trans('menu.pipe')." " .app_name())
@section('section_type_dashboard',"wishlist-section")
@section('content')
<div class="bravo_user_profile" id="my_wishlist_page">
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
                                    <li class=" active"> My Wishlist </li>
                                </ul>
                                <div class="bravo-more-menu-user"><i class="fa fa-bars"></i></div>
                            </div>
                            @include('mydashboard::includes.sidebar_top_header_menu')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 wishlist-content">
                <div class="user-form-settings">
                    <div class="selected fadeInUp animated2 delay1 ">
                        <h2 class="title-bar">
                            <span>
                                My Wishlist
                            </span>
                        </h2>
                        <div class="user-profile-lists">
                            <div class="inner_content w-100 wishlist-inner">
                                <div class="wishlist-thumb  selected fadeInUp animated2 delay1">
                                    <div id="result" class="paginate-run">
                                        @include('property::frontend.wishlist.ajax_my_wishlist')
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