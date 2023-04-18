<ul class="profile-section align-items-center">
   <li>
      <img src="{{Auth::user()->ThumbPicturePath}}" class="profile-img head_dp" onerror="this.src='{{onerrorProImage()}}'">
   </li>
   <li class="username_dash">
      <h6>{{Auth::user()->FullName}}</h6>
      <span class="username_dash_mail">{{Auth::user()->email}}</span>
   </li>
</ul>