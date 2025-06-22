<?php

namespace Modules\Booking\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingReply;

class BookingReplyCreated
{
    use SerializesModels, Dispatchable;

    public $_reply;

    public $_booking;

    public function __construct(BookingReply $reply, Booking $booking)
    {

        $this->_ooking = $booking;
        $this->_reply = $reply;
    }

}
