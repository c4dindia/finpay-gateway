{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!--SweetAlert2-->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <!-- external css  -->
    <link rel="stylesheet" href="{{ asset('css/client-login.css') }}">
    <!-- bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="pt-5 d-flex justify-content-center login-logo">
        <img src="{{ asset('logo/finpay-logo.png') }}">
    </div>
    <div class="container">
        <!-- Login Form -->
        <div class="login-container" id="login-container">
            <div class="login-form">
                <h2 style="font-weight: 700;">Admin Login</h2>
                <form id="login-form" action="{{ url('/login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="example@mail.com"
                            name="email" value="{{ old('email') }}" autocomplete="off" aria-autocomplete="off"
                            required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password"
                            placeholder="********" required>
                        <img src="{{ asset('icons/eye_open.png') }}" alt="eye"
                            class="position-absolute eye-icon img-fluid">
                        <img src="{{ asset('icons/eye_close.png') }}" alt="eye-slash"
                            class="position-absolute eye-slash-icon img-fluid" style="display: none;">
                    </div>
                    <!-- Remember Me -->
                    {{-- <div class="mb-3">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div> --}}
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
                        {{-- <a href="#" id="forgot-password-link">Forgot password?</a> |
            <a href="#" id="register-link">Register</a> --}}
                    </div>
                </form>
            </div>

        </div>
        <div class="d-flex flex-column align-items-center justify-content-center mt-5">
            <!--<div class="login-logo-footer">-->
            <!--    <img src="{{ asset('images/Rayzen-Pay-logo.png') }}">-->
            <!--</div>-->
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
