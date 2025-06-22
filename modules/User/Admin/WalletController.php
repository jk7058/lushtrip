<?php
namespace Modules\User\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Booking\Models\Payment;
use Modules\User\Events\UpdateCreditPurchase;
use Modules\User\Models\Wallet\DepositPayment;
use App\Models\WalletHistory;
use Bavix\Wallet\Models\Wallet;
use Modules\User\Models\Wallet\Transaction;
use Illuminate\Support\Str;

class WalletController extends AdminController
{
    public function addCredit($user_id = ''){
        if(empty($user_id)){
            abort(404);
        }
        $row = User::find($user_id);
        if(!$row){
            abort(404);
        }

        $data = [
            'row'=>$row,
            'page_title'=>__("Add Credit"),
            'breadcrumbs'=>[
                [
                    'url'=>route('user.admin.index'),
                    'name'=>__("Users"),
                ],
                [
                    'url'=>'#',
                    'name'=>__('Add credit for :name',['name'=>$row->display_name]),
                ],
            ],
            'transactions'=>$row->transactions()->with(['payment','author'])->orderBy('id','desc')->paginate(15)
        ];
        return view("User::admin.wallet.add-credit",$data);
    }
    public function store($user_id = ''){

        if(empty($user_id)){
            abort(404);
        }
        $row = User::find($user_id);
        if(!$row){
            abort(404);
        }
        $amount = request()->input('credit_amount',0);
        $debit_amount = request()->input('debit_amount');
        $reason = request()->input('reason_comment');
        $wallet_id = request()->input('wallet_id');

        if($amount>0 && $debit_amount == 0){
            try {
                $row->deposit($amount,[
                    'admin_deposit'=>auth()->id()
                ]);

                // VD:17052023 - Wallet History
                $wallet = Wallet::find($row->id);
                $history = new WalletHistory();
                $history->wallet_id = $wallet->id;
                $history->holder_id = $wallet->holder_id;
                $history->type = 'Credit';
                $history->amount = $amount;
                $history->total_balance = $wallet->balance;
                $history->reason = $reason;
                $history->save();

            }catch (\Exception $exception){
                return redirect()->back()->with("error",$exception->getMessage());
            }

            return redirect()->back()->with("success",__(":amount credited",['amount'=>$amount]));
        } else {
            try {
                // VD:18052023 - Wallet History
                $wallet = Wallet::find($wallet_id);
                $get_balance = $wallet->balance;
                $new_balance = $get_balance-$debit_amount;
                $wallet->balance = $new_balance;
                if ($wallet->save()) {
                    $history = new WalletHistory();
                    $history->wallet_id = $wallet->id;
                    $history->holder_id = $wallet->holder_id;
                    $history->type = 'Debit';
                    $history->amount = $debit_amount;
                    $history->total_balance = $new_balance;
                    $history->reason = $reason;
                    $history->save();

                    // VD:28052023 - user-transactons table insertion
                    $obj = '{"wallet_total_used":'.$debit_amount.'}';
                    $trans = new Transaction();
                    $trans->payable_type = 'App\User';
                    $trans->payable_id = $user_id;
                    $trans->wallet_id = $row->id;
                    $trans->type = 'withdraw';
                    $trans->amount = -$debit_amount;
                    $trans->confirmed = 1;
                    $trans->meta = $obj;
                    $trans->uuid = Str::uuid();
                    $trans->save();
                }
            }catch (\Exception $exception){
                return redirect()->back()->with("error",$exception->getMessage());
            }

            return redirect()->back()->with("success",__(":amount debited",['amount'=>$debit_amount]));
        }
    }

    public function report(){
        $query = DepositPayment::query();

        $query->where('object_model','wallet_deposit')->orderBy('id','desc');
        if($user_id = request()->query('user_id'))
        {
            $query->where('object_id',$user_id);
        }

        $data = [
            'rows'=>$query->paginate(20),
            'page_title'=>__("Credit purchase report"),
            'breadcrumbs'=>[
                [
                    'url'=>route('user.admin.index'),
                    'name'=>__("Users"),
                ],
                [
                    'url'=>'#',
                    'name'=>__('Credit purchase report'),
                ],
            ]
        ];
        return view("User::admin.wallet.report",$data);
    }

    public function reportBulkEdit(Request $request){
        $ids = $request->input('ids');
        $action = $request->input('action');
        $comment_message = $request->input('comment_message');
        if (empty($ids))
            return redirect()->back()->with('error', __('Select at lease 1 item!'));
        if (empty($action))
            return redirect()->back()->with('error', __('Select an Action!'));
        if (empty($comment_message) || is_null($comment_message))
            return redirect()->back()->with('error', __('Enter Comemnt Message!'));
        if ($action == 'delete') {
//            foreach ($ids as $id) {
//                if($id == Auth::id()) continue;
//                $query = User::where("id", $id)->first();
//                if(!empty($query)){
//                    $query->email.='_d';
//                    $query->save();
//                    $query->delete();
//                }
//            }
        } else {
            foreach ($ids as $id) {
                switch ($action){
                    case "completed":
                        $payment = DepositPayment::find($id);
                        if($payment->payment_gateway == 'offline_payment' and $payment->status == 'processing'){
                            $payment->comment_message = $comment_message;
                            $payment->markAsCompleted();
                            //$payment->sendUpdatedPurchaseEmail();
                        }
                        event(new UpdateCreditPurchase(Auth::user(), $payment));

                        break;
                    case "rejected":
                        $payment = DepositPayment::find($id);
                        if($payment->payment_gateway == 'offline_payment' and $payment->status == 'processing'){
                            $payment->comment_message = $comment_message;
                            $payment->markAsRejected();
                            //$payment->sendUpdatedPurchaseEmail();
                        }
                        event(new UpdateCreditPurchase(Auth::user(), $payment));

                        break;
                }
            }
        }
        return redirect()->back()->with('success', __('Updated successfully!'));
    }
}
