@php
    $style = $style ?? '';
@endphp
<form action="{{ route("flight.search") }}" class="@if($style != 'carousel_v2')form bravo_form mainSearch -w-900 bg-white px-10 py-10 lg:px-20 lg:pt-5 lg:pb-20 rounded-100 @endif" method="get">
    @php $flight_search_fields = setting_item_array('flight_search_fields');
            $flight_search_fields = array_values(\Illuminate\Support\Arr::sort($flight_search_fields, function ($value) {
                return $value['position'] ?? 0;
            }));
            $item_count = count($flight_search_fields);
    @endphp
    <div class="button-grid items-center button-column-{{$item_count+1}}">
        @if(!empty($flight_search_fields))
            @foreach($flight_search_fields as $field)
                @php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" @endphp
                @switch($field['field'])
                    @case ('service_name')
                    @include('Flight::frontend.layouts.search.fields.service_name')
                    @break
                    @case ('location')
                    @include('Flight::frontend.layouts.search.fields.location')
                    @break
                    @case ('date')
                    @include('Flight::frontend.layouts.search.fields.date')
                    @break
                    @case ('guests')
                    @include('Flight::frontend.layouts.search.fields.guests')
                    @break
                    @case ('seat_type')
                    @include('Flight::frontend.layouts.search.fields.seat_type')
                    @break
                    @case ('from_where')
                    @include('Flight::frontend.layouts.search.fields.from-where-airport')
                    @break
                    @case ('to_where')
                    @include('Flight::frontend.layouts.search.fields.to-where-airport')
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
