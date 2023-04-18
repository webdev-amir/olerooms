@extends('layouts.app')
@section('title', " ".$pageInfo->name." ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content about-section-com" id="aboutPage">
   <section class="innerbanner about-olerooms">
      <div class="container text-center">
         <h1>{!! $pageInfo->banner_heading !!}</h1>
         <h3>We are India's Most Luxurious Place For Every Dream You Chase</h3>
      </div>
   </section>
   <section class="content-admin   about-setup">
      <div class="container">
         <div class="title heading-title mb-2 ab_top_tit">
         </div>
         <div class="native-content about-content">
            <p>{!! $pageInfo->Description !!}</p>
         </div>
      </div>
   </section>
   <section class="simplified about-setup p-0">
      <div class="container">
         <div class="about-simple">
            <div class="title heading-title mb-2">
               Our Mission
            </div>
            <div class="native-content about-content text-center">
               <p>Convert the way people staying away from your location by providing the better facilities by easy ways!</p>
               <i class="icofont-quote-right"></i>
            </div>
         </div>
      </div>
   </section>
   <section class="simplified about-setup pT50">
      <div class="container">
         <div class="about-tab">
            <div class="title heading-title mb-2">
               Our Team
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12  fadeInUp animated1 selected">
               <ul class="nav nav-tabs authTab border-0 mb-3" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                     <button class="nav-link active" id="Advisory-tab" data-toggle="tab" data-target="#Advisory" type="button" role="tab" aria-controls="Advisory" aria-selected="true">Advisory Members</button>
                  </li>
                  <li class="nav-item" role="presentation">
                     <button class="nav-link" id="Core-tab" data-toggle="tab" data-target="#Core" type="button" role="tab" aria-controls="Core" aria-selected="false">Core Team</button>
                  </li>
               </ul>
               <div class="tab-content" id="myTabContent">
                  <div class="tab-pane show active" id="Advisory" role="tabpanel" aria-labelledby="Advisory-tab">
                     <div class="row">
                        @forelse($teams_exe as $exe_val)
                        <div class="col-3">
                           <div class="hover-img">
                              <img src="{{$exe_val->PicturePath}}" alt="Avatar" class="image tab-new" style="width:100%">
                              <div class="middle">
                                 <div class="text">
                                    <h6>{{$exe_val->name}}</h6>
                                    <p>{!! $exe_val->designation !!}</p>
                                    @if($exe_val->linkedin_url)
                                    <a href="{!! $exe_val->linkedin_url !!}" title="Linkedin Profile" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.335 18.339H15.67v-4.177c0-.996-.02-2.278-1.39-2.278-1.389 0-1.601 1.084-1.601 2.205v4.25h-2.666V9.75h2.56v1.17h.035c.358-.674 1.228-1.387 2.528-1.387 2.7 0 3.2 1.778 3.2 4.091v4.715zM7.003 8.575a1.546 1.546 0 0 1-1.548-1.549 1.548 1.548 0 1 1 1.547 1.549zm1.336 9.764H5.666V9.75H8.34v8.589zM19.67 3H4.329C3.593 3 3 3.58 3 4.297v15.406C3 20.42 3.594 21 4.328 21h15.338C20.4 21 21 20.42 21 19.703V4.297C21 3.58 20.4 3 19.666 3h.003z" fill="rgba(47,204,113,1)"/></svg></a>
                                    @endif
                                 </div>
                              </div>
                           </div>
                        </div>
                        @empty
                        No Records Found!
                        @endforelse
                     </div>
                  </div>
                  <div class="tab-pane" id="Core" role="tabpanel" aria-labelledby="Core-tab">
                     <div class="row">
                        @forelse($teams_core as $core_val)
                        <div class="col-3">
                           <div class="hover-img">
                              <img src="{{$core_val->PicturePath}}" alt="Avatar" class="image tab-new" style="width:100%">
                              <div class="middle">
                                 <div class="text">
                                    <h6>{{$core_val->name}}</h6>
                                    <p>{!! $core_val->designation !!}</p>
                                    @if($core_val->linkedin_url)
                                    <a href="{!! $core_val->linkedin_url !!}" title="Linkedin Profile" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.335 18.339H15.67v-4.177c0-.996-.02-2.278-1.39-2.278-1.389 0-1.601 1.084-1.601 2.205v4.25h-2.666V9.75h2.56v1.17h.035c.358-.674 1.228-1.387 2.528-1.387 2.7 0 3.2 1.778 3.2 4.091v4.715zM7.003 8.575a1.546 1.546 0 0 1-1.548-1.549 1.548 1.548 0 1 1 1.547 1.549zm1.336 9.764H5.666V9.75H8.34v8.589zM19.67 3H4.329C3.593 3 3 3.58 3 4.297v15.406C3 20.42 3.594 21 4.328 21h15.338C20.4 21 21 20.42 21 19.703V4.297C21 3.58 20.4 3 19.666 3h.003z" fill="rgba(47,204,113,1)"/></svg></a>
                                    @endif
                                 </div>
                              </div>
                           </div>
                        </div>
                        @empty
                        No Records Found!
                        @endforelse
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="pT50">
      <div class="about-section">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <div class="middle-section-about">
                     <h4>Become an Associate </h4>
                     <p>Do you have a best product House, Flats, Hostel or any Rent house solutions that needs an audience? We at OLE Rooms believe in joining hands with the right associate partners in order to provide high-quality yet affordable solutions to our customers. Join our hands and associate with us!</p>
                     <div class="newsdata button mt-2">
                        @auth
                        @if(auth()->user()->hasRole('vendor'))
                        <a href="{{route('vendor.myproperty')}}" class="btn btn-success font16 regular mr-2 bR12">List My Property</a>
                        @else
                        <a href="{{route('propertyowner.home')}}" class="btn btn-success font16 regular mr-2 bR12">List My Property</a>
                        @endif
                        @else
                        <a href="{{route('propertyowner.home')}}" class="btn btn-success font16 regular mr-2 bR12">List My Property</a>
                        @endauth
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="simplified  about-setup padding50">
      <div class="container">
         <div class="row crteria">
            <div class="col-3 founder-image">
               <img src="{{URL::to('images/founder-image.png')}}" alt="Devendra Sharma" title="Devendra Sharma" class="abt-img">
            </div>
            <div class="col-9 crte-normal">
               <div class="about-newtrm">
                  <h4>Founder Message </h4>
                  <p>A dream of many ideas coming together, with great passion and an opportunity ceased is how
                     OLE ROOMS was born. Our aim at building OLE ROOMS was to create an entity independent in
                     all aspects of rooms.</p>
                  <p>We have built our divisions in order to create the perfect utilization of our available resources and
                     eliminate needs of sub-contracting, which in turn saves great deals of time in the servicing
                     sector</p>
                  <p>We have been developing our divisions with immense speed and take great interest in the
                     interests of our consumers and understanding their problems. We have noticed major changes in
                     our rooms inventory, division expansions and an increase in employee numbers. These changes
                     prove a positive motion which we continue to practice and achieve.</p>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="brandSlider self-track ">
      <div class="title heading-title mb-3 text-center">
         Our B2B Partners
      </div>
      @include('frontend.brandSlider')
   </section>
   @include('includes.counter-section')
   @endsection
</div>