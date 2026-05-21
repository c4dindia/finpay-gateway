<!doctype html>
<html lang="en">
  <head>
    <title>New Jensen X1</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <script src="https://test.payments-ryvyl.eu/v1/paymentWidgets.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="row " style="justify-items:center">
        <div class="container">
            <div class="mt-5 p-5">


                <div class="p-3 text-center">
                    <div class="col-12 justify-items-center">
                        <img src="data:image/png;base64,{{ $payment_html }}" alt="payment_html h ye img">
                    </div>
                    <div class="col-12 m-2 p-3 justify-items-center justify-self-center">
                        <h6>Link: {{ $payment_link }}</h6>
                        <a href="{{ $payment_link }}" class="btn btn-success text-black" >PAY NOW</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>


{{-- <!doctype html>
<html lang="en">
  <head>
    <title>Useless Page</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    //<?php
    //    /**
    //    * Executes request
    //    *
    //    * @param       string      $url                Url for payment method
    //    * @param       array       $requestFields      Request data fields
    //    *
    //    * @return      array                           Host response fields
    //    *
    //    * @throws      RuntimeException                Error while executing request
    //    */
    //    function sendRequest($url, array $requestFields)
    //    {
    //        $curl = curl_init($url);
//
    //        curl_setopt_array($curl, array
    //        (
    //            CURLOPT_HEADER         => 0,
    //            CURLOPT_USERAGENT      => 'Finera-Client/1.0',
    //            CURLOPT_SSL_VERIFYHOST => 0,
    //            CURLOPT_SSL_VERIFYPEER => 0,
    //            CURLOPT_POST           => 1,
    //            CURLOPT_RETURNTRANSFER => 1
    //        ));
//
    //        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestFields));
//
    //        $response = curl_exec($curl);
//
    //        if(curl_errno($curl))
    //        {
    //            $error_message  = 'Error occurred: ' . curl_error($curl);
    //            $error_code     = curl_errno($curl);
    //        }
    //        elseif(curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200)
    //        {
    //            $error_code     = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //            $error_message  = "Error occurred. HTTP code: '{$error_code}'";
    //        }
//
    //        curl_close($curl);
//
    //        if (!empty($error_message))
    //        {
    //            throw new RuntimeException($error_message, $error_code);
    //        }
//
    //        if(empty($response))
    //        {
    //            throw new RuntimeException('Host response is empty');
    //        }
//
    //        $responseFields = array();
    //        parse_str($response, $responseFields);
//
    //        return $responseFields;
    //    }
//
    //    function signString($s, $merchantControl)
    //    {
    //        return sha1($s . $merchantControl);
    //    }
//
    //    /**
    //     * Signs payment (sale/auth/transfer) request
    //     *
    //     * @param 	array		$requestFields		request array
    //     * @param	string		$endpointOrGroupId	endpoint or endpoint group ID
    //     * @param	string		$merchantControl	merchant control key
    //     */
    //    function signPaymentRequest($requestFields, $endpointOrGroupId, $merchantControl)
    //    {
    //        $base = '';
    //        $base .= $endpointOrGroupId;
    //        $base .= $requestFields['client_orderid'];
    //        $base .= $requestFields['amount'] * 100;
    //        $base .= $requestFields['email'];
//
    //        return signString($base, $merchantControl);
    //    }
//
    //    /**
    //     * Signs status request
    //     *
    //     * @param 	array		$requestFields		request array
    //     * @param	string		$login			merchant login
    //     * @param	string		$merchantControl	merchant control key
    //     */
    //    function signStatusRequest($requestFields, $login, $merchantControl)
    //    {
    //        $base = '';
    //        $base .= $login;
    //        $base .= $requestFields['client_orderid'];
    //        $base .= $requestFields['orderid'];
//
    //        return signString($base, $merchantControl);
    //    }
//
    //    function signAccountVerificationRequest($requestFields, $endpointOrGroupId, $merchantControl)
    //    {
    //        $base = '';
    //        $base .= $endpointOrGroupId;
    //        $base .= $requestFields['client_orderid'];
    //        $base .= $requestFields['email'];
    //        return signString($base, $merchantControl);
    //    }
//
    //    $endpointId = "1";
    //    $merchantControl = 'B17F59B4-A7DC-41B4-8FF9-37D986B43D20';
//
    //    $requestFields = array(
    //        'client_orderid' => '902B4FF5',
    //        'order_desc' => 'Test Order Description',
    //        'first_name' => 'John',
    //        'last_name' => 'Smith',
    //        'ssn' => '1267',
    //        'birthday' => '19820115',
    //        'address1' => '100 Main st',
    //        'city' => 'Seattle',
    //        'state' => 'WA',
    //        'zip_code' => '98102',
    //        'country' => 'US',
    //        'phone' => '+12063582043',
    //        'cell_phone' => '+19023384543',
    //        'amount' => '1',
    //        'email' => 'john.smith@gmail.com',
    //        'currency' => 'USD',
    //        'ipaddress' => $_SERVER['REMOTE_ADDR'],
    //        'site_url' => 'www.google.com',
    //        'credit_card_number' => '0560013382566730',
    //        'card_printed_name' => 'CARD HOLDER',
    //        'expire_month' => '12',
    //        'expire_year' => '2099',
    //        'cvv2' => '123',
    //        'purpose' => 'user_account1',
    //        'redirect_url' => 'https://api.finera.com//doc/dummy.htm',
    //        'server_callback_url' => 'https://httpstat.us/200',
    //        'merchant_data' => 'VIP customer',
    //        'dapi_imei' => '123',
    //    );
//
    //    $requestFields['control'] = signPaymentRequest($requestFields, $endpointId, $merchantControl);
    //    Log::info('requestFields : ',$requestFields);
    //    $responseFields = sendRequest('https://sandbox.finera.com/paynet/api/v2/sale/'.$endpointId , $requestFields);
    //?>
  </head>
  <body>
    <div class="container">
        <div class="my-5">
            <h3> Finera sale API output:</h3>
        </div>
        <div class="p-3">
            <!-- {{ dd($responseFields) }} -->
            <!--<pre>{{ print_r($responseFields) }}</pre> -->
        </div>
    </div>


    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html> --}}



<div class="card-container-dashboard bg-brown-gradient" {{ $txn->currency != 'INR' ? 'style=display:none;' : '' }}>
    line 270 

    @php
    $company = Auth::user()->company()->first();
    $services = [
        'p12' => ['label' => '2D/3D',           'model' => \App\Models\PTwelvePaymentMethod::class],
        'p17' => ['label' => 'P-17 Dire',       'model' => \App\Models\Direpay::class],
        'p22' => ['label' => 'P-22 Uniqo',       'model' => \App\Models\UniqoPay::class],
        'p23' => ['label' => 'P-23 UPI',       'model' => \App\Models\UPIPayment::class],
    ];
@endphp
@foreach($services as $key => $service)
    @if($company && $service['model']::where('company_id', $company->id)->where('status', 1)->exists())
        <option value="{{ $key }}" {{ session('service') == $key ? 'selected' : '' }}>
            {{ $service['label'] }}
        </option>
    @endif
@endforeach

service filter