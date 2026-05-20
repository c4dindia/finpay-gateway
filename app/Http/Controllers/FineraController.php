<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class FineraController extends Controller
{
    private string $endpointId = '22903';
    private string $merchantControl = 'B17F59B4-A7DC-41B4-8FF9-37D986B43D20';
    private string $login = 'TestYujik';
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, //  Disable only in sandbox/testing
            'headers' => [
                'User-Agent' => 'Finera-Client/1.0'
            ]
        ]);
    }

    public function makePayment(array $data): array
    {
        $data['control'] = $this->signPaymentRequest($data);
        $url = "https://sandbox.finera.com/paynet/api/v2/sale/{$this->endpointId}";
        return $this->sendRequest($url, $data);
    }

    public function checkStatus(array $data): array
    {
        $data['control'] = $this->signStatusRequest($data);
        $url = 'https://sandbox.finera.com/paynet/api/v2/status';
        return $this->sendRequest($url, $data);
    }

    public function verifyAccount(array $data): array
    {
        $data['control'] = $this->signAccountVerificationRequest($data);
        $url = "https://sandbox.finera.com/paynet/api/v2/account/verify/{$this->endpointId}";
        return $this->sendRequest($url, $data);
    }

    private function sendRequest(string $url, array $requestFields): array
    {
        try {
            $response = $this->client->post($url, [
                'form_params' => $requestFields
            ]);

            $body = (string) $response->getBody();
            parse_str($body, $parsed);
            Log::info("Response Body : ".$body);

            if (empty($parsed)) {
                throw new RuntimeException('Host response is empty');
            }

            return $parsed;

        } catch (GuzzleException $e) {
            throw new RuntimeException('HTTP Request failed: ' . $e->getMessage(), $e->getCode());
        }
    }

    private function signPaymentRequest(array $fields): string
    {
        $base = $this->endpointId
              . $fields['client_orderid']
              . ((int)($fields['amount'] * 100))
              . $fields['email'];

        return $this->signString($base);
    }

    private function signStatusRequest(array $fields): string
    {
        $base = $this->login
              . $fields['client_orderid']
              . $fields['orderid'];

        return $this->signString($base);
    }

    private function signAccountVerificationRequest(array $fields): string
    {
        $base = $this->endpointId
              . $fields['client_orderid']
              . $fields['email'];

        return $this->signString($base);
    }

    private function signString(string $input): string
    {
        return sha1($input . $this->merchantControl);
    }

    // public function handle(Request $request)
    // {
    //     try {
    //         $client4 = new Client();
    //         $response4 = $client4->post('https://support.neurosyncventures.com/api/v1/decrypt-msg', [
    //         'headers' => [
    //             'Content-Type' => 'application/json',
    //             'Accept' => 'application/json'
    //         ],
    //         'json' => [
    //             'encrypted_card_number' =>  "-----BEGIN CardNumber MESSAGE-----\\ng0Aky2xvqb+nnpJnjzqiqEWxlZQZ8IT+iL8QBy7dg93LObGf9zvcLC7MMLKA/nRt\\nd4XSCusv34y7IYr4ssxLl8zs251aYw0vpT+llHMxrXZnV6nN89wnW8pQWz8x4cvP\\noKxD16A05ttSD5Q+aWKHaQmOO8hjXfKoXZwEykJkGs9l5ps8AzLaRlHfGjZdQmxR\\nBwYGz/j/GHiedNmhosktCe8mRtSSBYTx9z65Dir7mQddf8G08IsCWy+uqAwI+b7k\\nE2X7xn8g1mAZogL4xCCBTVUMFy6IYa1TPspxVV/MXPlEPWmPW0KRp5JFg/1/kF/6\\nzDXqZFUtxmIu7id9CInOMg==\\n-----END CardNumber MESSAGE-----\\n",
    //             'encrypted_cvv2' =>  "-----BEGIN CVV2 MESSAGE-----\\nYYzBRF1f6uJlWZe1TuFNj1m0c96qV4XAvg73IGZPSG880g6qyOwSPy1cDgZ9EUl0\\n5v9iJlI0rDKmARTx/XD5iusXy47nlb5bSgZnNd7Tees4u5EWAXIyAAFsCMELOn7u\\nAF3gYdMqgZgF6+ei2auZI15cTtE9txy6bZuL8HU1ZgA3a9tiap4ToKB6ylp8dEEd\\nIgip3+MPfQ82QPUaw71REnp5ax0I45GFXlW5tP24uOEImTV0RNLYt8i77y/Btoxz\\nsMk6p9d4mcCmikM0EAUKAZ4Gien/j/cB4fyikaAFMu8sxaxTH9qxjX5Dc+hA4i6C\\nVky/QqVtIqiBP0IS3bvJVw==\\n-----END CVV2 MESSAGE-----\\n"
    //         ]
    //     ]);
    //         return response()->json(["details" => $response4],200);
    //     $responseData4 = json_decode($response4->getBody()->getContents(), true);
    //     Log::info('decrypted data: ', ['response' => $responseData4 ?? null]);
    //     // dd($responseData4);
    //     $decryptCard = $responseData4['card_number'];
    //     $decryptCVV2 = $responseData4['cvv2'];

    //     return response()->json(["card" => $decryptCard, "cvv" => $decryptCVV2],200);
    //     } catch (RuntimeException $e) {
    //         Log::warning("warning",["error" => $e]);
    //     } catch(\Exception $e){
    //         Log::error($e);
    //     }
    // }

    public function handle(Request $request)
    {
        try {
            $client4 = new Client();

            $response4 = $client4->post('https://support.neurosyncventures.com/api/v1/decrypt-msg', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ],
                'json' => [
                    'encrypted_card_number' => "-----BEGIN CardNumber MESSAGE-----\ng0Aky2xvqb+nnpJnjzqiqEWxlZQZ8IT+iL8QBy7dg93LObGf9zvcLC7MMLKA/nRt\nd4XSCusv34y7IYr4ssxLl8zs251aYw0vpT+llHMxrXZnV6nN89wnW8pQWz8x4cvP\noKxD16A05ttSD5Q+aWKHaQmOO8hjXfKoXZwEykJkGs9l5ps8AzLaRlHfGjZdQmxR\nBwYGz/j/GHiedNmhosktCe8mRtSSBYTx9z65Dir7mQddf8G08IsCWy+uqAwI+b7k\nE2X7xn8g1mAZogL4xCCBTVUMFy6IYa1TPspxVV/MXPlEPWmPW0KRp5JFg/1/kF/6\nzDXqZFUtxmIu7id9CInOMg==\n-----END CardNumber MESSAGE-----\n",
                    'encrypted_cvv2' => "-----BEGIN CVV2 MESSAGE-----\nYYzBRF1f6uJlWZe1TuFNj1m0c96qV4XAvg73IGZPSG880g6qyOwSPy1cDgZ9EUl0\n5v9iJlI0rDKmARTx/XD5iusXy47nlb5bSgZnNd7Tees4u5EWAXIyAAFsCMELOn7u\nAF3gYdMqgZgF6+ei2auZI15cTtE9txy6bZuL8HU1ZgA3a9tiap4ToKB6ylp8dEEd\nIgip3+MPfQ82QPUaw71REnp5ax0I45GFXlW5tP24uOEImTV0RNLYt8i77y/Btoxz\nsMk6p9d4mcCmikM0EAUKAZ4Gien/j/cB4fyikaAFMu8sxaxTH9qxjX5Dc+hA4i6C\nVky/QqVtIqiBP0IS3bvJVw==\n-----END CVV2 MESSAGE-----\n",
                ],
            ]);

            // 1) Get raw body
            $bodyString = (string) $response4->getBody();

            Log::info('Decrypt API raw response: ' . $bodyString);

            // 2) Strip garbage before the first '{' (or '[' if they ever send arrays)
            $pos = strpos($bodyString, '{');
            if ($pos === false) {
                Log::error('Decrypt API: no JSON object found in response', [
                    'raw' => $bodyString,
                ]);

                return response()->json([
                    'message' => 'Invalid response from decrypt API',
                ], 500);
            }

            $cleanJson = substr($bodyString, $pos);

            // 3) Decode clean JSON
            $responseData4 = json_decode($cleanJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode decrypt API JSON', [
                    'error' => json_last_error_msg(),
                    'raw'   => $cleanJson,
                ]);

                return response()->json([
                    'message' => 'Failed to decode decrypt API response',
                ], 500);
            }

            $decryptCard = $responseData4['card_number'] ?? null;
            $decryptCVV2 = $responseData4['cvv2'] ?? null;

            return response()->json([
                // 'raw'  => $responseData4,
                'card' => $decryptCard,
                'cvv'  => $decryptCVV2,
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Decrypt API error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error calling decrypt API',
            ], 500);
        }
    }

    public function paypalContinue(Request $request)
    {
        $client = new Client(['verify' => false]); // Only disable in test mode

        $amount = $request->input('amount', '2.00');
        $currency = $request->input('currency', 'EUR');

        try {
            $response = $client->post("https://test.payments-ryvyl.eu/v1/checkouts", [
                'headers' => [
                    'Authorization' => 'Bearer OGFjN2E0Yzc4YTgxMjU1MzAxOGE4NDgzZjcyOTAzNTl8UXlUaFlMWG1vOCFANThVUGthVGY=',
                    'Accept' => 'application/json',
                ],
                'form_params' => [
                    'entityId'    => '8ac7a4c78a812553018a8483f6750355',
                    'amount'      => $amount,
                    'currency'    => $currency,
                    'paymentType' => 'DB',
                    'integrity'   => 'true',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json([
                'checkoutId' => $data['id'] ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}



//check status
// $response = $finera->checkStatus([
//     'client_orderid' => '902B4FF5',
//     'orderid' => '12345678', // replace with real order ID
// ]);

// //acc verification
// $response = $finera->verifyAccount([
//     'client_orderid' => '902B4FF5',
//     'email' => 'john.smith@gmail.com'
// ]);

// //json body
// {
//     "type": "payment",
//     "client_orderid": "902B4FF5",
//     "order_desc": "Test Order",
//     "first_name": "John",
//     "last_name": "Smith",
//     "ssn": "1267",
//     "birthday": "19820115",
//     "address1": "100 Main st",
//     "city": "Seattle",
//     "state": "WA",
//     "zip_code": "98102",
//     "country": "US",
//     "phone": "+12063582043",
//     "cell_phone": "+19023384543",
//     "amount": "10.42",
//     "email": "john.smith@gmail.com",
//     "currency": "USD",
//     "ipaddress": "65.153.12.232",
//     "site_url": "www.google.com",
//     "credit_card_number": "4538977399606732",
//     "card_printed_name": "CARD HOLDER",
//     "expire_month": "12",
//     "expire_year": "2099",
//     "cvv2": "123",
//     "purpose": "user_account1",
//     "redirect_url": "https://api.finera.com/doc/dummy.htm",
//     "server_callback_url": "https://httpstat.us/200",
//     "merchant_data": "VIP customer",
//     "dapi_imei": "123"
//   }
