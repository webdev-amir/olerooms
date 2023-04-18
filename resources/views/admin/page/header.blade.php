<header class="main-header">
  <!-- Logo -->
  <a href="javascript:;" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->

    <span class="logo-mini"><img src="{{URL::to('img/minilogo.png')}}" class="adminheaderlog0" height="52" width="50" alt="{{ config('app.name') }}" /></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b><img src="{{URL::to('img/logo.png')}}" class="adminheaderlog0 biglogo" height="53" alt="{{ config('app.name') }}" /></b></span>

  </a>
  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
          <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!-- The user image in the navbar-->
            <img src="{{Auth::guard('admin')->user()->PicturePath}}" class="user-image" alt="User Image" onerror="this.src='{{onerrorProImage()}}'">
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs">{{ucwords(Auth::guard('admin')->user()->FullName)}}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header">
               <img src="{{Auth::guard('admin')->user()->PicturePath}}" class="img-circle" alt="User Image"  onerror="this.src='{{onerrorProImage()}}'">
              <p>
                {{ucwords(Auth::guard('admin')->user()->FullName)}} - {{ucwords(Auth::guard('admin')->user()->roles[0]->name)}}
                <small>Member since {{date('M Y', strtotime(Auth::guard('admin')->user()->created_at))}}</small>
              </p>
            </li>
            <!-- Menu Body -->
            <li class="user-body">
              <div class="row">
              </div>
              <!-- /.row -->
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{route('subadmin.show',Auth::user()->slug)}}" class="btn btn-default btn-flat">Profile</a>
                @endif
              </div>
              <div class="pull-right">
                  <a class="dropdown-item btn btn-default btn-flat" href="{{ route('admin.logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Sign out') }}
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                    </form>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>