@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<li>   
    <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl()}}">{{$translation->title}}</a>
</li>
