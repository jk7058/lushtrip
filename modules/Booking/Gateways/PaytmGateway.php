<?php
namespace Modules\Booking\Gateways;

use App\Currency;
use Illuminate\Http\Request;
use Mockery\Exception;
use Modules\Booking\Events\BookingCreatedEvent;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Payment;
use Illuminate\Support\Facades\Log;
// use Paytm\PaytmChecksum\PaytmChecksum;
// require_once("./vendor/Paytm/paytmchecksum/paytmchecksum/paytmchecksum.php");


class PaytmGateway extends BaseGateway
{
    public $name = 'Paytm Payment Gateway';

    protected $gateway;

    public function getOptionsConfigs()
    {
        return [
            [
                'type'  => 'checkbox',
                'id'    => 'enable',
                'label' => __('Enable Paytm Gateway?')
            ],
            [
                'type'       => 'input',
                'id'         => 'name',
                'label'      => __('Custom Name'),
                'std'        => __("Paytm"),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'upload',
                'id'    => 'logo_id',
                'label' => __('Custom Logo'),
            ],
            [
                'type'  => 'editor',
                'id'    => 'html',
                'label' => __('Custom HTML Description'),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'input',
                'id'    => 'merchant_id',
                'label' => __('Merchant ID')
            ],
            [
                'type'  => 'input',
                'id'    => 'merchant_key',
                'label' => __('Merchant Key')
            ],
            [
                'type'  => 'input',
                'id'    => 'website',
                'label' => __('Website')
            ],
            [
                'type'  => 'input',
                'id'    => 'industry_type',
                'label' => __('Industry Type')
            ],
            [
                'type'  => 'checkbox',
                'id'    => 'test',
                'label' => __('Enable Sandbox Mode?')
            ],
        ];
    }

    public function process(Request $request, $booking, $service)
    {

        if (in_array($booking->status, [
            $booking::PAID,
            $booking::COMPLETED,
            $booking::CANCELLED
        ])) {
            throw new Exception(__("Booking status does not need to be paid"));
        }
        if (!$booking->pay_now) {
            throw new Exception(__("Booking total is zero. Cannot process payment gateway!"));
        }

        $this->getGateway();
        $payment = new Payment();
        $payment->booking_id = $booking->id;
        $payment->payment_gateway = $this->id;
        $payment->status = 'draft';
        $payment->save();

        $order_id = $booking->code . '_' . time();
        $paytmParams = $this->handlePurchaseData([

            'MID' =>  'iINIVi13498701245505',
            'ORDER_ID' => $order_id,
            'CUST_ID' => $booking->customer_id,
            'TXN_AMOUNT' => (float)$booking->pay_now,
            'CHANNEL_ID' => 'WEB',
            'WEBSITE' => $this->getOption('website'),
            'INDUSTRY_TYPE_ID' => $this->getOption('industry_type'),
            'CALLBACK_URL' => $this->getReturnUrl() . '?c=' . $booking->code,
        ]);
        // dd($this->getOption('merchant_key'));
        
        $paytmChecksum = \paytm\paytmchecksum\PaytmChecksum::generateSignature($paytmParams, $this->getOption('merchant_key'));
        $paytmParams['CHECKSUMHASH'] = $paytmChecksum;
        // https://securegw-stage.paytm.in/theia/processTransaction


        // Redirect to Paytm gateway
        return response()->json([
            'url' => 'https://securegw-stage.paytm.in/theia/processTransaction',
            'params' => $paytmParams
        ])->send();
    }

    public function confirmPayment(Request $request)
    {
        $c = $request->query('c');
        $booking = Booking::where('code', $c)->first();
        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {
            $this->getGateway();

            $paytmChecksum = $request->input('CHECKSUMHASH');
            $isValidChecksum = PaytmChecksum::verifySignature($request->all(), $this->getOption('merchant_key'), $paytmChecksum);

            if ($isValidChecksum && $request->input('STATUS') == 'TXN_SUCCESS') {
                $payment = $booking->payment;
                if ($payment) {
                    $payment->status = 'completed';
                    $payment->logs = json_encode($request->all());
                    $payment->save();
                }
                try {
                    $booking->paid += (float)$booking->pay_now;
                    $booking->markAsPaid();
                } catch (\Swift_TransportException $e) {
                    Log::warning($e->getMessage());
                }
                return redirect($booking->getDetailUrl())->with("success", __("Your payment has been processed successfully"));
            } else {
                $payment = $booking->payment;
                if ($payment) {
                    $payment->status = 'fail';
                    $payment->logs = json_encode($request->all());
                    $payment->save();
                }
                try {
                    $booking->markAsPaymentFailed();
                } catch (\Swift_TransportException $e) {
                    Log::warning($e->getMessage());
                }
                return redirect($booking->getDetailUrl())->with("error", __("Payment Failed"));
            }
        }
        return redirect(url('/'));
    }

    public function cancelPayment(Request $request)
    {
        $c = $request->query('c');
        $booking = Booking::where('code', $c)->first();
        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {
            $payment = $booking->payment;
            if ($payment) {
                $payment->status = 'cancel';
                $payment->logs = json_encode(['customer_cancel' => 1]);
                $payment->save();
            }

            $booking->tryRefundToWallet(false);

            return redirect($booking->getDetailUrl())->with("error", __("You cancelled the payment"));
        }
        return redirect(url('/'));
    }

    public function getGateway()
    {
        // No external gateway library like Omnipay is needed here as we are using direct Paytm API.
        // You can add additional initialization logic here if necessary.
    }

    private function handlePurchaseData($data)
    {
        return array_merge($data, [
            'MID' => $this->getOption('merchant_id'),
            'WEBSITE' => $this->getOption('website'),
            'INDUSTRY_TYPE_ID' => $this->getOption('industry_type'),
        ]);
    }

    private function getPaytmUrl()
    {
        return $this->getOption('test') 
            ? 'https://securegw-stage.paytm.in/order/process' 
            : 'https://securegw.paytm.in/order/process';
    }
}
