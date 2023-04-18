@php
$guests = request()->get('guests')?request()->get('guests'):'1';
@endphp
<div class="form-select-guests select-guests">
	<div class="form-group">
		<div class="form-content dropdown-toggle pr-0 pl-0" data-toggle="dropdown">
			<div class="smart-search">
				<div class="wrapper-more">
					<div class="render">
						<span class="guests"><span class="one" data-gusethtml=":count Guests">{{$guests}} Guests</span>
							<span class="d-none multi" data-html=":count Guests">{{$guests}} Guests</span></span>

					</div>
				</div>
			</div>
		</div>
		<div class="dropdown-menu select-guests-dropdown guests-dropdown">
			<div class="dropdown-item-row">
				<div class="label">Guests</div>
				<div class="val">
					<span class="btn-minus" data-input="guests"><i class="icon ion-md-remove"></i></span>
					<span class="count-display"><input type="text" readonly class="numberonly" name="guests" value="{{$guests}}" min="1" maxlength="2"  max="10" readonly  /></span>
					<span class="btn-add" data-input="guests"><i class="icon ion-ios-add"></i></span>
				</div>
			</div>
		</div>
	</div>
</div>