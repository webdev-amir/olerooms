@extends('layouts.dashboard_master')
@section('title', "Update Password".trans('menu.pipe')." " .app_name())
@section('content')
<div class="dashboardrightPanel">
   <update-password-component 
      auth-userid="{{ Auth::user()->id }}" 
      action-route="{{route('dashboard.updatePassword')}}"
   > 
</div>
@endsection
