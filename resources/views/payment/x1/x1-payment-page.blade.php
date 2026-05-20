<!doctype html>
<html lang="en">
  <head>
    <title>X1 Payment Page</title>
    <link rel="icon" href="{{ asset('images/ryzen-fav-logo.png') }}" type="image/x-icon">
    <!-- Apple touch icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/ryzen-fav-logo.png') }}">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
      <div class="row text-center pt-2" style="justify-content:center;" >
          <img src="{{ asset('/images/new-logo.png')}}" style="height: 90px;">
      </div>
      <!--style="background-image: url({{ asset('images/money-background.jpg') }}); background-repeat:no-repeat; background-size:cover; background-position:center"-->
    <div class="container">
        <div class="row py-3 justify-content-center">
            <h2 class="text-center text-white">Welcome to Payments Page</h2>
        </div>

        <div id="sprkwdgt-{{ $widget_id }}"
            data-from-amount="{{ round($amount,2) }}"
            data-disable-from-amount="true"
            data-from-amount-hash="{{  hash('sha256', round($amount,2).$widget_secret_key) }}"
            data-from-currency="{{ $currency }}"
            data-disable-from-currency="true"
            data-from-currency-hash="{{  hash('sha256', $currency.$widget_secret_key) }}"
            data-from-method="EMP"
            data-from-method-hash="{{  hash('sha256', 'EMP'.$widget_secret_key) }}"
            data-hide-from-method="true"
            data-hide-to-amount="false"
            data-disable-to-amount="true"
            data-to-currency="USDC"
            data-hide-to-currency="true"
            data-to-currency-hash="{{  hash('sha256', 'USDC'.$widget_secret_key) }}"
            data-to-blockchain="TRX"
            data-disable-to-blockchain="true"
            data-to-blockchain-hash="{{  hash('sha256', 'TRX'.$widget_secret_key) }}"
            data-hide-destination="true"
            data-email="{{ $email }}"
            data-hide-email="true"
            data-email-hash="{{  hash('sha256', $email.$widget_secret_key) }}"
            data-address="{{ $data_address  }}"
            data-disable-address="true"
            data-address-hash="{{  hash('sha256', $data_address .$widget_secret_key) }}"
            data-hide-buy-more-button="true"
            data-hide-try-again-button="true"
            data-locale="en"
            data-payload='{ "account_id": "{{ $account_id }}", "checkout_id": "{{ $checkout_id }}" }'
            data-success-url="{{ $successUrl ?? null }}"
            data-error-url="{{ $errorUrl ?? null }}"
            data-redirect-top-parent="true"
            @if(in_array($widget_id,["WHLTDVOJ","WKEIKPGL"])) data-to-method="ETH" @endif
        ></div>
        </div>
    </div>

    <script async src="https://{{ $script_url }}/widgets/sdk.js"></script>
    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
 {{-- https://payments.howtopay.com/results/approved/ https://payments.howtopay.com/results/error/ --}}
