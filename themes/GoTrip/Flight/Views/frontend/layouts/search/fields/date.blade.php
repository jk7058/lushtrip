<div class="searchMenu-date px-30 lg:py-20 lg:px-0 form-date-search-hotel position-relative">
    <div class="date-wrapper" data-x-dd-click="searchMenu-date">
        <h4 class="text-15 fw-500 ls-2 lh-16">{{ $field['title'] }}</h4>

        <div class="text-15 text-light-1 ls-2 lh-16">
            <span class="js-first-date render check-in-render">{{Request::query('start',display_date(strtotime("today")))}}</span>
            -
            <span class="js-last-date render check-out-render">{{Request::query('end',display_date(strtotime("+1 day")))}}</span>
        </div>
    </div>
    <input type="hidden" class="check-in-input" value="{{Request::query('start',display_date(strtotime("today")))}}" name="start">
    <input type="hidden" class="check-out-input" value="{{Request::query('end',display_date(strtotime("+1 day")))}}" name="end">
    <input type="text" class="check-in-out" name="date" value="{{Request::query('date',date("Y-m-d")." - ".date("Y-m-d",strtotime("+1 day")))}}">
</div>
