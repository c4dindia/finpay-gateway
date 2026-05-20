<!doctype html>
<html lang="en">
  <head>
    <title>P4 Payment Page</title>
    <link rel="icon" href="{{ asset('images/ryzen-fav-logo.png') }}" type="image/x-icon">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Roboto Font-family -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">-->

    <style>
        #Checkout-header{
            font-family:"Roboto";
            font-weight:500;
            font-size:42px;
            line-height:100%;
            letter-spacing:0%;
        },
        .form-control:focus{
            font-family:"Roboto" !important;
            color: #000 !important;
            font-weight:400 !important;
            font-size:22px !important;
        },

        /* Hide number input up and down arrows in Chrome, Safari, and Edge */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Hide number input up and down arrows in Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
  </head>
  <body style="color: none!;">

    <div class="col-12" style="height: auto;">
        <div class="row">
            {{-- Left Side --}}
            <div class="text-center justify-content-center" style="background-image:url({{ asset('images/p4-bgr-img.png') }}) ; height:100vh; width:35%">
                <div class="p-1 mt-5">
                    <img src="{{ asset('images/p4-ryzen-pay-logo-white.png') }}" alt="" style="width:-webkit-fill-available">
                </div>
                <div class="mt-5">
                    <span style="font-family: roboto;font-weight:400; font-size:36px; line-height:100%; letter-spacing:0%; color:white">Amount to be Paid: </span><span style="font-family: roboto;font-weight: 700;  font-size:36px; line-height:100%; letter-spacing:0%; color:white">{{ $currency }} {{ $amount }}</span>
                </div>
            </div>
            {{-- Right Side --}}
            <div class="justify-content-center" style="height:100vh; width: 65%; ">
                <div class="p-1 mt-5 text-center">
                    <span id="Checkout-header">CHECKOUT PAGE</span>
                </div>
                <div class="container w-75 mt-5">
                    <form class="" method="POST" action="{{ route('processStradaPay',['checkout_id' => $checkout_id]) }}">
                        @csrf
                        <input type="text" name="merchant_ref_no" value="{{ $checkout_id }}" hidden>
                        <input type="text" name="currency" id="currency" class="form-control" placeholder="" value="{{ $currency }}" hidden aria-hidden="true">
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="" value="{{ $amount }}" hidden aria-hidden="true">
                        <div class="py-3 my-2" style="border-bottom: 1px solid  ">
                            <div class="row justify-content-between">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="card_holder_name" class="" style="color: #4E4E4E !important;">Name on Card</label>
                                        <input type="text" name="card_holder_name" id="card_holder_name" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="Card name" aria-describedby="helpId" required>
                                        <small id="helpId" class="text-muted">@error('card_holder_name'){{ $message }}@enderror</small>
                                    </div>
                                </div>
                                <div class="row col-6 justify-content-between">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label for="card_expiry_month" class="form-label"  style="color: #4E4E4E !important;">Expiry</label>
                                            <input type="text" name="expiry_date" id="expiry_date" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="MM/YYYY"  pattern="^(0[1-9]|1[0-2])\/\d{4}$" title="should be in MM/YYYY format" aria-describedby="helpId" required>
                                            <!--<input type="text" name="card_expiry_month" id="card_expiry_month" class="form-control" placeholder="" aria-describedby="helpId">-->
                                            <small id="helpId" class="text-muted">@error('card_expiry_month'){{ $message }}@enderror</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-between">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="card_number" class="form-label" style="color: #4E4E4E !important;">Card Number</label>
                                        <input type="number" name="card_number" id="card_number" inputmode="numeric" pattern="[0-9\s]{13,19}" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="Your 15-16 Digits Card Number..." aria-describedby="helpId" required>
                                        <!--<small id="helpId" class="text-muted">@error('card_number'){{ $message }}@enderror</small>-->
                                    </div>
                                </div>
                                <div class="row col-6 justify-content-between">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label for="cvv" class="form-label" style="color: #4E4E4E !important;">CVV</label>
                                            <input type="number" name="cvv" id="cvv" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="XXX" aria-describedby="helpId" required>
                                            <!--<small id="helpId" class="text-muted">@error('cvv'){{ $message }}@enderror</small>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="firstname" class="form-label"  style="color: #4E4E4E !important;">First Name</label>
                                    <input type="text" name="firstname" id="firstname" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="Omit Titles such as Dr., Col. etc." aria-describedby="helpId" required >
                                    <small id="helpId" class="text-muted">@error('firstname'){{ $message }}@enderror</small>
                                  </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="lastname" class="form-label"  style="color: #4E4E4E !important;">Last Name</label>
                                    <input type="text" name="lastname" id="lastname" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="" aria-describedby="helpId" required>
                                    <!--<small id="helpId" class="text-muted">@error('lastname'){{ $message }}@enderror</small>-->
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label" style="color: #4E4E4E !important;">Phone </label>
                                    <input type="text" name="phone" id="phone" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="10-12 Digit Phone Number" aria-describedby="helpId" required>
                                    <!--<small id="helpId" class="text-muted">@error('phone'){{ $message }}@enderror</small>-->
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email" class="form-label" style="color: #4E4E4E !important;">Email </label>
                                    <input type="email" name="email" id="email" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="your email address...." aria-describedby="helpId" required>
                                    <!--<small id="helpId" class="text-muted">@error('email'){{ $message }}@enderror</small>-->
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="address" class="form-label" style="color: #4E4E4E !important;">Address</label>
                                    <input type="text" name="address" id="address" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="Room No./Building/Street/Area..." aria-describedby="helpId" required>
                                    <!--<small id="helpId" class="text-muted">@error('address'){{ $message }}@enderror</small>-->
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="zipcode" class="form-label" style="color: #4E4E4E !important;">Zipcode</label>
                                    <input type="text" name="zipcode" id="zipcode" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="ZIP/Postal Code" aria-describedby="helpId" required>
                                    <!--<small id="helpId" class="text-muted">@error('zipcode'){{ $message }}@enderror</small>-->
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="city" class="form-label" style="color: #4E4E4E !important;">City</label>
                                    <input type="text" name="city" id="city" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="" aria-describedby="helpId" required>
                                    <!--<small id="helpId" class="text-muted">@error('city'){{ $message }}@enderror</small>-->
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="state" class="form-label" style="color: #4E4E4E !important;">State </label>
                                    <input type="text" name="state" id="state" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" placeholder="" aria-describedby="helpId" required>
                                    <!--<small id="helpId" class="text-muted">@error('state'){{ $message }}@enderror</small>-->
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="country" style="color: #4E4E4E !important;">Select Country</label>
                                    <select name="country" id="country" class="form-control" style="border: 1px solid #181818 !important; box-shadow: 0 0 0 1px #181818ba !important;" required>
                                        <option value="" disabled selected>Select a Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country['code'] }}">{{ $country['name'] }} ({{ $country['code'] }})</option>
                                        @endforeach
                                    </select>
                                    <!--<small id="helpId" class="text-muted">@error('country'){{ $message }}@enderror</small>-->
                                </div>
                            </div>
                        </div>



                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary text-center sm-rounded" style="height: 50px; width: 300px;">PAY</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

  </body>
</html>
