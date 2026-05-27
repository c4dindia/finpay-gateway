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
    $pageCount = $transactions->count();
    $pageVolume = $transactions->sum(function ($t) use ($isFailedPage) {
        return (float) ($isFailedPage ? $t->amount : ($t->settled_amount ?? $t->amount));
    });
    $hasPageStats = method_exists($transactions, 'firstItem') && $pageCount > 0;
    $rangeLabel = $hasPageStats
        ? $transactions->firstItem() . '–' . $transactions->lastItem()
        : '';
@endphp
<div class="fd-trans-tiles">
    <div class="fd-trans-tile fd-trans-tile--{{ $isFailedPage ? 'rose' : 'green' }}">
        <div class="fd-trans-tile__glow" aria-hidden="true"></div>
        <div class="fd-trans-tile__top">
            <span class="fd-trans-tile__label">{{ $isFailedPage ? 'Failed total' : 'Total transactions' }}</span>
            @unless ($isFailedPage)
                <svg class="fd-trans-tile__arrow fd-trans-tile__arrow--green" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <path d="M3.5 10.5L10.5 3.5M10.5 3.5H5M10.5 3.5V9" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            @endunless
        </div>
        <div class="fd-trans-tile__value">{{ number_format($totalCount) }}</div>
    </div>

    @if ($hasPageStats)
        <div class="fd-trans-tile fd-trans-tile--purple">
            <div class="fd-trans-tile__glow" aria-hidden="true"></div>
            <div class="fd-trans-tile__top">
                <span class="fd-trans-tile__label">This page</span>
            </div>
            <div class="fd-trans-tile__value">{{ number_format($pageCount) }}</div>
            <div class="fd-trans-tile__foot">
                <span class="fd-trans-tile__sub">{{ $rangeLabel }}</span>
            </div>
        </div>

        <div class="fd-trans-tile fd-trans-tile--blue">
            <div class="fd-trans-tile__glow" aria-hidden="true"></div>
            <div class="fd-trans-tile__top">
                <span class="fd-trans-tile__label">Page volume</span>
            </div>
            <div class="fd-trans-tile__value">{{ number_format($pageVolume, 2) }}</div>
            <div class="fd-trans-tile__foot">
                <span class="fd-trans-tile__sub">{{ $isFailedPage ? 'amount total' : 'Settled total' }}</span>
            </div>
        </div>
    @endif

    <div class="fd-trans-tile fd-trans-tile--slate">
        <div class="fd-trans-tile__glow" aria-hidden="true"></div>
        <div class="fd-trans-tile__top">
            <span class="fd-trans-tile__label">View</span>
        </div>
        <div class="fd-trans-tile__value fd-trans-tile__value--text">{{ $periodLabel }}</div>
        <div class="fd-trans-tile__foot">
            <span class="fd-trans-tile__sub">{{ $serviceLabel }}</span>
        </div>
    </div>
</div>
