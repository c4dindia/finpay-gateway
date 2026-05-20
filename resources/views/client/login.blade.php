<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- external css  -->
    <link rel="stylesheet" href="{{ asset('css/client-login.css') }}">
    <!-- bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="pt-5 d-flex justify-content-center login-logo">
        <img src="{{ asset('logo/finpay-logo.png') }}">
        {{-- <img src="https://payment.ryzen-pay.com/public/images/Rayzen-Pay-logo.png"> --}}
    </div>
    <div class="container">
        <!-- Login Form -->
        <div class="login-container" id="login-container">
            <div class="login-form">

                <form id="login-form" action="{{ route('clientSignIn') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control login-form-control" id="email"
                            placeholder="example@mail.com" name="email" value="{{ old('email') }}" autocomplete="off"
                            aria-autocomplete="off" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control pe-5" id="password"
                            placeholder="********" required>
                            <img src="{{ asset('icons/eye_open.png') }}" alt="eye" class="position-absolute eye-icon img-fluid">
                            <img src="{{ asset('icons/eye_close.png') }}" alt="eye-slash"
                                class="position-absolute eye-slash-icon img-fluid" style="display: none;">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-user btn-block btn-login mt-4">Sign
                            In</button>
                    </div>
                    <div class="mt-3 text-center">
                        @if ($errors->has('email') || $errors->has('password'))
                            <div class="alert alert-danger" id="error-message" role="alert">
                                Invalid Email or Password!
                            </div>
                        @endif
                        {{-- <a href="#" id="forgot-password-link">Forgot password?</a>
            <a href="#" id="register-link">Register</a> --}}
                    </div>
                </form>
            </div>

        </div>

        <div class="d-flex flex-column align-items-center justify-content-center mt-5">
            <div class="login-logo-footer">
                <img src="{{ asset('logo/finpay-logo.png') }}">
            </div>
            <div>
                <p class="footer-text">
                    Copyright © Finpay Group 2024. All Rights Reserved </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>

    <script>
        setTimeout(function() {
            var alertMessage = document.getElementById('error-message');
            if (alertMessage) {
                alertMessage.style.display = 'none'; // Hide the alert after 3 seconds
            }
        }, 3000); // 3000 ms = 3 seconds
    </script>
    {{-- @if (session('success'))
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
                location.reload(); // Reload the page
            }
         });
      </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                // showConfirmButton: true
            }).then((result) => {
            // Flush the session message
            @php
                session()->forget('error');
            @endphp
        });
        </script>
    @endif --}}

    <script>
        const eyeIcon = document.querySelector('.eye-icon');
        const eyeSlashIcon = document.querySelector('.eye-slash-icon');
        const passwordInput = document.querySelector('#password');
        eyeIcon.addEventListener('click', () => {
            eyeIcon.style.display = 'none';
            eyeSlashIcon.style.display = 'block';
            passwordInput.type = 'text';
        });
        eyeSlashIcon.addEventListener('click', () => {
            eyeIcon.style.display = 'block';
            eyeSlashIcon.style.display = 'none';
            passwordInput.type = 'password';
        });
    </script>
</body>

</html>
