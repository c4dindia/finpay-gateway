<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — Admin portal</title>
    <link rel="icon" href="{{ asset('images/Rayzen-Pay-logo.png') }}" type="image/x-icon">
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

        .fd-root.fd-login-page {
            --fd-bg: #ecf0fb;
            --fd-card: #ffffff;
            --fd-line: rgba(10, 15, 30, 0.06);
            --fd-ink: #0a0f1e;
            --fd-muted: #6b7494;
            --fd-select-bg: #ffffff;
            --fd-select-color: #0a0f1e;
            --fd-select-border: rgba(10, 15, 30, 0.18);
            --fd-topbar-title: #0a0f1e;
            --fd-topbar-sub: #9ba4be;
            --fd-toggle-bg: rgba(10, 15, 30, 0.08);
        }

        html.is-dark .fd-root.fd-login-page {
            --fd-bg: #07080f;
            --fd-card: #0f1525;
            --fd-line: rgba(255, 255, 255, 0.06);
            --fd-ink: #e2e8f0;
            --fd-muted: #8892ac;
            --fd-select-bg: #1a2236;
            --fd-select-color: #e2e8f0;
            --fd-select-border: rgba(255, 255, 255, 0.12);
            --fd-topbar-title: #e2e8f0;
            --fd-topbar-sub: #5a6480;
            --fd-toggle-bg: rgba(255, 255, 255, 0.08);
        }

        .fd-root.fd-login-page > .fd-topbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
        }

        html.is-dark .fd-root.fd-login-page > .fd-topbar {
            background: rgba(16, 24, 40, 0.8) !important;
            backdrop-filter: blur(12px);
        }

        html.theme-preload,
        html.theme-preload *,
        html.theme-preload *::before,
        html.theme-preload *::after {
            transition: none !important;
            animation: none !important;
        }

        .fd-login-brand-img {
            height: 40px;
            width: auto;
            max-width: 140px;
        }

        .fd-login-card-logo {
            max-width: 240px;
            width: 100%;
            height: auto;
            max-height: 56px;
        }
    </style>
    <script>
        (function () {
            try {
                var mode = localStorage.getItem('fdMode');
                var dark = mode ? mode === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('is-dark', dark);
                document.documentElement.style.setProperty('background-color', dark ? '#07080f' : '#ecf0fb', 'important');
                var s = document.createElement('style');
                s.id = 'fd-vt-bg';
                s.textContent = '::view-transition{background-color:' + (dark ? '#07080f' : '#ecf0fb') + '}';
                document.head.appendChild(s);
            } catch (e) { }
            document.addEventListener('pagereveal', function (e) {
                if (!e.viewTransition) return;
                try {
                        e.viewTransition.skipTransition();
                } catch (_) { }
            });
        })();
    </script>
    <link rel="preload" href="{{ asset('css/clientside/index.css') }}" as="style">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/clientside/index.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="fd-root fd-login-page d-flex flex-column min-vh-100">
        <script>
            (function () {
                try {
                    var root = document.currentScript.parentElement;
                    root.classList.toggle('is-dark', document.documentElement.classList.contains('is-dark'));
                } catch (e) { }
            })();
        </script>
        <div class="fd-topbar fd-login-topbar">
            <div class="fd-topbar-left">
                <div class="fd-login-brand-wrap" aria-hidden="true">
                    <img src="{{ asset('images/fin-group-logo.svg') }}" alt="Fin Group" class="fd-login-brand-img"
                        width="120" height="40" decoding="async">
                </div>
                <div class="fd-topbar-titles">
                    <p class="fd-topbar-title mb-0">Admin portal</p>
                    <p class="fd-topbar-sub mb-0">Sign in to manage Finpay</p>
                </div>
            </div>
            <div class="fd-topbar-right">
                <button type="button" class="fd-dark-toggle" id="fdDarkToggle" aria-label="Toggle dark mode"
                    title="Toggle dark mode">
                    <svg id="fdIconMoon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="fd-login-theme-ico">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                    </svg>
                    <svg id="fdIconSun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="fd-login-theme-ico" style="display:none">
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
            </div>
        </div>

        <main class="fd-shell fd-login-shell flex-grow-1 d-flex flex-column align-items-center justify-content-center">
            <div class="fd-login-card w-100">
                <div class="text-center mb-4">
                    
                    <h1 class="fd-login-heading mt-3 mb-0">Welcome back</h1>
                    <p class="fd-login-lead mb-0">Enter your admin credentials to access the dashboard.</p>
                </div>

                <form id="login-form" action="{{ url('/login') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fd-login-label">Email</label>
                        <input type="email" class="form-control fd-login-control" id="email"
                            placeholder="you@company.com" name="email" value="{{ old('email') }}"
                            autocomplete="username" required>
                    </div>
                    <div class="mb-2">
                        <label for="password" class="form-label fd-login-label">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" class="form-control fd-login-control pe-5"
                                id="password" placeholder="••••••••" autocomplete="current-password" required>
                            <button type="button" class="fd-login-eye-btn" tabindex="-1" aria-label="Show password"
                                data-eye-open>
                                <svg class="fd-login-eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <button type="button" class="fd-login-eye-btn" tabindex="-1" aria-label="Hide password"
                                data-eye-close hidden>
                                <svg class="fd-login-eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.274M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn fd-login-submit">Sign in</button>
                    </div>
                    <div class="mt-3 text-center">
                        @if ($errors->has('email') || $errors->has('password'))
                            <div class="alert fd-login-alert mb-0" id="error-message" role="alert">
                                Invalid email or password.
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <footer class="fd-login-footer mt-5 text-center">
                <p class="fd-login-footer-text mb-0">Copyright © Fin Group {{ date('Y') }}. All rights reserved.</p>
            </footer>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        (function () {
            const html = document.documentElement;
            const root = document.querySelector('.fd-root');
            const btn = document.getElementById('fdDarkToggle');
            const moon = document.getElementById('fdIconMoon');
            const sun = document.getElementById('fdIconSun');
            const mq = window.matchMedia('(prefers-color-scheme: dark)');

            function applyMode(dark) {
                html.classList.toggle('is-dark', dark);
                if (root) root.classList.toggle('is-dark', dark);
                if (moon) moon.style.display = dark ? 'none' : '';
                if (sun) sun.style.display = dark ? '' : 'none';
                try {
                    document.documentElement.style.setProperty(
                        'background-color',
                        dark ? '#07080f' : '#ecf0fb',
                        'important'
                    );
                } catch (_) { }
            }

            const savedMode = localStorage.getItem('fdMode');
            const initialDark = savedMode === 'dark' ? true : (savedMode === 'light' ? false : mq.matches);
            applyMode(initialDark);

            mq.addEventListener('change', function (event) {
                if (localStorage.getItem('fdMode')) return;
                applyMode(event.matches);
            });

            btn && btn.addEventListener('click', function () {
                const nowDark = !html.classList.contains('is-dark');
                localStorage.setItem('fdMode', nowDark ? 'dark' : 'light');
                applyMode(nowDark);
            });
        })();

        setTimeout(function () {
            var alertMessage = document.getElementById('error-message');
            if (alertMessage) alertMessage.style.display = 'none';
        }, 5000);

        (function () {
            var openBtn = document.querySelector('[data-eye-open]');
            var closeBtn = document.querySelector('[data-eye-close]');
            var passwordInput = document.querySelector('#password');
            if (!openBtn || !closeBtn || !passwordInput) return;

            function showPassword(show) {
                passwordInput.type = show ? 'text' : 'password';
                openBtn.hidden = show;
                closeBtn.hidden = !show;
            }

            openBtn.addEventListener('click', function () {
                showPassword(true);
            });
            closeBtn.addEventListener('click', function () {
                showPassword(false);
            });
        })();
    </script>
</body>

</html>
