<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('images/fin-group-logo.svg') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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

        .fd-topbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
        }

        html.is-dark .fd-topbar {
            background: rgba(16, 24, 40, 0.8) !important;
            backdrop-filter: blur(12px);
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
                var dark = mode ? mode === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (dark) document.documentElement.classList.add('is-dark');
                else document.documentElement.classList.remove('is-dark');
                document.documentElement.style.setProperty('background-color', dark ? '#07080f' : '#ecf0fb', 'important');
                var s = document.createElement('style');
                s.id = 'fd-vt-bg';
                s.textContent = '::view-transition{background-color:' + (dark ? '#07080f' : '#ecf0fb') + '}';
                document.head.appendChild(s);
            } catch (e) { }

            document.addEventListener('pagereveal', function (e) {
                if (!e.viewTransition) return;
                try {
                    document.documentElement.classList.remove('theme-preload');
                    e.viewTransition.skipTransition();
                } catch (_) { }
            });
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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">

    <link rel="preload" href="{{ asset('css/clientside/index.css') }}" as="style">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="{{ asset('css/clientside/index.css') }}">
    @yield('css')
    <link rel="stylesheet" href="{{ asset('css/adminside/admin-ui.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/v4-shims.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        .profile-parent {
            position: relative;
        }
    </style>
</head>

<body>
    @php
        $userName = Auth::user()->name ?? 'Admin';
        $userInitials = collect(explode(' ', trim($userName)))
            ->filter()
            ->take(2)
            ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
        $userInitials = $userInitials !== '' ? $userInitials : 'A';
        $topbarTitle = trim($__env->yieldContent('topbar-title'));
        if ($topbarTitle === '') {
            $topbarTitle = trim($__env->yieldContent('title'));
        }
        $topbarSubtitle = trim($__env->yieldContent('topbar-subtitle'));
        if ($topbarSubtitle === '') {
            $topbarSubtitle = \Carbon\Carbon::now()->format('M j, Y');
        }
    @endphp

    <nav class="navbar navbar-expand-lg d-flex fixed-top d-lg-none shadow-sm">
        <div class="container-fluid">
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
                    <img src="{{ asset('images/fin-group-logo.svg') }}" alt="Finpay" class="navbar-logo" loading="lazy"
                        decoding="async">
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end gap-2 fd-admin-mobile-topbar-right">
                <form method="POST" action="{{ route('logout') }}" class="m-0 fd-topbar-logout">
                    @csrf
                    <button type="submit" class="fd-logout-btn" aria-label="Logout">
                        <svg class="fd-logout-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M10 17l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            <path d="M21 3v18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
                <div class="fd-userbadge fd-userbadge-mob" aria-hidden="true">{{ $userInitials }}</div>
            </div>

            <div class="offcanvas offcanvas-admin offcanvas-start rounded-top-end-3 rounded-bottom-end-3 fd-admin-mobile-nav" tabindex="-1"
                id="mobileNav" aria-labelledby="mobileNavLabel" style="max-width: max-content">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="mobileNavLabel">
                        <img src="{{ asset('images/fin-group-logo.svg') }}" alt="Finpay" style="height: 48px;">
                    </h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body fd-offcanvas-body">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item @if ($currentPage == 'Dashboard') active @endif">
                            <a class="nav-link" href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> &nbsp; Dashboard</a>
                        </li>
                        <li class="nav-item @if ($currentPage == 'Register Company') active @endif">
                            <a class="nav-link" href="{{ route('showRegisterCompany') }}"><i class="fa-solid fa-file-circle-check"></i>&nbsp; Register Company</a>
                        </li>
                        <li class="nav-item @if ($currentPage == 'Add Payment Service') active @endif">
                            <a class="nav-link" href="{{ route('showAddPaymentService') }}"><i class="fa-solid fa-file-lines"></i>&nbsp; Add Payment Service</a>
                        </li>
                        {{-- <li class="nav-item @if ($currentPage == 'P12 Services') active @endif"><a class="nav-link" href="{{ route('showPGTechPayService') }}"><i class="fa-solid fa-credit-card"></i>&nbsp; P12 Services</a></li>
                        <li class="nav-item @if ($currentPage == 'P17 Services') active @endif"><a class="nav-link" href="{{ route('showDirepayService') }}"><i class="fa-solid fa-credit-card"></i>&nbsp; P17 Services</a></li>
                        <li class="nav-item @if ($currentPage == 'P22 Services') active @endif"><a class="nav-link" href="{{ route('showUniqoPayService') }}"><i class="fa-solid fa-credit-card"></i>&nbsp; P22 Services</a></li> --}}
                        <li class="nav-item @if ($currentPage == 'P23 Services') active @endif">
                            <a class="nav-link" href="{{ route('showUpipayService') }}"><i class="fa-solid fa-credit-card"></i>&nbsp; P23 Services</a>
                        </li>
                        <li class="nav-item @if ($currentPage == 'Manage VPAs') active @endif">
                            <a class="nav-link" href="{{ route('showUpipayMerchants') }}"><i class="fa-solid fa-credit-card"></i>&nbsp; Manage VPAs</a>
                        </li>
                        <li class="nav-item @if ($currentPage == 'All Transactions') active @endif">
                            <a class="nav-link" href="{{ route('showAllTransactions') }}"><i class="fa-solid fa-cash-register"></i>&nbsp; All Transactions</a>
                        </li>
                        <li class="nav-item @if ($currentPage == 'All Declined Transactions') active @endif">
                            <a class="nav-link" href="{{ route('showAllFailedTransactions') }}"><i class="fa-solid fa-cash-register"></i>&nbsp; Decline Transaction</a>
                        </li>
                        <li class="nav-item @if ($currentPage == 'Settlements') active @endif">
                            <a class="nav-link" href="{{ route('showSettlements') }}"><i class="fa-solid fa-cash-register"></i>&nbsp; Settlements</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="content">
        <div class="fd-sidenav fd-admin-sidenav d-none d-lg-flex flex-column p-3">
            <div class="d-flex align-items-center justify-content-center fd-sidenav-brand-wrap">
                <div class="fd-brand" style="height: 75px;">
                    <img alt="Finpay" src="{{ asset('images/fin-group-logo.svg') }}">
                </div>
            </div>
            <ul class="nav flex-column fd-sidenav-nav">
                <li class="nav-item @if ($currentPage == 'Dashboard') active @endif">
                    <a class="nav-link" href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> &nbsp; Dashboard</a>
                </li>
                <li class="nav-item @if ($currentPage == 'Register Company') active @endif">
                    <a class="nav-link" href="{{ route('showRegisterCompany') }}"><i class="fa-solid fa-file-circle-check"></i>&nbsp; Register Company</a>
                </li>
                <li class="nav-item @if ($currentPage == 'Add Payment Service') active @endif">
                    <a class="nav-link" href="{{ route('showAddPaymentService') }}"><i class="fa-solid fa-file-lines"></i>&nbsp; Add Payment Service</a>
                </li>
                {{-- <li class="nav-item @if ($currentPage == 'P12 Services') active @endif"><a class="nav-link" href="{{ route('showPGTechPayService') }}"><i class="fa-solid fa-credit-card"></i>&nbsp; P12 Services</a></li>
                <li class="nav-item @if ($currentPage == 'P17 Services') active @endif"><a class="nav-link" href="{{ route('showDirepayService') }}"><i class="fa-solid fa-credit-card"></i>&nbsp; P17 Services</a></li>
                <li class="nav-item @if ($currentPage == 'P22 Services') active @endif"><a class="nav-link" href="{{ route('showUniqoPayService') }}"><i class="fa-solid fa-credit-card"></i>&nbsp; P22 Services</a></li> --}}
                <li class="nav-item @if ($currentPage == 'P23 Services') active @endif">
                    <a class="nav-link" href="{{ route('showUpipayService') }}"><i class="fa-solid fa-credit-card"></i>&nbsp; P23 Services</a>
                </li>
                <li class="nav-item @if ($currentPage == 'Manage VPAs') active @endif">
                    <a class="nav-link" href="{{ route('showUpipayMerchants') }}"><i class="fa-solid fa-credit-card"></i>&nbsp; Manage VPAs</a>
                </li>
                <li class="nav-item @if ($currentPage == 'All Transactions') active @endif">
                    <a class="nav-link" href="{{ route('showAllTransactions') }}"><i class="fa-solid fa-cash-register"></i>&nbsp; All Transactions</a>
                </li>
                <li class="nav-item @if ($currentPage == 'All Declined Transactions') active @endif">
                    <a class="nav-link" href="{{ route('showAllFailedTransactions') }}"><i class="fa-solid fa-cash-register"></i>&nbsp; Decline Transactions</a>
                </li>
                <li class="nav-item @if ($currentPage == 'Settlements') active @endif">
                    <a class="nav-link" href="{{ route('showSettlements') }}"><i class="fa-solid fa-cash-register"></i>&nbsp; Settlements</a>
                </li>
            </ul>
            <div class="fd-sidebar-accent" aria-hidden="true">
                <div class="fd-sidebar-accent-bar"></div>
            </div>
        </div>

        <div class="client-main-content admin-main-content" style="background-color: var(--right-sideBg-color);">
            <div class="fd-root fd-admin-page">
                <script>
                    (function () {
                        try {
                            var r = document.currentScript.parentElement;
                            r.classList.toggle('is-dark', document.documentElement.classList.contains('is-dark'));
                        } catch (e) { }
                    })();
                </script>
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
                        <div class="fd-topbar-titles dash-header-parent">
                            <p class="fd-topbar-title card-title text-uppercase mb-0">{{ $topbarTitle }}</p>
                            <p class="fd-topbar-sub dash-date mb-0">{{ $topbarSubtitle }}</p>
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
                                    <path d="M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    <path d="M21 3v18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
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

                if (usePreload) {
                    try {
                        requestAnimationFrame(function () {
                            setTimeout(function () { html.classList.remove('theme-preload'); }, 30);
                        });
                    } catch (_) { }
                }
            }

            const savedMode = localStorage.getItem('fdMode');
            const initialDark = savedMode === 'dark' ? true : (savedMode === 'light' ? false : mq.matches);
            applyMode(initialDark, false);

            mq.addEventListener('change', function (event) {
                if (localStorage.getItem('fdMode')) return;
                applyMode(event.matches, true);
            });

            btn && btn.addEventListener('click', function () {
                const nowDark = !html.classList.contains('is-dark');
                localStorage.setItem('fdMode', nowDark ? 'dark' : 'light');
                applyMode(nowDark, true);
            });
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const root = document.querySelector('.fd-admin-page');
            if (!root) return;

            root.querySelectorAll('.table-container .modal, .vpa-edit-modal, .summary-card-transactions-client .modal').forEach(function (modal) {
                root.appendChild(modal);
            });

            const tableSelectors = [
                '.table-container table',
                '.summary-card-transactions-client .table-wrapper table'
            ];

            const statusClasses = ['fd-status-success', 'fd-status-danger', 'fd-status-warning', 'fd-status-created', 'fd-status-neutral'];
            const rateClasses = ['fd-rate-high', 'fd-rate-mid', 'fd-rate-low'];

            function cleanText(value) {
                return (value || '').replace(/\s+/g, ' ').trim();
            }

            function classifyStatus(value) {
                const text = cleanText(value).toLowerCase();
                if (!text || text === '-') return '';

                if (['active', 'enabled', 'approved', 'completed', 'complete', 'success', 'succeeded', 'captured', 'paid', 'allocated'].some(function (word) {
                    return text.includes(word);
                })) {
                    return 'fd-status-success';
                }

                if (['deactivated', 'deactivate', 'inactive', 'disabled', 'failed', 'rejected', 'cancelled', 'canceled', 'declined', 'expired', 'blocked'].some(function (word) {
                    return text.includes(word);
                })) {
                    return 'fd-status-danger';
                }

                if (['pending', 'attempting', 'processing', 'waiting', 'awaiting'].some(function (word) {
                    return text.includes(word);
                })) {
                    return 'fd-status-warning';
                }

                if (['created', 'initialized', 'started', 'confirming'].some(function (word) {
                    return text.includes(word);
                })) {
                    return 'fd-status-created';
                }

                return 'fd-status-neutral';
            }

            function classifyRate(value) {
                const match = cleanText(value).match(/-?\d+(\.\d+)?/);
                if (!match) return '';

                const rate = Number(match[0]);
                if (!Number.isFinite(rate)) return '';
                if (rate >= 80) return 'fd-rate-high';
                if (rate >= 50) return 'fd-rate-mid';
                return 'fd-rate-low';
            }

            function applyValueClass(element, classes, className) {
                if (!element || !className) return;
                element.classList.remove.apply(element.classList, classes);
                element.classList.add(className);
            }

            function decorateStatusCell(cell, className) {
                if (!cell || !className) return;

                applyValueClass(cell, statusClasses, className);

                let pill = cell.querySelector('.fd-status-pill');
                if (!pill) {
                    pill = Array.from(cell.querySelectorAll('span, strong')).find(function (element) {
                        return cleanText(element.textContent) !== '';
                    });
                }

                if (!pill) {
                    const textNode = Array.from(cell.childNodes).find(function (node) {
                        return node.nodeType === Node.TEXT_NODE && cleanText(node.textContent) !== '';
                    });

                    if (textNode) {
                        pill = document.createElement('span');
                        pill.textContent = cleanText(textNode.textContent);
                        cell.replaceChild(pill, textNode);
                    }
                }

                if (!pill) return;

                pill.classList.add('fd-status-pill');
                applyValueClass(pill, statusClasses, className);
            }

            root.querySelectorAll(tableSelectors.join(',')).forEach(function (table) {
                if (table.dataset.mobileCardsGenerated === '1') return;
                if (table.classList.contains('vpa-merchants-table') || table.dataset.customMobileCards === '1') return;

                const headers = Array.from(table.querySelectorAll('thead th')).map(function (th, index) {
                    const text = cleanText(th.textContent);
                    return text || ('Column ' + (index + 1));
                });

                if (!headers.length) return;

                const rows = Array.from(table.querySelectorAll('tbody tr')).filter(function (tr) {
                    const cells = Array.from(tr.children).filter(function (cell) {
                        return cell.matches('td, th');
                    });
                    if (!cells.length) return false;
                    if (cells.length === 1 && cells[0].hasAttribute('colspan')) return false;
                    return cells.some(function (cell) {
                        return cleanText(cell.textContent) !== '';
                    });
                });

                if (!rows.length) return;

                const cards = document.createElement('div');
                cards.className = 'fd-admin-mobile-table d-lg-none';

                rows.forEach(function (tr) {
                    const card = document.createElement('div');
                    card.className = 'ind-card fd-admin-mobile-table-card';

                    const modalTarget = tr.querySelector('[data-bs-toggle][data-bs-target]');
                    if (modalTarget) {
                        card.setAttribute('data-bs-toggle', modalTarget.getAttribute('data-bs-toggle'));
                        card.setAttribute('data-bs-target', modalTarget.getAttribute('data-bs-target'));
                        card.style.cursor = 'pointer';
                    }

                    Array.from(tr.children).forEach(function (cell, index) {
                        if (!cell.matches('td, th')) return;

                        const labelText = headers[index] || ('Column ' + (index + 1));
                        const normalizedLabel = labelText.toLowerCase();
                        const valueText = cleanText(cell.textContent);
                        const hasElements = cell.children.length > 0;
                        if (!valueText && !hasElements) return;

                        if (normalizedLabel.includes('status')) {
                            decorateStatusCell(cell, classifyStatus(valueText));
                        }

                        if (normalizedLabel.includes('success rate')) {
                            applyValueClass(cell.querySelector('.fd-rate-pill') || cell, rateClasses, classifyRate(valueText));
                        }

                        const field = document.createElement('div');
                        field.className = 'fd-admin-mobile-field';

                        const label = document.createElement('span');
                        label.className = 'card-el-head';
                        label.textContent = labelText;

                        const value = document.createElement('div');
                        value.className = 'card-el-content';

                        ['text-success', 'text-danger', 'text-warning', 'statusSuccess', 'statusDanger', 'statusdanger', 'statuswarning', 'statusCreated', 'fd-status-success', 'fd-status-danger', 'fd-status-warning', 'fd-status-created', 'fd-status-neutral', 'fd-rate-high', 'fd-rate-mid', 'fd-rate-low'].forEach(function (className) {
                            if (cell.classList.contains(className)) value.classList.add(className);
                        });

                        if (hasElements) {
                            Array.from(cell.childNodes).forEach(function (node) {
                                value.appendChild(node.cloneNode(true));
                            });
                        } else {
                            value.textContent = valueText || '-';
                        }

                        field.appendChild(label);
                        field.appendChild(value);
                        card.appendChild(field);
                    });

                    if (card.children.length) cards.appendChild(card);
                });

                if (!cards.children.length) return;

                const mount = table.closest('.table-container') || table.closest('.summary-card-transactions-client');
                if (!mount || !mount.parentNode) return;

                table.dataset.mobileCardsGenerated = '1';
                mount.insertAdjacentElement('afterend', cards);

                Array.from(mount.parentNode.children).forEach(function (element) {
                    if (element !== cards && element.matches('.row.d-lg-none') && element.querySelector('.ind-card')) {
                        element.classList.add('fd-admin-legacy-mobile-cards');
                    }
                });
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: @json(session('success')),
                showConfirmButton: true
            }).then(function (result) {
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
                title: @json(session('error')),
            });
        </script>
    @endif

    @yield('scripts')
</body>

</html>
