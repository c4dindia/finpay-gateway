<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPI V2 Payment</title>
    <link rel="icon" href="{{ asset('images/Rayzen-Pay-logo.png') }}" type="image/x-icon">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: #fff;
            color: #fff;
            -webkit-font-smoothing: antialiased;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
        }

        a {
            color: inherit;
            text-underline-offset: 3px;
        }

        .page {
            width: 100%;
            max-width: 520px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        .glass {
            background:
                radial-gradient(1200px 400px at 50% -140px, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 60%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.06) 0%, rgba(255, 255, 255, 0.02) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow:
                0 24px 60px rgba(0, 0, 0, 0.55),
                0 0 0 1px rgba(255, 255, 255, 0.04) inset;
            backdrop-filter: blur(10px);
        }

        .edge-glow {
            position: relative;
            overflow: hidden;
        }

        .edge-glow::before {
            content: "";
            position: absolute;
            inset: -2px;
            background: conic-gradient(from 180deg,
                    rgba(255, 255, 255, 0) 0deg,
                    rgba(255, 255, 255, 0.12) 60deg,
                    rgba(255, 255, 255, 0) 140deg,
                    rgba(255, 255, 255, 0.09) 220deg,
                    rgba(255, 255, 255, 0) 360deg);
            filter: blur(18px);
            opacity: 0.55;
            pointer-events: none;
        }

        .edge-glow>* {
            position: relative;
            z-index: 1;
        }

        .checkout-flow.is-hidden {
            display: none;
        }

        .checkout-section.is-hidden {
            display: none !important;
        }

        .pay-section {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 22px 18px 20px;
            background: #0a0a0a;
        }

        .pay-section--apps {
            display: flex;
            flex-direction: column;
            min-height: 520px;
        }

        .pay-section--apps .pay-list {
            margin-top: auto;
        }

        .pay-section--qr {
            display: flex;
            flex-direction: column;
            min-height: 520px;
        }

        .pay-section--qr.pay-section--qr-solo {
            min-height: unset;
        }

        .pay-section--qr.pay-section--qr-solo .qr-footer {
            margin-top: 14px;
        }

        .pay-section--qr .qr-footer {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pay-section-title {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #6c6c6c;
            text-align: center;
            margin: 0 0 18px;
        }

        .qr-top {
            align-self: center;
            width: 240px;
            height: 240px;
            padding: 14px;
            background: #ffffff;
            border-radius: 22px;
            margin: 0 auto 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow:
                0 22px 60px rgba(255, 255, 255, 0.08),
                0 12px 28px rgba(0, 0, 0, 0.6);
            position: relative;
            overflow: hidden;
        }

        .qr-top img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            filter: contrast(1.2) brightness(1.15);
        }

        .qr-top::after {
            content: "";
            position: absolute;
            inset: -40%;
            background: radial-gradient(circle at 35% 30%, rgba(255, 255, 255, 0.55) 0%, rgba(255, 255, 255, 0) 58%);
            transform: rotate(-14deg);
            opacity: 0.65;
            pointer-events: none;
        }

        .amount-label {
            text-align: center;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #8a8a8a;
            margin: 0 0 10px;
        }

        .amount-value {
            text-align: center;
            font-size: 36px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 20px;
            line-height: 1.1;
        }

        .pay-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            width: 100%;
        }

        .pay-btn, .pay-btn-dark {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.12);
            cursor: pointer;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92) 0%, rgba(255, 255, 255, 0.78) 100%);
            color: #0b0b0b;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            padding: 14px 18px;
            border-radius: 999px;
            transition: transform 0.15s ease, filter 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.45);
            text-decoration: none;
            margin-top: 10px;
        }

        .pay-btn-dark{
            background: linear-gradient(135deg, #fab300 0%, #f89d00 55%, #ff8800 100%); color: #1a1a1a; box-shadow: 0 6px 18px rgba(245, 158, 11, 0.28);
            color: #fff !important;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .pay-btn:hover {
            filter: brightness(1.03);
            box-shadow:
                0 16px 34px rgba(0, 0, 0, 0.55),
                0 0 0 1px rgba(255, 255, 255, 0.06) inset;
        }

        .pay-btn:active {
            transform: scale(0.98);
        }

        .pay-btn:focus-visible {
            outline: 2px solid #ffffff;
            outline-offset: 3px;
        }

        .pay-btn[data-method] {
            justify-content: flex-start;
        }

        .pay-btn[data-method]::after {
            content: "›";
            margin-left: auto;
            font-size: 18px;
            opacity: 0.6;
            transform: translateY(-1px);
        }

        .pay-btn--ghost {
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: none;
        }

        .pay-btn--ghost:hover {
            filter: none;
            background: rgba(255, 255, 255, 0.09);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.55);
        }

        .switch-row {
            display: flex;
            gap: 12px;
            margin: 0 0 16px;
        }

        .switch-row .pay-btn {
            padding: 12px 16px;
            font-size: 14px;
        }

        .pay-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .pay-icon-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .status-bar {
            padding-top: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            text-align: center;
        }

        .status-row {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 15px;
            color: #8a8a8a;
            font-weight: 500;
        }

        .status-row.is-expired {
            color: rgba(255, 255, 255, 0.92);
            background: linear-gradient(180deg, rgba(255, 59, 48, 0.14) 0%, rgba(255, 59, 48, 0.06) 100%);
            border: 1px solid rgba(255, 107, 107, 0.35);
            padding: 8px 10px;
            border-radius: 14px;
            box-shadow:
                0 16px 44px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(255, 59, 48, 0.08) inset;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #3a3a3a;
            border-top-color: #9a9a9a;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
            will-change: transform;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .timer {
            color: #9a9a9a;
            font-variant-numeric: tabular-nums;
        }

        .timer.is-critical {
            color: #ffd166;
            animation: timerPulse 0.7s ease-in-out infinite;
        }

        @keyframes timerPulse {
            0% {
                transform: translateY(0);
                opacity: 0.85;
            }

            50% {
                transform: translateY(-1px);
                opacity: 1;
            }

            100% {
                transform: translateY(0);
                opacity: 0.85;
            }
        }

        .result-sheet {
            display: none;
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
        }

        .result-sheet.is-visible {
            display: flex;
            animation: panelPop 0.45s cubic-bezier(.2, .9, .2, 1);
        }

        .result-card {
            border-radius: 22px;
            padding: 18px 16px 16px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background:
                radial-gradient(900px 240px at 50% -120px, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0) 60%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%),
                #0a0a0a;
            box-shadow: 0 26px 80px rgba(0, 0, 0, 0.72);
            text-align: center;
        }

        .result-top {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding-top: 4px;
        }

        .result-icon {
            width: 86px;
            height: 86px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            position: relative;
            overflow: hidden;
        }

        .result-icon::before {
            content: "";
            position: absolute;
            inset: -3px;
            border-radius: 999px;
            background: conic-gradient(from 180deg,
                    rgba(255, 255, 255, 0) 0deg,
                    rgba(255, 255, 255, 0.18) 90deg,
                    rgba(255, 255, 255, 0) 180deg,
                    rgba(255, 255, 255, 0.14) 270deg,
                    rgba(255, 255, 255, 0) 360deg);
            filter: blur(14px);
            opacity: 0.7;
            animation: spin 1.5s linear infinite;
        }

        .result-icon-inner {
            width: 72px;
            height: 72px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            position: relative;
            z-index: 1;
        }

        .result-icon svg {
            width: 38px;
            height: 38px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .result-title {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            margin: 2px 0 0;
            letter-spacing: -0.01em;
        }

        .result-sub {
            margin: 0;
            color: rgba(255, 255, 255, 0.68);
            font-size: 14px;
            line-height: 1.45;
        }

        .result-meta {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .meta-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
            color: rgba(255, 255, 255, 0.82);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .meta-pill b {
            color: #fff;
            font-weight: 800;
        }

        .result-divider {
            margin: 16px auto 0;
            width: 92%;
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
        }

        .result-footer {
            margin-top: 14px;
            display: flex;
            justify-content: center;
            color: rgba(255, 255, 255, 0.55);
            font-size: 12px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .result--success .result-icon-inner {
            background: rgba(52, 199, 89, 0.18);
            color: #34c759;
            box-shadow: 0 18px 50px rgba(52, 199, 89, 0.14);
        }

        .result--fail .result-icon-inner {
            background: rgba(255, 59, 48, 0.18);
            color: #ff3b30;
            box-shadow: 0 18px 50px rgba(255, 59, 48, 0.14);
        }

        .result--expired .result-icon-inner {
            background: rgba(255, 209, 102, 0.18);
            color: #ffd166;
            box-shadow: 0 18px 50px rgba(255, 209, 102, 0.14);
        }

        @keyframes panelPop {
            0% {
                opacity: 0;
                transform: translateY(10px) scale(0.98);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 420px) {
            .page {
                max-width: 420px;
            }

            .qr-top {
                width: 210px;
                height: 210px;
            }

            .amount-value {
                font-size: 32px;
            }
        }
    </style>
</head>

@php
    $checkoutType = ($type ?? 'QR');
    $initialStep = strtoupper($checkoutType) === 'QR' ? 'qr' : 'app';

    $currencySymbol = strtoupper($transaction->currency) === 'INR' ? '₹' : $transaction->currency;
    $amountText = trim($currencySymbol . ' ' . number_format((float) $transaction->amount, 2));
@endphp

<body data-checkout-step="{{ $initialStep }}">
    <div class="page">
        <div id="checkoutFlow" class="checkout-flow">
            <div id="sectionQr" class="checkout-section pay-section pay-section--qr {{ $initialStep === 'qr' ? 'pay-section--qr-solo' : '' }} glass edge-glow {{ $initialStep === 'qr' ? '' : 'is-hidden' }}" aria-labelledby="qrSectionTitle">
                <h2 id="qrSectionTitle" class="pay-section-title">Pay with QR</h2>

                <div class="qr-top" aria-hidden="true">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($paymentData) }}" alt="UPI QR">
                </div>

                <p class="amount-label">Amount to pay</p>
                <p class="amount-value" id="amountDisplay">{{ $amountText }}</p>

                <div class="qr-footer">
                    <footer class="status-bar">
                        <div class="status-row" id="statusRowQr">
                            <span class="spinner status-spinner" aria-hidden="true"></span>
                            <span class="status-text" id="statusTextQr">Waiting for payment</span>
                            <span class="timer pay-timer" id="payTimerQr">--:--</span>
                        </div>
                    </footer>

                    @if ($initialStep === 'app')
                        <div class="switch-row">
                            <button type="button" class="pay-btn pay-btn--ghost" id="btnBackToUpi" aria-controls="sectionApp">
                                ← Back to UPI
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div id="sectionApp" class="checkout-section pay-section pay-section--apps glass edge-glow {{ $initialStep === 'app' ? '' : 'is-hidden' }}" aria-labelledby="appsSectionTitle">
                <h2 id="appsSectionTitle" class="pay-section-title">Pay with UPI app</h2>
                <p class="amount-label">Amount to pay</p>
                <p class="amount-value" id="amountDisplayApps">{{ $amountText }}</p>

                @php
                    $intentUrl = $paymentData;
                    $googlePay = str_replace('upi://pay', 'tez://upi/pay', $intentUrl);
                    $paytm = str_replace('upi://pay', 'paytmmp://pay', $intentUrl);
                    $phonepe = str_replace('upi://pay', 'phonepe://pay', $intentUrl);
                @endphp

                <div class="pay-list">
                    <a class="pay-btn" href="{{ $googlePay }}" data-method="gpay">
                        <span class="pay-icon">
                            <img src="{{ asset('images/gpay.webp') }}" alt="Google Pay" class="pay-icon-img" onerror="this.style.display='none'">
                        </span>
                        Pay with Google Pay
                    </a>
                    <a class="pay-btn" href="{{ $paytm }}" data-method="paytm">
                        <span class="pay-icon">
                            <img src="{{ asset('images/paytm.png') }}" alt="Paytm" class="pay-icon-img" onerror="this.style.display='none'">
                        </span>
                        Pay with Paytm
                    </a>
                    <a class="pay-btn" href="{{ $phonepe }}" data-method="phonepe">
                        <span class="pay-icon">
                            <img src="{{ asset('images/phonepe.webp') }}" alt="PhonePe" class="pay-icon-img" onerror="this.style.display='none'">
                        </span>
                        Pay with PhonePe
                    </a>
                    <button type="button" class="pay-btn" id="btnOpenQr" aria-controls="sectionQr" data-method="qr">
                        <span class="pay-icon" aria-hidden="true">
                            <svg class="" xmlns="http://www.w3.org/2000/svg" fill="#000000" width="25px" height="25px" viewBox="0 0 24 24">
                                <path d="M16.1666667,6 C16.0746192,6 16,6.07461921 16,6.16666667 L16,7.83333333 C16,7.92538079 16.0746192,8 16.1666667,8 L17.8333333,8 C17.9253808,8 18,7.92538079 18,7.83333333 L18,6.16666667 C18,6.07461921 17.9253808,6 17.8333333,6 L16.1666667,6 Z M16,18 L16,17.5 C16,17.2238576 16.2238576,17 16.5,17 C16.7761424,17 17,17.2238576 17,17.5 L17,18 L18,18 L18,17.5 C18,17.2238576 18.2238576,17 18.5,17 C18.7761424,17 19,17.2238576 19,17.5 L19,18.5 C19,18.7761424 18.7761424,19 18.5,19 L14.5,19 C14.2238576,19 14,18.7761424 14,18.5 L14,17.5 C14,17.2238576 14.2238576,17 14.5,17 C14.7761424,17 15,17.2238576 15,17.5 L15,18 L16,18 L16,18 Z M13,11 L13.5,11 C13.7761424,11 14,11.2238576 14,11.5 C14,11.7761424 13.7761424,12 13.5,12 L11.5,12 C11.2238576,12 11,11.7761424 11,11.5 C11,11.2238576 11.2238576,11 11.5,11 L12,11 L12,10 L10.5,10 C10.2238576,10 10,9.77614237 10,9.5 C10,9.22385763 10.2238576,9 10.5,9 L13.5,9 C13.7761424,9 14,9.22385763 14,9.5 C14,9.77614237 13.7761424,10 13.5,10 L13,10 L13,11 Z M18,12 L17.5,12 C17.2238576,12 17,11.7761424 17,11.5 C17,11.2238576 17.2238576,11 17.5,11 L18,11 L18,10.5 C18,10.2238576 18.2238576,10 18.5,10 C18.7761424,10 19,10.2238576 19,10.5 L19,12.5 C19,12.7761424 18.7761424,13 18.5,13 C18.2238576,13 18,12.7761424 18,12.5 L18,12 Z M13,14 L12.5,14 C12.2238576,14 12,13.7761424 12,13.5 C12,13.2238576 12.2238576,13 12.5,13 L13.5,13 C13.7761424,13 14,13.2238576 14,13.5 L14,15.5 C14,15.7761424 13.7761424,16 13.5,16 L10.5,16 C10.2238576,16 10,15.7761424 10,15.5 C10,15.2238576 10.2238576,15 10.5,15 L13,15 L13,14 L13,14 Z M16.1666667,5 L17.8333333,5 C18.4776655,5 19,5.52233446 19,6.16666667 L19,7.83333333 C19,8.47766554 18.4776655,9 17.8333333,9 L16.1666667,9 C15.5223345,9 15,8.47766554 15,7.83333333 L15,6.16666667 C15,5.52233446 15.5223345,5 16.1666667,5 Z M6.16666667,5 L7.83333333,5 C8.47766554,5 9,5.52233446 9,6.16666667 L9,7.83333333 C9,8.47766554 8.47766554,9 7.83333333,9 L6.16666667,9 C5.52233446,9 5,8.47766554 5,7.83333333 L5,6.16666667 C5,5.52233446 5.52233446,5 6.16666667,5 Z M6.16666667,6 C6.07461921,6 6,6.07461921 6,6.16666667 L6,7.83333333 C6,7.92538079 6.07461921,8 6.16666667,8 L7.83333333,8 C7.92538079,8 8,7.92538079 8,7.83333333 L8,6.16666667 C8,6.07461921 7.92538079,6 7.83333333,6 L6.16666667,6 Z M6.16666667,15 L7.83333333,15 C8.47766554,15 9,15.5223345 9,16.1666667 L9,17.8333333 C9,18.4776655 8.47766554,19 7.83333333,19 L6.16666667,19 C5.52233446,19 5,18.4776655 5,17.8333333 L5,16.1666667 C5,15.5223345 5.52233446,15 6.16666667,15 Z M6.16666667,16 C6.07461921,16 6,16.0746192 6,16.1666667 L6,17.8333333 C6,17.9253808 6.07461921,18 6.16666667,18 L7.83333333,18 C7.92538079,18 8,17.9253808 8,17.8333333 L8,16.1666667 C8,16.0746192 7.92538079,16 7.83333333,16 L6.16666667,16 Z M13,6 L10.5,6 C10.2238576,6 10,5.77614237 10,5.5 C10,5.22385763 10.2238576,5 10.5,5 L13.5,5 C13.7761424,5 14,5.22385763 14,5.5 L14,7.5 C14,7.77614237 13.7761424,8 13.5,8 C13.2238576,8 13,7.77614237 13,7.5 L13,6 Z M10.5,8 C10.2238576,8 10,7.77614237 10,7.5 C10,7.22385763 10.2238576,7 10.5,7 L11.5,7 C11.7761424,7 12,7.22385763 12,7.5 C12,7.77614237 11.7761424,8 11.5,8 L10.5,8 Z M5.5,14 C5.22385763,14 5,13.7761424 5,13.5 C5,13.2238576 5.22385763,13 5.5,13 L7.5,13 C7.77614237,13 8,13.2238576 8,13.5 C8,13.7761424 7.77614237,14 7.5,14 L5.5,14 Z M9.5,14 C9.22385763,14 9,13.7761424 9,13.5 C9,13.2238576 9.22385763,13 9.5,13 L10.5,13 C10.7761424,13 11,13.2238576 11,13.5 C11,13.7761424 10.7761424,14 10.5,14 L9.5,14 Z M11,18 L11,18.5 C11,18.7761424 10.7761424,19 10.5,19 C10.2238576,19 10,18.7761424 10,18.5 L10,17.5 C10,17.2238576 10.2238576,17 10.5,17 L12.5,17 C12.7761424,17 13,17.2238576 13,17.5 C13,17.7761424 12.7761424,18 12.5,18 L11,18 Z M9,11 L9.5,11 C9.77614237,11 10,11.2238576 10,11.5 C10,11.7761424 9.77614237,12 9.5,12 L8.5,12 C8.22385763,12 8,11.7761424 8,11.5 L8,11 L7.5,11 C7.22385763,11 7,10.7761424 7,10.5 C7,10.2238576 7.22385763,10 7.5,10 L8.5,10 C8.77614237,10 9,10.2238576 9,10.5 L9,11 Z M5,10.5 C5,10.2238576 5.22385763,10 5.5,10 C5.77614237,10 6,10.2238576 6,10.5 L6,11.5 C6,11.7761424 5.77614237,12 5.5,12 C5.22385763,12 5,11.7761424 5,11.5 L5,10.5 Z M15,10.5 C15,10.2238576 15.2238576,10 15.5,10 C15.7761424,10 16,10.2238576 16,10.5 L16,12.5 C16,12.7761424 15.7761424,13 15.5,13 C15.2238576,13 15,12.7761424 15,12.5 L15,10.5 Z M17,15 L17,14.5 C17,14.2238576 17.2238576,14 17.5,14 L18.5,14 C18.7761424,14 19,14.2238576 19,14.5 C19,14.7761424 18.7761424,15 18.5,15 L18,15 L18,15.5 C18,15.7761424 17.7761424,16 17.5,16 L15.5,16 C15.2238576,16 15,15.7761424 15,15.5 L15,14.5 C15,14.2238576 15.2238576,14 15.5,14 C15.7761424,14 16,14.2238576 16,14.5 L16,15 L17,15 Z M3,6.5 C3,6.77614237 2.77614237,7 2.5,7 C2.22385763,7 2,6.77614237 2,6.5 L2,4.5 C2,3.11928813 3.11928813,2 4.5,2 L6.5,2 C6.77614237,2 7,2.22385763 7,2.5 C7,2.77614237 6.77614237,3 6.5,3 L4.5,3 C3.67157288,3 3,3.67157288 3,4.5 L3,6.5 Z M17.5,3 C17.2238576,3 17,2.77614237 17,2.5 C17,2.22385763 17.2238576,2 17.5,2 L19.5,2 C20.8807119,2 22,3.11928813 22,4.5 L22,6.5 C22,6.77614237 21.7761424,7 21.5,7 C21.2238576,7 21,6.77614237 21,6.5 L21,4.5 C21,3.67157288 20.3284271,3 19.5,3 L17.5,3 Z M6.5,21 C6.77614237,21 7,21.2238576 7,21.5 C7,21.7761424 6.77614237,22 6.5,22 L4.5,22 C3.11928813,22 2,20.8807119 2,19.5 L2,17.5 C2,17.2238576 2.22385763,17 2.5,17 C2.77614237,17 3,17.2238576 3,17.5 L3,19.5 C3,20.3284271 3.67157288,21 4.5,21 L6.5,21 Z M21,17.5 C21,17.2238576 21.2238576,17 21.5,17 C21.7761424,17 22,17.2238576 22,17.5 L22,19.5 C22,20.8807119 20.8807119,22 19.5,22 L17.5,22 C17.2238576,22 17,21.7761424 17,21.5 C17,21.2238576 17.2238576,21 17.5,21 L19.5,21 C20.3284271,21 21,20.3284271 21,19.5 L21,17.5 Z"/>
                              </svg>
                        </span>
                        Pay with QR
                    </button>
                </div>


                <footer class="status-bar">
                    <div class="status-row" id="statusRowApp">
                        <span class="spinner status-spinner" aria-hidden="true"></span>
                        <span class="status-text" id="statusTextApp">Waiting for payment</span>
                        <span class="timer pay-timer" id="payTimerApp">--:--</span>
                    </div>
                </footer>
            </div>
        </div>

        <div id="successPanel" class="result-sheet result--success" role="status" aria-live="polite">
            <div class="result-card">
                <div class="result-top">
                    <div class="result-icon" aria-hidden="true">
                        <div class="result-icon-inner">
                            <svg viewBox="0 0 24 24">
                                <path d="M20 6L9 17l-5-5" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="result-title">Payment successful</h2>
                    <p class="result-sub">Your payment has been completed successfully.</p>
                </div>

                <div class="result-meta" aria-label="Payment details">
                    <span class="meta-pill"><span>Checkout ID</span> <b>{{ $checkout_id }}</b></span>
                </div>

                <div class="result-divider" aria-hidden="true"></div>
                <div class="result-footer">Saved • Secure • Instant</div>
            </div>
        </div>

        <div id="failPanel" class="result-sheet result--fail" role="status" aria-live="polite">
            <div class="result-card">
                <div class="result-top">
                    <div class="result-icon" aria-hidden="true">
                        <div class="result-icon-inner">
                            <svg viewBox="0 0 24 24">
                                <path d="M18 6L6 18" />
                                <path d="M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="result-title">Payment failed</h2>
                    <p class="result-sub">We could not complete your payment. Please try again or generate a new payment link.</p>
                </div>

                <div class="result-meta" aria-label="Payment details">
                    <span class="meta-pill"><span>Checkout ID</span> <b>{{ $checkout_id }}</b></span>
                </div>

                <div class="result-divider" aria-hidden="true"></div>
                <div class="result-footer">No amount was debited</div>
            </div>

            <form method="GET" action="{{ route('p23.payment.retry.v2', $checkout_id) }}" onsubmit="disableRetryButton(this)">
                @csrf
                <input type="hidden" name="token" value="{{ request()->get('token') }}">
                <button class="pay-btn-dark" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                    </svg>
                    <span>Retry</span>
                </button>
            </form>
        </div>

        <div id="expiredPanel" class="result-sheet result--expired" role="status" aria-live="polite">
            <div class="result-card">
                <div class="result-top">
                    <div class="result-icon" aria-hidden="true">
                        <div class="result-icon-inner">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 6v7" />
                                <path d="M12 17h.01" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="result-title">Payment link expired</h2>
                    
                    @php
                        $expiryMinutes = (int) config('services.p23.payment_expiry_minutes');
                    @endphp
                    <p class="result-sub">This payment link was valid for {{ $expiryMinutes }} minutes only. Please generate a new payment link to continue.</p>
                </div>

                <div class="result-meta" aria-label="Payment details">
                    <span class="meta-pill"><span>Checkout ID</span> <b>{{ $checkout_id }}</b></span>
                </div>

                <div class="result-divider" aria-hidden="true"></div>
                <div class="result-footer">No amount was debited</div>
            </div>
            
            <form method="GET" action="{{ route('p23.payment.retry.v2', $checkout_id) }}" onsubmit="disableRetryButton(this)">
                @csrf
                <input type="hidden" name="token" value="{{ request()->get('token') }}">
                <button class="pay-btn-dark" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                    </svg>
                    <span>Retry</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function disableRetryButton(form) {
            const button = form.querySelector('button[type="submit"]');

            if (!button) return true;

            button.disabled = true;
            button.style.opacity = '0.6';
            button.style.cursor = 'not-allowed';

            const span = button.querySelector('span');
            if (span) {
                span.textContent = 'Please wait...';
            }

            return true;
        }
    </script>

    <script>
        (function () {
            var checkoutId = @json($checkout_id);
            var expiresAt = (@json($expiresAt) || 0) * 1000;
            var initialStatus = @json($transaction->payment_status ?? 'Pending');
            var initialExpired = @json($isExpired);
            var initialStep = @json($initialStep);

            let checkoutFlow = document.getElementById('checkoutFlow');

            let successPanel = document.getElementById('successPanel');
            let failPanel = document.getElementById('failPanel');
            let expiredPanel = document.getElementById('expiredPanel');

            const successPanelHtml = successPanel ? successPanel.outerHTML : '';
            const failPanelHtml = failPanel ? failPanel.outerHTML : '';
            const expiredPanelHtml = expiredPanel ? expiredPanel.outerHTML : '';

            var payTimerQr = document.getElementById('payTimerQr');
            var payTimerApp = document.getElementById('payTimerApp');

            var statusRowQr = document.getElementById('statusRowQr');
            var statusRowApp = document.getElementById('statusRowApp');
            var statusTextQr = document.getElementById('statusTextQr');
            var statusTextApp = document.getElementById('statusTextApp');

            var countdownInterval = null;
            var statusInterval = null;
            var isFinalStatusShown = false;

            function removeInitialResultPanels() {
                if (successPanel) {
                    successPanel.remove();
                    successPanel = null;
                }

                if (failPanel) {
                    failPanel.remove();
                    failPanel = null;
                }

                if (expiredPanel) {
                    expiredPanel.remove();
                    expiredPanel = null;
                }
            }

            function insertPanel(html) {
                if (!html) return;

                const page = document.querySelector('.page');

                if (page) {
                    page.insertAdjacentHTML('beforeend', html);
                } else {
                    document.body.insertAdjacentHTML('beforeend', html);
                }
            }

            function getStep() {
                return document.body.getAttribute('data-checkout-step') || initialStep || 'qr';
            }

            function pad(n) {
                return (n < 10 ? '0' : '') + n;
            }

            function renderTimer(msLeft) {
                var secLeft = Math.max(0, Math.floor(msLeft / 1000));
                var m = Math.floor(secLeft / 60);
                var s = secLeft % 60;
                var text = pad(m) + ':' + pad(s);

                if (payTimerQr) payTimerQr.textContent = text;
                if (payTimerApp) payTimerApp.textContent = text;

                var critical = secLeft > 0 && secLeft <= 7;

                if (payTimerQr) payTimerQr.classList.toggle('is-critical', critical);
                if (payTimerApp) payTimerApp.classList.toggle('is-critical', critical);
            }

            function stopIntervals() {
                if (countdownInterval) clearInterval(countdownInterval);
                if (statusInterval) clearInterval(statusInterval);

                countdownInterval = null;
                statusInterval = null;
            }

            function removeCheckoutFlow() {
                if (checkoutFlow) {
                    checkoutFlow.remove();
                    checkoutFlow = null;
                }
            }

            function showBox(type) {
                if (isFinalStatusShown) return;

                if (type === 'payment') {
                    removeInitialResultPanels();

                    if (checkoutFlow) {
                        checkoutFlow.classList.remove('is-hidden');
                    }

                    return;
                }

                isFinalStatusShown = true;
                stopIntervals();
                removeCheckoutFlow();

                // Remove any existing result panels first
                document.getElementById('successPanel')?.remove();
                document.getElementById('failPanel')?.remove();
                document.getElementById('expiredPanel')?.remove();

                if (type === 'success') {
                    insertPanel(successPanelHtml);

                    const panel = document.getElementById('successPanel');
                    if (panel) {
                        panel.classList.remove('is-hidden');
                        panel.classList.add('is-visible');
                    }

                    return;
                }

                if (type === 'failed') {
                    insertPanel(failPanelHtml);

                    const panel = document.getElementById('failPanel');
                    if (panel) {
                        panel.classList.remove('is-hidden');
                        panel.classList.add('is-visible');
                    }

                    return;
                }

                if (type === 'expired') {
                    insertPanel(expiredPanelHtml);

                    const panel = document.getElementById('expiredPanel');
                    if (panel) {
                        panel.classList.remove('is-hidden');
                        panel.classList.add('is-visible');
                    }

                    return;
                }
            }

            function setExpiredUi() {
                if (!checkoutFlow) return;

                var row = (getStep() === 'qr') ? statusRowQr : statusRowApp;
                var text = (getStep() === 'qr') ? statusTextQr : statusTextApp;

                if (row) row.classList.add('is-expired');
                if (text) text.textContent = 'Payment window expired';
            }

            function clearExpiredUi() {
                if (!checkoutFlow) return;

                if (statusRowQr) statusRowQr.classList.remove('is-expired');
                if (statusRowApp) statusRowApp.classList.remove('is-expired');

                if (statusTextQr) statusTextQr.textContent = 'Waiting for payment';
                if (statusTextApp) statusTextApp.textContent = 'Waiting for payment';
            }

            function updateCountdown() {
                if (isFinalStatusShown) return;

                var diff = expiresAt - Date.now();

                if (diff <= 0) {
                    renderTimer(0);
                    setExpiredUi();

                    fetch('/p23/payment-expired/' + encodeURIComponent(checkoutId))
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function (data) {
                            if (!data || !data.success) {
                                showBox('expired');
                                return;
                            }

                            if (data.status === 'Expired') {
                                showBox('expired');
                                return;
                            }

                            showBox('expired');
                        })
                        .catch(function () {
                            showBox('expired');
                        });

                    return;
                }

                clearExpiredUi();
                renderTimer(diff);
            }

            function startStatusCheck() {
                statusInterval = setInterval(function () {
                    if (isFinalStatusShown) return;

                    fetch('/p23/payment-status/v2/' + encodeURIComponent(checkoutId))
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function (data) {
                            if (!data || !data.success) return;

                            if (data.status === 'Completed') {
                                showBox('success');
                                return;
                            }

                            if (data.status === 'Failed') {
                                showBox('failed');
                                return;
                            }
                        })
                        .catch(function () { });
                }, 5000);
            }

            function openSection(step) {
                if (!checkoutFlow || isFinalStatusShown) return;

                var sectionQr = document.getElementById('sectionQr');
                var sectionApp = document.getElementById('sectionApp');

                if (sectionQr) sectionQr.classList.add('is-hidden');
                if (sectionApp) sectionApp.classList.add('is-hidden');

                if (step === 'qr' && sectionQr) sectionQr.classList.remove('is-hidden');
                if (step === 'app' && sectionApp) sectionApp.classList.remove('is-hidden');

                document.body.setAttribute('data-checkout-step', step);

                renderTimer(expiresAt - Date.now());
            }

            var btnOpenQr = document.getElementById('btnOpenQr');
            var btnBackToUpi = document.getElementById('btnBackToUpi');

            if (btnOpenQr) {
                btnOpenQr.addEventListener('click', function () {
                    openSection('qr');
                });
            }

            if (btnBackToUpi) {
                btnBackToUpi.addEventListener('click', function () {
                    openSection('app');
                });
            }

            // Initial page state
            if (initialStatus === 'Completed') {
                removeInitialResultPanels();
                showBox('success');
                return;
            }

            if (initialStatus === 'Failed') {
                removeInitialResultPanels();
                showBox('failed');
                return;
            }

            if (initialStatus === 'Expired' || initialExpired) {
                removeInitialResultPanels();
                showBox('expired');
                return;
            }

            openSection(initialStep === 'app' ? 'app' : 'qr');

            showBox('payment');
            updateCountdown();

            if (!isFinalStatusShown) {
                countdownInterval = setInterval(updateCountdown, 1000);
                startStatusCheck();
            }
        })();
    </script>
</body>

</html>