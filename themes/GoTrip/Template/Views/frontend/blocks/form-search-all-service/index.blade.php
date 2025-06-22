@if(in_array($style,['carousel','']))
    @include("Template::frontend.blocks.form-search-all-service.style-normal")
@endif

@if(!empty($style) && $style == 'carousel_v2')
    @include("Template::frontend.blocks.form-search-all-service.carousel_v2")
@endif
