@extends('propertyownerdashboard::layouts.add_property_dashboard_master')
@section('title', "Create Property ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_wrap doNotReverseThisPage" id="manage_property">
   <div class="stepRow" id="doNotReverseThisPage">
      @if($sessionData && isset($sessionData->step_1))
      @php $formData = $sessionData->step_1; @endphp
      @else
      @php
      $formData = [];
      @endphp
      @endif
      <?php
      $titleContent = 'Basic Information';
      if (isset($sessionAllData->current_step)) {
         switch ($sessionAllData->current_step) {
            case "1": // Hostel/PG
               $titleContent = 'Basic Information';
               break;
            case "2": //Flat
               $titleContent = 'Property Details';
               break;
            case "3": //Guest/Hotel
               $titleContent = 'Property Images';
               break;
            case "4": //Hostel/PG(One Day)
               $titleContent = 'Payment Details';
               break;
            default:
               $titleContent = 'Basic Information';
         }
      }

      ?>

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
                           <!-- <p class="grey font12 regular">It is a long established fact that a reader will be distracted by the readable content of a page when looking.</p> -->
                           <p class="grey font12 regular content_steps">{{isset($sessionAllData->current_step) ?  $titleContent : 'Basic Information'}}</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="step_rightPanel">
         @include('property::frontend.manageProperty.steps.create.step_1')
         @include('property::frontend.manageProperty.steps.create.step_2')
         @include('property::frontend.manageProperty.steps.create.step_3')
         @include('property::frontend.manageProperty.steps.create.step_4')
      </div>
   </div>
</div>

@endsection