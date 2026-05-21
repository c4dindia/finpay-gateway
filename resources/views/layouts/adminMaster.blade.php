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


    <!--SweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- layout index css  -->
    <link rel="stylesheet" href="{{ asset('css/clientside/index.css') }}">

    <!-- Poppins font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- page-content css -->
    @yield('css')

    <!-- font awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .profile-parent {
            position: relative;
        }
    </style>
</head>

<body>
    {{-- Mobile nav bar --}}
    <nav class="navbar navbar-expand-lg d-flex fixed-top d-lg-none shadow-sm">
        <div class="container-fluid">
            {{-- <a class="navbar-brand" href="#">Logo</a> --}}
            <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileNav" aria-controls="mobileNav" aria-label="Toggle navigation">
                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M28.8684 25.5882C29.4158 25.5885 29.9422 25.8067 30.3383 26.1977C30.7345 26.5886 30.9702 27.1223 30.9965 27.6881C31.0228 28.254 30.8377 28.8086 30.4796 29.2371C30.1216 29.6655 29.6179 29.935 29.073 29.9897L28.8684 30H6.13158C5.58417 29.9997 5.05784 29.7815 4.66166 29.3906C4.26548 28.9996 4.02983 28.466 4.00352 27.9001C3.97721 27.3343 4.16227 26.7797 4.52035 26.3512C4.87844 25.9227 5.38209 25.6532 5.92695 25.5985L6.13158 25.5882H28.8684ZM28.8684 15.2941C29.4338 15.2941 29.9759 15.5265 30.3757 15.9402C30.7754 16.3539 31 16.915 31 17.5C31 18.085 30.7754 18.6461 30.3757 19.0598C29.9759 19.4735 29.4338 19.7059 28.8684 19.7059H6.13158C5.56625 19.7059 5.02407 19.4735 4.62433 19.0598C4.22458 18.6461 4 18.085 4 17.5C4 16.915 4.22458 16.3539 4.62433 15.9402C5.02407 15.5265 5.56625 15.2941 6.13158 15.2941H28.8684ZM28.8684 5C29.4338 5 29.9759 5.2324 30.3757 5.64609C30.7754 6.05977 31 6.62085 31 7.20588C31 7.79092 30.7754 8.35199 30.3757 8.76568C29.9759 9.17936 29.4338 9.41176 28.8684 9.41176H6.13158C5.56625 9.41176 5.02407 9.17936 4.62433 8.76568C4.22458 8.35199 4 7.79092 4 7.20588C4 6.62085 4.22458 6.05977 4.62433 5.64609C5.02407 5.2324 5.56625 5 6.13158 5H28.8684Z"
                        fill="black" />
                </svg>

            </button>
            <div class="navbar-logo-container">
                <img src="{{ asset('logo/finpay-logo.png') }}" alt="" class=" navbar-logo">
            </div>
            <div
                class="profile-parent d-flex d-lg-none flex-column flex-md-row align-items-center justify-content-center justify-content-md-between gap-3 client-top-right">
                <div class="logout-box-mob">
                    {{-- <p class="mb-0"> Logout &nbsp; <i class="fa-solid fa-right-from-bracket"></i></p> --}}
                    <form method="POST" action="{{ route('logout') }}" style="text-align: center">
                        @csrf
                        <button type="submit" class="logout-btn justify-content-center">LOGOUT</button> &nbsp; <i
                            class="fa-solid fa-right-from-bracket"></i>
                    </form>
                </div>
                <div class="admin-company-pic-mob">
                    <img src="https://st4.depositphotos.com/24244980/25394/i/450/depositphotos_253942934-stock-photo-company-profile-eyeball-blue-round.jpg"
                        alt="" id="profile-mob" class="profile-trigger">
                </div>
            </div>
            <div class="offcanvas offcanvas-admin offcanvas-start rounded-top-end-3 rounded-bottom-end-3" tabindex="-1"
                id="mobileNav" aria-labelledby="mobileNavLabel" style="max-width: max-content">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="mobileNavLabel">
                        <img src="{{ asset('images/Rayzen-Pay-logo.png') }}" alt="ryzen-pay-logo">
                    </h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item @if ($currentPage == 'Dashboard') active @endif"><a class="nav-link"
                                href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> &nbsp; Dashboard</a>
                        </li>
                        <li class="nav-item @if ($currentPage == 'Register Company') active @endif"><a class="nav-link"
                                href="{{ route('showRegisterCompany') }}"><i
                                    class="fa-solid fa-file-circle-check"></i>&nbsp; Register
                                Company</a></li>
                        <li class="nav-item @if ($currentPage == 'Add Payment Service') active @endif"><a
                                class="nav-link" href="{{ route('showAddPaymentService') }}"><i
                                    class="fa-solid fa-file-lines"></i>&nbsp; Add Payment Service</a>
                        </li>
                        {{-- <li class="nav-item @if ($currentPage == 'P12 Services') active @endif"><a class="nav-link"
                                href="{{ route('showPGTechPayService') }}"><i
                                    class="fa-solid fa-credit-card"></i>&nbsp; P12 Services</a></li>
                        <li class="nav-item @if ($currentPage == 'P17 Services') active @endif"><a class="nav-link"
                                href="{{ route('showDirepayService') }}"><i
                                    class="fa-solid fa-credit-card"></i>&nbsp; P17 Services</a></li>
                        <li class="nav-item @if ($currentPage == 'P22 Services') active @endif"><a class="nav-link"
                                href="{{ route('showUniqoPayService') }}"><i
                                    class="fa-solid fa-credit-card"></i>&nbsp; P22 Services</a></li> --}}
                        <li class="nav-item @if ($currentPage == 'P23 Services') active @endif"><a class="nav-link"
                                href="{{ route('showUpipayService') }}"><i
                                    class="fa-solid fa-credit-card"></i>&nbsp; P23 Services</a></li>
                        <li class="nav-item @if ($currentPage == 'Manage VPAs') active @endif"><a class="nav-link"
                                href="{{ route('showUpipayMerchants') }}"><i
                                    class="fa-solid fa-credit-card"></i>&nbsp; Manage VPAs</a></li>
                        <li class="nav-item @if ($currentPage == 'All Transactions') active @endif"><a class="nav-link"
                                href="{{ route('showAllTransactions') }}"><i
                                    class="fa-solid fa-cash-register"></i>&nbsp;All Transactions</a>
                        </li>
                        <li class="nav-item @if ($currentPage == 'All Declined Transactions') active @endif"><a
                                class="nav-link" href="{{ route('showAllFailedTransactions') }}"><i
                                    class="fa-solid fa-cash-register"></i>&nbsp;Decline Transaction</a>
                        </li>
                        <li class="nav-item @if ($currentPage == 'Settlements') active @endif"><a
                                class="nav-link" href="{{ route('showSettlements') }}"><i
                                    class="fa-solid fa-money-bill-wave"></i>&nbsp;Settlements</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row content admin-body" style="display:flex; min-height: 100vh;">
            <div class="col-lg-2 sidenav admin-sidenav d-none d-lg-block p-3">
                {{-- <h2 class="text-center p-4">Logo</h2> --}}
                <div class="admin-logo" style="justify-self: center; width: -webkit-fill-available !important">
                    <img src="{{ asset('logo/finpay-logo.png') }}" alt=""
                        style="height: 90px; width: -webkit-fill-available !important">
                </div>
                <ul class="nav nav-pills d-flex flex-column gap-3">
                    <li class="nav-item @if ($currentPage == 'Dashboard') active @endif"><a class="nav-link"
                            href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> &nbsp; Dashboard</a></li>
                    <li class="nav-item @if ($currentPage == 'Register Company') active @endif"><a class="nav-link"
                            href="{{ route('showRegisterCompany') }}"><i
                                class="fa-solid fa-file-circle-check"></i>&nbsp; Register Company</a></li>
                    <li class="nav-item @if ($currentPage == 'Add Payment Service') active @endif"><a class="nav-link"
                            href="{{ route('showAddPaymentService') }}"><i class="fa-solid fa-file-lines"></i>&nbsp; Add
                            Payment Service</a></li>
                    {{-- <li class="nav-item @if ($currentPage == 'P12 Services') active @endif"><a class="nav-link"
                            href="{{ route('showPGTechPayService') }}"><i class="fa-solid fa-credit-card"></i>&nbsp;
                            P12 Services</a></li>
                    <li class="nav-item @if ($currentPage == 'P17 Services') active @endif"><a class="nav-link" href="{{ route('showDirepayService') }}">
                            <i class="fa-solid fa-credit-card"></i>&nbsp; P17 Services</a>
                    </li>
                    <li class="nav-item @if ($currentPage == 'P22 Services') active @endif"><a class="nav-link" href="{{ route('showUniqoPayService') }}">
                            <i class="fa-solid fa-credit-card"></i>&nbsp; P22 Services</a>
                    </li> --}}
                    <li class="nav-item @if ($currentPage == 'P23 Services') active @endif"><a class="nav-link" href="{{ route('showUpipayService') }}">
                            <i class="fa-solid fa-credit-card"></i>&nbsp; P23 Services</a>
                    </li>
                    <li class="nav-item @if ($currentPage == 'Manage VPAs') active @endif"><a class="nav-link" href="{{ route('showUpipayMerchants') }}">
                            <i class="fa-solid fa-credit-card"></i>&nbsp; Manage VPAs</a>
                    </li>
                    <li class="nav-item @if ($currentPage == 'All Transactions') active @endif"><a class="nav-link"
                            href="{{ route('showAllTransactions') }}"><i class="fa-solid fa-cash-register"></i>&nbsp;
                            All Transactions</a></li>
                    <li class="nav-item @if ($currentPage == 'All Declined Transactions') active @endif"><a
                            class="nav-link" href="{{ route('showAllFailedTransactions') }}"><i
                                class="fa-solid fa-cash-register"></i>&nbsp; Decline Transactions</a></li>
                    <li class="nav-item @if ($currentPage == 'Settlements') active @endif"><a class="nav-link"
                            href="{{ route('showSettlements') }}"><i class="fa-solid fa-money-bill-wave"></i>&nbsp; Settlements</a></li>
                </ul>
            </div>

            <div class="ol-lg-7 col-xl-10 admin-main-content" style="background-color: var(--right-sideBg-color);">


                <div
                    class="d-flex align-items-center justify-content-center justify-content-lg-between pt-3 pt-lg-4 admin-header-constant a-head-content">
                    <div class="dash-header-parent d-flex flex-column align-items-center  gap-0 ">
                        <h4 class="card-title text-uppercase" style="height:50%;">@yield('title')</h4>
                        <!-- <p class="dash-date" style="margin-bottom: 0;">{{ \Carbon\Carbon::now()->format('d M, Y') }}</p> -->
                    </div>
                    <div
                        class="profile-parent d-flex align-items-center justify-content-between gap-0 gap-md-3 d-none d-lg-block admin-top-right">
                        <div class="logout-box">
                            <!-- <p class="mb-0"> Logout &nbsp; <i class="fa-solid fa-right-from-bracket"></i></p> -->
                            <form method="POST" action="{{ route('logout') }}" style="text-align: center">
                                @csrf
                                <button type="submit" class="logout-btn justify-content-center">LOGOUT</button> &nbsp;
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </form>
                        </div>
                        <div class="d-flex justify-content-center align-items-center gap-2">

                            <img src="https://st4.depositphotos.com/24244980/25394/i/450/depositphotos_253942934-stock-photo-company-profile-eyeball-blue-round.jpg"
                                alt="">
                            <p class="mb-0" id="profile">{{ Auth::User()->name }}</p>

                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- logoout box script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const parents = document.querySelectorAll('.profile-parent');

            parents.forEach(parent => {
                // DESKTOP
                const trigger = parent.querySelector('#profile') || parent.querySelector('.profile-trigger');
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
                const triggerMob = parent.querySelector('#profile-mob') || parent.querySelector('.profile-trigger');
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
