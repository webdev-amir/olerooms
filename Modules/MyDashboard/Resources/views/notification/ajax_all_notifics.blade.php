<ul>
    @forelse($notifications as $key => $notify)
    @php
    // $image = isset(json_decode($notify->data)->notification->image) ? json_decode($notify->data)->notification->image : '';
    $image = $notify->getCreateUserInfo?$notify->getCreateUserInfo->PicturePath:'';
    $property_url = isset(json_decode($notify->data)->notification->link) ? json_decode($notify->data)->notification->link : '' ;
    @endphp
    <li>
        <div class="row notification-align" data-id="{{$notify->id}}">
            <div class="col-md-2">
                <img src="{{ $image }}" class="profile-img head_dp" alt="User Image" onerror="this.src='{{onerrorProImage()}}'">
            </div>
            <div class="col-md-10">
                @if($property_url !='')
                <a href="{{$property_url}}" style="text-decoration: none;">
                    <p>
                        {!! isset(json_decode($notify->data)->notification->content) && json_decode($notify->data)->notification->content? json_decode($notify->data)->notification->content : json_decode($notify->data)->notification->message !!}

                    </p>
                    <p class="time-notification">{{$notify->created_date}}</p>
                </a>
                @else
                <p>
                    {!! isset(json_decode($notify->data)->notification->content) && json_decode($notify->data)->notification->content? json_decode($notify->data)->notification->content : json_decode($notify->data)->notification->message !!}

                </p>
                <p class="time-notification">{{$notify->created_date}}</p>
                @endif
            </div>
        </div>
    </li>
    @empty
    <div class="col-lg-12 noRecord wishlistnoreocrd"> No Notification Found</div>
    @endforelse
</ul>
<div class="custom_pagination frontpaginate mT30 pull-right">
    {!! $notifications->links('front_dash_pagination') !!}
</div>