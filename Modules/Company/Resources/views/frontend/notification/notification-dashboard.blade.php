<div class="dropdown dropdown-notifications float-right" style="min-width: 0">
   <a data-toggle="dropdown" class="user-dropdown d-flex align-items-center" aria-haspopup="true" aria-expanded="false"> <i class="ri-notification-fill"></i><span class="badge badge-danger notification-icon">{{$notoficationCnt}}</span> </a>
   <div class="dropdown-menu overflow-auto notify-items dropdown-container dropdown-menu-right dropdown-large" aria-labelledby="dropdownMenuButton">
      <div class="dropdown-toolbar">
         <div class="dropdown-toolbar-actions"> <a href="#" class="markAllAsRead">Mark all as read</a> </div>
         <h3 class="dropdown-toolbar-title">Notifications (<span class="notif-count">{{$notoficationCnt}}</span>)</h3> </div>
      <ul class="dropdown-list-items p-0"> 
      @if(isset($notoficationData) && (count($notoficationData)>0))
                @foreach($notoficationData as $notification)
                @php 
                    $data = json_decode($notification['data'],true);
                    $name = $data['notification']['name'];
                    $avatar = $notification->getUserInfo->PicturePath;
                    $style = empty($notification['read_at']) ? 'active' : '';
                @endphp
                    <li class="notification {{$style}}">
                        <a class="markAsRead" data-id="{{$notification['id']}}" href="{{url($data['notification']['link'])}}">
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
      <div class="dropdown-footer text-center" style="padding: 9.6px 12px; background-color:#0077c8"> <a href="{{url('notifications')}}">View More</a> </div>
   </div>
</div>