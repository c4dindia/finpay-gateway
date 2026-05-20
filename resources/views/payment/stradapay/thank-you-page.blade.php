<!doctype html>
<html lang="en">
<head>
    <title>Thank You</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--Bootstrap 5-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!--SweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Arial', sans-serif;
        }
        .thank-you-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .thank-you-container h1 {
            font-size: 2.5rem;
            color: #a9a7a7;
        }
        .thank-you-container h2 {
            font-size: 1.8rem;
            /*color: #dc3545;*/
        }
        .thank-you-container h3 {
            font-size: 1.25rem;
            color: #333;
        }
        .thank-you-container .card {
            /*background-color: #f8f9fa;*/
            border: none;
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="container mt-5 text-center">
    <div class="thank-you-container">
        <h1 class="mb-3 fw-bold">Thank You!</h1>

        @if (session('success'))
            <div class="card" style="background-color:  @if($status == 'Approved')  rgba(111, 219, 111, 0.904) @else rgb(228 122 21 / 41%) @endif">
                <h2 class="fw-bold" style="color:@if($status == 'Approved')  rgb(23 89 54 / 92%) @else rgb(104 36 36 / 90%) @endif">Payment {{ $status }}</h2>
                <h4><strong>Description:</strong> {{ ucfirst($description) }}</h4>
                <h4><strong>Amount:</strong> {{ $currency }} {{ round($amount, 2) }}</h4>
                <button class="btn-custom mt-3" onclick="window.location.href='#'">Have A Nice Day!</button>
            </div>
        @endif

        @if (session('error'))
            <div class="card" style="background-color: rgba(221, 163, 55, 0.904)">
                <h2>Error</h2>
                <h4>{{ session('error') }}</h4>
                <button class="btn-custom mt-3" onclick="window.location.href='#'">Try Again</button>
            </div>
        @endif
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@yield('scripts')

<!-- SweetAlert2 for notifications -->
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
            }
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: true
        }).then((result) => {
            @php
                session()->forget('error');
            @endphp
        });
    </script>
@endif

</body>
</html>
