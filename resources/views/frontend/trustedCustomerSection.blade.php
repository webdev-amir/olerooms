@if(count($trusted_customers)>0)
<section class="customer_trust bravo-list-hotel bravo-client-feedback bg-transparent">
  <div class="container">
    <div class="title text-center">Trusted by <span class="green_text"> 10,000+ Customers </span></div>
  </div>
  <div class="list-item owl-carousel p-0">
    @foreach($trusted_customers as $customer)
    <div class="item">
      <div class="feedbackouter d-flex">
        <figure style="background:url('{{$customer->picture_path}}'),url('{{onerrorProImage()}}'); ;"></figure>
        <div class="content_wrap">
          <div class="desc mb-4">
            <i class="icofont-quote-right top_ic"></i>
            {!! $customer->description!!}
            <i class="icofont-quote-right"></i>
          </div>

          <div class="clienttitle">{{ucfirst($customer->name)}}</div>
          <div class="sub_title">{{ucfirst($customer->designation)}}</div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>
@endif