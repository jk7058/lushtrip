<?php
namespace Modules\Booking\Models;

use App\BaseModel;
use App\User;

class BookingReply extends BaseModel
{
    protected $table      = 'bravo_booking_replies';

    public function booking()
    {
        return $this->belongsTo(Booking::class,'parent_id');
    }
    public function author(){
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }
}
