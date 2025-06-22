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
use Modules\Booking\Events\EnquiryReplyCreated;
use Modules\Booking\Models\Enquiry;
use Modules\Booking\Models\EnquiryReply;

class EnquiryController extends FrontendController
{

    public function __construct(Enquiry $enquiryClass)
    {
        parent::__construct();
        $this->enquiryClass = $enquiryClass;
    }

    public function enquiryReport(Request $request){
        $this->checkPermission('inquery_report_customer');
        $user_id = Auth::id();
        $rows = $this->enquiryClass::where("create_user",$user_id)
        ->whereIn('object_model',array_keys(get_bookable_services()))
        ->orderBy('id', 'desc');

        // dd($rows);
        $data = [
            'rows'        => $rows->withCount(['replies'])->paginate(5),
            'statues'     => $this->enquiryClass::$enquiryStatus,
            'has_permission_enquiry_update' => $this->hasPermission('inquery_customer_update'),
            'breadcrumbs' => [
                [
                    'name'  => __('Enquiry History'),
                    'class' => 'active'
                ],
            ],
            'page_title'  => __("Enquiry History"),
        ];
        return view('User::frontend.inquery.index', $data);
    }

    public function reply(Enquiry $enquiry,Request  $request){
        if($enquiry->create_user != \auth()->id()){
            abort(404);
        }
        $this->checkPermission('inquery_report_customer');
        // dd($request);

        $data = [
            'rows'=>$enquiry->replies()->orderByDesc('id')->paginate(20),

            'breadcrumbs' => [
                [
                    'name' => __('Enquiry'),
                    'url'  => route('user.enquiry_report')
                ],
                [
                    'name'  => __('Enquiry :name',['name'=>'#'.$enquiry->id.' - '.($enquiry->service->title ?? '')]),
                    'url'=>'#'
                ],
                [
                    'name'  => __('All Replies'),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Replies"),
            'enquiry'=>$enquiry,
        ];
        return view("User::frontend.inquery.reply",$data);
    }

    public function replyStore(Enquiry $enquiry,Request  $request){
        if($enquiry->create_user != \auth()->id()){
            abort(404);
        }

        $this->checkPermission('inquery_report_customer');

        $request->validate([
            'content'=>'required'
        ]);

        $reply = new EnquiryReply();
        $reply->content = $request->input('content');
        $reply->parent_id = $enquiry->id;
        $reply->user_id = auth()->id();

        $reply->save();

        EnquiryReplyCreated::dispatch($reply,$enquiry);

        return back()->with('success',__("Reply added"));
    }

    public function enquiryReportBulkEdit($enquiry_id, Request $request)
    {
        $status = $request->input('status');
        if (!empty( $this->hasPermission('inquery_customer_update') ) and !empty($status) and !empty($enquiry_id)) {
            $query = $this->enquiryClass::where("id", $enquiry_id);
            $query->where("create_user", Auth::id());
            $item = $query->first();
            if (!empty($item)) {
                $item->status = $status;
                $item->save();
                return redirect()->back()->with('success', __('Update success'));
            }
            return redirect()->back()->with('error', __('Enquiry not found!'));
        }
        return redirect()->back()->with('error', __('Update fail!'));
    }

}
