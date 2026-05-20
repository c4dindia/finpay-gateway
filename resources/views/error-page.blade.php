<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('images/Rayzen-Pay-logo.png') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Expired</title>
     <!--SweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container justify-contents- text-center mt-5">
        <h1>Checkout Session has been Expired</h1>
        <p>This link is expired. Get a new one!</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
  {{-- sweetalert2 script --}}
    @if (session('success'))
    <script>
       Swal.fire({
           icon: 'success',
           title: '{{ session('success') }}',
           showConfirmButton: true
       }).then((result) => {
          if (result.isConfirmed) {
              @php
                  session()->forget('success');
              @endphp
              location.reload();
          }
       });
    </script>
    @endif

    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('error') }}',
        }).then((result) => {
        @php
            session()->forget('error');
        @endphp
    });
    </script>
    @endif
</html>
