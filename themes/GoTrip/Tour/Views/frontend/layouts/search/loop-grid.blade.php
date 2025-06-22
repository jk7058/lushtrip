@php
    $translation = $row->translateOrOrigin(app()->getLocale());
    $layout_style = $layout_style ?? '';
@endphp

@if($layout_style != 'home_2')
<div class="item-loop {{$wrap_class ?? ''}}">
@endif
    <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl()}}" class="hotelsCard -type-1 ">
        <div class="hotelsCard__image">
            <div class="cardImage ratio ratio-1:1">
                <div class="cardImage__content">
                    @if($row->image_url)
                        @if(!empty($disable_lazyload))
                            <img  src="{{$row->image_url}}" class="img-responsive rounded-4 col-12 js-lazy" alt="">
                        @else
                            {!! get_image_tag($row->image_id,'medium',['class'=>'img-responsive rounded-4 col-12 js-lazy','alt'=>$translation->title]) !!}
                        @endif
                    @endif
                </div>
                <div class="cardImage__wishlist">
                    <button class="button -blue-1 bg-white size-30 rounded-full shadow-2 service-wishlist {{$row->isWishList()}}" data-id="{{ $row->id }}" data-type="{{ $row->type }}">
                        <i class="icon-heart text-12"></i>
                    </button>
                </div>
                @if($row->is_featured == "1")
                <div class="cardImage__leftBadge">
                    <div class="py-5 px-15 rounded-right-4 text-12 lh-16 fw-500 uppercase bg-dark-1 text-white">
                        {{__("Featured")}}
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="hotelsCard__content mt-10">
            <h4 class="hotelsCard__title text-dark-1 text-18 lh-16 fw-500">
                <span>{{ $translation->title }}</span>
            </h4>
            @if(!empty($row->location->name))
                @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
            @endif
            <p class="text-light-1 lh-14 text-14 mt-5">{{$location->name ?? ''}}</p>
            @if(setting_item('tour_enable_review'))
                <?php $reviewData = $row->getScoreReview(); $score_total = $reviewData['score_total'];?>
                <div class="d-flex items-center mt-20">
                    <div class="flex-center bg-blue-1 rounded-4 size-30 text-12 fw-600 text-white">{{ $reviewData['score_total'] }}</div>
                    <div class="text-14 text-dark-1 fw-500 ml-10">{{ $reviewData['review_text'] ?? '' }}</div>
                    <div class="text-14 text-light-1 ml-10">
                        @if($reviewData['total_review'] > 1)
                            {{ __(":number Reviews",["number"=>$reviewData['total_review'] ]) }}
                        @else
                            {{ __(":number Review",["number"=>$reviewData['total_review'] ]) }}
                        @endif
                    </div>
                </div>
            @endif
            <div class="mt-5">
                <div class="fw-500">
                    {{ __('Starting from') }} <span class="text-blue-1">{{ $row->display_price }}</span>
                </div>
            </div>
        </div>
    </a>
@if($layout_style != 'home_2')
</div>
@endif
