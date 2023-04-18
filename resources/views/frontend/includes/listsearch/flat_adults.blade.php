@php
$adults = request()->get('adults')?request()->get('adults'):'1';
$children = request()->get('children')?request()->get('children'):'0';
$bhk_id = request()->get('bhk_id')?request()->get('bhk_id'):'1';
if($bhk_id)
$maxval = intval($bhk_id*2);
else
$maxval = 0;

@endphp

<div class="form-select-guests select-flat-adults">
	<div class="form-group">
		<div class="form-content dropdown-toggle pr-0 pl-0" data-toggle="dropdown">
			<div class="smart-search">
				<div class="wrapper-more">
					<div class="render flat_adult_render">
						<span class="adults"><span class="one" data-flatadulthtml=":count Adults">{{$adults}} Adult</span>
							<span class="d-none multi" data-html=":count Adults">{{$adults}} Adults</span></span>
						-
						<span class="children">
							<span class="one" data-html=":count Child">{{$children}} Child</span>
							<span class="multi d-none" data-html=":count Children">{{$children}} Children</span>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="dropdown-menu select-guests-dropdown select-flat-adults-dropdown">
			<div class="dropdown-item-row">
				<div class="label">Adults</div>
				<div class="val">
					<span class="btn-minus" data-input="adults"><i class="icon ion-md-remove"></i></span>
					<span class="count-display"><input type="text" class="numberonly" readonly maxlength="2" name="adults" id="flat_adults" value="{{$adults}}" min="1" max="{{$maxval}}" /></span>
					<span class="btn-add" data-input="adults"><i class="icon ion-ios-add"></i></span>
				</div>
			</div>
			<div class="dropdown-item-row">
				<div class="label">Children</div>
				<div class="val">
					<span class="btn-minus" data-input="children"><i class="icon ion-md-remove"></i></span>
					<span class="count-display">
						<input type="text" class="numberonly" maxlength="2" readonly name="children" id="flat_children" value="{{$children}}" min="0" max="{{$maxval}}" />
					</span>
					<span class="btn-add" data-input="children"><i class="icon ion-ios-add"></i></span>
				</div>
			</div>
		</div>
	</div>
</div>