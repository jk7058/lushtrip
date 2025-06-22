@switch($style)
    @case('style_2') @include('Tour::frontend.blocks.testimonial.style_2') @break
    @case('style_3') @include('Tour::frontend.blocks.testimonial.style_3') @break
    @default @include('Tour::frontend.blocks.testimonial.style_1')
@endswitch
