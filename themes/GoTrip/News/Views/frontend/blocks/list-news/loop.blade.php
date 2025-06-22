@php $translation = $row->translateOrOrigin(app()->getLocale());@endphp

@if($style != 'style_2')
<div class="item-news">
    <a href="{{$row->getDetailUrl()}}" class="blogCard -type-1 d-block ">
        <div class="blogCard__image">
            <div class="ratio ratio-4:3 rounded-4 rounded-8">
                @if($row->image_id)
                    @if(!empty($disable_lazyload))
                        <img class="img-ratio js-lazy" src="#" data-src="{{get_file_url($row->image_id,'medium')}}" alt="{{$translation->name ?? ''}}">
                    @else
                        {!! get_image_tag($row->image_id,'medium',['class'=>'img-ratio js-lazy','alt'=>$row->title]) !!}
                    @endif
                @endif
            </div>
        </div>
        <div class="mt-20">
            <h4 class="text-dark-1 text-18 fw-500">{!! clean($translation->title) !!}</h4>
            <div class="text-light-1 text-15 lh-14 mt-5">{{ display_date($row->updated_at)}}</div>
        </div>
    </a>
</div>

@else
<a href="{{$row->getDetailUrl()}}" class="blogCard -type-1 d-block ">
    <div class="blogCard__image">
        <div class="ratio ratio-1:1 rounded-4 rounded-8">
            @if($row->image_id)
                @if(!empty($disable_lazyload))
                    <img class="img-ratio js-lazy" src="#" data-src="{{get_file_url($row->image_id,'medium')}}" alt="{{$translation->name ?? ''}}">
                @else
                    {!! get_image_tag($row->image_id,'medium',['class'=>'img-ratio js-lazy','alt'=>$row->title]) !!}
                @endif
            @endif
        </div>
    </div>

    <div class="mt-20">
        <h4 class="text-dark-1 text-18 fw-500">{!! clean($translation->title) !!}</h4>
        <div class="text-light-1 text-15 lh-14 mt-5">{{ display_date($row->updated_at)}}</div>
    </div>
</a>
@endif
