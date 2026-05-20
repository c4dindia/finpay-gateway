<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Payment Page</title>
  <link rel="icon" href="{{ asset('images/ryzen-fav-logo.png') }}" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('css/online_p1_payment_card.css') }}">
   <!-- font awesome  -->
   <script src="https://kit.fontawesome.com/6036d46694.js" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body style="background-image: linear-gradient(to left, var(--main-color), #000000);">

<!-- Navbar for small screens -->
<nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-none">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border: none;background-color: none;">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">
      <img src="../assets/logo.png" alt="">
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav side-nav">
        <li class="nav-item active">
          <a class="nav-link" href="#"><i class="fa-solid fa-house px-2"></i>Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fa-solid fa-clipboard px-2"></i>Staff</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fa-solid fa-calendar-week px-2"></i>Payroll</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fa-solid fa-circle-check px-2"></i>Subscription</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fa-solid fa-store px-2"></i>Card expenses</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!------------------------ Main Content ----------------------->
<div class="container">

<!-- ------------------------Right side content ------------------- -->
        <div class="row mt-5 d-flex justify-content-center" style="padding:0 20px;">
                <div class="row align-items-center">
                    <div class="col-sm-12 col-md-6 col-lg-6 payment-card-left-side-wrap p-3 text-center" style="align-content: center;" >
                       <div class="pay-page-logo">
                        <img src="https://ryzen-pay.com/wp-content/uploads/2024/10/Rayzen-Pay-logo.png" alt="">
                       </div>
                       {{-- <div class="pt-5 pb-2">
                          <h5  style="font-size: 25px;" class="mb-0">To:</h5>
                          <p class="mb-0" style="font-weight:600;font-size: 30px;color:aliceblue;">{{ $companyName }}</p>
                       </div> --}}
                       <div class="row date-time-section mt-3">
                            <h4  style="font-size: 25px;">Amount To be Paid:</h4>
                            <p class="mb-0" style="font-weight:600;font-size: 30px;color:aliceblue;">{{ $currency }}<span> {{ $amount }}</span></p>
                       </div>
                        <div class="pt-5 pb-2">
                            <h5  style="font-size: 25px;" class="mb-0">Time Remaining:  <span class="mb-0" style="font-weight:600;font-size: 30px;color:aliceblue;" id="timer">15:00</span></h5>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-6 d-flex  flex-column align-items-center payment-card-right-side-wrap" >
                      <h4 class="mt-5" style="color: black;font-size: 50px;font-weight: 600;">PAYMENT PAGE</h4>
                      <div class="mt-5 py-5 d-flex justify-content-center flex-column align-items-center">
                      <form action="{{ url('/payment/p5/payment-status') }}/{{ $checkout_id }}" class="paymentWidgets " data-brands="MAESTRO MASTER MASTERDEBIT VISA VISADEBIT VISAELECTRON VPAY RUPAY AMEX" method="get">
                      </form>
                    </div>
                    <p style="font-size:25px;font-weight:300;color: black;">Make your Payments Faster & Simple. </p>
                    <div class="pay-img-wrapper mt-5 p-5 d-flex gap-2 justify-content-center align-items-center">
                        <img src="{{ asset('images/visa.png') }}" alt="">
                        <img src="{{ asset('images/mastercard.png') }}" alt="">
                        <img src="{{ asset('images/American-Express.png') }}" alt="">
                        <img src="{{ asset('images/Rupay-Logo.png') }}" alt="">
                    </div>
                    </div>
                </div>
        </div>

</div>

<!-- Bootstrap 5 JavaScript (includes Bootstrap's bundled JS for components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    // Pass the $chkId value safely from PHP to JavaScript
    var checkoutId = @json($checkout_id);
    var integrity = @json($integrity);
    console.log(checkoutId);
    console.log(integrity);
    // Dynamically create the script tag for the paymentWidgets.js script
    var script = document.createElement('script');
    script.src = `https://test.payments-ryvyl.eu/v1/paymentWidgets.js?checkoutId=${checkoutId}`; //paystrax prod. url: `https://eu-prod.oppwa.com/v1/paymentWidgets.js?checkoutId=${checkoutId}`
    script.type = 'text/javascript';
    script.integrity = `${integrity}`;
    script.crossOrigin = "anonymous";
    document.body.appendChild(script);
</script>
<script>
    var wpwlOptions = {
        locale: "en",
        iframeStyles: {
             'card-number-placeholder': {
                // 'color': '#FF0000',
                'font-size': '16px',
                'font-family': 'Arial'
            },
            'cvv-placeholder': {
                // 'color': '#0000FF',
                'font-size': '16px',
                'font-family': 'Arial'
            },
            'cardholder-name-placeholder': {
                // 'color': '#0000FF',
                'font-size': '16px',
                'font-family': 'Arial'
            }
        }
    }
</script>
<!--Countdown Timer Script-->
    <script>
        const createdAt = new Date("{{ \Carbon\Carbon::parse($created_at)->toIso8601String() }}");
        const expiryTime = new Date(createdAt.getTime() + 20 * 60000); // 20 mins in milliseconds

        function updateCountdown() {
            const now = new Date();
            const diff = expiryTime - now;

            if (diff <= 0) {
                window.location.href = "{{ url('/error-payment-page') }}";
                return;
            }

            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);

            const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
            const formattedSeconds = seconds < 10 ? '0' + seconds : seconds;

            document.getElementById('timer').textContent = formattedMinutes + ':' + formattedSeconds;
        }

        updateCountdown(); // Initial call
        setInterval(updateCountdown, 1000); // Update every second
    </script>
</body>
</html>
