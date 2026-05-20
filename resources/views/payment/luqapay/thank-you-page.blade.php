<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/ryzen-fav-logo.png') }}" type="image/x-icon">

    <style>
        :root {
            --bg: #0f172a;
            --card-bg: #ffffff;
            --primary: #0ea5e9;
            --success: #22c55e;
            --danger: #ef4444;
            --text-main: #0f172a;
            --text-muted: #6b7280;
            --border: #e5e7eb;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            background: radial-gradient(circle at top, #1e293b, var(--bg));
        }

        .wrapper {
            width: 100%;
            max-width: 480px;
        }

        .card {
            background: var(--card-bg);
            border-radius: 18px;
            padding: 24px 20px;
            box-shadow:
                0 18px 40px rgba(15, 23, 42, 0.25),
                0 0 0 1px rgba(148, 163, 184, 0.15);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            background: rgba(34, 197, 94, 0.08);
            color: var(--success);
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--success);
        }

        .title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 10px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
            margin-bottom: 18px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--border), transparent);
            margin: 12px 0 16px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 10px;
            gap: 8px;
        }

        .label {
            font-size: 13px;
            color: var(--text-muted);
        }

        .value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            text-align: right;
            word-break: break-all;
        }

        .amount {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
        }

        .transaction-id {
            font-family: "SF Mono", ui-monospace, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px;
            padding: 6px 10px;
            background: #f9fafb;
            border-radius: 10px;
            border: 1px dashed var(--border);
            margin-top: 4px;
        }

        .footer-note {
            margin-top: 16px;
            font-size: 11px;
            color: var(--text-muted);
            text-align: center;
        }

        @media (max-width: 480px) {
            .card {
                padding: 20px 16px;
            }
            .amount {
                font-size: 20px;
            }
            .title {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    @php
        // Normalize payment status
        $status = strtoupper((string) $checkout->payment_status);
        $isSuccess = in_array($status, ['SUCCESS', 'SUCCEEDED', 'PAID', 'APPROVED']);

        // Parse description like:
        // "Message: APPROVED | Status: APPROVED | Reference No: d196d49f-..."
        $rawDesc = trim($checkout->description ?? '');
        $parsedDesc = null;

        if ($rawDesc && str_contains($rawDesc, '|')) {
            $parsedDesc = [];

            foreach (explode('|', $rawDesc) as $segment) {
                $segment = trim($segment);
                if ($segment === '' || !str_contains($segment, ':')) {
                    continue;
                }

                [$key, $value] = array_map('trim', explode(':', $segment, 2));

                $normalizedKey = strtolower(str_replace([' ', '.'], '_', $key));

                if (in_array($normalizedKey, ['reference_no', 'reference_no_', 'reference'])) {
                    $normalizedKey = 'reference';
                }

                $parsedDesc[$normalizedKey] = $value;
            }

            if (empty($parsedDesc)) {
                $parsedDesc = null;
            }
        }
    @endphp

    <div class="wrapper">
        <div class="card">
            {{-- Status badge --}}
            <div style="text-align:center;">
                <div class="badge" style="{{ $isSuccess ? '' : 'background: rgba(239,68,68,0.08); color: var(--danger);' }}">
                    <span class="badge-dot" style="{{ $isSuccess ? '' : 'background: var(--danger);' }}"></span>
                    {{ $isSuccess ? 'Payment Successful' : $status }}
                </div>
            </div>

            <h1 class="title">Thank You for Your Payment</h1>
            <p class="subtitle">
                Your transaction has been processed. Below are your payment details.
            </p>

            <div class="divider"></div>

            <div class="row">
                <span class="label">Amount</span>
                <span class="value amount">
                    {{ $checkout->currency }} {{ number_format($checkout->amount, 2) }}
                </span>
            </div>

            <div class="row">
                <span class="label">Status</span>
                <span class="value">
                    {{ $checkout->payment_status }}
                </span>
            </div>

            {{-- Description / parsed gateway message --}}
            @if ($rawDesc !== '')
                @if ($parsedDesc)
                    <div class="row">
                        <span class="label">Message</span>
                        <span class="value">
                            {{ $parsedDesc['message'] ?? '-' }}
                        </span>
                    </div>

                    <div class="row">
                        <span class="label">Gateway Status</span>
                        <span class="value">
                            {{ $parsedDesc['status'] ?? '-' }}
                        </span>
                    </div>

                    <div class="row">
                        <span class="label">Reference No</span>
                        <span class="value">
                            {{ $parsedDesc['reference'] ?? '-' }}
                        </span>
                    </div>
                @else
                    <div class="row">
                        <span class="label">Description</span>
                        <span class="value">
                            {{ $checkout->description }}
                        </span>
                    </div>
                @endif
            @endif

            <div style="margin-top: 10px;">
                <span class="label">Transaction ID</span>
                <div class="transaction-id">
                    {{ $checkout->payment_id }}
                </div>
            </div>

            <p class="footer-note">
                If you have any questions about this payment, please contact our support team with your transaction ID.
            </p>
        </div>
    </div>
</body>
</html>
