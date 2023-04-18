@php
$current_date = date('Y-m-d');
$check_out_date = request()->get('check_out_date')?request()->get('check_out_date'):$current_date;
@endphp
<div class="form-group">
	<div class="form-content">
		<div class="form-date-search smart-search">
			<div class="date-wrapper">
				<div class="check-in-wrapper">
					<div class="render check-out-render cursor-pointer">
						{{date('d/m/Y',strtotime($check_out_date))}}
						<i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>
					</div>
				</div>
			</div>
			<input type="hidden" class="check-out-input" value="{{$check_out_date}}" name="check_out_date" />
		</div>
	</div>
</div>