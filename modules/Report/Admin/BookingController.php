<?php
namespace Modules\Report\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Booking\Emails\NewBookingEmail;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Booking\Models\Booking;
use Modules\Booking\Events\BookingReplyCreated;
use Modules\Booking\Models\BookingReply;

class BookingController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu(route('report.admin.booking'));
    }

    public function index(Request $request)
    {
        $this->checkPermission('booking_view');
        $query = Booking::where('status', '!=', 'draft');
        if (!empty($request->s)) {
            if( is_numeric($request->s) ){
                $query->Where('id', '=', $request->s);
            }else{
                $query->where(function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->s . '%')
                        ->orWhere('last_name', 'like', '%' . $request->s . '%')
                        ->orWhere('reference_id','=', "#".$request->s)
                        ->orWhere('reference_id','=', $request->s)
                        ->orWhere('email', 'like', '%' . $request->s . '%')
                        ->orWhere('phone', 'like', '%' . $request->s . '%')
                        ->orWhere('address', 'like', '%' . $request->s . '%')
                        ->orWhere('address2', 'like', '%' . $request->s . '%');
                });
            }
        }
        if ($this->hasPermission('booking_manage_others')) {
            if (!empty($request->vendor_id)) {
                $query->where('vendor_id', $request->vendor_id);
            }
        } else {
            $query->where('vendor_id', Auth::id());
        }
        $query->whereIn('object_model', array_keys(get_bookable_services()));
        $query->orderBy('id','desc');
        $data = [
            'rows'                  => $query->paginate(20),
            'page_title'            => __("All Bookings"),
            'booking_manage_others' => $this->hasPermission('booking_manage_others'),
            'booking_update'        => $this->hasPermission('booking_update'),
            'statues'               => config('booking.statuses')
        ];
        return view('Report::admin.booking.index', $data);
    }

    public function bulkEdit(Request $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select action'));
        }
        if ($action == "delete") {
            foreach ($ids as $id) {
                $query = Booking::where("id", $id);
                if (!$this->hasPermission('booking_manage_others')) {
                    $query->where("vendor_id", Auth::id());
                }
                $row = $query->first();
                if(!empty($row)){
                    $row->delete();
                    event(new BookingUpdatedEvent($row));

                }
            }
        } else {
            foreach ($ids as $id) {
                $query = Booking::where("id", $id);
                if (!$this->hasPermission('booking_manage_others')) {
                    $query->where("vendor_id", Auth::id());
                    $this->checkPermission('booking_update');
                }
                $item = $query->first();
                if(!empty($item)){
                    $item->status = $action;
                    $item->save();

                    if($action == Booking::CANCELLED) $item->tryRefundToWallet();
                    event(new BookingUpdatedEvent($item));
                }
            }
        }
        return redirect()->back()->with('success', __('Update success'));
    }

    public function email_preview(Request $request, $id)
    {
        $booking = Booking::find($id);
        return (new NewBookingEmail($booking))->render();
    }

    // VD:09052023 - Booking cancellation request
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
    public function addConfirmationNumber(Request $request) {
        $bookingId = $request->get('booking_id');
        $bookingNumber = $request->get('confirmation_number');
        $query = Booking::where('id', $bookingId)->first();
        $query->confirmation_number    = $bookingNumber;
        if ($query->save()) {
            return redirect()->back()->with('success', __('Update success'));
        }
    }
    public function reply(Booking $booking,Request  $request){
        $this->checkPermission('booking_view');
        
        $data = [
            'rows'=>$booking->replies()->where('hide_from_admin', '0')->orderByDesc('id')->paginate(20),

            'breadcrumbs' => [
                [
                    'name' => __('Booking'),
                    'url'  => route('report.admin.booking')
                ],
                [
                    'name'  => __('Booking :name',['name'=>'#'.$booking->id.' - '.($booking->service->title ?? '')]),
                ],
                [
                    'name'  => __('All Replies'),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Replies"),
            'booking'=>$booking
        ];

        return view("Report::admin.booking.reply",$data);
    }

    public function replyStore(Booking $booking,Request  $request){
        $this->checkPermission('booking_view');

        $request->validate([
            'content'=>'required'
        ]);

        $reply = new BookingReply();
        $reply->content = $request->input('content');
        $reply->hide_from_vendor = $request->input('hide_from_vendor') ? 1 : 0;
        $reply->hide_from_customer = $request->input('hide_from_customer') ? 1 : 0;
        $reply->parent_id = $booking->id;
        $reply->user_id = auth()->id();

        $reply->save();

        BookingReplyCreated::dispatch($reply,$booking);

        return back()->with('success',__("Reply added"));
    }
}
