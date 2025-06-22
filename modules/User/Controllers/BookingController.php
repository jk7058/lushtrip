<?php
namespace Modules\User\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Matrix\Exception;
use Modules\FrontendController;
use Modules\User\Events\NewVendorRegistered;
use Modules\User\Events\SendMailUserRegistered;
use Modules\User\Models\Newsletter;
use Modules\User\Models\Subscriber;
use Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Modules\Vendor\Models\VendorRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Validator;
use Modules\Booking\Models\Booking;
use App\Helpers\ReCaptchaEngine;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Booking\Models\Enquiry;
use Modules\Booking\Events\BookingReplyCreated;
use Modules\Booking\Models\BookingReply;

class BookingController extends FrontendController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function bookingInvoice($code)
    {
        $booking = Booking::where('code', $code)->first();
        $user_id = Auth::id();
        if (empty($booking)) {
            return redirect('user/booking-history');
        }
        if ($booking->customer_id != $user_id and $booking->vendor_id != $user_id) {
            return redirect('user/booking-history');
        }
        $data = [
            'booking'    => $booking,
            'service'    => $booking->service,
            'page_title' => __("Invoice")
        ];
        return view('User::frontend.bookingInvoice', $data);
    }
    public function ticket($code = '')
    {
        $booking = Booking::where('code', $code)->first();
        $user_id = Auth::id();
        if (empty($booking)) {
            return redirect('user/booking-history');
        }
        if ($booking->customer_id != $user_id and $booking->vendor_id != $user_id) {
            return redirect('user/booking-history');
        }
        $data = [
            'booking'    => $booking,
            'service'    => $booking->service,
            'page_title' => __("Ticket")
        ];
        return view('User::frontend.booking.ticket', $data);
    }
    public function reply(Booking $booking,Request  $request){
        
        // dd($booking->create_user);

        if($booking->create_user != \auth()->id()){
            abort(404);
        }
        // $this->checkPermission('booking_report_customer');
        
        $data = [
            'rows'=>$booking->replies()->where('hide_from_customer', '0')->orderByDesc('id')->paginate(20),

            'breadcrumbs' => [
                [
                    'name' => 'Booking',
                    'url'  => route('user.booking_history')
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
            'booking'=>$booking,
        ];
        return view("User::frontend.booking.reply",$data);
    }

    public function replyStore(Booking $booking,Request  $request){
        // if($Booking->create_user != \auth()->id()){
        //     abort(404);
        // }

        // $this->checkPermission('booking_report_customer');

        $request->validate([
            'content'=>'required'
        ]);

        $reply = new BookingReply();
        $reply->content = $request->input('content');
        $reply->parent_id = $booking->id;
        $reply->user_id = auth()->id();

        $reply->save();

        BookingReplyCreated::dispatch($reply,$booking);

        return back()->with('success',__("Reply added"));
    }
    public function cancellationRquest(Request $request) {
        $bookingId = $request->get('booking_id');
        $reason = $request->get('cancellation');

        $query = Booking::where('id', $bookingId)->first();
        $query->status          = 'Cancellation Request';
        $query->cancellation    = $reason;
        if ($query->save()) {
            return redirect()->back()->with('success', __('Update success'));
        }
    }

}
