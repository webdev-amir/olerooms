	<div class="form-group">
		<div class="form-content">
			<div class="form-date-search">
				<div class="date-wrapper" title="Check out">
					<div class="check-in-wrapper">
						<div class="render check-out-render cursor-pointer">
							{{now()->addDay()->format('d/m/Y')}}
							<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa" title="Check out"></i>
						</div>
					</div>
				</div>
				<input type="hidden" class="check-out-input" value="{{now()->addDay()->format('m/d/Y')}}" name="check_out_date" />
			</div>
		</div>
	</div>