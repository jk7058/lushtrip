<style>
#myInput {
  background-image: url('/css/searchicon.png');
  background-position: 10px 12px;
  background-repeat: no-repeat;
  font-size: 16px;
  padding: 5px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myUL {
    list-style-type: none;
    padding: 0;
    margin: 0;
    float: right;
    overflow: auto;
    position: absolute;
    z-index: 9;
    top: 36px;
    width: 203px;
    left: 169px;
    height: 150px;
}

#myUL li a {
    border: 1px solid #dddddd94;
    margin-top: -1px;
    background-color: #f6f6f6;
    padding: 5px;
    text-decoration: none;
    font-size: 14px;
    color: black;
    display: block;
}

#myUL li a:hover:not(.header) {
  background-color: #eee;
}

#myUL {
    display: none;
}
</style>

<div class="item">
    <a href="{{ route("hotel.search",['_layout'=>'map']) }}">{{__("Show on the map")}}</a>
</div>
<div class="item">
    @php
        $param = request()->input();
        $orderby =  request()->input("orderby");
    @endphp
    <div class="item-title">
        {{ __("Sort by:") }}
    </div>
    <div class="dropdown">
        <span class=" dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @switch($orderby)
                @case("price_low_high")
                {{ __("Price (Low to high)") }}
                @break
                @case("price_high_low")
                {{ __("Price (High to low)") }}
                @break
                @case("rate_high_low")
                {{ __("Rating (High to low)") }}
                @break
                @default
                {{ __("Recommended") }}
            @endswitch
        </span>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            @php $param['orderby'] = "" @endphp
            <a class="dropdown-item" href="{{ route("hotel.search",$param) }}">{{ __("Recommended") }}</a>
            @php $param['orderby'] = "price_low_high" @endphp
            <a class="dropdown-item" href="{{ route("hotel.search",$param) }}">{{ __("Price (Low to high)") }}</a>
            @php $param['orderby'] = "price_high_low" @endphp
            <a class="dropdown-item" href="{{ route("hotel.search",$param) }}">{{ __("Price (High to low)") }}</a>
            @php $param['orderby'] = "rate_high_low" @endphp
            <a class="dropdown-item" href="{{ route("hotel.search",$param) }}">{{ __("Rating (High to low)") }}</a>
        </div>

        <!-- VD: add search button filter hotel names only -->
        <input type="text" placeholder="Search Hotel Name" id="myInput" onkeyup="myFunction()"/>
        <ul id="myUL">
        @if($rows->total() > 0)
            @foreach($rows as $row)
                @php $layout = setting_item("hotel_layout_item_search",'list') @endphp
                @if($layout == "list")
                    @include('Hotel::frontend.layouts.search.custom-search')
                @else
                    @include('Hotel::frontend.layouts.search.loop-grid',['wrap_class'=>'mb-3'])
                @endif
            @endforeach
        @endif
        </ul>
    </div>
</div>

<script>
function myFunction() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName("li");
    
    // If we've got more than 1 char in <input>, show it, otherwise, hide
	const inputDisplay = input.value.length > 0 ? 'block' : 'none';
	ul.style.display = inputDisplay;

    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}
</script>