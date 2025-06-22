@php
    $style = $style ?? '';
@endphp

<form action="{{ route("car.search") }}" class="@if($style != 'carousel_v2')form bravo_form mainSearch -w-900 bg-white px-10 py-10 lg:px-20 lg:pt-5 lg:pb-20 rounded-100 @endif" method="get">
    @php
        $car_search_fields = setting_item_array('car_search_fields');
            $space_search_fields = array_values(\Illuminate\Support\Arr::sort($car_search_fields, function ($value) {
                return $value['position'] ?? 0;
            }));
         $item_count = count($car_search_fields);
    @endphp
    <div class="button-grid items-center button-column-{{$item_count+1}}">
        @if(!empty($car_search_fields))
            @foreach($car_search_fields as $field)
                @php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" @endphp
                @switch($field['field'])
                    @case ('service_name')
                    @include('Car::frontend.layouts.search.fields.service_name', ['style' => $style])
                    @break
                    @case ('location')
                    @include('Car::frontend.layouts.search.fields.location', ['style' => $style])
                    @break
                    @case ('date')
                    @include('Car::frontend.layouts.search.fields.date', ['style' => $style])
                    @break
                    @case ('attr')
                    @include('Car::frontend.layouts.search.fields.attr', ['style' => $style])
                    @break
                    @case ('guests')
                    @include('Car::frontend.layouts.search.fields.guests', ['style' => $style])
                    @break
                @endswitch
            @endforeach
        @endif
        <div class="button-item">
            <button class="mainSearch__submit button -dark-1 @if($style != 'carousel_v2') h-60 px-35 col-12 rounded-100 bg-blue-1 text-white @else py-15 px-35 h-60 col-12 rounded-4 bg-yellow-1 text-dark-1 @endif" type="submit">
                <i class="icon-search text-20 mr-10"></i> {{__("Search")}}
            </button>
        </div>
    </div>
</form>
