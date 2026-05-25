@php
    $code = strtoupper(trim((string) ($code ?? '')));
    $faIcons = [
        'INR' => 'fa-indian-rupee-sign',
        'USD' => 'fa-dollar-sign',
        'GBP' => 'fa-sterling-sign',
        'EUR' => 'fa-euro-sign',
    ];
    $symbols = [
        'USDT' => '₮',
        'ETH' => 'Ξ',
        'CAD' => 'C$',
    ];
@endphp
@if ($code === '')
@elseif (isset($faIcons[$code]))
    <i class="fa-solid {{ $faIcons[$code] }} fd-currency-icon" aria-hidden="true"></i>
    <span class="visually-hidden">{{ $code }}</span>
@elseif (isset($symbols[$code]))
    <span class="fd-currency-symbol" aria-hidden="true">{{ $symbols[$code] }}</span>
    <span class="visually-hidden">{{ $code }}</span>
@else
    <span class="fd-currency-code">{{ $code }}</span>
@endif
