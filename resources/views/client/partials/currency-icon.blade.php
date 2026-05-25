@php
    $code = strtoupper(trim((string) ($code ?? '')));
@endphp
@switch($code)
    @case('INR')
        <i class="fa-solid fa-indian-rupee-sign fd-currency-icon" aria-hidden="true"></i>
        <span class="visually-hidden">INR</span>
        @break
    @case('USD')
        <i class="fa-solid fa-dollar-sign fd-currency-icon" aria-hidden="true"></i>
        <span class="visually-hidden">USD</span>
        @break
    @default
        <span class="fd-currency-code">{{ $code }}</span>
@endswitch
