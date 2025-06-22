<?php
$minValue = 0;
?>
<div class="searchMenu-guests px-30 lg:py-20 lg:px-0 js-form-dd form-select-seat-type">
    <div data-x-dd-click="searchMenu-guests" class="overflow-hidden seat-input">
        <h4 class="text-15 fw-500 ls-2 lh-16">{{ $field['title'] }}</h4>

        <div class="text-15 text-light-1 ls-2 lh-16">
            @php
                $seatTypeGet = request()->query('seat_type',[]);
            @endphp
            <div class="render font-size-14">
                @foreach($seatType as $type)
                    <?php
                    $inputRender = 'seat_type_'.$type->code.'_render';
                    $inputValue = $seatTypeGet[$type->code] ?? $minValue;
                    ?>
                    <span class="" id="{{$inputRender}}">
                        <span class="one @if($inputValue > $minValue) d-none @endif">{{__( ':min :name',['min'=>$minValue,'name'=>$type->name])}}</span>
                        <span class="@if($inputValue <= $minValue) d-none @endif multi" data-html="{{__(':count '.$type->name)}}">{{__(':count'.$type->name,['count'=>$inputValue??$minValue])}}</span>
                    </span>
                @endforeach
            </div>
        </div>
    </div>
    <div class="searchMenu-guests__field select-seat-type-dropdown shadow-2" data-x-dd="searchMenu-guests" data-x-dd-toggle="-is-active">
        <div class="bg-white px-30 py-30 rounded-4">
            @foreach($seatType as $type)
                <?php
                $inputName = 'seat_type_'.$type->code;
                $inputValue = $seatTypeGet[$type->code] ?? $minValue;
                ?>

                <div class="row y-gap-10 justify-between items-center">
                    <div class="col-auto">
                        <div class="text-15 fw-500">{{__('Adults :type',['type'=>$type->name])}}</div>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex items-center">
                            <span class="button -outline-blue-1 text-blue-1 size-38 rounded-4 btn-minus" data-input="{{$inputName}}" data-input-attr="id"><i class="icon-minus text-12"></i></span>
                            <span class="flex-center size-20 ml-15 mr-15 count-display">
                                <input id="{{$inputName}}" type="number" name="seat_type[{{$type->code}}]" value="{{$inputValue}}" min="{{$inputValue}}">
                            </span>
                            <span class="button -outline-blue-1 text-blue-1 size-38 rounded-4 btn-add" data-input="{{$inputName}}" data-input-attr="id"><i class="icon-plus text-12"></i></span>
                        </div>
                    </div>

<!--                    <div class="val">
                        <span class="btn-minus" data-input="{{$inputName}}" data-input-attr="id"><i class="icon ion-md-remove"></i></span>
                        <span class="count-display"><input id="{{$inputName}}" type="number" name="seat_type[{{$type->code}}]" value="{{$inputValue}}" min="{{$minValue}}"></span>
                        <span class="btn-add" data-input="{{$inputName}}" data-input-attr="id"><i class="icon ion-ios-add"></i></span>
                    </div>-->
                </div>
            @endforeach

<!--            <div class="row y-gap-10 justify-between items-center">
                <div class="col-auto">
                    <div class="text-15 fw-500">{{ __('Rooms') }}</div>
                </div>

                <div class="col-auto">
                    <div class="d-flex items-center">
                        <span class="button -outline-blue-1 text-blue-1 size-38 rounded-4 btn-minus" data-input="room"><i class="icon-minus text-12"></i></span>
                        <span class="flex-center size-20 ml-15 mr-15 count-display">
                            <input type="number" name="room" value="{{request()->query('room',1)}}" min="1">
                        </span>
                        <span class="button -outline-blue-1 text-blue-1 size-38 rounded-4 btn-add" data-input="room"><i class="icon-plus text-12"></i></span>
                    </div>
                </div>
            </div>

            <div class="border-top-light mt-24 mb-24"></div>

            <div class="row y-gap-10 justify-between items-center">
                <div class="col-auto">
                    <div class="text-15 fw-500">{{ __('Adults') }}</div>
                </div>

                <div class="col-auto">
                    <div class="d-flex items-center">
                        <span class="button -outline-blue-1 text-blue-1 size-38 rounded-4 btn-minus" data-input="adults"><i class="icon-minus text-12"></i></span>
                        <span class="flex-center size-20 ml-15 mr-15 count-display">
                            <input type="number" name="adults" value="{{request()->query('adults',1)}}" min="1">
                        </span>
                        <span class="button -outline-blue-1 text-blue-1 size-38 rounded-4 btn-add" data-input="adults"><i class="icon-plus text-12"></i></span>
                    </div>
                </div>
            </div>

            <div class="border-top-light mt-24 mb-24"></div>

            <div class="row y-gap-10 justify-between items-center">
                <div class="col-auto">
                    <div class="text-15 fw-500">{{ __('Children') }}</div>
                </div>

                <div class="col-auto">
                    <div class="d-flex items-center">
                        <span class="button -outline-blue-1 text-blue-1 size-38 rounded-4 btn-minus" data-input="children"><i class="icon-minus text-12"></i></span>
                        <span class="flex-center size-20 ml-15 mr-15 count-display">
                            <input type="number" name="children" value="{{request()->query('children',0)}}" min="0">
                        </span>
                        <span class="button -outline-blue-1 text-blue-1 size-38 rounded-4 btn-add" data-input="children"><i class="icon-plus text-12"></i></span>
                    </div>
                </div>
            </div>-->
        </div>
    </div>



	{{--<div class="form-group">
		<i class="field-icon icofont-ticket"></i>
		<div class="form-content dropdown-toggle" data-toggle="dropdown">
			<div class="wrapper-more">
				<label> {{ $field['title'] }} </label>
				@php
					$seatTypeGet = request()->query('seat_type',[]);
				@endphp
				<div class="render font-size-14">
					@foreach($seatType as $type)
						<?php
						$inputRender = 'seat_type_'.$type->code.'_render';
						$inputValue = $seatTypeGet[$type->code] ?? $minValue;
						?>
						<span class="" id="{{$inputRender}}">
                            <span class="one @if($inputValue > $minValue) d-none @endif">{{__( ':min :name',['min'=>$minValue,'name'=>$type->name])}}</span>
                            <span class="@if($inputValue <= $minValue) d-none @endif multi" data-html="{{__(':count '.$type->name)}}">{{__(':count'.$type->name,['count'=>$inputValue??$minValue])}}</span>
                        </span>
					@endforeach
				</div>
			</div>
		</div>
		<div class="dropdown-menu select-seat-type-dropdown" >
			@foreach($seatType as $type)
				<?php
				$inputName = 'seat_type_'.$type->code;
				$inputValue = $seatTypeGet[$type->code] ?? $minValue;
				;?>

				<div class="dropdown-item-row">
					<div class="label">{{__('Adults :type',['type'=>$type->name])}}</div>
					<div class="val">
						<span class="btn-minus" data-input="{{$inputName}}" data-input-attr="id"><i class="icon ion-md-remove"></i></span>
						<span class="count-display"><input id="{{$inputName}}" type="number" name="seat_type[{{$type->code}}]" value="{{$inputValue}}" min="{{$minValue}}"></span>
						<span class="btn-add" data-input="{{$inputName}}" data-input-attr="id"><i class="icon ion-ios-add"></i></span>
					</div>
				</div>
			@endforeach
		</div>
	</div>--}}
</div>
