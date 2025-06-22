<section class="layout-pt-md layout-pb-md bravo-list-locations @if(!empty($layout)) {{ $layout }} @endif">
    <div class="container">
        <div class="row">
            <div class="col-auto">
                <div class="sectionTitle -md">
                    <h2 class="sectionTitle__title">{{$title}}</h2>
                    <p class=" sectionTitle__text mt-5 sm:mt-0">{{$desc}}</p>
                </div>
            </div>
        </div>

        <div class="tabs -pills pt-40 js-tabs">
            <div class="tabs__content pt-30 js-tabs-content">
                <div class="row y-gap-20">
                    @if($rows)
                        @foreach($rows as $row)
                            @php $translation = $row->translateOrOrigin(app()->getLocale()); @endphp
                            <div class="w-1/5 lg:w-1/4 md:w-1/3 sm:w-1/2">
                                <a href="{{$row->getDetailUrl()}}" class="d-block">
                                    <div class="text-15 fw-500">{{$translation->name}}</div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
