<div class="table-responsive customtable_responsive br30">
   <table class="table tableDesign m-0">
      <thead>
         <tr>
            <th scope="col">Plan Name</th>
            <th scope="col">Amount</th>
            <th scope="col">Transaction ID</th>
            <th scope="col">By</th>
            <th scope="col">Purchase Date</th>
            <th scope="col">Plan Start Date</th>
            <th scope="col">Expiry Date</th>
            <th scope="col">Status & Date</th>

         </tr>
      </thead>
      <tbody>
         @if(count($payments)>0)
         @foreach($payments as $list)
         <tr>
            <th scope="row">@if($list->order) {{$list->order->plan->title}} @else N/A @endif</th>
            <td>{{numberformatWithCurrency($list->amount)}}</td>
            <td>{{$list->TranID}}</td>
            <td>{{$list->PaidByText}}</td>
            <td>{{$list->payplan->created_at->format(config('custom.default_date_time_formate'))}}</td>
            <td>{{$list->payplan->created_at->format(config('custom.default_date_time_formate'))}}</td>
            <td>@if($list->payplan) {{$list->payplan->expired_at->format(config('custom.default_date_time_formate'))}} @else N/A @endif</td>
            <td>
               <span class="text-success d-block @if($list->payplan) {{$list->payplan->UserPlanCurrentStatus}} @endif">{{$list->payplan->UserPlanCurrentStatus}}</span>
               @if($list->payplan)
               {{$list->payplan->UserPlanCurrentStatusWithDate}}
               @endif
            </td>
         </tr>
         @endforeach
         @else
         <tr class="text-center">
            <td class="noRecord" colspan="10"> No record found </td>
         </tr>
         @endif
      </tbody>
   </table>
</div>
<div class="custom_pagination frontpaginate mT30 pull-right">
   {!! $payments->links('front_dash_pagination') !!}
</div>