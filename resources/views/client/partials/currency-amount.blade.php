@php
    $currency = $currency ?? '';
    $amount = (float) ($amount ?? 0);
    $decimals = (int) ($decimals ?? 2);
@endphp
<span class="fd-currency-amount">
    @if ($currency !== '' && $currency !== '-')
        @include('client.partials.currency-icon', ['code' => $currency])
    @endif
    <span class="fd-currency-amount__value">{{ number_format($amount, $decimals) }}</span>
</span>
