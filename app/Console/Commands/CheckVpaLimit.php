<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\UPIPayment;
use App\Models\UpiMerchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\UpiPaymentController;

class CheckVpaLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckVpaLimit:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Vpa Limit';

    /**
     * Execute the console command.
     */

    protected $baseUrl = "https://gatewayeng.azure-api.net/upi/api";
    protected $baseUrlV2 = "https://payment-gateway.in/upi/api/2.12";
    protected $subscriptionKey = "1ceb19d850404bac9ae417b1ba0a4191";

    public function handle()
    {
        // Check for expired transactions

        $this->payinV1();

        $this->payinV2();
    }

    public function payinV1()
    {
        $transactions = Transaction::where('status', 'p23')
            ->where('payment_status', 'Pending')
            ->whereNotNull('payment_id')
            ->whereNotNull('card_number')
            ->where('card_number', 'like', '%@%')
            ->get();

        foreach ($transactions as $trans) {
            $client = new Client();

            try {
                $path = $this->baseUrl . "/1.0/checktxndetails";

                $payload = [
                    "txnid" => $trans->payment_id,
                    "clientrefid" => $trans->customer_details,
                ];

                $upiController = app(UpiPaymentController::class);

                $token = $upiController->accessToken();
                $checkSum = $upiController->generateChecksum($payload);

                $response = $client->post($path, [
                    'headers' => [
                        'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token,
                    ],
                    'json' => [
                        "payload" => $payload,
                        "checksum" => $checkSum,
                    ],
                ]);

                $data = json_decode($response->getBody(), true);

                if ($response->getStatusCode() === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {
                    $status = strtolower($data['response']['txnStatus'] ?? '');

                    if (strtolower($status) === 'success') {
                        $trans->payment_status = 'Completed';
                    } elseif (strtolower($status) === 'generated') {
                        $trans->payment_status = 'Pending';
                    } else {
                        $trans->payment_status = ucfirst(strtolower($status));
                    }

                    $createdAt = $trans->created_at->timestamp;
                    $expiresAt = $createdAt + (config('services.p23.payment_expiry_minutes') * 60); // expiry minutes
                    $isExpired = time() > $expiresAt;

                    if ($isExpired && $trans->payment_status == 'Pending') {
                        $trans->payment_status = 'Expired';
                    }

                    $trans->save();
                } else {
                    Log::error('UPI Cron: Transaction status API failed', [
                        'checkout_id' => $trans->checkout_id
                    ]);
                }
            } catch (RequestException $e) {
                Log::error('UPI Cron: Transaction status request exception', [
                    'checkout_id' => $trans->checkout_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function payinV2()
    {
        $transactions = Transaction::where('status', 'p23')
            ->where('payment_status', 'Pending')
            ->whereNotNull('payment_id')
            ->whereNotNull('card_number')
            ->where('card_number', 'not like', '%@%')
            ->get();

        foreach ($transactions as $trans) {
            $client = new Client();

            try {
                $path = $this->baseUrlV2 . "/checktxndetails";

                $payload = [
                    "txnid" => $trans->payment_id,
                    "clientrefid" => $trans->customer_details,
                    "mid"   => $trans->card_number
                ];

                $response = $client->post($path, [
                    'headers' => [
                        'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        "payload" => $payload
                    ],
                ]);

                $data = json_decode($response->getBody(), true);

                if ($response->getStatusCode() === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {
                    $status = strtolower($data['response']['txnStatus'] ?? '');

                    if (strtolower($status) === 'success') {
                        $trans->payment_status = 'Completed';
                    } elseif (strtolower($status) === 'generated') {
                        $trans->payment_status = 'Pending';
                    } else {
                        $trans->payment_status = ucfirst(strtolower($status));
                    }

                    $createdAt = $trans->created_at->timestamp;
                    $expiresAt = $createdAt + (config('services.p23.payment_expiry_minutes') * 60); // expiry minutes
                    $isExpired = time() > $expiresAt;

                    if ($isExpired && $trans->payment_status == 'Pending') {
                        $trans->payment_status = 'Expired';
                    }

                    $trans->save();
                } else {
                    Log::error('UPI Cron: V2Transaction status API failed', [
                        'checkout_id' => $trans->checkout_id,
                        'error' => $response->getBody()
                    ]);
                }
            } catch (RequestException $e) {
                Log::error('UPI Cron: V2 Transaction status request exception', [
                    'checkout_id' => $trans->checkout_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
