<?php
	if (empty($inputName)){
	    $inputName = 'airport_id';
	}
;?>
<div class="item">
	<!-- Input -->
	<span class="d-block text-gray-1  font-weight-normal mb-0 text-left">
		{{ $field['title'] ?? "" }}
	</span>
	<div class="mb-4">
		<div class="input-group border-bottom-1 py-2">
			<i class="flaticon-pin-1 d-flex align-items-center mr-2 text-primary font-weight-semi-bold"></i>
			<?php
			$airport_name = "";
			$list_json = [];
			
			// Get airports from session or database
			if ($field['title'] == 'From where') {
				if (session()->has('flight_from_airports')) {
					$airports = session()->get('flight_from_airports');
				} else {
					$airports = DB::table('bravo_flight')
						->select('bravo_airport.id', 'bravo_airport.name', 'bravo_airport.code', 'bravo_airport.address')
						->join('bravo_airport', 'bravo_airport.id', '=', 'bravo_flight.airport_from')
						->where('bravo_flight.status', 'publish')
						->groupBy('bravo_airport.id')
						->orderBy('bravo_airport.name', 'ASC')
						->get();
					session()->put('flight_from_airports', $airports);
				}
			} elseif ($field['title'] == 'To where') {
				if (session()->has('flight_to_airports')) {
					$airports = session()->get('flight_to_airports');
				} else {
					$airports = DB::table('bravo_flight')
						->select('bravo_airport.id', 'bravo_airport.name', 'bravo_airport.code', 'bravo_airport.address')
						->join('bravo_airport', 'bravo_airport.id', '=', 'bravo_flight.airport_to')
						->where('bravo_flight.status', 'publish')
						->groupBy('bravo_airport.id')
						->orderBy('bravo_airport.name', 'ASC')
						->get();
					session()->put('flight_to_airports', $airports);
				}
			}

			// Build the list for dropdown
			foreach ($airports as $airport) {
				if (Request::query($inputName) == $airport->id) {
					$airport_name = $airport->name . ' (' . $airport->code . ')';
					// Store airport name in session for display
					if ($field['title'] == 'From where') {
						session()->put('flight_from_airport_name', $airport_name);
					} else {
						session()->put('flight_to_airport_name', $airport_name);
					}
				}
				$list_json[] = [
					'id' => $airport->id,
					'title' => $airport->name . ' (' . $airport->code . ') - ' . $airport->address
				];
			}
			?>
			<div class="smart-search border-0 p-0 form-control height-40 bg-transparent">
				<input type="text" class="smart-search-airport parent_text font-weight-bold font-size-16 shadow-none hero-form font-weight-bold border-0 p-0" 
					{{ ( empty(setting_item("flight_location_search_style")) or setting_item("flight_location_search_style") == "normal" ) ? "readonly" : ""  }} 
					placeholder="{{__("Select Airport")}}" 
					value="{{ $airport_name }}" 
					data-onLoad="{{__("Loading...")}}"
					data-default="{{ json_encode($list_json) }}">
				<input type="hidden" class="child_id" name="{{$inputName}}" value="{{Request::query($inputName)}}">
			</div>
		</div>
	</div>
	<!-- End Input -->
</div> 