@extends('layouts.app')
@section('title', " ".$pageInfo->name." ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content">
   <section class="innerbanner">
         <div class="container text-center">
            <span class="subheading d-block">Come Work with Us</span>
            <h1>{!! $pageInfo->banner_heading !!}</h1>
         </div>
   </section>
   <section class="bravo-list-hotel padding50 m-0">
      <div class="container">
            <div class="static_content mB50 fadeInUp animated1 selected lifeat">
               <h1>{!! $pageInfo->name !!} </h1>
               <p>{!! $pageInfo->Description !!}</p>
            </div>
      </div>
   </section>
</div>
@endsection