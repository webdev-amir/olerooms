@extends('layouts.app')
@section('title', " ".$pageInfo->name." ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content">
   <section class="innerbanner">
         <div class="container text-center">
            <span class="subheading d-block">Guest House, PG, Hostel, Flats,</span>
            <h1>{!! $pageInfo->banner_heading !!}</h1>
         </div>
   </section>
   <section class="bravo-list-hotel padding50 m-0">
      <div class="container">
            <div class="static_content mB50 fadeInUp animated1 selected">
               <div class="title44">
                  <h2> {!! $pageInfo->name !!} </h2>
                  <hr class="mB50 mt-4">
               </div>
               <h4 class="font18 regular black mb-3">Ownership of Site Agreement to Terms of Use</h4>
               <p>{!! $pageInfo->Description !!}</p>
            </div>
      </div>
   </section>
</div>
@endsection