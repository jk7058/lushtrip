<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;

class PaytmController extends Controller
{
    /**
     * Redirect the user to the Payment Gateway.
     *
     * @return Response
     */
    public function paytmPayment(Request $request)
    {
        // dd($request->all());
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => rand(),
          'user' => rand(10,1000),  //user name
          'mobile_number' => '123456789',
          'email' => 'paytmtest@gmail.com',
          'amount' => $request->amount,
          'callback_url' => route('paytm.callback'),
        ]);

        // dd($payment);
        return $payment->receive();
    }


    /**
     * Obtain the payment information.
     *
     * @return Object
     */
    public function paytmCallback()
    {
        $transaction = PaytmWallet::with('receive');
        
        $response = $transaction->response(); // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm
        // dd($response);

        if($transaction->isSuccessful()){
          //Transaction Successful
          // return view('paytm.paytm-success-page');
          dd('Successful');
        }else if($transaction->isFailed()){
          //Transaction Failed
          // return view('paytm.paytm-fail');
          dd('Failed');
        }else if($transaction->isOpen()){
          //Transaction Open/Processing
          // return view('paytm.paytm-fail');
          dd('Open/Processing');
        }
        echo $transaction->getResponseMessage(); //Get Response Message If Available
        //get important parameters via public methods
        echo $transaction->getOrderId(); // Get order id
        echo $transaction->getTransactionId(); // Get transaction id
    }

    /**
     * Paytm Payment Page
     *
     * @return Object
     */
    public function paytmPurchase()
    {
        return view('paytm.payment-page');
    }
}
