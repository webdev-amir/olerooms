<section class="rooms_available">
  <div class="container">
    <div class="rooms_available_top">
      <div class="title_cus">
        <span>OLE Rooms </span>
        <p class="mb-0">Available On</p>
      </div>
      <div class="cityList">
        <div class="bravo-list-hotel layout_carousel m-0">
          <div class="list-item">
            <div class="owl-carousel owl-loaded owl-drag">
              <div class="owl-stage-outer">
                <div class="city-carousel owl-item active" style="width: auto;">
                  @if(isset($cities))
                  @foreach($cities as $key => $city)
                  @if($city->status == 1)
                  <a href="{{route('search')}}?city_id={{$city->id}}">
                    <div class="item-loop pb-0 shadow-none mb-0 fleft" style="width: auto;margin-right: 30px;">
                      <div class="cityBox">
                        <figure><img src="{{$city->ThumbPicturePath}}" alt="{{$city->name}}" onerror="this.src='{{URL::to('img/nocity.svg')}}'" /></figure>
                        <h4>{{$city->name}}</h4>
                      </div>
                    </div>
                  </a>
                  @else
                  <div class="item-loop pb-0 shadow-none mb-0 fleft" style="width: auto;margin-right: 30px;">
                    <div class="cityBox commingsoon">
                      <figure><span>Coming Soon</span><img src="{{$city->ThumbPicturePath}}" alt="{{$city->name}}" onerror="this.src='{{URL::to('img/nocity.svg')}}'" /></figure>
                      <h4>{{$city->name}}</h4>
                    </div>
                  </div>
                  @endif
                  @endforeach
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>