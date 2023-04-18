@extends('layouts.app')
@section('title',"Property Success".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content">
    <section class="succesStep">
        <figure class="coverImage"></figure>
        <div class="succesContent text-center fadeInUp animated3 delay1 selected">
            <img src="{{URL::to('images/partnership.svg')}}" alt="image not found" />
            <h4 class="mT50">Congratulations {{auth()->user()->name}}!</h4>
            <p>Property ID is {{@$property->property_code}}</p>
            <span>Your Property added succesfully. If you have any query <br> contact with us on <a href="tel:{!! $configVariables['admincontact']['value'] !!}" class="contact_us_phone"> {!! $configVariables['admincontact']['value'] !!}</a></span>

            <div class="mT30">
                <a href="{{ route('vendor.dashboard') }}" class="btn customBtn btn-success minw-184"> Go to Dashboard </a>
            </div>
            <span class="my-2">Note:-
                Our Executive will come to this property shortly to verify your details and
                collect the keys to your property. We request you to be available during the inspection time.
            </span>
        </div>
    </section>
</div>
@endsection