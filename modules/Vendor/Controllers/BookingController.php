<?php

namespace Modules\Vendor\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Events\BookingReplyCreated;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingReply;
use Modules\FrontendController;

class BookingController extends FrontendController
{
    public $bookingClass;

    public function __construct(Booking $bookingClass)
    {
        parent::__construct();
        $this->bookingClass = $bookingClass;
    }

   
    public function reply(Booking $booking,Request  $request){

        $data = [
            'rows'=>$booking->replies()->where('hide_from_vendor', '0')->orderByDesc('id')->paginate(20),

            'breadcrumbs' => [
                [
                    'name' => __('Booking'),
                    'url'  => route('vendor.bookingReport')
                ],
                [
                    'name'  => __('Booking :name',['name'=>'#'.$booking->id.' - '.($booking->service->title ?? '')]),
                    'url'=>'#'
                ],
                [
                    'name'  => __('All Replies'),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Replies"),
            'booking'=>$booking
        ];
        // dd($data);
        return view("Vendor::frontend.bookingReport.reply",$data);
    }

    public function replyStore(Booking $booking,Request  $request){

        $reply = new BookingReply();
        $reply->content = $request->input('content');
        $reply->hide_from_admin = $request->input('hide_from_admin') ? 1 : 0;
        $reply->hide_from_customer = $request->input('hide_from_customer') ? 1 : 0;
        $reply->parent_id = $booking->id;
        $reply->user_id = auth()->id();

        $reply->save();

        BookingReplyCreated::dispatch($reply,$booking);

        return back()->with('success',__("Reply added"));
    }
    public function addConfirmationNumber(Request $request) {
        $bookingId = $request->get('booking_id');
        $bookingNumber = $request->get('confirmation_number');
        $query = Booking::where('id', $bookingId)->first();
        $query->confirmation_number    = $bookingNumber;
        if ($query->save()) {
            return redirect()->back()->with('success', __('Update success'));
        }
    }
}
