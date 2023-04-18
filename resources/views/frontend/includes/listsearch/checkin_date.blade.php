@php
$current_date = date('Y-m-d');
$check_in_date = request()->get('check_in_date')?request()->get('check_in_date'):$current_date;
@endphp
<div class="form-group">
	<div class="form-content">
		<div class="form-date-search smart-search">
			<div class="date-wrapper">
				<div class="check-in-wrapper">
					<div class="render check-in-render cursor-pointer">
						{{date('d/m/Y',strtotime($check_in_date))}}
						<i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>
					</div>
				</div>
			</div>
			<input type="hidden" class="check-in-input" value="{{$check_in_date}}" name="check_in_date" />
		</div>
	</div>
</div>