<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transaction Report</title>

    <style>
        @page { margin: 18px 22px; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        /* Header */
        .top-wrap { margin-bottom: 14px; }
        .brand-title { font-size: 13px; font-weight: 700; margin-top: 4px; }
        .brand-sub { font-size: 10px; color: #666; margin-top: 2px; }

        .info-box {
            border: 1px solid #e3e3e3;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 11px;
        }
        .info-box table { width: 100%; border-collapse: collapse; }
        .info-box td { border: none; padding: 2px 0; }
        .label { color: #555; width: 34%; }
        .value { font-weight: 600; color: #111; }

        h2 {
            margin: 8px 0 2px;
            text-align: center;
            font-size: 15px;
        }
        .subtitle {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-bottom: 12px;
        }

        /* Table */
        table.report {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.report thead { display: table-header-group; }
        table.report th, table.report td {
            border: 1px solid #d8d8d8;
            padding: 6px 6px;
            vertical-align: top;
            word-break: break-word;
        }

        table.report th {
            background: #f3f4f6;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        table.report tbody tr:nth-child(even) td { background: #fafafa; }

        .muted { color: #666; font-size: 9px; }
        .mono { font-family: DejaVu Sans Mono, monospace; font-size: 9px; }

        .nowrap { white-space: nowrap; }
        .right { text-align: right; }
        .center { text-align: center; }

        .status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            border: 1px solid transparent;
        }
        .st-success { background: #e9f7ef; color: #0f6b3a; border-color: #bfe7cf; }
        .st-failed  { background: #fdecec; color: #b42318; border-color: #f6c9c9; }
        .st-pending { background: #fff6e6; color: #9a5b00; border-color: #ffe0a3; }
        .st-unknown { background: #eef2ff; color: #2b3a67; border-color: #cdd6ff; }

        .foot {
            margin-top: 10px;
            font-size: 9px;
            color: #777;
        }
    </style>
</head>

<body>
@php
    $fmtMoney = function ($n) {
        if ($n === null || $n === '') return '—';
        return number_format((float) $n, 2, '.', ',');
    };

    $statusClass = function ($s) {
        $s = trim((string) $s);
        if ($s === '') return 'st-unknown';

        $key = mb_strtolower($s);

        $success = ['succeeded', 'success', 'completed', 'complete', 'approved', 'captured', 'paid', 'allocated', 'underacharged', 'overcharged'];
        $failed  = ['failed', 'declined', 'rejected', 'cancelled', 'canceled', 'expired', 'error'];
        $pending = ['pending', 'awaiting', 'waiting', 'attempting', 'created', 'processing', 'redirect'];

        if (in_array($key, $success, true)) {
            return 'st-success';
        } elseif (in_array($key, $failed, true)) {
            return 'st-failed';
        } elseif (in_array($key, $pending, true)) {
            return 'st-pending';
        } else {
            //for any unexpected status
            return 'st-unknown';
        }
    };

    // $totalAmount = (float) $transactions->sum('amount');
    // $totalNet    = (float) $transactions->sum('net_amount');
    // $totalFees   = (float) $transactions->sum('fees');
@endphp

    <!-- Header -->
    <table class="top-wrap" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
        <tr>
            <td width="55%" style="border: none; vertical-align: middle;">
                <div style="text-align: left;">
                    <img src="{{ public_path('images/Rayzen-Pay-logo.png') }}" alt="Logo" style="height: 46px;">
                    <div class="brand-title">{{ config('app.name') }} Private Limited</div>
                    <div class="brand-sub">Leicester, United Kingdom</div>
                </div>
            </td>

            <td width="45%" style="border: none; vertical-align: middle;">
                <div class="info-box">
                    <table>
                        <tr>
                            <td class="label">For Company:</td>
                            <td class="value">{{ $company ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Email:</td>
                            <td class="value">{{ $email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Downloaded:</td>
                            <td class="value">{{ ($generated_at ?? now())->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <h2>Transaction Report</h2>
    <div class="subtitle">
        From <span class="nowrap">{{ $start_date->format('d M Y') }}</span>
        to <span class="nowrap">{{ $end_date->format('d M Y') }}</span>
        &nbsp;•&nbsp; Total: <strong>{{ $transactions->count() }}</strong>
    </div>

    <!-- Table -->
    <table class="report">
        <thead>
            <tr>
                <th style="width: 18%;">Checkout / Reason</th>
                <th style="width: 10%;">Date &amp; Time</th>
                <th style="width: 14%;">Amount</th>
                <th style="width: 16%;">Card</th>
                <th style="width: 24%;">Customer</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 8%;">Service</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($transactions as $txn)
            <tr>
                <td>
                    <div class="mono">{!! wordwrap(e($txn->checkout_id ?? '—'), 28, '<br>', true) !!}</div>
                    <div class="muted">Payment ID: <span class="mono">{{ $txn->payment_id ?? '—' }}</span></div>
                    @if(!empty($txn->description))
                        <div style="margin-top: 4px;">{{ $txn->description }}</div>
                    @endif
                </td>

                <td class="nowrap">
                    {{ optional($txn->created_at)->format('d/m/Y') }}<br>
                    <span class="muted">{{ optional($txn->created_at)->format('H:i') ?? '' }}</span>
                </td>

                <td class="right">
                    <div><strong>{{ $txn->currency ?? '—' }} {{ $fmtMoney($txn->amount) }}</strong></div>

                    @if(!empty($txn->from_currency) && !empty($txn->from_amount))
                        <div class="muted">From: {{ $txn->from_currency }} {{ $fmtMoney($txn->from_amount) }}</div>
                    @endif

                    @if($txn->net_amount !== null && $txn->net_amount !== '')
                        <div class="muted">Net: {{ $txn->currency ?? '—' }} {{ $fmtMoney($txn->net_amount) }}</div>
                    @endif

                    @if($txn->fees !== null && $txn->fees !== '')
                        <div class="muted">Fees: {{ $txn->currency ?? '—' }} {{ $fmtMoney($txn->fees) }}</div>
                    @endif
                </td>

                <td>
                    <div><strong>{{ $txn->transvoucher_card_brand ?? '—' }}</strong></div>
                    <div class="muted mono">
                        {{ $txn->card_number ?? '—' }}
                    </div>
                </td>

                <td>
                    @php
                        $cust = $txn->customer_details ?? '';
                    @endphp
                    <div>{!! $cust !== '' ? nl2br(e(str_replace(' , ', "\n", $cust))) : '—' !!}</div>
                </td>

                <td class="center">
                    @php
                        $ps = (string) ($txn->payment_status ?? '');
                    @endphp
                    <span class="status {{ $statusClass($ps) }}">
                        {{ $ps !== '' ? $ps : 'UNKNOWN' }}
                    </span>
                </td>

                <td class="center">
                    <span class="mono">
                        @if ($txn->status == 'p1')
                        P1-Card
                        @elseif ($txn->status == 'p2')
                        P2-Crypto
                        @elseif ($txn->status == 'p3')
                        P3-Card
                        @elseif ($txn->status == 'p4')
                        P4-Card
                        @elseif ($txn->status == 'p5')
                        P5-Card
                        @elseif ($txn->status == 'p6')
                        P6-(TrV)
                        @elseif ($txn->status == 'p7')
                        P7-(SPZ)
                        @elseif ($txn->status == 'p8')
                        P8-(LQP)
                        @elseif ($txn->status == 'p9')
                        P9-(TrP)
                        @elseif ($txn->status == 'p10')
                        P10-(INB)
                        @elseif ($txn->status == 'p11')
                        P11-(PYT)
                        @elseif ($txn->status == 'p12')
                        P12-(PGT)
                        @elseif ($txn->status == 'p13')
                        P13-(Alz)
                        @elseif ($txn->status == 'p14')
                        P14-(Nio)
                        @else
                        -
                        @endif
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="center" style="padding: 14px;">
                    No transactions found for the selected period.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- Totals -->
    {{-- @if($transactions->count())
        <div class="foot">
            <strong>Totals:</strong>
            Amount: {{ $fmtMoney($totalAmount) }}
            @if($totalNet) &nbsp;•&nbsp; Net: {{ $fmtMoney($totalNet) }} @endif
            @if($totalFees) &nbsp;•&nbsp; Fees: {{ $fmtMoney($totalFees) }} @endif
        </div>
    @endif --}}

    {{-- Page numbers (Dompdf) --}}
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("DejaVu Sans", "normal");
            $pdf->page_text(740, 560, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 9, array(0.45,0.45,0.45));
        }
    </script>
</body>
</html>
