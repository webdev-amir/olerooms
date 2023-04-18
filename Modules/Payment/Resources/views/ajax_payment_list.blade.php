<table class="table table-bordered table-hover table-striped dataTable no-footer" id="data_filter">
    <thead>
        <tr>
            <th style="display:none"></th>
            <th>@lang('menu.sn')</th>
            <th>@lang($model.'::menu.sidebar.form.username')</th>
            <th>Ip Address</th>
            <th>@lang($model.'::menu.sidebar.form.transection-id')</th>
            <th>@lang($model.'::menu.sidebar.form.amount')</th>
            <th>@lang($model.'::menu.sidebar.form.status')</th>
            <th>@lang($model.'::menu.sidebar.form.transection_date')</th>
            <th>@lang($model.'::menu.sidebar.form.action')</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($records) && count($records)>0)
        @foreach($records as $key => $list)
        <tr>
            <td style="display:none">{{$key+1}}</td>
            <td>{{ $list->id }}</td>
            <td>@if($list->user) {{ ucfirst($list->user->FullName) }} @else N/A @endif</td>
            <td>@if($list->ip_address) {{ $list->ip_address }} @else N/A @endif</td>
            <td id="transactionid{{$list->id}}">@if($list->transaction_id) {{ucfirst($list->transaction_id)}} @else --- @endif</td>
            <td>{{ numberformatWithCurrency($list->amount,2) }} </td>
            <td id="chngTextStatus{{$list->id}}">
                <span class="label {{$list->status == 'captured'?'label-success':'label-danger'}}">{{ucfirst($list->status)}}</span>
            </td>
            <td>{{$list->FullTranDate}} </td>
            <td>
                <span class="margin-r-5"><a data-toggle="tooltip" class="" title="View" href="{{route('payment.show', [$list->slug])}}"><i class="fa fa-eye" aria-hidden="true"></i></a> </span>
            </td>
        </tr>
        @endforeach
        @else
        <tr class="text-center">
            <td colspan="9">No Payment Found</td>
        </tr>
        @endif
    </tbody>
</table>
@if(isset($records))
<div class="pull-right">
    {!! $records->links('pagination') !!}
</div>
@endif