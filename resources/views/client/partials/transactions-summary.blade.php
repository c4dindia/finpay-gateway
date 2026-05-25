@php
    $isFailedPage = $isFailedPage ?? false;
    $periodKey = request('name', $select ?? 'total');
    $periodLabels = [
        'total' => 'All time',
        'thisMonth' => 'This month',
        'lastMonth' => 'Last month',
        'lastFewMonths' => 'Last 3 months',
    ];
    $periodLabel = $periodLabels[$periodKey] ?? 'All time';
    $serviceKey = request('service', 'all');
    $serviceOptionLabels = [
        'p12' => 'P-12 [2D/3D]',
        'p17' => 'P-17 Dire',
        'p22' => 'P-22 Uniqo',
        'p23' => 'P-23 UPI',
    ];
    $serviceLabel = $serviceKey === 'all'
        ? 'All services'
        : ($serviceOptionLabels[$serviceKey] ?? strtoupper($serviceKey));
    $totalCount = method_exists($transactions, 'total') ? $transactions->total() : count($transactions);
    $pageVolume = $transactions->sum(function ($t) use ($isFailedPage) {
        return (float) ($isFailedPage ? $t->amount : ($t->settled_amount ?? $t->amount));
    });
@endphp
<div class="fd-trans-summary">
    <div class="fd-trans-summary__item fd-trans-summary__item--primary">
        <span class="fd-trans-summary__label">{{ $isFailedPage ? 'Failed' : 'Total' }}</span>
        <span class="fd-trans-summary__value">{{ number_format($totalCount) }}</span>
        <span class="fd-trans-summary__meta">transactions</span>
    </div>
    @if (method_exists($transactions, 'firstItem') && $transactions->count() > 0)
        <div class="fd-trans-summary__divider" aria-hidden="true"></div>
        <div class="fd-trans-summary__item">
            <span class="fd-trans-summary__label">This page</span>
            <span class="fd-trans-summary__value">{{ number_format($transactions->count()) }}</span>
            <span class="fd-trans-summary__meta">{{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} shown</span>
        </div>
        <div class="fd-trans-summary__divider" aria-hidden="true"></div>
        <div class="fd-trans-summary__item">
            <span class="fd-trans-summary__label">Page volume</span>
            <span class="fd-trans-summary__value">{{ number_format($pageVolume, 2) }}</span>
            <span class="fd-trans-summary__meta">{{ $isFailedPage ? 'amount total' : 'settled total' }}</span>
        </div>
    @endif
    <div class="fd-trans-summary__divider fd-trans-summary__divider--hide-sm" aria-hidden="true"></div>
    <div class="fd-trans-summary__item fd-trans-summary__item--filters">
        <span class="fd-trans-summary__label">View</span>
        <span class="fd-trans-summary__value fd-trans-summary__value--text">{{ $periodLabel }}</span>
        <span class="fd-trans-summary__meta">{{ $serviceLabel }}</span>
    </div>
</div>
