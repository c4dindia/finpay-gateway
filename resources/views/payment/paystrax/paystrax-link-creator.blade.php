<!doctype html>
<html lang="en">
  <head>
    <title>Payment Link Provider</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{ asset('images/ryzen-fav-logo.png') }}" type="image/x-icon">
    <!-- font awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- sweetalert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body style="background-color: rgb(240, 255, 244);">
    <div class="container" style=" height: 100vh;">
        <div class="text-center pt-5">
            <h3><img src="{{ asset('images/Rayzen-Pay-logo.png') }}" height="60px">Payment Link Provider</h3>
        </div>
        <div class="my-3">
            <form action="{{ route('paymentLinkPaystrax') }}" method="post" class="">
                @csrf
                <div class="p-2 my-3 align-iems-center">
                    <label for="currency" class="form-label">Enter the amount to create payment link:</label>
                    <select name="currency" id="currency" class="form-control">
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                    </select>
                </div>
                <div class="p-2 my-3 align-iems-center">
                    <label for="amount" class="form-label">Enter the amount to create payment link:</label>
                    <input type="number" class="form-control" step="0.01" name="amount" id="amount" placeholder="Enter Amount" required>
                </div>

                <div style="justify-self: center;">
                    <button type="submit" class="btn btn-success sm-rounded">Create Link</button>
                </div>
            </form>
        </div>
        @if (isset($checkout_id))
        <div class="container m-3 text-center">
            <div class="row align-iems-center text-center justify-content-center">
                <strong class="text-center">Generated Payment Link:
                    <small><a href="https://payment.ryzen-pay.com/p1/payment-link/{{ $checkout_id }}" id="link"> https://payment.ryzen-pay.com/p1/payment-link/{{ $checkout_id }}</a></small>
                </strong>
                <div class="px-2 text-center">
                    <button class=" btn fa-solid fa-copy" onclick="copytext()"></button>
                </div>
            </div>
        </div>
        @endif

    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

    <!-- Optional JavaScript -->
    <script>
        function copytext() {
            console.log('function called');
            var copyText = document.getElementById("link");
            var textToCopy = copyText.innerHTML;
            navigator.clipboard.writeText(textToCopy);
        }
    </script>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
