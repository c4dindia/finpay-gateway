<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('images/Rayzen-Pay-logo.png') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Applied before any stylesheet — prevents background flash */
        html {
            background-color: #ecf0fb !important;
        }

        html.is-dark {
            background-color: #07080f !important;
        }

        html,
        body {
            margin: 0;
        }

        /* body inherits html's bg so Bootstrap's #fff never shows before index.css loads */
        body {
            background-color: inherit !important;
        }


        .fd-root {
            --fd-bg: #ecf0fb;
            --fd-card: #ffffff;
            --fd-line: rgba(10, 15, 30, 0.06);
            --fd-toolbar-bg: #ffffff;
            --fd-toolbar-border: rgba(10, 15, 30, 0.07);
            --fd-topbar-bg: linear-gradient(168.43deg, #ffffff 7.74%, #f0effe 54.23%, #e8f0ff 92.27%);
            --fd-topbar-border: rgba(10, 15, 30, 0.07);
        }

        html.is-dark .fd-root {
            --fd-bg: #07080f;
            --fd-card: #0f1525;
            --fd-line: rgba(255, 255, 255, 0.06);
            --fd-toolbar-bg: #0f1525;
            --fd-toolbar-border: rgba(255, 255, 255, 0.07);
            --fd-topbar-bg: linear-gradient(168.43deg, #0f1525 7.74%, #0b1020 54.23%, #090e1c 92.27%);
            --fd-topbar-border: rgba(255, 255, 255, 0.06);
        }

        /* Pre-set final values that index.css will apply so there is zero
           visual change between inline-style paint and external-CSS paint.
           Light-mode values are the defaults; dark overrides use html.is-dark
           (set by the inline script below) so they are correct before the
           body script adds .is-dark to .fd-root. */
        .fd-topbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
        }

        html.is-dark .fd-topbar {
            background: rgba(16, 24, 40, 0.8) !important;
            backdrop-filter: blur(12px);
        }

        .fd-toolbar {
            background: var(--fd-toolbar-bg);
        }

        .fd-card {
            background: var(--fd-card);
        }

        /* Suppress transitions briefly while toggling light/dark (not on first paint) */
        html.theme-preload,
        html.theme-preload *,
        html.theme-preload *::before,
        html.theme-preload *::after {
            transition: none !important;
            animation: none !important;
        }
    </style>
    <script>
        (function () {
            try {
                var mode = localStorage.getItem('fdMode');
                var dark = mode ? mode === 'dark' : (window.matchMedia('(prefers-color-scheme: dark)').matches);
                if (dark) document.documentElement.classList.add('is-dark');
                else document.documentElement.classList.remove('is-dark');
                document.documentElement.style.setProperty('background-color', dark ? '#07080f' : '#ecf0fb', 'important');
                // Give the ::view-transition overlay the correct bg so the crossfade
                // has no transparent gaps that expose the wrong colour during tab navigation.
                var s = document.createElement('style');
                s.id = 'fd-vt-bg';
                s.textContent = '::view-transition{background-color:' + (dark ? '#07080f' : '#ecf0fb') + '}';
                document.head.appendChild(s);
            } catch (e) { }
            function shouldSkipForServiceSwitch(target) {
                try {
                    if (!target || !target.closest) return false;
                    var tab = target.closest('.fd-tabs .fd-tab');
                    var a = target.closest('a[href]');
                    var href = a ? a.getAttribute('href') : '';
                    return !!(tab || (typeof href === 'string' && href.indexOf('service=') !== -1));
                } catch (_) { return false; }
            }

            // Mark very early so the old-page capture can skip transitions.
            document.addEventListener('pointerdown', function (ev) {
                if (!shouldSkipForServiceSwitch(ev.target)) return;
                try { sessionStorage.setItem('fdSkipVT', '1'); } catch (_) { }
            }, true);

            // Keyboard activation support (Enter/Space on focused link/button).
            document.addEventListener('keydown', function (ev) {
                if (ev.key !== 'Enter' && ev.key !== ' ') return;
                if (!shouldSkipForServiceSwitch(ev.target)) return;
                try { sessionStorage.setItem('fdSkipVT', '1'); } catch (_) { }
            }, true);

      
            document.addEventListener('pagereveal', function (e) {
                if (!e.viewTransition) return;
                try {
                    document.documentElement.classList.remove('theme-preload');
                    sessionStorage.removeItem('fdSkipVT');
                    e.viewTransition.skipTransition();
                } catch (_) { }
            });

            // Backup marker on click as an extra guard.
            document.addEventListener('click', function (ev) {
                try {
                    if (shouldSkipForServiceSwitch(ev.target)) {
                        sessionStorage.setItem('fdSkipVT', '1');
                    }
                } catch (_) { }
            }, true);
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Plus Jakarta latin variable woff2 (Google CSS v12); preloads cut sidebar FOUT after full reload --}}
    <link rel="preload" as="font" type="font/woff2" crossorigin
        href="https://fonts.gstatic.com/s/plusjakartasans/v12/LDIoaomQNQcsA88c7O9yZ4KMCoOg4Ko20yw.woff2">
    <link rel="preload" as="font" type="font/woff2" crossorigin
        href="https://fonts.gstatic.com/s/plusjakartasans/v12/LDIuaomQNQcsA88c7O9yZ4KMCoOg4Koz4y6qhA.woff2">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap">
    <!-- Preload index.css before CDN requests so it arrives as early as possible -->
    <link rel="preload" href="{{ asset('css/clientside/index.css') }}" as="style">
    <!-- Bootstrap CSS — render-blocking (grid/utilities needed for first paint) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Popper.js removed — bootstrap.bundle.min.js already includes it --}}

    {{-- SweetAlert2 — only loaded when a flash message exists, not on every page --}}
    @if (session('success') || session('error'))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endif

    <!-- layout index css  -->
    <link rel="stylesheet" href="{{ asset('css/clientside/index.css') }}">
    <!-- page-content css -->
    @yield('css')

    {{-- Font Awesome — non-blocking: only used in the hidden mobile nav,
         not needed for the first visible frame on desktop --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/v4-shims.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/v4-shims.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    </noscript>

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
                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                {{-- <img
                    src="
    @if (Auth::User()->name == 'HowToPay') {{ asset('images/Rayzen-Pay-logo.png') }}
    @elseif(Auth::User()->name == 'Jensen')
      {{ asset('images/Rayzen-Pay-logo.png') }}
    @else
      https://st4.depositphotos.com/24244980/25394/i/450/depositphotos_253942934-stock-photo-company-profile-eyeball-blue-round.jpg @endif"
                    alt="" id="profile-mob" class="profile-trigger" style="cursor:pointer;"> --}}
            </div>


        </div>
    </nav>

    {{-- Offcanvas nav (kept outside the hidden mobile navbar so fd-topbar hamburger works) --}}
    <div class="offcanvas offcanvas-start rounded-top-end-3 rounded-bottom-end-3" tabindex="-1" id="mobileNav"
        aria-labelledby="mobileNavLabel" style="max-width: max-content">
        <div class="w-100 d-flex justify-content-end">
            <button type="button" class="btn-close pe-4 pt-4 mt-3" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-header">
            <div class="fd-mobile-brand">
                <div class="fd-brand" style="height: 75px;">
                    <img alt="Finpay" src="{{ asset('images/fin-group-logo.svg') }}" loading="lazy" decoding="async">
                </div>
                
            </div>
        </div>

        @php
            $figmaNavIcons = [
                // inline SVGs sourced from Figma (node 1:6)
                'Home' => '<svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.625 1.875H2.5C2.15482 1.875 1.875 2.15482 1.875 2.5V6.875C1.875 7.22018 2.15482 7.5 2.5 7.5H5.625C5.97018 7.5 6.25 7.22018 6.25 6.875V2.5C6.25 2.15482 5.97018 1.875 5.625 1.875Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.5 1.875H9.375C9.02982 1.875 8.75 2.15482 8.75 2.5V4.375C8.75 4.72018 9.02982 5 9.375 5H12.5C12.8452 5 13.125 4.72018 13.125 4.375V2.5C13.125 2.15482 12.8452 1.875 12.5 1.875Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.5 7.5H9.375C9.02982 7.5 8.75 7.77982 8.75 8.125V12.5C8.75 12.8452 9.02982 13.125 9.375 13.125H12.5C12.8452 13.125 13.125 12.8452 13.125 12.5V8.125C13.125 7.77982 12.8452 7.5 12.5 7.5Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.625 10H2.5C2.15482 10 1.875 10.2798 1.875 10.625V12.5C1.875 12.8452 2.15482 13.125 2.5 13.125H5.625C5.97018 13.125 6.25 12.8452 6.25 12.5V10.625C6.25 10.2798 5.97018 10 5.625 10Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Documentations' => '<svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.375 1.25H3.75C3.41848 1.25 3.10054 1.3817 2.86612 1.61612C2.6317 1.85054 2.5 2.16848 2.5 2.5V12.5C2.5 12.8315 2.6317 13.1495 2.86612 13.3839C3.10054 13.6183 3.41848 13.75 3.75 13.75H11.25C11.5815 13.75 11.8995 13.6183 12.1339 13.3839C12.3683 13.1495 12.5 12.8315 12.5 12.5V4.375L9.375 1.25Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.75 1.25V3.75C8.75 4.08152 8.8817 4.39946 9.11612 4.63388C9.35054 4.8683 9.66848 5 10 5H12.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.25 5.625H5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 8.125H5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 10.625H5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'developers-area' => '<svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.25 10L13.75 7.5L11.25 5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.75 5L1.25 7.5L3.75 10" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.0625 2.5L5.9375 12.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Transactions' => '<svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.5 3.125H2.5C1.80964 3.125 1.25 3.68464 1.25 4.375V10.625C1.25 11.3154 1.80964 11.875 2.5 11.875H12.5C13.1904 11.875 13.75 11.3154 13.75 10.625V4.375C13.75 3.68464 13.1904 3.125 12.5 3.125Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M1.25 6.25H13.75" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Failed-Transactions' => '<svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.5 13.75C10.9518 13.75 13.75 10.9518 13.75 7.5C13.75 4.04822 10.9518 1.25 7.5 1.25C4.04822 1.25 1.25 4.04822 1.25 7.5C1.25 10.9518 4.04822 13.75 7.5 13.75Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.5 5V7.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.5 10H7.50625" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            ];
        @endphp

        <div class="offcanvas-body fd-offcanvas-body">
            <ul class="navbar-nav d-flex justify-content-start">
                <li class="nav-item trans-icon-fill @if ($currentPage == 'Home') active @endif"><a class="nav-link"
                        href="{{ route('showHome') }}">
                        <span class="fd-nav-ico" aria-hidden="true">
                            {!! $figmaNavIcons['Home'] !!}
                        </span>
                        Dashboard</a></li>
                <li class="nav-item trans-icon-fill @if ($currentPage == 'Documentations') active @endif"><a
                        class="nav-link" href="{{ route('showDocumentations') }}">
                        <span class="fd-nav-ico" aria-hidden="true">
                            {!! $figmaNavIcons['Documentations'] !!}
                        </span>
                        Documentations</a></li>
                <li class="nav-item trans-icon-fill @if ($currentPage == 'developers-area') active @endif"><a
                        class="nav-link" href="{{ route('showDevelopersArea') }}">
                        <span class="fd-nav-ico" aria-hidden="true">
                            {!! $figmaNavIcons['developers-area'] !!}
                        </span>
                        Developer's Area</a></li>
                <li class="nav-item @if ($currentPage == 'Transactions') active @endif"><a class="nav-link"
                        href="{{ route('showTransactions') }}">
                        <span class="fd-nav-ico" aria-hidden="true">
                            {!! $figmaNavIcons['Transactions'] !!}
                        </span>
                        Transactions</a></li>
                <li class="nav-item @if ($currentPage == 'Failed-Transactions') active @endif">
                    <a class="nav-link" href="{{ route('showFailed-Transactions') }}">
                        <span class="fd-nav-ico" aria-hidden="true">
                            {!! $figmaNavIcons['Failed-Transactions'] !!}
                        </span>
                        Failed-Transactions
                    </a>
                </li>
            </ul>

            <div class="fd-sidebar-accent" aria-hidden="true">
                <div class="fd-sidebar-accent-bar"></div>
            </div>

            {{-- <div class="fd-sidebar-logo">
                <img alt="Finpay" src="{{ asset('images/finpayLogoWide.svg') }}">
            </div>
            --}}
        </div>
    </div>


    <div class="">
        <div class="content">
            <div class="sidenav fd-sidenav d-none d-lg-flex flex-column p-3">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="fd-brand" style="height: 75px;">
                        <img alt="Finpay" src="{{ asset('images/fin-group-logo.svg') }}">
                    </div>
                   
                </div>


                <ul class="nav flex-column">
                    <li class="nav-item trans-icon-fill @if ($currentPage == 'Home') active @endif"><a class="nav-link"
                            href="{{ route('showHome') }}">
                            <span class="fd-nav-ico" aria-hidden="true">
                                {!! $figmaNavIcons['Home'] !!}
                            </span>
                            Dashboard</a></li>
                    <li class="nav-item trans-icon-fill @if ($currentPage == 'Documentations') active @endif"><a
                            class="nav-link" href="{{ route('showDocumentations') }}">
                            <span class="fd-nav-ico" aria-hidden="true">
                                {!! $figmaNavIcons['Documentations'] !!}
                            </span>
                            Documentations</a>
                    </li>
                    <li class="nav-item trans-icon-fill @if ($currentPage == 'developers-area') active @endif"><a
                            class="nav-link" href="{{ route('showDevelopersArea') }}">
                            <span class="fd-nav-ico" aria-hidden="true">
                                {!! $figmaNavIcons['developers-area'] !!}
                            </span>
                            Developer's Area</a></li>
                    <li class="nav-item trans-icon @if ($currentPage == 'Transactions') active @endif"><a
                            class="nav-link" href="{{ route('showTransactions') }}">
                            <span class="fd-nav-ico" aria-hidden="true">
                                {!! $figmaNavIcons['Transactions'] !!}
                            </span>
                            Transactions</a></li>
                    <li class="nav-item trans-icon @if ($currentPage == 'Failed-Transactions') active @endif"><a
                            class="nav-link" href="{{ route('showFailed-Transactions') }}">
                            <span class="fd-nav-ico" aria-hidden="true">
                                {!! $figmaNavIcons['Failed-Transactions'] !!}
                            </span>
                            Failed Transactions</a></li>
                </ul>

                <div class="fd-sidebar-accent" aria-hidden="true">
                    <div class="fd-sidebar-accent-bar"></div>
                </div>

                {{-- <div class="fd-sidebar-logo">
                    <img alt="Finpay" src="{{ asset('images/finpayLogoWide.svg') }}">
                </div> --}}
            </div>

            <div class="client-main-content" style="background-color: var(--right-sideBg-color);">
                @php
                    $userName = Auth::user()->name ?? '—';
                    $userInitials = collect(explode(' ', trim($userName)))
                        ->filter()
                        ->take(2)
                        ->map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)))
                        ->implode('');
                    $userInitials = $userInitials !== '' ? $userInitials : 'U';
                @endphp

                <div class="fd-root">
                    <div class="fd-topbar">
                        <div class="fd-topbar-left">
                            <button class="fd-nav-toggle" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#mobileNav" aria-controls="mobileNav" aria-label="Open navigation">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                    viewBox="0 0 16 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.5" d="M2 4h12M2 8h12M2 12h12" />
                                </svg>
                            </button>

                            <div class="fd-appmark" aria-hidden="true">
                                @hasSection('topbar-icon')
                                    @yield('topbar-icon')
                                @else
                                    @php
                                        $tb = $currentPage ?? '';
                                    @endphp

                                    @if ($tb === 'Transactions')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none">
                                            <path d="M7 7h14M7 12h14M7 17h14" stroke="white" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M3 7h.01M3 12h.01M3 17h.01" stroke="white" stroke-width="3"
                                                stroke-linecap="round" />
                                        </svg>
                                    @elseif ($tb === 'Failed-Transactions')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none">
                                            <path d="M7 7h14M7 12h14M7 17h14" stroke="white" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M4 8l3-3M7 8L4 5" stroke="white" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    @elseif ($tb === 'Documentations')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none">
                                            <path d="M7 4h10a2 2 0 0 1 2 2v14H7a2 2 0 0 0-2 2V6a2 2 0 0 1 2-2Z" stroke="white"
                                                stroke-width="2" stroke-linejoin="round" />
                                            <path d="M9 8h8M9 12h8M9 16h6" stroke="white" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                    @elseif ($tb === 'developers-area')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none">
                                            <path d="M8 9l-4 3 4 3M16 9l4 3-4 3" stroke="white" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M14 6l-4 12" stroke="white" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none">
                                            <path d="M15 16.6667V8.33333" stroke="white" stroke-width="1.66667"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10 16.6667V3.33333" stroke="white" stroke-width="1.66667"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M5 16.6667V11.6667" stroke="white" stroke-width="1.66667"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @endif
                                @endif
                            </div>

                            @php
                                $topbarTitle = trim($__env->yieldContent('topbar-title'));
                                if ($topbarTitle === '')
                                    $topbarTitle = trim($__env->yieldContent('title'));

                                $topbarSubtitle = trim($__env->yieldContent('topbar-subtitle'));
                                if ($topbarSubtitle === '')
                                    $topbarSubtitle = \Carbon\Carbon::now()->format('M j, Y');
                            @endphp

                            <div class="fd-topbar-titles">
                                <p class="fd-topbar-title">{{ $topbarTitle }}</p>
                                <p class="fd-topbar-sub">{{ $topbarSubtitle }}</p>
                            </div>
                        </div>

                        <div class="fd-topbar-right">
                            <form method="POST" action="{{ route('logout') }}" class="fd-topbar-logout">
                                @csrf
                                <button type="submit" class="fd-logout-btn" aria-label="Logout">
                                    <svg class="fd-logout-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        aria-hidden="true">
                                        <path d="M10 17l5-5-5-5" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M15 12H3" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                        <path d="M21 3v18" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                            <button class="fd-dark-toggle" id="fdDarkToggle" aria-label="Toggle dark mode"
                                title="Toggle dark mode">
                                <svg id="fdIconMoon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="#6b7494" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                                </svg>
                                <svg id="fdIconSun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    style="display:none">
                                    <circle cx="12" cy="12" r="5" />
                                    <line x1="12" y1="1" x2="12" y2="3" />
                                    <line x1="12" y1="21" x2="12" y2="23" />
                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                                    <line x1="1" y1="12" x2="3" y2="12" />
                                    <line x1="21" y1="12" x2="23" y2="12" />
                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                                </svg>
                            </button>

                            <div class="fd-userpill">
                                <div class="fd-userbadge">{{ $userInitials }}</div>
                                <div class="fd-usertext">
                                    <div class="fd-username">{{ $userName }}</div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="fd-shell">
                        @yield('page-content')
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        (function () {
            const html = document.documentElement;
            const root = document.querySelector('.fd-root');
            const btn = document.getElementById('fdDarkToggle');
            const moon = document.getElementById('fdIconMoon');
            const sun = document.getElementById('fdIconSun');
            const mq = window.matchMedia('(prefers-color-scheme: dark)');

            function applyMode(dark, usePreload) {
                if (usePreload) {
                    try { html.classList.add('theme-preload'); } catch (_) { }
                }

                html.classList.toggle('is-dark', dark);
                root && root.classList.toggle('is-dark', dark);

                var vtBg = document.getElementById('fd-vt-bg');
                if (vtBg) vtBg.textContent = '::view-transition{background-color:' + (dark ? '#07080f' : '#ecf0fb') + '}';

                if (moon) moon.style.display = dark ? 'none' : '';
                if (sun) sun.style.display = dark ? '' : 'none';

                if (typeof window._updateAmountChartTheme === 'function') {
                    window._updateAmountChartTheme(dark);
                }

                if (usePreload) {
                    try {
                        requestAnimationFrame(function () {
                            setTimeout(function () { html.classList.remove('theme-preload'); }, 30);
                        });
                    } catch (_) { }
                }
            }

            const savedMode = localStorage.getItem('fdMode');
            const initialDark = (savedMode === 'dark') ? true : (savedMode === 'light' ? false : mq.matches);
            applyMode(initialDark, false);

            mq.addEventListener('change', function (e) {
                if (localStorage.getItem('fdMode')) return;
                applyMode(e.matches, true);
            });

            btn && btn.addEventListener('click', function () {
                const nowDark = !html.classList.contains('is-dark');
                localStorage.setItem('fdMode', nowDark ? 'dark' : 'light');
                applyMode(nowDark, true);
            });
        })();
    </script>


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
            });
        </script>
    @endif

    @yield('scripts')

</body>

</html>