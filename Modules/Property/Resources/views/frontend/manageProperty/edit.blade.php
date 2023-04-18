@extends('propertyownerdashboard::layouts.add_property_dashboard_master')
@section('title', "Edit Property ".trans('menu.pipe')." " .app_name())
@section('content')
@php
if($sessionData && isset($sessionData)){
$formData = $sessionData;
}else{
$formData = [];
}
@endphp
<div class="bravo_wrap" id="manage_property">
    <div class="stepRow">
        <div class="step_leftPanel">
            <div class="logo"><img src="{{URL::to('images/logo-icon-white.svg')}}" alt="image not found" /></div>
            <div class="leftcontent">
                <span class="grey regular font20">Step <span id="stepCount">{{isset($sessionAllData->current_step) ? $sessionAllData->current_step : 1}}</span> of 4</span>
                <p class="black regular font28 mt-3">Let's Begin Your<br>
                    Property Adding Journey
                </p>
                <div class="staticBox">
                    <div class="cardouter">
                        <div class="card border-0">
                            <div class="text-center">
                                <span class="inputIcon m-auto"><img src="{{URL::to('images/gift.svg')}}" alt="fire"></span>
                                <div class="modalContent mt-4">
                                    <h4 class="bluedark bold font16 black mb-2">Get Tenant Early</h4>
                                    <p class="grey font12 regular content_steps">Basic Information</p>
                                    <!-- <p class="grey font12 regular">It is a long established fact that a reader will be distracted by the readable content of a page when looking.</p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="step_rightPanel">
            @include('property::frontend.manageProperty.steps.edit.step_1')
            @include('property::frontend.manageProperty.steps.edit.step_2')
            @include('property::frontend.manageProperty.steps.edit.step_3')
            @include('property::frontend.manageProperty.steps.edit.step_4')
        </div>
    </div>
</div>
@endsection