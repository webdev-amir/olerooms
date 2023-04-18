@extends('propertyownerdashboard::layouts.master')
@section('content')
<div class="page-template-content" id="propertyownerlandingpage">
   <section class="heroBanner">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-sm-12 col-md-6">
               <div class="bannrContent">
                  <h1>Complete solution to Rent and <span class="d-block">Advertisement of your property </span></h1>
                  <p>Property ko kiraya per lagana aur bharna hua aur bhi easy,<br>
                     Fill your Hostel/PG/ FLATS made easy.<br>
                     Sirf aapke liye. OLE ROOMS baniye sirf 5 minutes mein.</p>
               </div>
            </div>
             @guest
               <div class="col-sm-12 col-md-6">
                  <div class="customForm fadeInUp animated1 selected">
                     @include('propertyownerdashboard::frontend.auth.register')
                  </div>
               </div>
             @else
               <div class="col-sm-12 col-md-6 afterownerRegistration">

               </div>
            @endif
         </div>
      </div>
   </section>
   <section class="numberBlock padding50 ">
      <div class="container">
         @include('includes.owner-counter-section')
      </div>
   </section>
   <section class="whyus_section bravo-list-tour padding50 fadeInUp animated3 delay2">
      <div class="rightabstract"></div>
      <div class="container">
         <div class="title heading-title text-center mb-5">
           Associate with us
            <p class="simplified-sub mt-2">Do you have a best product House, Flates, Hostel or any Rent house Solutions that needs an audience? We at OLE Rooms belive in joining hands with the right associate partners in order to provide high-quality yet affordable solutions to our customers. Join our hands and associate with us! </p>
         </div>
         <div class="row align-items-center associate-with-us">
            <div class="col-sm-12 col-md-12 col-lg-7 order2 ">
               <div class="title heading-title mb-5">
               </div>
               <div class="iconList">
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-check-circle"></i></figure>
                     <div class="content">
                        <p> Broker Free Rental Portal</p>
                        <span></span>
                     </div>
                  </div>
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-thumbs-o-up"></i></figure>
                     <div class="content">
                        <p> Verified Rental Property </p>
                        <span></span>
                     </div>
                  </div>
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-search-plus"></i></figure>
                     <div class="content">
                        <p>Hassle Free Search</p>
                        <span></span>
                     </div>
                  </div>
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-universal-access"></i></figure>
                     <div class="content">
                        <p>Time & Money Save For Your Future</p>
                        <span></span>
                     </div>
                  </div>
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-money"></i></figure>
                     <div class="content">
                        <p>Save Brokerage</p>
                        <span></span>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-5 order1">
               <figure class="mb-0 bgimage"></figure>
            </div>
         </div>
      </div>
   </section>
   <section class="whyus_section bravo-list-tour fadeInUp animated3 delay3">
      <div class="leftabstract"></div>
      <div class="container">
         <div class="row align-items-center">
            <div class="col-sm-12 col-md-12 col-lg-5 order1">
               <figure class="mb-0 bgimage" style="background-image: url({{URL::to('images/fea2.png')}});"></figure>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-7 order2">
               <div class="pl-4">
                  <div class="title heading-title mb-5">
                  </div>
                  <div class="iconList">
                     <div class="iconBox d-flex">
                        <figure class="mb-0 mr-4"><i class="fa fa-object-group"></i></figure>
                        <div class="content">
                           <p>Personal Assistance</p>
                           <span></span>
                        </div>
                     </div>
                     <div class="iconBox d-flex">
                        <figure class="mb-0 mr-4"><i class="fa fa-credit-card-alt"></i></figure>
                        <div class="content">
                           <p>Secure Payment Methods </p>
                           <span></span>
                        </div>
                     </div>
                     <div class="iconBox d-flex">
                        <figure class="mb-0 mr-4"><i class="fa fa-volume-control-phone"></i></figure>
                        <div class="content">
                           <p>24X7 Customer Support</p>
                           <span></span>
                        </div>
                     </div>
                     <div class="iconBox d-flex">
                        <figure class="mb-0 mr-4"><i class="fa fa-home"></i></figure>
                        <div class="content">
                           <p>Enjoy with Get Your dream property</p>
                           <span></span>
                        </div>
                     </div>
                     <div class="iconBox d-flex">
                        <figure class="mb-0 mr-4"><i class="fa fa-location-arrow"></i></figure>
                        <div class="content">
                           <p>Convenient Locations </p>
                           <span></span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="whyus_section bravo-list-tour padding50 fadeInUp animated3 delay4">
      <div class="rightabstract"></div>
      <div class="container">
         <div class="row align-items-center">
            <div class="col-sm-12 col-md-12 col-lg-7 order2">
               <div class="title heading-title mb-5">
               </div>
               <div class="iconList">
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-check-square-o"></i></figure>
                     <div class="content">
                        <p>Standardized Amenities</p>
                        <span></span>
                     </div>
                  </div>
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-percent"></i></figure>
                     <div class="content">
                        <p>Discount & Offers</p>
                        <span></span>
                     </div>
                  </div>
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-calendar-check-o"></i></figure>
                     <div class="content">
                        <p>Instant Booking </p>
                        <span></span>
                     </div>
                  </div>
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-user"></i></figure>
                     <div class="content">
                        <p>User Friendly</p>
                        <span></span>
                     </div>
                  </div>
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-dollar"></i></figure>
                     <div class="content">
                        <p>Unbeatable Price</p>
                        <span></span>
                     </div>
                  </div>
                  <div class="iconBox d-flex">
                     <figure class="mb-0 mr-4"><i class="fa fa-hand-peace-o"></i></figure>
                     <div class="content">
                        <p> Personal Assistance</p>
                        <span></span>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-5 order1">
               <figure class="mb-0 bgimage"></figure>
            </div>
         </div>
      </div>
   </section>
   <!-- Trusted Customer Section -->
   @if(isset($trustedCustomers))
      @include('propertyownerdashboard::includes.trusted_customer_section')
   @endif

   <!-- Partner Section -->
   @if(isset($partners))
	   @include('propertyownerdashboard::includes.business_partner_section')
   @endif
</div>
@endsection