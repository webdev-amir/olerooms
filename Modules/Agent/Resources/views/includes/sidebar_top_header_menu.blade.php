<ul class="profile-section align-items-center">
   @if(auth()->user()->agent_code)
   <li class=" depth-0 copycodeblock">
     <p class="mb-0 font18 d-flex align-items-center mycoupon_code"> My Booking Code:<span class="green ml-2">#<span id="cpcode">{{strtoupper(auth()->user()->agent_code)}}</span></span>
      <a href="javascript:;" class="copycode" data-cid="cpcode"><i class="ri-file-copy-line ml-2 mr-2"></i></a>
      </p>                       
   </li>
   @endif
   <li>
      <img src="{{Auth::user()->ThumbPicturePath}}" class="profile-img head_dp" onerror="this.src='{{onerrorProImage()}}'"> 
   </li>
   <li>
      <h6>{{Auth::user()->FullName}}</h6>
      {{Auth::user()->email}}
   </li>
</ul> 