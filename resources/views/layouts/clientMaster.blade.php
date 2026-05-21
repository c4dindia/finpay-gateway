<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('images/Rayzen-Pay-logo.png') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Updated Bootstrap 5 CDN links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

    <!--SweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- layout index css  -->
    <link rel="stylesheet" href="{{ asset('css/clientside/index.css') }}">
    <!-- page-content css -->
    @yield('css')
    <!-- font awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* ensure anchor container can position dropdown */
        .profile-parent {
            position: relative;
        }
    </style>
</head>

<body>
@php
     $accId = \App\Models\Company::where('user_id', Auth::id())->value('accountId');
@endphp
    {{-- Mobile nav bar --}}
    <nav class="navbar navbar-expand-lg d-flex fixed-top d-lg-none shadow-sm">
        <div class="container-fluid">
            {{-- <a class="navbar-brand" href="#">Logo</a> --}}
            <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileNav" aria-controls="mobileNav" aria-label="Toggle navigation">
                <svg width="35" height="35" viewBox="0 0 35 35" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M28.8684 25.5882C29.4158 25.5885 29.9422 25.8067 30.3383 26.1977C30.7345 26.5886 30.9702 27.1223 30.9965 27.6881C31.0228 28.254 30.8377 28.8086 30.4796 29.2371C30.1216 29.6655 29.6179 29.935 29.073 29.9897L28.8684 30H6.13158C5.58417 29.9997 5.05784 29.7815 4.66166 29.3906C4.26548 28.9996 4.02983 28.466 4.00352 27.9001C3.97721 27.3343 4.16227 26.7797 4.52035 26.3512C4.87844 25.9227 5.38209 25.6532 5.92695 25.5985L6.13158 25.5882H28.8684ZM28.8684 15.2941C29.4338 15.2941 29.9759 15.5265 30.3757 15.9402C30.7754 16.3539 31 16.915 31 17.5C31 18.085 30.7754 18.6461 30.3757 19.0598C29.9759 19.4735 29.4338 19.7059 28.8684 19.7059H6.13158C5.56625 19.7059 5.02407 19.4735 4.62433 19.0598C4.22458 18.6461 4 18.085 4 17.5C4 16.915 4.22458 16.3539 4.62433 15.9402C5.02407 15.5265 5.56625 15.2941 6.13158 15.2941H28.8684ZM28.8684 5C29.4338 5 29.9759 5.2324 30.3757 5.64609C30.7754 6.05977 31 6.62085 31 7.20588C31 7.79092 30.7754 8.35199 30.3757 8.76568C29.9759 9.17936 29.4338 9.41176 28.8684 9.41176H6.13158C5.56625 9.41176 5.02407 9.17936 4.62433 8.76568C4.22458 8.35199 4 7.79092 4 7.20588C4 6.62085 4.22458 6.05977 4.62433 5.64609C5.02407 5.2324 5.56625 5 6.13158 5H28.8684Z"
                        fill="black" />
                </svg>

            </button>
            <div class="d-flex justify-content-start align-items-center">
                <div class="navbar-logo-container-mob d-flex justify-content-center align-items-center">
                    <img src="{{ asset('logo/finpay-logo.png') }}" alt="Main-logo" class="navbar-logo" loading="lazy"
                        decoding="async">
                </div>
            </div>
            <div
                class="profile-parent d-flex d-lg-none flex-column flex-md-row align-items-center justify-content-center justify-content-md-between gap-3 client-top-right">
                <div class="logout-box-mob">
                    <!-- <p class="mb-0">Logout &nbsp; <i class="fa-solid fa-right-from-bracket"></i></p> -->
                    <form method="POST" action="{{ route('logout') }}" style="text-align:center">
                        @csrf
                        <button type="submit" class="logout-btn justify-content-center">LOGOUT</button>
                        &nbsp; <i class="fa-solid fa-right-from-bracket"></i>
                    </form>
                </div>

                @php
                    $initial = substr(Auth::User()->name, 0, 1);
                @endphp
                <div class="d-flex align-items-center justify-content-center rounded-circle text-white px-2"
                    style="background: #0035AA;">
                    <p class="fs-5 fw-bold p-1 mb-0">{{ $initial }}</p>
                </div>
                {{-- <img src="
    @if (Auth::User()->name == 'HowToPay') {{ asset('images/Rayzen-Pay-logo.png') }}
    @elseif(Auth::User()->name == 'Jensen')
      {{ asset('images/Rayzen-Pay-logo.png') }}
    @else
      https://st4.depositphotos.com/24244980/25394/i/450/depositphotos_253942934-stock-photo-company-profile-eyeball-blue-round.jpg @endif"
                    alt="" id="profile-mob" class="profile-trigger" style="cursor:pointer;"> --}}
            </div>


            <div class="offcanvas offcanvas-start rounded-top-end-3 rounded-bottom-end-3" tabindex="-1" id="mobileNav"
                aria-labelledby="mobileNavLabel" style="max-width: max-content">
                <div class="w-100 d-flex justify-content-end">
                    <button type="button" class="btn-close pe-4 pt-4 mt-3" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-header">
                    <div class="navbar-logo-container">
                        <img src="{{ asset('logo/finpay-logo.png') }}" alt="Main-logo" class="navbar-logo" loading="lazy"
                            decoding="async">
                    </div>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav d-flex justify-content-start">
                        <li class="nav-item trans-icon-fill @if ($currentPage == 'Home') active @endif"><a
                                class="nav-link" href="{{ route('showHome') }}"><svg width="34" height="34"
                                    viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path class="{{ $currentPage == 'Home' ? 'fill-white' : 'fill-black' }}"
                                        d="M18.8333 11.5V0.5H33.5V11.5H18.8333ZM0.5 18.8333V0.5H15.1667V18.8333H0.5ZM18.8333 33.5V15.1667H33.5V33.5H18.8333ZM0.5 33.5V22.5H15.1667V33.5H0.5Z"
                                        fill="white" />
                                </svg>
                                Dashboard</a></li>
                        <li class="nav-item trans-icon-fill @if ($currentPage == 'Documentations') active @endif"><a
                                class="nav-link" href="{{ route('showDocumentations') }}"><svg width="34"
                                    height="34" viewBox="0 0 34 34" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path class="{{ $currentPage == 'Documentations' ? 'fill-white' : 'fill-black' }}"
                                        fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16.7507 1.16667C16.7507 1.05616 16.7068 0.950179 16.6286 0.872039C16.5505 0.793899 16.4445 0.75 16.334 0.75H4.66732C3.45174 0.75 2.28595 1.23289 1.42641 2.09243C0.56687 2.95197 0.0839844 4.11776 0.0839844 5.33333V28.6667C0.0839844 29.8822 0.56687 31.048 1.42641 31.9076C2.28595 32.7671 3.45174 33.25 4.66732 33.25H21.334C22.5496 33.25 23.7154 32.7671 24.5749 31.9076C25.4344 31.048 25.9173 29.8822 25.9173 28.6667V12.245C25.9173 12.1345 25.8734 12.0285 25.7953 11.9504C25.7171 11.8722 25.6112 11.8283 25.5007 11.8283H18.0007C17.6691 11.8283 17.3512 11.6966 17.1168 11.4622C16.8823 11.2278 16.7507 10.9099 16.7507 10.5783V1.16667ZM18.0007 17.4167C18.3322 17.4167 18.6501 17.5484 18.8845 17.7828C19.119 18.0172 19.2507 18.3351 19.2507 18.6667C19.2507 18.9982 19.119 19.3161 18.8845 19.5505C18.6501 19.785 18.3322 19.9167 18.0007 19.9167H8.00065C7.66913 19.9167 7.35119 19.785 7.11677 19.5505C6.88235 19.3161 6.75065 18.9982 6.75065 18.6667C6.75065 18.3351 6.88235 18.0172 7.11677 17.7828C7.35119 17.5484 7.66913 17.4167 8.00065 17.4167H18.0007ZM18.0007 24.0833C18.3322 24.0833 18.6501 24.215 18.8845 24.4494C19.119 24.6839 19.2507 25.0018 19.2507 25.3333C19.2507 25.6649 19.119 25.9828 18.8845 26.2172C18.6501 26.4516 18.3322 26.5833 18.0007 26.5833H8.00065C7.66913 26.5833 7.35119 26.4516 7.11677 26.2172C6.88235 25.9828 6.75065 25.6649 6.75065 25.3333C6.75065 25.0018 6.88235 24.6839 7.11677 24.4494C7.35119 24.215 7.66913 24.0833 8.00065 24.0833H18.0007Z" />
                                </svg>Documentations</a></li>
                        <li class="nav-item trans-icon-fill @if ($currentPage == 'developers-area') active @endif"><a
                                class="nav-link" href="{{ route('showDevelopersArea') }}"><svg width="34"
                                    height="34" viewBox="0 0 34 34" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        class="{{ $currentPage == 'developers-area' ? 'fill-white' : 'fill-black' }}"
                                        fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11.3748 8.125H27.6248V9.75C27.6248 10.6438 28.3561 11.375 29.2498 11.375C30.1436 11.375 30.8748 10.6438 30.8748 9.75V4.875C30.8748 3.0875 29.4123 1.64125 27.6248 1.64125L11.3748 1.625C9.58733 1.625 8.12483 3.0875 8.12483 4.875V9.75C8.12483 10.6438 8.85608 11.375 9.74983 11.375C10.6436 11.375 11.3748 10.6438 11.3748 9.75V8.125ZM26.1948 25.805L31.3461 20.6537C31.4967 20.5034 31.6162 20.3248 31.6978 20.1283C31.7793 19.9317 31.8213 19.7209 31.8213 19.5081C31.8213 19.2953 31.7793 19.0846 31.6978 18.888C31.6162 18.6914 31.4967 18.5128 31.3461 18.3625L26.1948 13.2113C25.5611 12.5775 24.5211 12.5775 23.8873 13.2113C23.7367 13.3616 23.6172 13.5402 23.5356 13.7367C23.4541 13.9333 23.4121 14.1441 23.4121 14.3569C23.4121 14.5697 23.4541 14.7804 23.5356 14.977C23.6172 15.1736 23.7367 15.3522 23.8873 15.5025L27.9011 19.5L23.8873 23.5138C23.7367 23.6641 23.6172 23.8427 23.5356 24.0392C23.4541 24.2358 23.4121 24.4466 23.4121 24.6594C23.4121 24.8722 23.4541 25.0829 23.5356 25.2795C23.6172 25.4761 23.7367 25.6547 23.8873 25.805C24.5211 26.4387 25.5611 26.4387 26.1948 25.805ZM15.0961 23.4975L11.0986 19.5L15.0961 15.5025C15.2467 15.3522 15.3662 15.1736 15.4478 14.977C15.5293 14.7804 15.5713 14.5697 15.5713 14.3569C15.5713 14.1441 15.5293 13.9333 15.4478 13.7367C15.3662 13.5402 15.2467 13.3616 15.0961 13.2113C14.4623 12.5775 13.4223 12.5775 12.7886 13.2113L7.63733 18.3625C7.48669 18.5128 7.36717 18.6914 7.28563 18.888C7.20408 19.0846 7.16211 19.2953 7.16211 19.5081C7.16211 19.7209 7.20408 19.9317 7.28563 20.1283C7.36717 20.3248 7.48669 20.5034 7.63733 20.6537L12.7886 25.805C13.4223 26.4387 14.4623 26.4387 15.0961 25.805C15.7461 25.1712 15.7298 24.1313 15.0961 23.4975ZM27.6248 30.875H11.3748V29.25C11.3748 28.3563 10.6436 27.625 9.74983 27.625C8.85608 27.625 8.12483 28.3563 8.12483 29.25V34.125C8.12483 35.9125 9.58733 37.375 11.3748 37.375H27.6248C29.4123 37.375 30.8748 35.9125 30.8748 34.125V29.25C30.8748 28.3563 30.1436 27.625 29.2498 27.625C28.3561 27.625 27.6248 28.3563 27.6248 29.25V30.875Z" />
                                </svg> Developer's Area</a></li>
                        <li class="nav-item @if ($currentPage == 'Transactions') active @endif"><a class="nav-link"
                                href="{{ route('showTransactions') }}"><svg width="34" height="34"
                                    viewBox="-1 -1 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.25 11.375H32.5M26 3.25L34.125 11.375L26 19.5M35.75 27.625H6.5M13 19.5L4.875 27.625L13 35.75"
                                        stroke="{{ $currentPage === 'Transactions' ? 'white' : 'black' }}"
                                        stroke-width="3.25" />
                                </svg> Transactions</a></li>
                        <li class="nav-item @if ($currentPage == 'Failed-Transactions') active @endif">
                            <a class="nav-link" href="{{ route('showFailed-Transactions') }}"><svg width="34" height="34"
                                    viewBox="-1 -1 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.25 11.375H32.5M26 3.25L34.125 11.375L26 19.5M35.75 27.625H6.5M13 19.5L4.875 27.625L13 35.75"
                                        stroke="{{ $currentPage === 'Failed-Transactions' ? 'white' : 'black' }}"
                                        stroke-width="3.25" />
                                </svg>Failed-Transactions
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>


    <div class="">
        <div class="content">
            <div class="sidenav d-none d-lg-flex flex-column gap-5 p-3">
                {{-- <h2 class="text-center p-4">Logo</h2> --}}
                <div class="d-flex justify-content-start align-items-center">

                    <div class="navbar-logo-container">
                        <img src="{{ asset('logo/finpay-logo.png') }}" alt="Main-logo" class="navbar-logo"
                            loading="lazy" decoding="async">
                    </div>
                </div>
                <ul class="nav flex-column gap-4">
                    <li class="nav-item trans-icon-fill @if ($currentPage == 'Home') active @endif"><a
                            class="nav-link" href="{{ route('showHome') }}"><svg width="34" height="34"
                                viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="{{ $currentPage == 'Home' ? 'fill-white' : 'fill-black' }}"
                                    d="M18.8333 11.5V0.5H33.5V11.5H18.8333ZM0.5 18.8333V0.5H15.1667V18.8333H0.5ZM18.8333 33.5V15.1667H33.5V33.5H18.8333ZM0.5 33.5V22.5H15.1667V33.5H0.5Z"
                                    fill="white" />
                            </svg>
                            Dashboard</a></li>
                    <li class="nav-item trans-icon-fill @if ($currentPage == 'Documentations') active @endif"><a
                            class="nav-link" href="{{ route('showDocumentations') }}"><svg width="34"
                                height="34" viewBox="0 0 34 34" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path class="{{ $currentPage == 'Documentations' ? 'fill-white' : 'fill-black' }}"
                                    fill-rule="evenodd" clip-rule="evenodd"
                                    d="M16.7507 1.16667C16.7507 1.05616 16.7068 0.950179 16.6286 0.872039C16.5505 0.793899 16.4445 0.75 16.334 0.75H4.66732C3.45174 0.75 2.28595 1.23289 1.42641 2.09243C0.56687 2.95197 0.0839844 4.11776 0.0839844 5.33333V28.6667C0.0839844 29.8822 0.56687 31.048 1.42641 31.9076C2.28595 32.7671 3.45174 33.25 4.66732 33.25H21.334C22.5496 33.25 23.7154 32.7671 24.5749 31.9076C25.4344 31.048 25.9173 29.8822 25.9173 28.6667V12.245C25.9173 12.1345 25.8734 12.0285 25.7953 11.9504C25.7171 11.8722 25.6112 11.8283 25.5007 11.8283H18.0007C17.6691 11.8283 17.3512 11.6966 17.1168 11.4622C16.8823 11.2278 16.7507 10.9099 16.7507 10.5783V1.16667ZM18.0007 17.4167C18.3322 17.4167 18.6501 17.5484 18.8845 17.7828C19.119 18.0172 19.2507 18.3351 19.2507 18.6667C19.2507 18.9982 19.119 19.3161 18.8845 19.5505C18.6501 19.785 18.3322 19.9167 18.0007 19.9167H8.00065C7.66913 19.9167 7.35119 19.785 7.11677 19.5505C6.88235 19.3161 6.75065 18.9982 6.75065 18.6667C6.75065 18.3351 6.88235 18.0172 7.11677 17.7828C7.35119 17.5484 7.66913 17.4167 8.00065 17.4167H18.0007ZM18.0007 24.0833C18.3322 24.0833 18.6501 24.215 18.8845 24.4494C19.119 24.6839 19.2507 25.0018 19.2507 25.3333C19.2507 25.6649 19.119 25.9828 18.8845 26.2172C18.6501 26.4516 18.3322 26.5833 18.0007 26.5833H8.00065C7.66913 26.5833 7.35119 26.4516 7.11677 26.2172C6.88235 25.9828 6.75065 25.6649 6.75065 25.3333C6.75065 25.0018 6.88235 24.6839 7.11677 24.4494C7.35119 24.215 7.66913 24.0833 8.00065 24.0833H18.0007Z" />
                            </svg>
                            Documentations</a>
                    </li>
                    <li class="nav-item trans-icon-fill @if ($currentPage == 'developers-area') active @endif"><a
                            class="nav-link" href="{{ route('showDevelopersArea') }}">

                            <svg width="25" height="36" viewBox="0 0 25 36" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path class="{{ $currentPage == 'developers-area' ? 'fill-white' : 'fill-black' }}"
                                    d="M4.21272 6.5H20.4627V8.125C20.4627 9.01875 21.194 9.75 22.0877 9.75C22.9815 9.75 23.7127 9.01875 23.7127 8.125V3.25C23.7127 1.4625 22.2502 0.01625 20.4627 0.01625L4.21272 0C2.42522 0 0.962721 1.4625 0.962721 3.25V8.125C0.962721 9.01875 1.69397 9.75 2.58772 9.75C3.48147 9.75 4.21272 9.01875 4.21272 8.125V6.5ZM19.0327 24.18L24.184 19.0287C24.3346 18.8784 24.4541 18.6998 24.5357 18.5033C24.6172 18.3067 24.6592 18.0959 24.6592 17.8831C24.6592 17.6703 24.6172 17.4596 24.5357 17.263C24.4541 17.0664 24.3346 16.8878 24.184 16.7375L19.0327 11.5863C18.399 10.9525 17.359 10.9525 16.7252 11.5863C16.5746 11.7366 16.4551 11.9152 16.3735 12.1117C16.292 12.3083 16.25 12.5191 16.25 12.7319C16.25 12.9447 16.292 13.1554 16.3735 13.352C16.4551 13.5486 16.5746 13.7272 16.7252 13.8775L20.739 17.875L16.7252 21.8888C16.5746 22.0391 16.4551 22.2177 16.3735 22.4142C16.292 22.6108 16.25 22.8216 16.25 23.0344C16.25 23.2472 16.292 23.4579 16.3735 23.6545C16.4551 23.8511 16.5746 24.0297 16.7252 24.18C17.359 24.8137 18.399 24.8137 19.0327 24.18ZM7.93397 21.8725L3.93647 17.875L7.93397 13.8775C8.08461 13.7272 8.20413 13.5486 8.28567 13.352C8.36722 13.1554 8.40919 12.9447 8.40919 12.7319C8.40919 12.5191 8.36722 12.3083 8.28567 12.1117C8.20413 11.9152 8.08461 11.7366 7.93397 11.5863C7.30022 10.9525 6.26022 10.9525 5.62647 11.5863L0.475221 16.7375C0.324578 16.8878 0.205063 17.0664 0.123518 17.263C0.0419733 17.4596 0 17.6703 0 17.8831C0 18.0959 0.0419733 18.3067 0.123518 18.5033C0.205063 18.6998 0.324578 18.8784 0.475221 19.0287L5.62647 24.18C6.26022 24.8137 7.30022 24.8137 7.93397 24.18C8.58397 23.5462 8.56772 22.5063 7.93397 21.8725ZM20.4627 29.25H4.21272V27.625C4.21272 26.7313 3.48147 26 2.58772 26C1.69397 26 0.962721 26.7313 0.962721 27.625V32.5C0.962721 34.2875 2.42522 35.75 4.21272 35.75H20.4627C22.2502 35.75 23.7127 34.2875 23.7127 32.5V27.625C23.7127 26.7313 22.9815 26 22.0877 26C21.194 26 20.4627 26.7313 20.4627 27.625V29.25Z"
                                    fill="#434343" />
                            </svg>


                            Developer's Area</a></li>
                    <li class="nav-item trans-icon @if ($currentPage == 'Transactions') active @endif"><a
                            class="nav-link" href="{{ route('showTransactions') }}"><svg width="34"
                                height="34" viewBox="-1 -1 38 38" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="">
                                <path
                                    d="M3.25 11.375H32.5M26 3.25L34.125 11.375L26 19.5M35.75 27.625H6.5M13 19.5L4.875 27.625L13 35.75"
                                    class="{{ $currentPage == 'Transactions' ? 'stroke-white' : 'stroke-black' }}"
                                    stroke-width="3.25" />

                            </svg>
                            Transactions</a></li>
                    <li class="nav-item trans-icon @if ($currentPage == 'Failed-Transactions') active @endif"><a
                            class="nav-link" href="{{ route('showFailed-Transactions') }}"><svg width="34"
                                height="34" viewBox="-1 -1 38 38" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.25 11.375H32.5M26 3.25L34.125 11.375L26 19.5M35.75 27.625H6.5M13 19.5L4.875 27.625L13 35.75"
                                    class="{{ $currentPage == 'Failed-Transactions' ? 'stroke-white' : 'stroke-black' }}"
                                    stroke-width="3.25" />
                            </svg>
                            Failed-Transactions</a></li>
                </ul>
            </div>

            <div class="client-main-content" style="background-color: var(--right-sideBg-color);">
                <div
                    class="d-flex justify-content-center justify-content-lg-between align-items-center py-2 py-md-4 px-2 client-header">
                    <div class="dash-header-parent text-start">
                        <h4 class="card-title text-uppercase">@yield('title')</h4>
                        <small class="text-secondary">
                            @if (request()->routeIs('showDocumentations'))
                                Please Add return URLs, & make sure your base url is registered with us
                            @endif
                        </small>
                    </div>
                    <div
                        class="profile-parent d-none d-lg-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between gap-3 client-top-right">
                        <div class="logout-box">
                            <!-- <p class="mb-0">Logout &nbsp; <i class="fa-solid fa-right-from-bracket"></i></p> -->
                            <form method="POST" action="{{ route('logout') }}" style="text-align:center">
                                @csrf
                                <button type="submit" class="logout-btn justify-content-center">LOGOUT</button>
                                &nbsp; <i class="fa-solid fa-right-from-bracket"></i>
                            </form>
                        </div>


                        <div class="d-flex align-items-center justify-content-center rounded-circle text-white px-2"
                            style="background: #0035AA;">
                            <p class="fs-5 fw-bold p-1 mb-0">{{ $initial }}</p>
                        </div>
                        {{-- <img src="
    @if (Auth::User()->name == 'HowToPay') {{ asset('images/Rayzen-Pay-logo.png') }}
    @elseif(Auth::User()->name == 'Jensen')
      {{ asset('images/Rayzen-Pay-logo.png') }}
    @else
      https://st4.depositphotos.com/24244980/25394/i/450/depositphotos_253942934-stock-photo-company-profile-eyeball-blue-round.jpg @endif"
                            alt=""> --}}
                        <p class="mb-0 client-comp-name text-center profile-trigger" id="profile">
                            {{ Auth::User()->name }}</p>
                    </div>

                </div>
                {{-- start page content --}}

                @yield('page-content')

                {{-- end page content --}}
            </div>
        </div>


    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const parents = document.querySelectorAll('.profile-parent');

            parents.forEach(parent => {
                // DESKTOP
                const trigger = parent.querySelector('#profile') || parent.querySelector(
                    '.profile-trigger');
                const box = parent.querySelector('.logout-box');

                if (trigger && box) {
                    trigger.style.cursor = 'pointer';
                    trigger.addEventListener('click', (e) => {
                        e.stopPropagation();
                        // toggle this box, close any mobile box in same parent
                        box.classList.toggle('show');
                        const boxMob = parent.querySelector('.logout-box-mob');
                        if (boxMob) boxMob.classList.remove('show');
                    });
                }

                // MOBILE
                const triggerMob = parent.querySelector('#profile-mob') || parent.querySelector(
                    '.profile-trigger');
                const boxMob = parent.querySelector('.logout-box-mob');

                // NOTE: fixed typo here: check !boxMob, not !box
                if (triggerMob && boxMob) {
                    triggerMob.style.cursor = 'pointer';
                    triggerMob.addEventListener('click', (e) => {
                        e.stopPropagation();
                        boxMob.classList.toggle('show');
                        if (box) box.classList.remove('show');
                    });
                }
            });

            // One global outside-click closer
            document.addEventListener('click', () => {
                document.querySelectorAll('.logout-box.show, .logout-box-mob.show')
                    .forEach(el => el.classList.remove('show'));
            });

            // Optional: close on Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.logout-box.show, .logout-box-mob.show')
                        .forEach(el => el.classList.remove('show'));
                }
            });
        });
    </script>

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

    @yield('scripts')

</body>

</html>
