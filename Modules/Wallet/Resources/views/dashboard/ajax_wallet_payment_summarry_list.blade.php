<div class="table-responsive">
    <table class="table m-0">
        <thead class="text-center"> 
            <tr>
                <th style="width:30%;" scope="col" class="text-left pl-0">@lang('wallet::menu.sidebar.form.transactions')</th>
                <th style="width:30%;" scope="col">@lang('wallet::menu.sidebar.form.date')</th>
                <th style="width:30%;" scope="col">@lang('wallet::menu.sidebar.form.amount')</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($records) && count($records)>0)
                @foreach($records as $list)
                <tr class="text-center">
                    <td class="text-left pl-0">@if($list->description) {!! $list->description !!} @else ------ @endif </td>
                    <td>{{$list->created_at->format(Config::get('custom.default_date_formate'))}} </td>
                    <td class="{{$list->type}}-color">
                       {{ $list->WalletAmountWithSign }} 
                    </td>
                </tr>
                @endforeach
            @else
            <tr class="text-center">
                <td colspan="4">@lang('menu.no_record_found')</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="pull-right">
{!! $records->appends(request()->query())->links('front_dash_pagination') !!}
</div>