@php
    $isFailedPage = $isFailedPage ?? false;
    $showDownload = $showDownload ?? false;
    $formAction = $formAction ?? route('showTransactions');
    $clearUrl = $clearUrl ?? route('showTransactions');
    $selectedName = request('name', 'total');
    $selectedService = request('service', 'all');
    $company = Auth::user()->company()->first();
    $services = [
        'p12' => ['label' => '2D/3D', 'model' => \App\Models\PTwelvePaymentMethod::class],
        'p17' => ['label' => 'P-17 Dire', 'model' => \App\Models\Direpay::class],
        'p22' => ['label' => 'P-22 Uniqo', 'model' => \App\Models\UniqoPay::class],
        'p23' => ['label' => 'P-23 UPI', 'model' => \App\Models\UPIPayment::class],
    ];
@endphp
<div class="col-12 fd-trans-panel ps-lg-2 pe-lg-0">
    <div class="fd-trans-panel__inner">
        <div class="fd-trans-panel__stats">
            <div class="fd-trans-panel__stats-inner">
                @include('client.partials.transactions-summary', compact('isFailedPage'))
                @if ($showDownload)
                    <div class="fd-trans-export">
                        <button type="button" class="fd-trans-export-btn" data-bs-toggle="modal" data-bs-target="#downloadModal"
                            aria-label="Export transactions to Excel">
                            <i class="fa fa-download fd-trans-export-btn__icon" aria-hidden="true"></i>
                            <span class="fd-trans-export-btn__label">Download Transaction Records</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="fd-trans-panel__controls">
            <form action="{{ $formAction }}" method="GET" class="fd-trans-filters">
                <div class="fd-trans-filters__row fd-trans-filters__row--search">
                    <label class="fd-trans-field__label" for="trans-search-input">Search</label>
                    <div class="fd-trans-search-group">
                        <span class="fd-trans-search-group__icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21.0004 20.9999L16.6504 16.6499" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <input type="text" name="q" id="trans-search-input"
                            class="form-control trans-search fd-trans-search"
                            placeholder="Transaction ID or checkout ID"
                            value="{{ request('q') }}">
                        <div class="fd-trans-search-group__actions">
                            <button type="button" onclick="window.location='{{ $clearUrl }}'"
                                class="btn btn-outline-primary fd-trans-btn-secondary {{ request('q') == '' ? 'd-none' : '' }}">
                                Clear
                            </button>
                            <button id="searchbtn" class="btn btn-outline-primary trans-search-btn fd-trans-search-btn" type="submit" disabled>
                                Search
                            </button>
                        </div>
                    </div>
                </div>
                <div class="fd-trans-filters__row fd-trans-filters__row--fields">
                    <div class="fd-trans-field">
                        <label class="fd-trans-field__label" for="name">Period</label>
                        <select name="name" id="name" class="form-select trans-select fd-trans-select" onchange="this.form.submit()" required>
                            <option value="total" {{ $selectedName === 'total' ? 'selected' : '' }}>Total</option>
                            <option value="thisMonth" {{ $selectedName === 'thisMonth' ? 'selected' : '' }}>This month</option>
                            <option value="lastMonth" {{ $selectedName === 'lastMonth' ? 'selected' : '' }}>Last month</option>
                            <option value="lastFewMonths" {{ $selectedName === 'lastFewMonths' ? 'selected' : '' }}>Last 3 months</option>
                        </select>
                    </div>
                    <div class="fd-trans-field">
                        <label class="fd-trans-field__label" for="service">Service</label>
                        <select name="service" id="service" class="form-select trans-select fd-trans-select" onchange="this.form.submit()">
                            <option value="all" {{ $selectedService === 'all' ? 'selected' : '' }}>All services</option>
                            @foreach ($services as $key => $service)
                                @if ($company && $service['model']::where('company_id', $company->id)->where('status', 1)->exists())
                                    <option value="{{ $key }}" {{ $selectedService === $key ? 'selected' : '' }}>{{ $service['label'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@if ($showDownload)
    @include('client.partials.transactions-export-modal')
@endif
