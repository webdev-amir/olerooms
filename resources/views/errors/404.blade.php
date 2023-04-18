@extends('layouts.app')
@section('content')
<div class="errorWrap">
   <div class="errorPage">
     <figure></figure>
     <h4 class="black semibold">Page Not Found</h4>
     <p class="text-center font18 grey">
       Looks like the page you are trying to visit doesn't exist. Please
       check the URl and try again.
     </p>
     <div class="mT35">
       <a href="{{URL::to('/')}}" class="btn customBtn btn-success minw-184">
         Go to Home
       </a>
     </div>
   </div>
</div>
@endsection
