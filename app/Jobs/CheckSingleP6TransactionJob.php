<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Models\PSixPaymentMethod;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckSingleP6TransactionJob implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected static array $psixCache = [];

    public function __construct(public int $transactionId, public string $status) {}

    public function handle(): void
    {
        $trxn = Transaction::find($this->transactionId);

        if (!$trxn) {
            Log::warning("Transaction {$this->transactionId} not found");
            return;
        }

        $client = new Client([
            'timeout' => 15,
            'connect_timeout' => 5,
        ]);

        // Cache PSix per account
        if (!isset(self::$psixCache[$trxn->account_id])) {
            self::$psixCache[$trxn->account_id] =
                PSixPaymentMethod::where('accountId', $trxn->account_id)
                ->where('status', 1)
                ->first();
        }

        $checkaccId = self::$psixCache[$trxn->account_id];

        if (!$checkaccId) {
            Log::warning("No active PSixPaymentMethod for {$trxn->account_id}");
            return;
        }

        try {
            $response = $client->get(
                'https://api.transvoucher.com/v1.0/payment/status/' . $trxn->payment_id,
                [
                    'headers' => [
                        'X-API-Key'     => $checkaccId->apiKey,
                        'X-API-Secret'  => $checkaccId->secretKey,
                        'environment'   => 'production',
                        'Content-Type'  => 'application/json',
                    ]
                ]
            );

            $body = json_decode($response->getBody(), true);

            if (!($body['success'] ?? false)) {
                Log::warning("TransVoucher success=false for {$trxn->payment_id}", $body);
                return;
            }

            $data = $body['data'] ?? [];

            if (!empty($data['status'])) {
                $trxn->payment_status = ucfirst($data['status']);

                if (in_array($trxn->payment_status, ["Completed","Failed","Cancelled"])) {
                    $trxn->transvoucher_blockchainHashTrxn = $data['blockchain_tx_hash'] ?? null;
                    $trxn->transvoucher_card_brand = $data['payment_method']['card_brand'] ?? null;
                    $trxn->settled_amount = $data['settled_amount'] ?? null;
                }

                $trxn->save();

                Log::info("Transaction {$trxn->payment_id} updated to {$trxn->payment_status}");
            }

        } catch (RequestException $e) {
            Log::error("TransVoucher error for {$trxn->id}: ".$e->getMessage());
            return;
        }

        // Webhook
        if (ucfirst(strtolower($this->status)) != ucfirst(strtolower($trxn->payment_status))) {
            try {
                if ($checkaccId->redirect_url && $checkaccId->b_token) {
                    $webhook = new Client([
                        'timeout' => 15,
                        'connect_timeout' => 5,
                    ]);

                    $resp = $webhook->get(
                        $checkaccId->redirect_url . '/api/RyzenPay/p6/' . $trxn->checkout_id,
                        [
                            'headers' => [
                                'Content-Type'  => 'application/json',
                                'Authorization' => $checkaccId->b_token,
                            ]
                        ]
                    );

                    Log::info("Webhook OK for {$trxn->id}, status {$resp->getStatusCode()}");
                }
            } catch (\Exception $e) {
                Log::warning("Webhook failed for {$trxn->id}: ".$e->getMessage());
            }
        }
    }
}
