<section class="layout-pt-lg layout-pb-md">
    <div data-anim-wrap class="container">
        <div data-anim-child="slide-up delay-1" class="row y-gap-20 justify-center text-center">
            <div class="col-auto">
                <div class="sectionTitle -md">
                    <h2 class="sectionTitle__title">{{$title ?? ''}}</h2>
                    <p class=" sectionTitle__text mt-5 sm:mt-0">{{$desc ?? ''}}</p>
                </div>
            </div>
        </div>

        <div data-anim-child="slide-up delay-2" class="relative pt-40 js-section-slider" data-gap="30" data-slider-cols="xl-6 lg-4 md-3 sm-2 base-1">
            <div class="swiper-wrapper">
                @if($rows)
                    @foreach($rows as $row)
                        @php $translation = $row->translateOrOrigin(app()->getLocale()); @endphp
                        <div class="swiper-slide">
                            <a href="{{$row->getDetailUrl()}}" class="citiesCard -type-2 ">
                                @if($row->image_id)
                                    <div class="citiesCard__image rounded-4 ratio ratio-1:1">
                                        <img class="img-ratio rounded-4 js-lazy" data-src="{{ $row->getImageUrl() }}" src="{{$row->getDetailUrl()}}" alt="{{ $translation->name }}">
                                    </div>
                                @endif

                                <div class="citiesCard__content mt-10">
                                    <h4 class="text-18 lh-13 fw-500 text-dark-1">{{ $translation->name }}</h4>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>


            <button class="section-slider-nav -prev flex-center button -blue-1 bg-white shadow-1 size-40 rounded-full sm:d-none js-prev">
                <i class="icon icon-chevron-left text-12"></i>
            </button>

            <button class="section-slider-nav -next flex-center button -blue-1 bg-white shadow-1 size-40 rounded-full sm:d-none js-next">
                <i class="icon icon-chevron-right text-12"></i>
            </button>

        </div>
    </div>
</section>
