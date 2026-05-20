<?php

namespace App\Http\Controllers;

use App\Models\PNinePaymentMethod;
use App\Models\Transaction;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Validation\Rule;

class TrigoPaymentsController extends Controller
{
    /**
     * Map ISO currency codes to Trigo Currency IDs
     */
    protected array $currencyMap = [
        'USD' => 1,
        'EUR' => 2,
        'GBP' => 3,
        'AUD' => 4,
        'CAD' => 5,
        'JPY' => 6,
        'NOK' => 7,
        'PLN' => 8,
        'MXN' => 9,
        'ZAR' => 10,
        'RUB' => 11,
        'TRY' => 12,
        'CHF' => 13,
        'INR' => 14,
        'DKK' => 15,
        'SEK' => 16,
        'CNY' => 17,
        'HUF' => 18,
        'NZD' => 19,
        'HKD' => 20,
        'KRW' => 21,
        'SGD' => 22,
        'THB' => 23,
        'BSD' => 24,
        'KES' => 25,
        'BTC' => 26,
        'ETC' => 27,
        'SAR' => 29,
        'ACP' => 30,
        'AED' => 31,
        'KWD' => 32,
        'PHP' => 33,
        'TWD' => 34,
        'MYR' => 35,
        'UGX' => 36,
        'AFN' => 37,
        'ALL' => 38,
        'AMD' => 39,
        'ANG' => 40,
        'AOA' => 41,
        'ARS' => 42,
        'AWG' => 43,
        'AZN' => 44,
        'BAM' => 45,
        'BBD' => 46,
        'BDT' => 47,
        'BGN' => 48,
        'BHD' => 49,
        'BIF' => 50,
        'BMD' => 51,
        'BNB' => 172,
        'BND' => 52,
        'BOB' => 53,
        'BRL' => 54,
        'BTN' => 55,
        'BWP' => 56,
        'BYN' => 57,
        'BZD' => 58,
        'CDF' => 59,
        'CLF' => 60,
        'CLP' => 61,
        'COP' => 62,
        'CRC' => 63,
        'CRP' => 10006,
        'CUC' => 64,
        'CUP' => 65,
        'CVE' => 66,
        'CZK' => 67,
        'DJF' => 68,
        'DOP' => 69,
        'DZD' => 70,
        'EEK' => 71,
        'EGP' => 72,
        'ERN' => 73,
        'ETB' => 74,
        'FJD' => 75,
        'FKP' => 76,
        'GEL' => 77,
        'GGP' => 78,
        'GHS' => 79,
        'GIP' => 80,
        'GMD' => 81,
        'GNF' => 82,
        'GTQ' => 83,
        'GYD' => 84,
        'HNL' => 85,
        'HRK' => 86,
        'HTG' => 87,
        'IDR' => 88,
        'ILS' => 0,
        'IMP' => 89,
        'IQD' => 90,
        'IRR' => 91,
        'ISK' => 92,
        'JEP' => 93,
        'JMD' => 94,
        'JOD' => 95,
        'KGS' => 96,
        'KHR' => 97,
        'KMF' => 98,
        'KPW' => 99,
        'KYD' => 100,
        'KZT' => 101,
        'LAK' => 102,
        'LBP' => 103,
        'LKR' => 104,
        'LRD' => 105,
        'LSL' => 106,
        'LYD' => 107,
        'MAD' => 108,
        'MDL' => 109,
        'MGA' => 110,
        'MKD' => 111,
        'MMK' => 112,
        'MNT' => 113,
        'MOP' => 114,
        'MRU' => 115,
        'MTL' => 116,
        'MUR' => 117,
        'MVR' => 118,
        'MWK' => 119,
        'MZN' => 120,
        'NAD' => 121,
        'NGN' => 122,
        'NIO' => 123,
        'NPR' => 124,
        'OMR' => 125,
        'PAB' => 126,
        'PEN' => 127,
        'PGK' => 128,
        'PKR' => 129,
        'PNT' => 174,
        'PYG' => 130,
        'QAR' => 131,
        'RON' => 132,
        'RSD' => 133,
        'RWF' => 134,
        'SBD' => 135,
        'SCR' => 136,
        'SDG' => 137,
        'SHP' => 138,
        'SLL' => 139,
        'SOL' => 170,
        'SOS' => 140,
        'SRD' => 141,
        'SSP' => 142,
        'STN' => 143,
        'SVC' => 144,
        'SYP' => 145,
        'SZL' => 146,
        'TJS' => 147,
        'TMT' => 148,
        'TND' => 149,
        'TOP' => 150,
        'TTD' => 151,
        'TZS' => 152,
        'UAH' => 153,
        'USC' => 171,
        'USN' => 173,
        'UYU' => 154,
        'UZS' => 155,
        'VEF' => 156,
        'VND' => 157,
        'VUV' => 158,
        'WST' => 159,
        'XAF' => 160,
        'XAG' => 161,
        'XCD' => 162,
        'XDR' => 163,
        'XOF' => 164,
        'XPD' => 165,
        'XPF' => 166,
        'XPT' => 167,
        'YER' => 168,
        'ZMW' => 169,
    ];

    // protected $personal_hash = "7BPE5DCQNT";
    // protected $company_number = "8602997";
    // protected $trigopayBaseURL = "https://process.trigopayments.com/member/remote_charge.asp?";
     protected $trigopayHostedURL = "https://uiservices.trigopayments.com/hosted/?";


    // create a SALE transaction (TransType=0) via Server-to-Server
    public function s2hTrigoPay(Request $request, $accId)
    {
        $checkaccId = PNinePaymentMethod::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkaccId) {
            return response()->json(['error' => 'Unauthorized Account Id'], 401);
        }

        $validated = $request->validate([
            'amount'        => 'required|numeric|min:0.1',
            'currency'      => 'required|string|in:USD',
            'redirect_url'  => 'nullable',
        ]);

        $currencyInput = strtoupper($validated['currency']);

        if (is_numeric($currencyInput)) {
            $currencyId = (int) $currencyInput;
            $currencyCode = array_search($currencyId, $this->currencyMap, true);
            if ($currencyCode === false) {
                return response()->json(['error' => "Unsupported currency ID: {$currencyId}"], 422);
            }
        } else {
            if (!isset($this->currencyMap[$currencyInput])) {
                return response()->json(['error' => "Unsupported currency ISO code: {$currencyInput}"], 422);
            }
            $currencyCode = $currencyInput;
        }

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $personal_hash      = $checkaccId->trigo_personal_hash;
        $company_number     = $checkaccId->trigo_company_number;
        $trans_installments = 1;
        $ui_version         = 8;
        $trans_type         = 0;
        $trans_amount       = number_format($validated['amount'], 2, '.', '');
        $trans_currency     = $currencyCode;
        $disp_recurring     = 0;
        $disp_lng           = 'en-gb';
        $disp_mobile        = 'auto';
        $trans_refNum        = $uuid;
        $return_url         = $request->redirect_url ?? $checkaccId->redirect_url;

        $signaturePlain =
            $company_number
            . $trans_installments
            . $ui_version
            . $trans_type
            . $trans_amount
            . $trans_currency
            . $trans_refNum
            . $disp_recurring
            . $disp_lng
            . $disp_mobile
            . $return_url
            . $personal_hash;

        $signature = base64_encode(hash('sha256', $signaturePlain, true));
        $params = [
            'merchantID'        => $company_number,
            'trans_installments' => $trans_installments,
            'ui_version'         => $ui_version,
            'trans_type'         => $trans_type,
            'trans_amount'       => $trans_amount,
            'trans_currency'     => $trans_currency,
            'trans_refNum'       => $trans_refNum,
            'disp_recurring'    => $disp_recurring,
            'disp_lng'          => $disp_lng,
            'disp_mobile'       => $disp_mobile,
            'url_redirect'      => $return_url,
            'signature'         => $signature,
        ];

        $fullUrl = rtrim($this->trigopayHostedURL, '?') . '?' . http_build_query($params);

        $trxn = new Transaction();

        $trxn->account_id     = $checkaccId->accountId;
        $trxn->currency       = $currencyCode;
        $trxn->amount         = $trans_amount;
        $trxn->from_currency  = $currencyCode;
        $trxn->from_amount    = $trans_amount;
        $trxn->checkout_id    = $uuid;
        // $trans->payment_id     = $transactionId;
        $trxn->payment_status = 'Created';
        $trxn->description    = 'Payment created for '. $uuid;
        $trxn->status         = 'p9';

        $trxn->save();

        return response()->json([
            'success' => true,
            'checkout_id' => $uuid,
            'amount' => $trans_amount,
            'currency' => $trans_currency,
            'payment_link' => $fullUrl
        ], 200);
    }

    public function s2hTrigoPaySubscription(Request $request, $accId)
    {
        $checkaccId = PNinePaymentMethod::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkaccId) {
            return response()->json(['error' => 'Unauthorized Account Id'], 401);
        }

        $validated = $request->validate([
            'amount'           => ['required', 'numeric', 'min:0.1'],
            'currency'         => ['required', 'string', Rule::in(['USD'])],
            'redirect_url'     => ['nullable', 'starts_with:https://','url'],
            'numberOfCharges'  => ['required', 'integer', 'min:1', 'max:12'],
            'gapUnit'          => ['required', Rule::in(['D','W','M','Q','Y'])],
            'gapLength'        => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $currencyInput = strtoupper($validated['currency']);

        if (is_numeric($currencyInput)) {
            $currencyId = (int) $currencyInput;
            $currencyCode = array_search($currencyId, $this->currencyMap, true);
            if ($currencyCode === false) {
                return response()->json(['error' => "Unsupported currency ID: {$currencyId}"], 422);
            }
        } else {
            if (!isset($this->currencyMap[$currencyInput])) {
                return response()->json(['error' => "Unsupported currency ISO code: {$currencyInput}"], 422);
            }
            $currencyCode = $currencyInput;
        }

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $personal_hash      = $checkaccId->trigo_personal_hash;
        $company_number     = $checkaccId->trigo_company_number;
        $trans_installments = 1;
        $ui_version         = 8;
        $trans_type         = 0;
        $trans_amount       = number_format($validated['amount'], 2, '.', '');
        $trans_currency     = $currencyCode;
        $disp_recurring     = 0;
        $disp_lng           = 'en-gb';
        $disp_mobile        = 'auto';
        $trans_refNum        = $uuid;
        $return_url         = $request->redirect_url ?? $checkaccId->redirect_url;
        $trans_recurringType = 0;
        $trans_recurring1    =  (string) $validated['numberOfCharges'] . $validated['gapUnit'] . (string) $validated['gapLength']; //    "10M1"

        $signaturePlain =
            $company_number
            . $trans_installments
            . $ui_version
            . $trans_type
            . $trans_amount
            . $trans_currency
            . $trans_refNum
            . $disp_recurring
            . $disp_lng
            . $disp_mobile
            . $return_url
            . $trans_recurringType
            . $trans_recurring1
            . $personal_hash;

        $signature = base64_encode(hash('sha256', $signaturePlain, true));
        $params = [
            'merchantID'         => $company_number,
            'trans_installments' => $trans_installments,
            'ui_version'         => $ui_version,
            'trans_type'         => $trans_type,
            'trans_amount'       => $trans_amount,
            'trans_currency'     => $trans_currency,
            'trans_refNum'       => $trans_refNum,
            'disp_recurring'     => $disp_recurring,
            'disp_lng'           => $disp_lng,
            'disp_mobile'        => $disp_mobile,
            'url_redirect'       => $return_url,
            'trans_recurringType' => $trans_recurringType,
            'trans_recurring1'    => $trans_recurring1,
            'signature'          => $signature
        ];

        $fullUrl = rtrim($this->trigopayHostedURL, '?') . '?' . http_build_query($params);

        $trxn = new Transaction();
        $trxn->account_id     = $checkaccId->accountId;
        $trxn->currency       = $currencyCode;
        $trxn->amount         = $trans_amount;
        $trxn->from_currency  = $currencyCode;
        $trxn->from_amount    = $trans_amount;
        $trxn->checkout_id    = $uuid;
        // $trans->payment_id     = $transactionId;
        $trxn->payment_status = 'Created';
        $trxn->description    = 'Payment created for ' . $uuid;
        $trxn->status         = 'p9';
        $trxn->save();

        return response()->json([
            'success' => true,
            'checkout_id' => $uuid,
            'amount' => $trans_amount,
            'currency' => $trans_currency,
            'payment_link' => $fullUrl
        ], 200);
    }

    /**
     * Webhook endpoint for notification_url (SILENTPOST)
     */
    public function webhook(Request $request)
    {
        Log::info("started Trigo-payments webhook");
        $httpMethod  = $request->getMethod();                        // GET / POST
        $contentType = $request->header('content-type', '');
        $ip          = $request->ip();
        $userAgent   = $request->header('user-agent', '');

        $data = $request->all();
        if (empty($data)) {
            $payload    = $request->getContent() ?: $request->server('QUERY_STRING', '');
            $normalized = preg_replace('/\s*&\s*/', '&', trim($payload));
            parse_str($normalized, $data);
        }

        Log::info('Trigo SALE webhook raw', [
            'method'      => $httpMethod,
            'contentType' => $contentType,
            'ip'          => $ip,
            'ua'          => $userAgent,
            'data'        => $data,
        ]);

        $replyCode      = $data['reply_code']      ?? null; // 000 / 553 / others
        $replyDesc      = $data['reply_desc']      ?? null;
        $transId        = $data['trans_id']        ?? null;
        $transAmount    = $data['trans_amount']    ?? null;
        $transCurrency  = $data['trans_currency']  ?? null; // ISO code: USD/EUR/GBP
        $transOrder     = $data['trans_order']     ?? null; // your order/checkout reference
        $merchantId     = $data['merchant_id']     ?? null;

        $clientFullname = $data['client_fullname'] ?? null;
        $clientPhone    = $data['client_phone']    ?? null;
        $clientEmail    = $data['client_email']    ?? null;

        $paymentDetails = $data['payment_details'] ?? null; // payment method + last4
        $cardBrand      = null;
        $cardNumber     = null;
        $debRefNum      = $data['debrefnum']       ?? null;
        $remoteSignature = $data['signature'] ?? $data['Signature'] ?? null;
        $systemReference = $data['system_reference'] ?? null;
        $refId = $transOrder ?: $systemReference ?: $debRefNum ?: $transId;

        if (!empty($paymentDetails))
        {
            $last4 = null;
            if (preg_match('/(\d{4})\D*$/', $paymentDetails, $m)) {
                $last4 = $m[1];
            }

            $dotPos   = strpos($paymentDetails, '.');
            $brandRaw = $dotPos !== false
                ? substr($paymentDetails, 0, $dotPos)
                : preg_replace('/[\s\.\-]*\d.*$/', '', $paymentDetails);

            $brandNorm = strtoupper(trim((string)$brandRaw, " \t\n\r\0\x0B.-"));
            if ($brandNorm !== '')  $cardBrand  = $brandNorm;
            if ($last4)             $cardNumber = '**** '.$last4;
        }

        // ── 2. Map reply_code → status text per spec ────────────────────────────────
        $rc = (string)($replyCode ?? '');                   // normalize to string

        $statusText  = 'DECLINED';                          // default as per "Bank Reject Codes"
        $actionHint  = null;                                // for internal use/logging

        if ($rc === '000') {
            $statusText = 'APPROVED';
        }
        elseif (in_array($rc, ['553','663','001'], true)) {
            $statusText = 'PENDING';
            if ($rc === '553') {
                $actionHint = 'REDIRECT_3DS_OR_APM';
            }
        }
        else {
            $isDeclined = false;

            if (str_starts_with($rc, '100.')) {
                $isDeclined = true;
            }

            if (in_array($rc, ['1001','1002','1300'], true)) {
                $isDeclined = true;
            }

            if (ctype_digit($rc)) {
                $n = (int)$rc;
                if ($n >= 500 && $n <= 665) {
                    $isDeclined = true;
                }
            }

            $statusText = $isDeclined ? 'DECLINED' : 'DECLINED';
        }

        Log::info('Trigo SALE webhook status resolution', [
            'reply_code'  => $rc,
            'status'      => $statusText,        // APPROVED | PENDING | DECLINED
            'action_hint' => $actionHint,        // e.g., REDIRECT_3DS_OR_APM
        ]);
        $payment_response_message = $replyDesc ?? $paymentDetails ?? 'Gateway response';

        $merchantHash = null;
        $checkaccId   = null;

        $trxn = Transaction::where('checkout_id', $refId)->where('status', 'p9')->first();
        $checkaccId = PNinePaymentMethod::where('accountId', $trxn->account_id)->first();

        if (!$trxn) {
            $trxn = new Transaction();
            // If you can derive the account from merchant, do it here:
            if ($checkaccId && !empty($checkaccId->accountId)) {
                $trxn->account_id = $checkaccId->accountId;
            }
        }

        if ($checkaccId && !empty($checkaccId->trigo_personal_hash) && $remoteSignature) {
            $merchantHash = $checkaccId->trigo_personal_hash;

            // base64( sha256( trans_id + trans_order + reply_code + trans_amount + trans_currency + merchanthash ) )
            $plain = ($transId ?? '')
                   . ($transOrder ?? '')     // NOTE: spec uses trans_order (may be empty)
                   . ($replyCode ?? '')
                   . ($transAmount ?? '')
                   . ($transCurrency ?? '')
                   . $merchantHash;

            $expected = base64_encode(hash('sha256', $plain, true));

            if (!hash_equals($expected, $remoteSignature)) {
                Log::warning('Trigo SALE webhook: invalid signature', [
                    'expected' => $expected,
                    'received' => $remoteSignature,
                    'refId'    => $refId,
                ]);

                // return response('Invalid signature', 400);
            }
        } else {
            Log::warning('Trigo SALE webhook: signature or merchant hash missing', [
                'hasSig'    => (bool)$remoteSignature,
                'refId'     => $refId,
            ]);
        }

        $trxn->currency       = $transCurrency;
        $trxn->amount         = $transAmount;
        $trxn->checkout_id    = $refId;                 // NEVER null now
        $trxn->payment_id     = $transId;
        $trxn->payment_status = ucfirst(strtolower($statusText)); // Approved/Pending/Declined
        $trxn->description    = $payment_response_message;
        $trxn->customer_details= Str::of("Name: {$clientFullname} , Email: {$clientEmail} , Phone: {$clientPhone}")->squish()->trim();
        $trxn->card_number = $cardNumber;             // **** 9581
        $trxn->transvoucher_card_brand = $cardBrand;  // VISA
        $trxn->status         = 'p9';

        $trxn->save();

        try {
            if ($checkaccId && $checkaccId->redirect_url && $checkaccId->b_token) {
                $headers = [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $checkaccId->b_token,
                ];

                $webhook = new Client();
                $resp = $webhook->get($checkaccId->redirect_url . '/api/RyzenPay/p9/' . $trxn->checkout_id, [
                    'headers' => $headers,
                    'timeout' => 15,
                ]);
                Log::info("P9 forward OK response from client, status: {$resp->getStatusCode()}");
            } else {
                Log::info(" missing PNinePaymentMethod details.");
            }
        } catch (GuzzleException $e) {                 // <— catches ConnectException, RequestException, etc.
            Log::warning("P9 forward toClient failed (Guzzle): {$e->getMessage()}");
        } catch (\Throwable $e) {                      // <— catch anything else, never leak to response
            Log::error("P9 forward toClient failed (Throwable): {$e->getMessage()}");
        }

        Log::info("completed Trigo-payments webhook");

        return response()->json([
            "success" => true
        ],200);
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = PNinePaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }
        $transaction = Transaction::where('checkout_id',$checkout_id)->where('status','p9')->latest('id')->first();
        if($transaction == null){
            return response()->json(['error' => 'Unauthorized Checkout Id'],401);
        }

        return response()->json([
            'data' => [
                "account_id" => $transaction->account_id,
                "currency" => $transaction->currency,
                "amount" => $transaction->amount,
                "from_currency" => $transaction->from_currency,
                "from_amount" => $transaction->from_amount,
                "checkout_id" => $transaction->checkout_id,
                "payment_id" => $transaction->payment_id,
                "payment_status" => ucfirst($transaction->payment_status),
                "description" => $transaction->description ?? null,
                "created_at" => $transaction->created_at,
                "updated_at" => $transaction->updated_at,
            ]
        ],200);
    }

}
