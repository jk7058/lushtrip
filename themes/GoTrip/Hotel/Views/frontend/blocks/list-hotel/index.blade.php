@switch($style_list)
    @case('carousel_v2')
    @case('carousel') @include('Hotel::frontend.blocks.list-hotel.carousel') @break
    @default @include('Hotel::frontend.blocks.list-hotel.normal')
@endswitch

