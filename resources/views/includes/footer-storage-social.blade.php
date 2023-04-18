<div class="col-lg-3 col-md-6">
   <div class="nav-footer">
      <div class="context">
         <ul>
            @if(count($storageTyData)>0)
            @foreach($storageTyData as $list)
            <li><a href="{{route('space')}}?storage_id={{$list->id}}">{{$list->name}}</a></li>
            @endforeach
            @endif
         </ul>
      </div>
   </div>
</div>
<div class="col-lg-3 col-md-6">
   <div class="nav-footer">
      <div class="context">
         <div class="contact">
            <div class="c-title">
               <figure><img src="{{URL::to('img/phone-white.svg')}}" alt="Phone"></figure>
            </div>
            <div class="sub"> {{$socialLinkData['admincontact']['value']}} </div>
         </div>
         <div class="contact">
            <div class="c-title">
               <figure><img src="{{URL::to('img/email-white.svg')}}" alt="Email"></figure>
            </div>
            <div class="sub"><a href="mailto:{{$socialLinkData['adminemail']['value']}}" class="__cf_email__">
                  {{$socialLinkData['adminemail']['value']}}
               </a>
            </div>
         </div>
         <div class="contact">
            <div class="sub social-footer">
               <a href="{{$socialLinkData['facebook']['value']}}">
                  <img src="{{URL::to('img/facebook-white.svg')}}" alt="Facebook" target="_blank">
               </a>
               <a href="{{$socialLinkData['instagram']['value']}}">
                  <img src="{{URL::to('img/instagram-white.svg')}}" alt="Instagram" target="_blank">
               </a>
               <a href="{{$socialLinkData['twitter']['value']}}">
                  <img src="{{URL::to('img/twitter-white.svg')}}" alt="Twitter" target="_blank">
               </a>
               <a href="{{$socialLinkData['pinterest']['value']}}">
                  <img src="{{URL::to('img/pinterest-white.svg')}}" alt="Pintrest" target="_blank">
               </a>
            </div>
         </div>
      </div>
   </div>
</div>