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
    <div class="fd-trans-panel__hero">
        @include('client.partials.transactions-summary', compact('isFailedPage'))
        @if ($showDownload)
            <button type="button" class="fd-trans-download" data-bs-toggle="modal" data-bs-target="#downloadModal"
                aria-label="Download transaction records">
                <span class="fd-trans-download__glow" aria-hidden="true"></span>
                <span class="fd-trans-download__icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M12 4V15M12 15L7.5 10.5M12 15L16.5 10.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 18.5H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="fd-trans-download__text">
                    <span class="fd-trans-download__title">Download Report</span>
                    <span class="fd-trans-download__sub">Excel / CSV export</span>
                </span>
            </button>
        @endif
    </div>

    <form action="{{ $formAction }}" method="GET" class="fd-trans-filters" id="fd-trans-filters-form" data-clear-url="{{ $clearUrl }}">
        <div class="fd-trans-filters__search">
            <label class="fd-trans-filters__label" for="trans-search-input">Search</label>
            <div class="fd-trans-filters__search-row">
                <div class="fd-trans-filters__search-field">
                    <span class="fd-trans-filters__search-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21.0004 20.9999L16.6504 16.6499" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <input type="text" name="q" id="trans-search-input"
                        class="fd-trans-filters__input"
                        placeholder="Search"
                        value="{{ request('q') }}">
                </div>
                <div class="fd-trans-filters__actions">
                    <button type="button" onclick="window.location='{{ $clearUrl }}'"
                        class="fd-trans-filters__btn fd-trans-filters__btn--clear {{ request('q') == '' ? 'd-none' : '' }}">
                        Clear
                    </button>
                    <button type="submit" id="searchbtn" class="fd-trans-filters__btn fd-trans-filters__btn--search" disabled>
                        Search
                    </button>
                </div>
            </div>
        </div>

        <div class="fd-trans-filters__selects">
            <div class="fd-trans-filters__field">
                <label class="fd-trans-filters__label" for="name">Period</label>
                <select name="name" id="name" class="fd-trans-filters__select" data-fd-filter required>
                    <option value="total" {{ $selectedName === 'total' ? 'selected' : '' }}>Total</option>
                    <option value="thisMonth" {{ $selectedName === 'thisMonth' ? 'selected' : '' }}>This month</option>
                    <option value="lastMonth" {{ $selectedName === 'lastMonth' ? 'selected' : '' }}>Last month</option>
                    <option value="lastFewMonths" {{ $selectedName === 'lastFewMonths' ? 'selected' : '' }}>Last 3 months</option>
                </select>
            </div>
            <div class="fd-trans-filters__field">
                <label class="fd-trans-filters__label" for="service">Service</label>
                <select name="service" id="service" class="fd-trans-filters__select" data-fd-filter>
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
<script>
    (function () {
        var form = document.getElementById('fd-trans-filters-form');
        if (!form) return;

        var searchInput = form.querySelector('input[name="q"]');
        var searchBtn = form.querySelector('#searchbtn');

        function buildUrl() {
            var action = form.getAttribute('action') || window.location.pathname;
            var params = new URLSearchParams();
            var inputs = form.querySelectorAll('input[name], select[name]');
            inputs.forEach(function (el) {
                var name = el.getAttribute('name');
                if (!name) return;
                var value = (el.value || '').trim();
                if (value === '') return;
                params.set(name, value);
            });
            var qs = params.toString();
            return qs ? action + '?' + qs : action;
        }

        function navigate() {
            window.location.assign(buildUrl());
        }

        function updateSearchBtn() {
            if (!searchInput || !searchBtn) return;
            searchBtn.disabled = searchInput.value.trim() === '';
        }

        form.addEventListener('submit', function (ev) {
            ev.preventDefault();
            if (searchBtn && searchBtn.disabled) return;
            navigate();
        });

        if (searchInput) {
            updateSearchBtn();
            searchInput.addEventListener('input', updateSearchBtn);
            searchInput.addEventListener('keydown', function (ev) {
                if (ev.key !== 'Enter') return;
                ev.preventDefault();
                if (searchBtn && searchBtn.disabled) return;
                navigate();
            });
        }

        form.querySelectorAll('select[data-fd-filter]').forEach(function (sel) {
            sel.addEventListener('change', navigate);
        });
    })();
</script>
@if ($showDownload)
    @include('client.partials.transactions-export-modal')
@endif
