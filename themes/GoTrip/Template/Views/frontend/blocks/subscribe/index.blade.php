@switch($style)
    @case('style_2') @include('Template::frontend.blocks.subscribe.style_2') @break
    @default @include('Template::frontend.blocks.subscribe.style_1')
@endswitch

