<table class="table table-bordered table-hover table-striped dataTable no-footer"  id="data_filter">
    <thead> 
        <tr>
            <th>@lang('menu.sn')</th>
            <th>@lang($model.'::menu.sidebar.form.username')</th>
            <th>@lang($model.'::menu.sidebar.form.amount')</th>
            <th>@lang($model.'::menu.sidebar.form.status')</th>
            <th>@lang($model.'::menu.sidebar.form.comments')</th>
            <th>@lang($model.'::menu.sidebar.form.transection-id')</th>
            <th>@lang($model.'::menu.sidebar.form.redeem_date')</th>
            <th>@lang($model.'::menu.sidebar.form.rejected_date')</th>
            <th>@lang($model.'::menu.sidebar.form.request_date')</th>
            <th>@lang($model.'::menu.sidebar.form.action')</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($records) && count($records)>0)
            @foreach($records as  $key => $list)
            <tr>
                <td>{{ $key+ $records->firstItem() }}</td>
                <td>{{ ucfirst($list->user->FullName) }}</td>
                <td>{{ numberformatWithCurrency($list->amount,2) }} </td>
                <td id="chngTextStatus{{$list->id}}">{{ucfirst($list->status)}} </td>
                <td id="comments{{$list->id}}">@if($list->comments) {{ucfirst($list->comments)}} @else --- @endif</td>
                <td id="transactionid{{$list->id}}">@if($list->transactionid) {{ucfirst($list->transactionid)}} @else --- @endif</td>
                <td id="completedDate{{$list->id}}">@if($list->completed_date) {{$list->completed_date->format(\Config::get('custom.default_date_formate'))}} @else --- @endif </td>
                <td id="rejectedDate{{$list->id}}">@if($list->rejected_date) {{$list->rejected_date->format(\Config::get('custom.default_date_formate'))}} @else --- @endif </td>
                <td>{{$list->created_at->format(\Config::get('custom.default_date_formate'))}} </td>
                <td>
                    @if(strtoupper($list->status) != 'PENDING')
                    <span class="label btext-{{$list->status}}">
                         {{strtoupper($list->status)}}
                     </span>
                    @else
                    <span id="chngStatus{{$list->id}}">
                        {{ Form::select('status', Config::get('custom.redeem_credit_request_status'), $list->status, ['class' => 'form-control','onChange'=>'statusAction(this);','data-default'=>$list->status,'data-id'=>$list->id,'data-action'=>route('redeemRequest.getConfirmBox')]) }}
                    </span>
                    @endif
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
@if(isset($records))
<div class="pull-right">
{!! $records->links('pagination') !!}
</div>
@endif