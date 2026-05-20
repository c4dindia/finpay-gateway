<?php

namespace App\Http\Controllers\Inabit;

use App\Http\Controllers\Controller;
use App\Models\PTenPaymentMethod;
use App\Models\Transaction;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\RequestException;

class InabitWebhookController extends Controller
{
    public function handleNotification(Request $request)
    {
        Log::channel('inabit')->info("----- INABIT WEBHOOK RECEIVED -----");
        Log::channel('inabit')->info("HTTP Method: " . $request->getMethod());
        Log::channel('inabit')->info("Headers: ", $request->headers->all());
        Log::channel('inabit')->info("Raw Inabit Webhook Body: " . $request->getContent());

        $payload = $request->json()->all();

        Log::channel('inabit')->info("Decoded Inabit Payload:", $payload);

        if (!isset($payload['event']) || !isset($payload['data'])) {
            Log::channel('inabit')->warning("Invalid Inabit Webhook Payload!");
            return response()->json(["success" => true]);
        }

        $event = $payload['event'];
        $data  = $payload['data'];

        Log::channel('inabit')->info("Event Type: " . $event);

        // -----------------------------
        // EVENT TYPE ROUTER
        // -----------------------------
        switch ($event) {

            case "IncomingTransactionReceived":
                $this->handleIncomingDeposit($data);
                break;

            case "OutgoingTransactionSent":
                $this->handleOutgoingWithdrawal($data);
                break;

            case "IncomingTransactionStatusUpdated":
                $this->handleStatusUpdate($data);
                break;

            default:
                Log::info("Unhandled event type: " . $event);
                break;
        }

        Log::channel('inabit')->info("----- INABIT WEBHOOK PROCESSED SUCCESSFULLY -----");
        return response()->json(["success" => true]);
    }

    // -------------------------------------------------
    // HANDLERS FOR DIFFERENT TYPES OF NOTIFICATIONS
    // -------------------------------------------------

    protected function handleIncomingDeposit(array $data)
    {
        Log::channel('inabit')->info("Handling Incoming Deposit Event", $data);
        /*
        STATUSES:
         Confirming , Unconfirmed ,PendingFork , Completed , Failed
         Initiated , Allocated , Undercharge , UnderchargeExpired , Overcharge ,Expired
        */

        $trxnID = $data['transactionId'];
        $trxnHash = $data['transactionHash'];
        $status = ucfirst(strtolower($data['status']));  // Confirming, Completed, etc.;
        $amount = $data['amount'];
        $asset = $data['asset'];
        $blockchain = $data['blockchain'] ?? '-';
        $srcAddress = $data['sourceAddress'] ?? '-';
        $destAddress = $data['destinationAddress'] ?? '-';
        $customerIdentifier = $data['customerIdentifier'];

        $trans = Transaction::where('checkout_id',$customerIdentifier)->where('status','p10')->first() ?: new Transaction();

        $trans->currency       = $asset;
        $trans->amount         = $amount;
        // $trans->from_currency  = $asset;
        // $trans->from_amount    = $amount;
        $trans->payment_id     = $trxnID;
        $trans->payment_status = $status;
        $trans->description    = 'Type: Incoming Deposit - '. $status;
        $trans->customer_details    = Str::of("Blockchain: {$blockchain} , Src Address: {$srcAddress} , Dest Address: {$destAddress}")->squish()->trim();
        $trans->transvoucher_blockchainHashTrxn = $trxnHash;
        $trans->status         = 'p10';

        $trans->save();
    }

    protected function handleOutgoingWithdrawal(array $data)
    {
        Log::channel('inabit')->info("Handling Withdrawal Event", $data);

        $html = "<h2>New Withdrawal Event</h2><table border='1' cellpadding='5' cellspacing='0'>";

        foreach ($data as $key => $value) {
            $html .= "<tr>
                        <td><strong>" . ucfirst($key) . "</strong></td>
                        <td>" . $value . "</td>
                     </tr>";
        }

        $html .= "</table>";

        Mail::html($html, function ($message) {
            $message->to('bobby@neurosyncventures.com')
                    ->subject('New Withdrawal Event Received');
        });
    }

    protected function handleStatusUpdate(array $data)
    {
        Log::channel('inabit')->info("Handling Transaction Status Update", $data);
        /*
        STATUSES:
         Confirming , Unconfirmed ,PendingFork , Completed , Failed
         Initiated , Allocated , Undercharge , UnderchargeExpired , Overcharge ,Expired
        */
        $trxnID = $data['transactionId'];
        $trxnHash = $data['transactionHash'];
        $status = ucfirst(strtolower($data['status']));  // Confirming, Completed, etc.;
        $customerIdentifier = $data['customerIdentifier'];

        $trans = Transaction::where('checkout_id',$customerIdentifier)->where('status','p10')->first() ?: new Transaction();
        $trans->payment_id     = $trxnID;
        $trans->payment_status = $status;
        $trans->description    = 'Type: Incoming Deposit - '. $status ;
        $trans->transvoucher_blockchainHashTrxn = $trxnHash;
        $trans->save();
    }

    // ------- PURCHASE ADDRESS WIDGET WEBHOOK --------
    public function handleNotificationPurchaseWidget(Request $request)
    {
        Log::channel('inabit')->info("----- PURCHASE WIDGET WEBHOOK RECEIVED -----");
        Log::channel('inabit')->info("Raw Purchase Webhook Body: " . $request->getContent());

        $payload = $request->json()->all();
        if (empty($payload)) {
            $payload = json_decode($request->getContent(), true) ?: [];
        }

        $event = $payload['event'] ?? null;
        Log::channel('inabit')->info("Webhook Event: ". $event ?? 'Event not found');

        $data  = $payload['data']  ?? [];

        if (!is_array($data)) $data = [];

        $purchaseId               = $data['purchaseId'] ?? null;
        $purchaseIdentifier       = $data['purchaseIdentifier'] ?? null;
        $title                    = $data['title'] ?? null;
        $subTitle                 = $data['subTitle'] ?? null;
        $siteName                 = $data['siteName'] ?? null;

        $widgetId                 = $data['widgetId'] ?? null;
        $widgetName               = $data['widgetName'] ?? null;

        $asset                    = $data['asset'] ?? null;
        $blockchain               = $data['blockchain'] ?? null;
        $address                  = $data['address'] ?? null;

        $transactions             = $data['transactions'] ?? []; // default empty array
        if (!is_array($transactions)) $transactions = [];

        $amount                   = $data['amount'] ?? null;
        $plannedAmount            = $data['plannedAmount'] ?? null;

        $fiatCurrency             = $data['fiatCurrency'] ?? null;
        $fiatAmount               = $data['fiatAmount'] ?? null;

        $baseCurrency             = $data['baseCurrency'] ?? null;
        $baseCurrencyAmount       = $data['baseCurrencyAmount'] ?? null;
        $plannedBaseCurrencyAmount= $data['plannedBaseCurrencyAmount'] ?? null;

        $currentDate              = $data['currentDate'] ?? null;
        $allocationDate           = $data['allocationDate'] ?? null;
        $expirationDate           = $data['expirationDate'] ?? null;

        $acceptPartialPayment     = $data['acceptPartialPayment'] ?? null;

        $sweepingStatus           = $data['sweepingStatus'] ?? null;
        $sweepingFee              = $data['sweepingFee'] ?? null;

        $redirectUrl              = $data['redirectUrl'] ?? null;
        $status                   = $data['status'] ?? null;

        Log::channel('inabit')->info(" Purchase Webhook", [
            'event' => $event,
            'widgetId'   => $widgetId,
            'widgetName' => $widgetName,
            'purchaseId' => $purchaseId,
            'status' => $status,
            'address' => $address,
            'asset' => $asset,
            'blockchain' => $blockchain,
            'fiatAmount' => $fiatAmount,
            'fiatCurrency' => $fiatCurrency,
            'plannedAmount' => $plannedAmount,
            'transactions_count' => count($transactions),
            'currentDate' => $currentDate,
            'allocationDate' => $allocationDate,
            'expirationDate' => $expirationDate,
        ]);

        $widgetHolders = PTenPaymentMethod::whereNotNull("inabit_purchase_merchant_name")->where("inabit_purchase_merchant_name",$widgetName)->get();
        foreach($widgetHolders as $wh){
            $serviceUser = null;
            $serviceUser = PTenPaymentMethod::where("id",$wh->id)->first();
            if(!$serviceUser){
                continue;
            }
            $serviceUser->inabit_purchase_widget_id = $widgetId;
            $serviceUser->save();
        }

        $trans = Transaction::where('checkout_id', $purchaseIdentifier)->where('status', 'p10')->first() ?: new Transaction();

        // $trans->account_id     = $checkaccId->accountId;
        $trans->currency       = $asset ?? $trans->currency ?? 'currency';
        $trans->amount         = $plannedAmount ?? 0;
        // $trans->net_amount     = $amount;
        // $trans->fees           = $amount;
        $trans->from_currency  = $fiatCurrency;
        $trans->from_amount    = $fiatAmount;
        $trans->checkout_id    = $purchaseIdentifier;
        $trans->payment_id     = $purchaseId;

        $description = "Sweeping Status: ". $sweepingStatus . " , Sweeping Fee: " . $sweepingFee;
        $trans->payment_status = ucfirst(strtolower($status));
        if($asset != null && $blockchain != null){
            $description   =  $description. " , Asset: " . $asset . " , Blockchain: " . $blockchain;
        }
        if($address != null || $address == ""){
            $description = $description . " , Address: " .$address;
        }
        $trans->description = $description;
        // $trans->customer_details    = 'Name: zorro kungen , Email: luinusojat@gmail.com , Phone: +46724030959';
        // $trans->transvoucher_blockchainHashTrxn = $amount;
        $trans->status         = 'p10';

        $trans->save();

        try {
            if ($trans->account_id) {
                $pTenUser = PTenPaymentMethod::where('accountId',$trans->account_id)->first();
                $headers = [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $pTenUser->b_token,
                ];

                $webhook = new Client();
                $resp = $webhook->get($pTenUser->redirect_url . '/api/RyzenPay/p10/purchase/' . $trans->checkout_id, [
                    'headers' => $headers,
                    'timeout' => 25,
                ]);
                Log::channel('inabit')->info("P10 forward OK response from client, status: {$resp->getStatusCode()}");
            } else {
                Log::channel('inabit')->info(" missing PTenPaymentMethod details.");
            }
        } catch (RequestException $e) {
            Log::channel('inabit')->warning("Downstream forward failed: " . $e->getMessage());
        } catch(\Exception $e){
            Log::channel('inabit')->warning("Exception while sending our client webhook notification: " . $e->getMessage());
        }

        return response()->json(["success" => true , "transaction" => $trans], 200);
    }
}
