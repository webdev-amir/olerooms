	<div class="form-group">
		<div class="form-content">
			<div class="form-date-search">
				<div class="date-wrapper" title="Check In">
					<div class="check-in-wrapper">
						<div class="render check-in-render cursor-pointer">
							{{now()->format('d/m/Y')}}
							<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa" title="Check In"></i>
						</div>
					</div>
				</div>
				<input type="hidden" class="check-in-input" value="{{now()->format('m/d/Y')}}" name="check_in_date" />
			</div>
		</div>
	</div> 