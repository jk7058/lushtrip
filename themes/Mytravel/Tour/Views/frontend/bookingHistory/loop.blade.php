<tr>
    <td class="booking-history-type">
        @if($service = $booking->service)
            <i class="{{$service->getServiceIconFeatured()}}"></i>
        @endif
        <small>{{$booking->object_model}}</small>
    </td>
    <td>
        @if($service = $booking->service)
            @php
                $translation = $service->translateOrOrigin(app()->getLocale());
            @endphp
            <a target="_blank" href="{{$service->getDetailUrl()}}">
                {!! clean($translation->title) !!} - {{$booking->service->reference_code}}
            </a>
        @else
            {{__("[Deleted]")}}
        @endif
    </td>
    <td>{{$booking->reference_id}}</td>
    <td class="a-hidden">{{display_date($booking->created_at)}}</td>
    <td class="a-hidden">
        {{__("Check in")}} : {{display_date($booking->start_date)}} <br>
        {{__("Duration")}} : {{ $booking->getMeta("duration") ?? "1"  }} {{__("hours")}}
    </td>
    <td>{{format_money($booking->total)}}</td>
    <td>{{format_money($booking->paid)}}</td>
    <td>{{format_money($booking->total - $booking->paid)}}</td>
    <td class="{{$booking->status}} a-hidden" <?php if(isset($booking->status) && $booking->status == 'cancelled' || $booking->status == 'Cancellation Request'){  ?> style="color: red;" <?php } ?>>{{$booking->statusName}}</td>
    <td width="2%">
        @if($service = $booking->service)
            <a class="btn btn-xs btn-primary btn-info-booking" data-toggle="modal" data-target="#modal-booking-{{$booking->id}}">
                <i class="fa fa-info-circle"></i>{{__("Details")}}
            </a>
            @include ($service->checkout_booking_detail_modal_file ?? '')
        @endif
        <a href="{{route('user.booking.invoice',['code'=>$booking->code])}}" class="btn btn-xs btn-primary btn-info-booking open-new-window mt-1" onclick="window.open(this.href); return false;">
            <i class="fa fa-print"></i>{{__("Invoice")}}
        </a>
        <a href="{{route('user.booking.reply',['booking'=>$booking->id])}}" class="btn btn-xs btn-primary btn-info-booking open-new-window mt-1" onclick="window.open(this.href); return false;">
            <i class="fa fa-reply"></i>{{__("Reply")}}
        </a>
        @if($booking->statusName != 'Cancelled' AND $booking->status != 'Cancellation Request')
        <a class="btn btn-xs btn-primary btn-info-booking" style="margin-top: 4px;" data-toggle="modal" data-target="#modal-booking-cancel-{{$booking->id}}">
            <i class="fa fa-close"></i>{{__("Cancellation")}}
        </a>
        @endif
    </td>
</tr>

<!-- VD:02052023 - Add cancellation popup -->
<div class="modal fade" id="modal-booking-cancel-{{$booking->id}}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{__("Booking ID")}}: {{$booking->reference_id}}</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
            <form action="{{route('user.booking.cancellation')}}" method="post">
                @csrf
                <p>Are you sure want to cancellation this booking?</p>
                <div class="form-group">
                    <input type="hidden" value="{{$booking->id}}" name="booking_id" id="booking_id" />
                    <textarea name="cancellation" id="cancellation" class="form-control" placeholder="Enter your reason*" style="height: 100px;" required></textarea>
                    </br>
                    <span class="btn btn-secondary pull-right" data-dismiss="modal">{{__("Close")}}</span>
                    <button type="submit" class="btn btn-xs btn-primary btn-flat show_confirm pull-right" style="margin-right: 5px;">Yes, Confirm!</button> 
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
