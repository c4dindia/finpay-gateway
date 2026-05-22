@php $metricCols = $metricCols ?? 'col-6'; @endphp
<div class="{{ $metricCols }}">
    <div class="fd-metric green">
        <div class="fd-metric-head">
            <span class="fd-metric-label">Captured</span>
            <div class="fd-metric-icon">
                <svg viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 6.5L5 9.5L11 3.5" stroke="#10b981" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
        </div>
        <div class="val">{{ $capturedCount }}</div>
        <div class="fd-meter">
            <div class="fd-meter-track"><div class="fd-meter-fill" style="width:{{ min($capturedPercentage, 100) }}%"></div></div>
            <span class="fd-foot">{{ number_format($capturedPercentage, 2) }}%</span>
        </div>
    </div>
</div>
<div class="{{ $metricCols }}">
    <div class="fd-metric amber">
        <div class="fd-metric-head">
            <span class="fd-metric-label">Awaiting</span>
            <div class="fd-metric-icon">
                <svg viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="6.5" cy="6.5" r="4" stroke="#f59e0b" stroke-width="1.5"/><path d="M6.5 4.5V6.5L7.5 7.5" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round"/></svg>
            </div>
        </div>
        <div class="val">{{ $awaitingCount }}</div>
        <div class="fd-meter">
            <div class="fd-meter-track"><div class="fd-meter-fill" style="width:{{ min($awaitingPercentage, 100) }}%"></div></div>
            <span class="fd-foot">{{ number_format($awaitingPercentage, 2) }}%</span>
        </div>
    </div>
</div>
<div class="{{ $metricCols }}">
    <div class="fd-metric red">
        <div class="fd-metric-head">
            <span class="fd-metric-label">Failed</span>
            <div class="fd-metric-icon">
                <svg viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 4L9 9M9 4L4 9" stroke="#ef4444" stroke-width="1.5" stroke-linecap="round"/></svg>
            </div>
        </div>
        <div class="val">{{ $failedCount }}</div>
        <div class="fd-meter">
            <div class="fd-meter-track"><div class="fd-meter-fill" style="width:{{ min($failedPercentage, 100) }}%"></div></div>
            <span class="fd-foot">{{ number_format($failedPercentage, 2) }}%</span>
        </div>
    </div>
</div>
<div class="{{ $metricCols }}">
    <div class="fd-metric violet">
        <div class="fd-metric-head">
            <span class="fd-metric-label">Total</span>
            <div class="fd-metric-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                    <path d="M11.9167 6.49998H10.5734C10.3366 6.49947 10.1063 6.57652 9.91748 6.71934C9.72869 6.86215 9.59187 7.06288 9.52796 7.29081L8.25504 11.8191C8.24684 11.8473 8.22973 11.872 8.20629 11.8896C8.18285 11.9071 8.15434 11.9166 8.12504 11.9166C8.09574 11.9166 8.06723 11.9071 8.04379 11.8896C8.02035 11.872 8.00325 11.8473 7.99504 11.8191L5.00504 1.18081C4.99684 1.15268 4.97973 1.12798 4.95629 1.1104C4.93285 1.09282 4.90434 1.08331 4.87504 1.08331C4.84574 1.08331 4.81723 1.09282 4.79379 1.1104C4.77035 1.12798 4.75324 1.15268 4.74504 1.18081L3.47212 5.70915C3.40846 5.93619 3.27246 6.13626 3.08476 6.27899C2.89706 6.42171 2.66792 6.49931 2.43212 6.49998H1.08337" stroke="#818CF8" stroke-width="1.08333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <div class="val">{{ $totalCount }}</div>
        <div class="fd-meter">
            <div class="fd-meter-track"><div class="fd-meter-fill" style="width:100%"></div></div>
            <span class="fd-foot">100.00%</span>
        </div>
    </div>
</div>
