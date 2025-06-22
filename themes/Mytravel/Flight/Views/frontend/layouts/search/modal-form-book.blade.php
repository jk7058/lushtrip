<!-- This page will be customized, already taken another backup -->
<style>
input {
  outline: 0;
  border-width: 0 0 2px;
  border-color: #00000038;
}
input:focus {
  border-color: #5191fa;
  outline: none;
}

span {
	cursor:pointer; 
}
.minus, .plus{
	background:#f2f2f2;
	border-radius:4px;
	padding:8px 5px 8px 5px;
	border:1px solid #ddd;
	display: inline-block;
	vertical-align: middle;
	text-align: center;
}
input {
	height: 34px;
    width: 90px;
    text-align: center;
    font-size: 26px;
    font-size: 16px;
}

input.calculation {
    border: none;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<div id="flightFormBookModal"  class="js-modal-window u-modal-window max-width-960" data-modal-type="ontarget" data-open-effect="fadeIn" data-close-effect="fadeOut" data-speed="500">
	<div class="card mx-4 mx-xl-0 mb-4 mb-md-0"  v-show="!onLoading">
		<button type="button" class="border-0 width-50 height-50 bg-primary flex-content-center position-absolute rounded-circle mt-n4 mr-n4 top-0 right-0" aria-label="Close" onclick="Custombox.modal.close();">
			<i aria-hidden="true" class="flaticon-close text-white font-size-14"></i>
		</button>
		<!-- Header -->
		<header class="card-header bg-light py-4 px-4">
		<div class="row align-items-center text-center">
				<div class="col-md-auto mb-4 mb-md-0">
					<div class="d-block d-lg-flex flex-horizontal-center">
						<img class="img-fluid mr-3 mb-3 mb-lg-0 max-width-10" src="" alt="Image-Description">
						<div class="font-size-14">{{$row->title}} | {{$row->code}}</div>
					</div>
				</div>
				<div class="col-md-auto mb-4 mb-md-0">
					<div class="mx-2 mx-xl-3 flex-content-center align-items-start d-block d-lg-flex">
						<div class="mr-lg-3 mb-1 mb-lg-0">
							<i class="flaticon-aeroplane font-size-30 text-primary"></i>
						</div>
						<div class="text-lg-left">
							<h6 class="font-weight-bold font-size-21 text-gray-5 mb-0">{{\Carbon\Carbon::parse($row->departure_time)->format("H:i A")}}</h6>
							<div class="font-size-14 text-gray-5">{{\Carbon\Carbon::parse($row->departure_time)->format("D M d")}}</div>
							<span class="font-size-14 text-gray-1">{{session()->get('flight_from_name')}}</span>
						</div>
					</div>
				</div>
				<div class="col-md-auto mb-4 mb-md-0">
					<div class="mx-2 mx-xl-3 flex-content-center flex-column">
						<h6 class="font-size-14 font-weight-bold text-gray-5 mb-0">{{human_time_diff($row->departure_time,$row->arrival_time)}}</h6>
						<div class="width-60 border-top border-primary border-width-2 my-1"></div>
					</div>
				</div>
				<div class="col-md-auto mb-4 mb-md-0">
					<div class="mx-2 mx-xl-3 flex-content-center align-items-start d-block d-lg-flex">
						<div class="mr-lg-3 mb-1 mb-lg-0">
							<i class="d-block rotate-90 flaticon-aeroplane font-size-30 text-primary"></i>
						</div>
						<div class="text-lg-left">
							<h6 class="font-weight-bold font-size-21 text-gray-5 mb-0">{{\Carbon\Carbon::parse($row->arrival_time)->format("H:i A")}}</h6>
							<div class="font-size-14 text-gray-5">{{\Carbon\Carbon::parse($row->arrival_time)->format("D M d")}}</div>
							<span class="font-size-14 text-gray-1">{{session()->get('flight_to_name')}}</span>
						</div>
					</div>
				</div>
			</div>
		</header>
		<!-- End Header -->
		
		<!-- Body -->
		<div class="card-body py-4 p-md-5">
			<div class="row">
				<div class="col-12 border-bottom mb-3">
				@foreach($flight as $data)
					<ul class="d-block d-md-flex justify-content-between list-group list-group-borderless list-group-horizontal list-group-flush no-gutters border-bottom">
						<li class="mr-md-8 mr-lg-8 mb-3 d-flex d-md-block justify-content-between list-group-item py-0">
							<div class="font-weight-bold text-dark">{{__('Seat type')}}</div>
							<span class="text-gray-1 text-capitalize">{{$data->seat_type}}</span>
						</li>
						<li class="mr-md-8 mr-lg-8 mb-3 d-flex d-md-block justify-content-between list-group-item py-0">
							<div class="font-weight-bold text-dark">{{__('Baggage')}}</div>
							<span class="text-gray-1 text-capitalize">{{$data->person}}</span>
						</li>
						<li class="mr-md-8 mr-lg-8 mb-3 d-flex d-md-block justify-content-between list-group-item py-0">
							<div class="font-weight-bold text-dark">{{__('Check-in')}}</div>
							<span class="text-gray-1">{{$data->baggage_check_in}} Kgs</span>
						</li>
						<li class="mr-md-8 mr-lg-8 mb-3 d-flex d-md-block justify-content-between list-group-item py-0">
							<div class="font-weight-bold text-dark">{{__('Cabin')}}</div>
							<span class="text-gray-1">{{$data->baggage_cabin}} Kgs</span>
						</li>
						<li class="mr-md-8 mr-lg-8 mb-3 d-flex d-md-block justify-content-between list-group-item py-0">
							<div class="font-weight-bold text-dark">{{__('Price')}}</div>
							<span class="text-gray-1">{{format_money(@$data->price)}}</span>
							<input type="hidden" id="price{{$data->id}}" class="price" value="{{format_money(@$data->price)}}" />
						</li>
						<li class="mr-md-8 mr-lg-8 mb-3 d-flex d-md-block justify-content-between list-group-item py-0">
							<div class="font-weight-bold text-dark">{{__('No Passengers')}}</div>
							<div class="number">
								<span class="minus{{$data->id}}">-</span>
								<input type="text" class="nop" value="0" readonly/>
								<span class="plus{{$data->id}}">+</span>
							</div>
							<input type="hidden" id="max_passengers{{$data->id}}" value="{{$data->max_passengers}}" />
						</li>
						<li class="mr-md-5 mr-lg-5 mb-3 d-flex d-md-block justify-content-between list-group-item py-0">
							<div class="font-weight-bold text-dark">{{__('Total Amount')}}</div>
							<input type="text" class="calculation" id="setvalue{{$data->id}}" value="0" />
						</li>
					</ul>

<script>
// VD:15072023 - Add nop actions
$(document).ready(function() {
	
	$("input[name='submit']").attr('disabled','disabled');
	localStorage.removeItem('nofpassenger');

	$('.minus{{$data->id}}').click(function () {
		var $input = $(this).parent().find('input');
		var count = parseInt($input.val()) - 1;
		count = count < 1 ? 0 : count;

		// less sum
		var price = $('#price{{$data->id}}').val();
		const noSpecialChars = price.replace(/[^a-zA-Z0-9 ]/g, '');
		$('#setvalue{{$data->id}}').val(noSpecialChars*count);

		$input.val(count);
		$input.change();

		// validate purpose -> if nof passenger more than 1 or not
		localStorage.setItem('nofpassenger', count);
		
	});

	$('.plus{{$data->id}}').click(function () {
		var $input = $(this).parent().find('input');
		var count = parseInt($input.val()) + 1;
		var number = 0;

		// get maximum passengers count
		var maxCount = $('#max_passengers{{$data->id}}').val();
		if (count < maxCount){ 
			number = count;
		} else {
			number = maxCount;
		}

		// add sum
		var price = $('#price{{$data->id}}').val();
		const noSpecialChars = price.replace(/[^a-zA-Z0-9 ]/g, '');
		$('#setvalue{{$data->id}}').val(noSpecialChars*number);

		$input.val(number);
		$input.change();

		// validate purpose -> if nof passenger more than 1 or not
		localStorage.setItem('nofpassenger', number);
	});
});
</script>					
				@endforeach
				</div>
				<div class="col-12 col-lg-12 offset-lg-4">
					<span class="text-danger text-center error-message"></span>	
				</div>
				<div class="col-12 col-lg-6 offset-lg-3">
					<div class="alert-text mt-3 text-left"></div>
					<div class="min-width-250">
						<ul class="list-unstyled font-size-1 mb-0 font-size-16">
							<li class="d-flex justify-content-center py-2 font-size-17 font-weight-bold">
								<a onclick="flightCheckOut('{{$row->id}}','{{$row->departure_time}}', '{{$row->arrival_time}}')" class="btn btn-blue-1 font-size-14 width-150 text-lh-lg transition-3d-hover py-1 text-white">{{__('Proceed To Booking')}}</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- End Body -->
	</div>
</div>

<script>
// VD:15072023 - Add custom checkout code instead of flight.js(flightCheckOut)
function flightCheckOut(flight_id, departure_time, arrival_time) {
	var me = this;
	
	// get price and nof passengers dynamically
	var price_arr = [];
	var nop_arr = [];
	$('.price').each(function () {
		var result = $(this).val();
		var final_result = result.replace(/[^a-zA-Z0-9 ]/g, '');
		var convertion = Number(final_result);
		price_arr.push(convertion);
	});	
	$('.nop').each(function () {
		var nop = Number($(this).val());
		nop_arr.push(nop);
	});
	// covert into single array key and value
	var hash = {};
	for (var i = 0; i < price_arr.length; i++) {
    	hash[price_arr[i]] = nop_arr[i];
	}

	// pass parameters in checkout
	var params = {
		service_id: flight_id,
		service_type:'flight',
		flight_seat : hash,
		type: 'customize',
		departure_time: departure_time,
		arrival_time: arrival_time
	}
	if(me.onSubmit==true){ 
		return false;
	}
	me.onSubmit = true;

	var nofpassengers = localStorage.getItem('nofpassenger');
	if (nofpassengers > 0) {
		$('.error-message').html('');
		$.ajax({
			url:bookingCore.url+'/booking/addToCart',
			data:params,
			dataType:'json',
			method:'post',
			success:function (json) {
				if (json.url) {
					localStorage.removeItem('nofpassenger');
					window.location.href = json.url;
				}
				if (json.errors && typeof json.errors == 'object')
				{
					console.log('error', json.errors);	
				}
				me.onSubmit = false;
			},
			error:function (e) {
				me.onSubmit = false;
				bravo_handle_error_response(e);
				if(e.status == 401){
					Custombox.modal.closeAll();
				}
				if(e.status != 401 && e.responseJSON){
					me.message.content = e.responseJSON.message ? e.responseJSON.message : 'Can not booking';
					me.message.type = false;
				}
				me.onSubmit = false;
			}
		});
	} else {
		$('.error-message').html('Please select atleast one passenger');
		me.onSubmit = false;	
	}
}	
</script>
