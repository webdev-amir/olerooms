@extends('layouts.app')
@section('title',ucfirst($pageInfo->name)." ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content">
      <section class="innerbanner">
          <div class="container text-center">
              <span class="subheading d-block">Guest House, PG, Hostel, Flats,</span>
              <h1>Home Rooms Available on Rent!</h1>
          </div>
      </section>
      <section class="bravo-list-hotel padding50  m-0 pb-0">
        <div class="container">
            <div class="static_content mB50">
              <div class="title44">
                <h2> Frequently Asked Questions </h2>
                <hr class="mB40 mt-4">
              </div>
              <div class="bravo_detail_car mt-0 fadeInUp animated1 selected">
                <div class="bravo_content mt-0 pt-0">
                  <div class="g-faq border-top-0 border-bottom-0 pt-0">
                    @foreach($faqs as $fkey => $faq)
                      <div class="item">
                        <div class="header" data-toggle="collapse" href="#collapse{!! $faq->id !!}" role="button" aria-expanded="false" aria-controls="collapse{!! $faq->id !!}">
                          <h5>{!! $faq->question !!}</h5>
                          <span class="arrow"><i class="fa fa-angle-down"></i></span>
                        </div>
                        <div class="body faqcontent" class="collapse" id="collapse{!! $faq->id !!}">
                          {!! $faq->answer !!}
                        </div>
                      </div>
                     @endforeach
                  </div>
                </div>
             </div>
            </div>
        </div>
      </section>
</div>
@endsection