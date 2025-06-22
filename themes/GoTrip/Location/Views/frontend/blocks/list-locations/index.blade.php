@switch($layout)
    @case('style_2') @include('Location::frontend.blocks.list-locations.style_2') @break
    @case('style_3') @include('Location::frontend.blocks.list-locations.style_3') @break
    @case('style_4') @include('Location::frontend.blocks.list-locations.style_4') @break
    @default @include('Location::frontend.blocks.list-locations.default')
@endswitch
