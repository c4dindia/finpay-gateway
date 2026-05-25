@extends('layouts.clientMaster')

@section('title')
Dashboard
@php
$currentPage = 'Home';
@endphp
@endsection

@section('topbar-title')
Dashboard
@endsection

@section('topbar-subtitle')
Overview · {{ \Carbon\Carbon::now()->format('M j, Y') }}
@endsection

@section('css')
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    /* Keep original chart height — do not stretch with fd-equal-row */
    .fd-equal-row > .col-lg-8 > .row > .col-12:last-child > .fd-volume-chart.fd-card {
        height: auto !important;
    }
    .fd-equal-row > .col-lg-8 > .row > .col-12:last-child .fd-volume-chart .fd-chart {
        flex: none !important;
        height: 290px !important;
        max-height: 290px;
        min-height: 0 !important;
    }
    .fd-volume-chart .fd-chart {
        position: relative;
    }
    .fd-volume-chart .fd-chart canvas {
        background-color: var(--fd-card, #ffffff);
        display: block;
    }
    @media (max-width: 991.98px) {
        .fd-equal-row > .col-lg-8 > .row > .col-12:last-child .fd-volume-chart .fd-chart {
            height: 200px !important;
            max-height: 200px;
        }
    }
    @media (max-width: 575.98px) {
        .fd-equal-row > .col-lg-8 > .row > .col-12:last-child .fd-volume-chart .fd-chart {
            height: 180px !important;
            max-height: 180px;
        }
    }
    .fd-volume-chart .fd-chart.is-busy::before {
        content: "";
        position: absolute;
        inset: 12px 18px 18px;
        border-radius: 12px;
        background: linear-gradient(
            105deg,
            transparent 0%,
            rgba(26, 61, 43, 0.04) 45%,
            rgba(26, 61, 43, 0.08) 50%,
            rgba(26, 61, 43, 0.04) 55%,
            transparent 100%
        );
        background-size: 200% 100%;
        animation: fd-chart-process 1.1s ease-in-out infinite;
        pointer-events: none;
        z-index: 1;
    }
    .fd-root.is-dark .fd-volume-chart .fd-chart.is-busy::before {
        background: linear-gradient(
            105deg,
            transparent 0%,
            rgba(212, 175, 55, 0.05) 45%,
            rgba(212, 175, 55, 0.1) 50%,
            rgba(212, 175, 55, 0.05) 55%,
            transparent 100%
        );
        background-size: 200% 100%;
    }
    @keyframes fd-chart-process {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Dashboard stats band — KPI + 2×2 metrics */
    .fd-stats-band > .row {
        align-items: stretch;
        --bs-gutter-x: 14px;
        --bs-gutter-y: 14px;
    }
    .fd-stats-band .fd-kpi {
        height: 100%;
    }
    .fd-stats-band .fd-kpi.fd-kpi--compact .fd-kpi-inner {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 100%;
        padding: 20px 22px 24px;
        position: relative;
    }
    .fd-stats-band .fd-kpi .fd-kpi-inner::after {
        height: 2px;
        left: 20px;
        right: 20px;
        bottom: 14px;
        opacity: 0.75;
    }
    .fd-stats-band .fd-kpi-top {
        min-height: 36px;
        align-items: center;
        gap: 12px;
    }
    .fd-stats-band .fd-kpi-amount {
        flex: 1;
        display: flex;
        align-items: center;
        margin: 10px 0 6px;
        font-size: clamp(1.4rem, 2.4vw, 1.75rem);
        font-weight: 800;
        letter-spacing: -0.03em;
        font-variant-numeric: tabular-nums;
        line-height: 1.15;
    }
    .fd-stats-band .fd-kpi-sub {
        margin: 0;
        padding-bottom: 6px;
    }
    .fd-stats-metrics {
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    .fd-stats-metrics > .row {
        flex: 1;
        --bs-gutter-x: 12px;
        --bs-gutter-y: 12px;
    }
    .fd-stats-metrics .fd-metric {
        display: flex;
        flex-direction: column;
        min-height: 100%;
        padding: 16px 18px 18px;
        border-radius: 18px;
        border-width: 1px;
        background: linear-gradient(168deg, #141c2e 0%, #0f1525 55%, #0d121f 100%);
        box-shadow:
            0 1px 0 rgba(255, 255, 255, 0.04) inset,
            0 8px 24px -6px rgba(0, 0, 0, 0.35);
    }
    .fd-stats-metrics .fd-metric::before {
        width: 72px;
        height: 72px;
        top: -18px;
        right: 14px;
        opacity: 0.14;
        filter: blur(18px);
    }
    .fd-stats-metrics .fd-metric:hover {
        transform: none;
        border-color: rgba(255, 255, 255, 0.1);
        box-shadow:
            0 1px 0 rgba(255, 255, 255, 0.05) inset,
            0 10px 28px -6px rgba(0, 0, 0, 0.4);
    }
    .fd-stats-metrics .fd-metric-head {
        flex-shrink: 0;
    }
    .fd-stats-metrics .fd-metric-label {
        font-size: 9px;
        letter-spacing: 0.14em;
        font-weight: 800;
    }
    .fd-stats-metrics .fd-metric-icon {
        width: 30px;
        height: 30px;
        border-radius: 10px;
    }
    .fd-stats-metrics .fd-metric .val {
        flex: 1;
        display: flex;
        align-items: center;
        margin: 4px 0 8px;
        font-size: clamp(1.65rem, 2.5vw, 2rem);
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1;
        font-variant-numeric: tabular-nums;
    }
    .fd-stats-metrics .fd-meter {
        margin-top: auto;
        padding-top: 2px;
        gap: 8px;
        flex-shrink: 0;
    }
    .fd-stats-metrics .fd-meter-track {
        height: 5px;
        background: rgba(255, 255, 255, 0.07);
    }
    .fd-stats-metrics .fd-meter-fill {
        height: 5px;
        border-radius: 99px;
        background: linear-gradient(90deg, color-mix(in srgb, var(--mc) 70%, transparent), var(--mc));
    }
    .fd-stats-metrics .fd-foot {
        font-size: 10px;
        min-width: 3.2em;
        text-align: right;
    }
    .fd-root.is-dark .fd-stats-metrics .fd-metric {
        background: linear-gradient(168deg, #151d32 0%, #0f1525 50%, #0b0f1a 100%);
        border-color: rgba(255, 255, 255, 0.08);
    }
    .fd-root.is-dark .fd-stats-metrics .fd-metric.green { border-color: rgba(16, 185, 129, 0.2); }
    .fd-root.is-dark .fd-stats-metrics .fd-metric.amber { border-color: rgba(245, 158, 11, 0.2); }
    .fd-root.is-dark .fd-stats-metrics .fd-metric.red { border-color: rgba(239, 68, 68, 0.2); }
    .fd-root.is-dark .fd-stats-metrics .fd-metric.violet { border-color: rgba(99, 102, 241, 0.22); }
    @media (min-width: 1200px) {
        .fd-stats-band .fd-kpi-amount {
            font-size: 1.65rem;
        }
    }
    .fd-root:not(.is-dark) .fd-stats-metrics .fd-metric {
        background: var(--fd-card);
        box-shadow: 0 4px 20px -4px rgba(10, 15, 30, 0.08);
    }
    .fd-root:not(.is-dark) .fd-stats-metrics .fd-meter-track {
        background: rgba(10, 15, 30, 0.08);
    }

    .fd-toolbar {
        padding: 12px 16px;
        align-items: center;
    }
    .fd-label {
        line-height: 1;
        padding-top: 1px;
    }

    .fd-amount-summary {
        padding: 20px 20px 22px;
    }
    .fd-amount-summary-title {
        margin: 0 0 18px;
        font-size: 17px;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--fd-ink);
    }
    .fd-amount-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        align-items: stretch;
    }
    .fd-amt-stat {
        border-radius: 14px;
        padding: 14px 12px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 6px;
        min-width: 0;
        text-align: center;
    }
    .fd-amt-stat-label {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        line-height: 1.2;
    }
    .fd-amt-stat-value {
        font-family: "JetBrains Mono", monospace;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .fd-card-value {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        font-size: 22px;
        letter-spacing: -0.02em;
    }
    .fd-tx-head .fd-link {
        font-size: 12px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 8px;
        transition: background 0.15s, color 0.15s;
    }
    .fd-tx-head .fd-link:hover {
        background: rgba(80, 70, 228, 0.1);
    }
    .fd-root.is-dark .fd-tx-head .fd-link {
        color: #818cf8;
    }
    .fd-root.is-dark .fd-tx-head .fd-link:hover {
        background: rgba(129, 140, 248, 0.12);
        color: #a5b4fc;
    }
    .fd-status .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }
    .fd-status.is-failed .lbl {
        color: #f87171;
    }
    .fd-kpi.fd-kpi--compact {
        min-height: 0;
    }
    .fd-cur-badge {
        font-size: 10px;
        letter-spacing: 0.02em;
    }
    .fd-currency-icon {
        line-height: 1;
    }
    .fd-kpi-amount .fd-currency-icon {
        font-size: 0.95em;
        margin-right: 6px;
    }
    .fd-kpi-icon .fd-currency-icon {
        font-size: 1.1rem;
    }
    .fd-card-value .fd-currency-icon {
        margin-right: 5px;
    }
    .fd-cur-badge .fd-currency-icon {
        font-size: 14px;
    }
    .fd-row-amt .fd-currency-icon {
        margin-right: 5px;
        font-size: 0.8em;
    }
    .fd-row {
        align-items: center;
    }
    .fd-volume-chart .fd-card-head {
        align-items: center;
    }
    .fd-amt-stat--received {
        background: #eff6ff;
    }
    .fd-amt-stat--received .fd-amt-stat-label {
        color: #64748b;
    }
    .fd-amt-stat--received .fd-amt-stat-value {
        color: #2563eb;
    }
    .fd-amt-stat--settled {
        background: #ecfdf5;
    }
    .fd-amt-stat--settled .fd-amt-stat-label {
        color: #6b7280;
    }
    .fd-amt-stat--settled .fd-amt-stat-value {
        color: #059669;
    }
    .fd-amt-stat--balance {
        background: #ecfdf5;
    }
    .fd-amt-stat--balance .fd-amt-stat-label {
        color: #6b7280;
    }
    .fd-amt-stat--balance .fd-amt-stat-value {
        color: #059669;
    }
    .fd-root.is-dark .fd-amt-stat--received {
        background: rgba(37, 99, 235, 0.12);
    }
    .fd-root.is-dark .fd-amt-stat--received .fd-amt-stat-label {
        color: rgba(148, 163, 184, 0.9);
    }
    .fd-root.is-dark .fd-amt-stat--received .fd-amt-stat-value {
        color: #60a5fa;
    }
    .fd-root.is-dark .fd-amt-stat--settled,
    .fd-root.is-dark .fd-amt-stat--balance {
        background: rgba(16, 185, 129, 0.12);
    }
    .fd-root.is-dark .fd-amt-stat--settled .fd-amt-stat-label,
    .fd-root.is-dark .fd-amt-stat--balance .fd-amt-stat-label {
        color: rgba(148, 163, 184, 0.85);
    }
    .fd-root.is-dark .fd-amt-stat--settled .fd-amt-stat-value,
    .fd-root.is-dark .fd-amt-stat--balance .fd-amt-stat-value {
        color: #34d399;
    }
    @media (max-width: 575.98px) {
        .fd-amount-summary-grid {
            grid-template-columns: 1fr;
        }
        .fd-amt-stat-value {
            font-size: 18px;
        }
    }
</style>
@endsection

@section('page-content')
@php
    $srv = session('service', 'all');
    $recentTransactions = $totalTransactions->sortByDesc('created_at')->take(5);

    $capturedTransactions = $totalTransactions->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid']);
    $capturedPercentage = $totalTransactions->count() > 0 ? ($capturedTransactions->count() / $totalTransactions->count()) * 100 : 0;
    $capturedCount = count($capturedTransactions);

    $awaitingTransactions = $totalTransactions->whereIn('payment_status', ['Pending', 'Processing', 'Attempting', 'Waiting', 'In-progress']);
    $awaitingPercentage = $totalTransactions->count() > 0 ? ($awaitingTransactions->count() / $totalTransactions->count()) * 100 : 0;
    $awaitingCount = count($awaitingTransactions);

    $failedTransactions = $totalTransactions->whereIn('payment_status', ['Declined', 'Failed', 'Rejected', 'Cancelled', 'Canceled', 'Expired', 'Payment-error']);
    $failedPercentage = $totalTransactions->count() > 0 ? ($failedTransactions->count() / $totalTransactions->count()) * 100 : 0;
    $failedCount = count($failedTransactions);

    $totalCount = $totalTransactions->count();

    $company = Auth::user()->company()->first();
    $serviceTabs = ['all' => 'All'];
    $services = [
        'p12' => ['label' => 'P-12 [2D/3D]', 'model' => \App\Models\PTwelvePaymentMethod::class],
        'p17' => ['label' => 'P-17 Dire', 'model' => \App\Models\Direpay::class],
        'p22' => ['label' => 'P-22 Uniqo', 'model' => \App\Models\UniqoPay::class],
        'p23' => ['label' => 'P-23 UPI', 'model' => \App\Models\UPIPayment::class],
    ];
    foreach ($services as $key => $service) {
        if ($company && $service['model']::where('company_id', $company->id)->where('status', 1)->exists()) {
            $serviceTabs[$key] = $service['label'];
        }
    }

    $activeCurrencies = $totalTransactions->unique('currency')->pluck('currency')->map(fn ($c) => strtoupper($c))->values();

    $currencyKpis = [
        'USD'  => ['total' => $usdTotal ?? 0,  'icon' => '$',  'slug' => 'usd'],
        'GBP'  => ['total' => $gbpTotal ?? 0,  'icon' => '£',  'slug' => 'gbp'],
        'USDT' => ['total' => $usdtTotal ?? 0, 'icon' => '₮', 'slug' => 'usdt'],
        'ETH'  => ['total' => $ethTotal ?? 0,  'icon' => 'Ξ',  'slug' => 'eth'],
        'EUR'  => ['total' => $eurTotal ?? 0,  'icon' => '€',  'slug' => 'eur'],
        'CAD'  => ['total' => $cadTotal ?? 0,  'icon' => 'C$', 'slug' => 'cad'],
        'INR'  => ['total' => $inrTotal ?? 0,  'icon' => '₹',  'slug' => 'inr'],
    ];

    $chartCurrencyOptions = $totalTransactions
        ->pluck('currency')
        ->map(fn ($c) => strtoupper(trim((string) $c)))
        ->filter()
        ->unique()
        ->values();

    $mainCurrency = strtoupper(trim((string) $chartCurrency));
    if (! $chartCurrencyOptions->contains($mainCurrency)) {
        $mainCurrency = $chartCurrencyOptions->first() ?? $mainCurrency;
    }

    // Same captured totals as KPI cards (approved/paid only), not all payment statuses
    $defaultChartTotal = (float) ($currencyKpis[$mainCurrency]['total'] ?? 0);

    $currencyTotalsJs = [
        'USD' => (float) ($usdTotal ?? 0),
        'GBP' => (float) ($gbpTotal ?? 0),
        'USDT' => (float) ($usdtTotal ?? 0),
        'ETH' => (float) ($ethTotal ?? 0),
        'EUR' => (float) ($eurTotal ?? 0),
        'CAD' => (float) ($cadTotal ?? 0),
        'INR' => (float) ($inrTotal ?? 0),
    ];
@endphp

<div id="fdShell">

    <div class="fd-toolbar mb-3">
        <span class="fd-label">Service</span>
        <span class="fd-divider"></span>
        <div class="fd-tabs">
            @foreach ($serviceTabs as $key => $label)
                <a class="fd-tab {{ $srv === $key ? 'is-active' : '' }}" href="{{ route('showHome', ['service' => $key]) }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="row g-3 fd-equal-row">
        <div class="col-12 col-xxl-8">
            <div class="row g-3">
                <div class="col-12 fd-stats-band">
                    <div class="row g-3 align-items-stretch">
                        @foreach ($currencyKpis as $code => $kpi)
                            @if ($activeCurrencies->contains($code))
                                <div class="col-12 {{ $activeCurrencies->count() === 1 ? 'col-lg-5' : 'col-md-6' }}">
                                    <div class="fd-kpi fd-kpi--compact">
                                        <div class="fd-kpi-inner">
                                            <div class="fd-kpi-top">
                                                <div class="fd-kpi-icon">
                                                    @if (in_array($code, ['INR', 'USD'], true))
                                                        @include('client.partials.currency-icon', ['code' => $code])
                                                    @else
                                                        {{ $kpi['icon'] }}
                                                    @endif
                                                </div>
                                                <span class="fd-kpi-filter-wrap">
                                                    <select class="fd-kpi-filter" id="kpiRange-{{ $kpi['slug'] }}" aria-label="{{ $code }} time filter" data-currency="{{ $code }}">
                                                        <option value="total" selected>Total</option>
                                                        <option value="thisMonth">This month</option>
                                                        <option value="lastMonth">Last month</option>
                                                        <option value="lastFewMonths">Last 3 months</option>
                                                    </select>
                                                </span>
                                            </div>
                                            <div class="fd-kpi-amount">
                                                @include('client.partials.currency-icon', ['code' => $code])
                                                <span id="kpiAmount-{{ $kpi['slug'] }}">{{ number_format((float) $kpi['total'], 2) }}</span>
                                            </div>
                                            <div class="fd-kpi-sub"><small id="kpiSub-{{ $kpi['slug'] }}">All time</small></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if ($activeCurrencies->count() === 1)
                            <div class="col-12 col-lg-7 fd-stats-metrics">
                                <div class="row g-3 h-100">
                                    @include('client.partials.dashboard-metrics', array_merge(compact('capturedCount', 'capturedPercentage', 'awaitingCount', 'awaitingPercentage', 'failedCount', 'failedPercentage', 'totalCount'), ['metricCols' => 'col-6']))
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($activeCurrencies->count() !== 1)
                    <div class="col-12">
                        <div class="row g-3">
                            @include('client.partials.dashboard-metrics', array_merge(compact('capturedCount', 'capturedPercentage', 'awaitingCount', 'awaitingPercentage', 'failedCount', 'failedPercentage', 'totalCount'), ['metricCols' => 'col-12 col-md-6 col-lg-3']))
                        </div>
                    </div>
                @endif

                <div class="col-12">
                    <div class="fd-card fd-volume-chart" id="volumeChartCard">
                        <div class="fd-card-head">
                            <div>
                                <div class="fd-card-title">Transaction Volume</div>
                                <div class="fd-card-value" id="chartTotalValue">
                                    <span id="chartTotalCurrency">@include('client.partials.currency-icon', ['code' => $mainCurrency])</span>
                                    <span id="chartTotalAmount">{{ number_format($defaultChartTotal, 2) }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fd-select-wrap">
                                    <select class="fd-select" id="currencySelect" aria-label="Chart currency">
                                        @foreach ($chartCurrencyOptions as $optCur)
                                            <option value="{{ $optCur }}" {{ $optCur === $mainCurrency ? 'selected' : '' }}>{{ $optCur }}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="fd-chart" id="amountChartWrap">
                            <canvas id="amountChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xxl-4 fd-equal-col d-flex flex-column gap-3">
            <div class="fd-card fd-amount-summary">
                <h4 class="fd-amount-summary-title">Amount Summary</h4>
                <div class="fd-amount-summary-grid">
                    <div class="fd-amt-stat fd-amt-stat--received">
                        <span class="fd-amt-stat-label">Total Received</span>
                        <span class="fd-amt-stat-value"><i class="fa-solid fa-indian-rupee-sign"></i> {{ number_format($inrTotal, 2) }}</span>
                    </div>
                    <div class="fd-amt-stat fd-amt-stat--settled">
                        <span class="fd-amt-stat-label">Total Settled</span>
                        <span class="fd-amt-stat-value"><i class="fa-solid fa-indian-rupee-sign"></i> {{ number_format($settledAmount, 2) }}</span>
                    </div>
                    <div class="fd-amt-stat fd-amt-stat--balance">
                        <span class="fd-amt-stat-label">Net Balance</span>
                        <span class="fd-amt-stat-value"><i class="fa-solid fa-indian-rupee-sign"></i> {{ number_format($inrTotal - $settledAmount - $settleAmountCommission, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="fd-card h-100 fd-tx-card">
                <div class="fd-tx-head">
                    <div class="fd-tx-head-left d-flex align-items-center gap-2">
                        <h3>Transactions</h3>
                        <span class="fd-tx-count">{{ $totalCount }}</span>
                    </div>
                    <a href="{{ route('showTransactions') }}" class="fd-link">View all ↗</a>
                </div>

                <div id="txList">
                    @foreach ($recentTransactions as $trans)
                        @php
                            $s = strtolower($trans->payment_status);
                            $cls = 'is-pending';
                            if (in_array($s, ['approved', 'succeeded', 'completed', 'done', 'captured', 'paid', 'success', 'complete'])) {
                                $cls = '';
                            }
                            if (in_array($s, ['declined', 'failed', 'rejected', 'cancelled', 'canceled', 'payment-error', 'expired'])) {
                                $cls = 'is-failed';
                            }
                            $amount = $trans->settled_amount ?? $trans->amount;
                            $cur = strtoupper($trans->currency ?? 'USD');
                            $checkoutId = (string) $trans->checkout_id;
                            $shortId = strlen($checkoutId) > 22
                                ? substr($checkoutId, 0, 10) . '…' . substr($checkoutId, -8)
                                : $checkoutId;
                            $curClass = match ($cur) {
                                'INR' => 'fd-cur-inr',
                                'USD' => 'fd-cur-usd',
                                default => 'fd-cur-other',
                            };
                        @endphp
                        <div class="fd-row" data-id="{{ $trans->checkout_id }}">
                            <div class="fd-cur-badge {{ $curClass }}">@include('client.partials.currency-icon', ['code' => $cur])</div>
                            <div class="fd-row-mid">
                                <div class="fd-row-id" title="{{ $checkoutId }}">{{ $shortId }}</div>
                                <div class="fd-row-date">{{ \Carbon\Carbon::parse($trans->created_at)->format('d/m/Y') }}</div>
                            </div>
                            <div class="fd-row-right">
                                <div class="fd-row-amt {{ $cls }}">@include('client.partials.currency-icon', ['code' => $cur]){{ number_format((float) $amount, 2) }}</div>
                                <div class="fd-status {{ $cls }}">
                                    <span class="dot"></span>
                                    <span class="lbl">{{ ucfirst($trans->payment_status) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function() {
    const months = @json($months);
    const amounts = @json($amounts);
    const canvas = document.getElementById('amountChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const ctx = canvas.getContext('2d');
    const html = document.documentElement;
    const chartBox = document.getElementById('amountChartWrap') || canvas.closest('.fd-chart');
    let chartFetchAbort = null;
    let amountChart = null;
    let chartAnimating = false;

    const DRAW_MS = 1200;
    const FETCH_DRAW_MS = 650;

    if (typeof Chart.register === 'function') {
        Chart.register({
            id: 'fdLineGradient',
            beforeDatasetDraw: function(chart, args) {
                if (!args.meta || args.meta.type !== 'line') return;
                const ds = chart.data.datasets[0];
                if (!ds) return;
                ds.backgroundColor = makeGradient(isDark(), chart);
            },
        });
    }

    function isDark() {
        return html.classList.contains('is-dark');
    }

    function chartTheme(dark) {
        return dark
            ? { line: '#d4af37', fillTop: 'rgba(212, 175, 55, 0.18)', fillBottom: 'rgba(212, 175, 55, 0)' }
            : { line: '#1a3d2b', fillTop: 'rgba(26, 61, 43, 0.18)', fillBottom: 'rgba(26, 61, 43, 0)' };
    }

    function scaleColors(dark) {
        return {
            tick: dark ? '#5a6480' : '#9ba4be',
            grid: dark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(10, 15, 30, 0.06)',
        };
    }

    function makeGradient(dark, chart) {
        const area = chart && chart.chartArea;
        const top = area && area.top > 0 ? area.top : 0;
        const bottom = area && area.bottom > top ? area.bottom : (chartBox ? chartBox.clientHeight : 290);
        const g = ctx.createLinearGradient(0, top, 0, bottom);
        const t = chartTheme(dark);
        g.addColorStop(0, t.fillTop);
        g.addColorStop(1, t.fillBottom);
        return g;
    }

    function yFromBaseline(ctx) {
        if (ctx.type === 'data' && amountChart && amountChart.scales.y) {
            return amountChart.scales.y.getPixelForValue(0);
        }
    }

    function setDrawAnimation(ms) {
        if (!amountChart) return;
        amountChart.options.animation = { duration: ms, easing: 'easeInOutCubic' };
        amountChart.options.animations = {
            tension: { duration: ms, easing: 'easeInOutCubic' },
            x: { duration: Math.round(ms * 0.28), easing: 'easeInOutCubic' },
            y: { duration: ms, easing: 'easeInOutCubic', from: yFromBaseline },
        };
    }

    function applyChartTheme(dark, mode) {
        if (!amountChart || chartAnimating) return;
        const t = chartTheme(dark);
        const colors = scaleColors(dark);
        amountChart.data.datasets[0].borderColor = t.line;
        amountChart.options.scales.x.ticks.color = colors.tick;
        amountChart.options.scales.y.ticks.color = colors.tick;
        amountChart.options.scales.y.grid.color = colors.grid;
        amountChart.update(mode || 'none');
    }

    /** Paint empty axes/grid on canvas (no line animation). */
    function renderCanvasShell(labels) {
        if (!amountChart) return;
        const zeros = (labels || months).map(function() { return 0; });
        amountChart.data.labels = labels || months;
        amountChart.data.datasets[0].data = zeros;
        amountChart.options.animation = false;
        amountChart.options.animations = false;
        amountChart.update('none');
        if (chartBox) {
            chartBox.classList.add('is-canvas-ready');
            chartBox.classList.remove('is-busy');
        }
    }

    /** Animate line + fill from baseline once data is ready. */
    function drawChartData(labels, series, ms) {
        if (!amountChart) return;
        chartAnimating = true;
        if (chartBox) chartBox.classList.remove('is-busy');

        setDrawAnimation(ms || DRAW_MS);
        amountChart.data.labels = labels;
        amountChart.data.datasets[0].data = series;

        var prevOnComplete = amountChart.options.animation.onComplete;
        amountChart.options.animation.onComplete = function() {
            chartAnimating = false;
            amountChart.options.animation = false;
            amountChart.options.animations = false;
            amountChart.options.animation.onComplete = prevOnComplete;
        };

        amountChart.update();
    }

    function buildChart() {
        const dark = isDark();
        const colors = scaleColors(dark);
        const t = chartTheme(dark);
        const zeroSeries = amounts.map(function() { return 0; });

        amountChart = window._amountChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    data: zeroSeries,
                    borderColor: t.line,
                    backgroundColor: 'transparent',
                    fill: true,
                    tension: 0.42,
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHitRadius: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                animations: false,
                transitions: {
                    active: { animation: { duration: FETCH_DRAW_MS, easing: 'easeInOutCubic' } },
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(10, 15, 30, 0.92)',
                        displayColors: false,
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: (c) => {
                                const v = c.parsed?.y ?? 0;
                                return new Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(v);
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: colors.tick, font: { size: 11, weight: '600' } }, border: { display: false } },
                    y: { beginAtZero: true, grid: { color: colors.grid }, ticks: { color: colors.tick, font: { size: 11, weight: '600' } }, border: { display: false } }
                }
            }
        });

        renderCanvasShell(months);

        requestAnimationFrame(function() {
            requestAnimationFrame(function() {
                drawChartData(months, amounts.slice(), DRAW_MS);
            });
        });

        if (chartBox && typeof ResizeObserver !== 'undefined') {
            let resizeTimer = null;
            new ResizeObserver(function() {
                if (!amountChart || chartAnimating) return;
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    amountChart.resize();
                    amountChart.update('none');
                }, 50);
            }).observe(chartBox);
        }
    }

    const allTransactions = @json($totalTransactionsJS);

    const currencyTotals = @json($currencyTotalsJs);

    const currencyLabelHtml = {
        INR: '<i class="fa-solid fa-indian-rupee-sign fd-currency-icon" aria-hidden="true"></i><span class="visually-hidden">INR</span>',
        USD: '<i class="fa-solid fa-dollar-sign fd-currency-icon" aria-hidden="true"></i><span class="visually-hidden">USD</span>',
    };

    function renderCurrencyLabel(el, currency) {
        if (!el) return;
        if (currencyLabelHtml[currency]) {
            el.innerHTML = currencyLabelHtml[currency];
        } else {
            el.textContent = currency;
        }
    }

    function syncChartHeader(currency) {
        const total = parseFloat(currencyTotals[currency] ?? 0);
        const formatted = new Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total);

        const curEl = document.getElementById('chartTotalCurrency');
        const amtEl = document.getElementById('chartTotalAmount');

        renderCurrencyLabel(curEl, currency);
        if (amtEl) amtEl.textContent = formatted;
    }

    const currencySelect = document.getElementById('currencySelect');

    function onCurrencyChange() {
        const currency = this.value;
        syncChartHeader(currency);

        if (chartFetchAbort) chartFetchAbort.abort();
        chartFetchAbort = new AbortController();

        if (chartBox) chartBox.classList.add('is-busy');

        fetch(`/user/home/updated-chart-data/${encodeURIComponent(currency)}`, { signal: chartFetchAbort.signal })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data || !amountChart) return;
                drawChartData(data.months || [], data.amounts || [], FETCH_DRAW_MS);
            })
            .catch(function(err) {
                if (err && err.name === 'AbortError') return;
                if (chartBox) chartBox.classList.remove('is-busy');
            });
    }

    function start() {
        buildChart();
        window._updateAmountChartTheme = function(dark) { applyChartTheme(dark, 'none'); };
        if (currencySelect) {
            currencySelect.addEventListener('change', onCurrencyChange);
            syncChartHeader(currencySelect.value);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
})();
</script>

<script>
(function() {
    const kpiTransactions = @json($totalTransactionsJS);

    document.querySelectorAll('.fd-kpi-filter[data-currency]').forEach(function(select) {
        const currency = select.getAttribute('data-currency');
        const slug = select.id.replace('kpiRange-', '');
        const amountEl = document.getElementById('kpiAmount-' + slug);
        const subEl = document.getElementById('kpiSub-' + slug);
        if (!amountEl) return;

        function filterByDate(range) {
            const now = new Date();
            const currentMonth = now.getMonth();
            const currentYear = now.getFullYear();

            return kpiTransactions.filter(function(t) {
                if ((t.currency || '').toUpperCase() !== currency) return false;

                const date = new Date(t.created_at);
                const month = date.getMonth();
                const year = date.getFullYear();

                if (range === 'total') return true;
                if (range === 'thisMonth') return month === currentMonth && year === currentYear;
                if (range === 'lastMonth') {
                    const lm = currentMonth - 1;
                    const lmY = lm < 0 ? currentYear - 1 : currentYear;
                    const lmM = (lm + 12) % 12;
                    return month === lmM && year === lmY;
                }
                if (range === 'lastFewMonths') {
                    const cutoff = new Date();
                    cutoff.setMonth(cutoff.getMonth() - 3);
                    return date >= cutoff;
                }
                return true;
            });
        }

        function updateAmount() {
            const range = select.value;
            const labels = {
                total: 'All time',
                thisMonth: 'This month',
                lastMonth: 'Last month',
                lastFewMonths: 'Last 3 months',
            };
            if (subEl) subEl.innerText = labels[range] || 'All time';

            const filtered = filterByDate(range);
            const sum = filtered.reduce(function(acc, t) {
                return acc + parseFloat(t.amount || 0);
            }, 0);

            amountEl.innerText = new Intl.NumberFormat(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(sum);
        }

        select.addEventListener('change', updateAmount);
        updateAmount();
    });
})();
</script>
@endsection
