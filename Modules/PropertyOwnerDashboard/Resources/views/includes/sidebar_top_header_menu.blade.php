<ul class="profile-section align-items-center">
   <li>
      <img src="{{Auth::user()->ThumbPicturePath}}" class="profile-img head_dp" onerror="this.src='{{onerrorProImage()}}'"> 
   </li>
   <li class="username_dash">
      <h6>{{Auth::user()->FullName}}</h6>
      {{Auth::user()->email}}
   </li>
</ul>