<?php

namespace App\Modules\Booking\Gateways;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaytmGateway extends BaseGateway
{
    protected $merchantId;
    protected $merchantKey;
    protected $website;
    protected $callbackUrl;

    public function __construct()
    {
        $this->merchantId = env('PAYTM_MERCHANT_ID');
        $this->merchantKey = env('PAYTM_MERCHANT_KEY');
        $this->website = env('PAYTM_WEBSITE');
        $this->callbackUrl = route('payment.callback');
    }

    public function processPayment($request)
    {
        $data = [
            'MID' => $this->merchantId,
            'ORDER_ID' => uniqid(), // Generate a unique order ID
            'TXN_AMOUNT' => $request->input('amount'),
            'CUST_ID' => $request->input('customer_id'),
            'CHANNEL_ID' => 'WEB',
            'WEBSITE' => $this->website,
            'CALLBACK_URL' => $this->callbackUrl,
        ];

        $data['CHECKSUMHASH'] = $this->generateChecksum($data);

        return $this->redirectToPaytm($data);
    }

    private function generateChecksum($data)
    {
        // Your logic to generate checksum hash here
        $checksum = ''; // Use your merchant key to generate checksum
        return $checksum;
    }

    private function redirectToPaytm($data)
    {
        $url = 'https://securegw-stage.paytm.in/theia/processTransaction';
        $form = '<form action="' . $url . '" method="post">';

        foreach ($data as $key => $value) {
            $form .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }

        $form .= '<input type="submit" value="Pay Now">';
        $form .= '</form>';

        return $form;
    }

    public function handleCallback($request)
    {
        $response = $request->all();
        $checksum = $response['CHECKSUMHASH'] ?? '';
        unset($response['CHECKSUMHASH']);

        if (!$this->verifyChecksum($response, $checksum)) {
            Log::error('Invalid checksum');
            return redirect()->route('payment.failure')->withErrors('Invalid checksum');
        }

        // Process the response
        $orderId = $response['ORDER_ID'] ?? null;
        $txnAmount = $response['TXN_AMOUNT'] ?? null;
        $paymentStatus = $response['STATUS'] ?? null;

        // Handle transaction details
        return view('transaction_details', compact('response'));
    }

    private function verifyChecksum($data, $checksum)
    {
        // Your logic to verify checksum
        return true; // Return true if valid
    }
}
