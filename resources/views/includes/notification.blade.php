<li class="dropdown-notifications dropdown p-0">
 <a href="#" data-toggle="dropdown" class="is_login" aria-expanded="false">
 <i class="fa fa-bell mr-2"></i>
 <span class="badge badge-danger notification-icon">{{$notoficationCnt}}</span>
 <i class="fa fa-angle-down"></i>
 </a>
 <ul class="dropdown-menu overflow-auto notify-items dropdown-container dropdown-menu-right dropdown-large" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(30px, 18px, 0px);">
    <div class="dropdown-toolbar">
       <div class="dropdown-toolbar-actions">
          <a href="#" class="markAllAsRead">Mark all as read</a>
       </div>
       <h3 class="dropdown-toolbar-title">Notifications (<span class="notif-count">{{$notoficationCnt}}</span>)</h3>
    </div>
    <ul class="dropdown-list-items p-0">
    @if(isset($notoficationData) && (count($notoficationData)>0))
                @foreach($notoficationData as $notification)
                @php 
                    $data = json_decode($notification['data'],true);
                    $name = $data['notification']['name'];
                    $avatar=$notification->getUserInfo->PicturePath;
                    $style = empty($notification['read_at']) ? 'active' : '';
                @endphp
                    <li class="notification {{$style}}">
                        <a class="markAsRead  p-0" data-id="{{$notification['id']}}" href="{{url($data['notification']['link'])}}">
                            <div class="media">
                                <div class="media-left">
                                    <div class="media-object">
                                            @if($avatar)
                                                <img class="image-responsive" src="{{$avatar}}" alt="{{ucfirst($name[0])}}">
                                            @else
                                                <span class="avatar-text">{{ucfirst($name[0])}}</span>
                                            @endif
                                        </div>
                                    </div>
                                <div class="media-body">
                                    {{$data['notification']['message']}}
                                    <div class="notification-meta">
                                            <small class="timestamp">{{format_interval($notification->created_at)}}</small>
                                        </div>
                                </div>
                            </div>
                        </a>
                        </li>
                @endforeach
        @endif    
    </ul>
    <div class="dropdown-footer text-center">
       <a href="{{url('notifications')}}">View More</a>
    </div>
 </ul>
</li>