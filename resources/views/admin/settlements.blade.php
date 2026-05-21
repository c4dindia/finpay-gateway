@extends('layouts.adminMaster')

@section('title')
Settlements
@php
$currentPage = 'Settlements';
$companiesByAccountId = $companies->keyBy('accountId');
$selectedService = old('payment_service', request('payment_service', ''));
@endphp
@endsection

@section('css')
@endsection

@section('page-content')

<div class="p-2 p-lg-4 gap-4 settlements-page">
    <h3 class="text-center admin-db-heading mb-3">Settlements of All Companies</h3>

    <div class="fd-admin-card settlements-form mb-4">
        <h5 class="settlements-form-title mb-4">Create Amount Settlement</h5>
        <form action="{{ route('addSettlement') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="accountId" class="form-label">Account <span class="text-danger">*</span></label>
                    <select name="accountId" id="accountId" class="form-select @error('accountId') is-invalid @enderror" required>
                        <option value="">-- Select Account --</option>
                        @foreach ($companies as $company)
                        <option value="{{ $company->accountId }}" {{ old('accountId') == $company->accountId ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('accountId')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                    <select name="currency" id="currency" class="form-select @error('currency') is-invalid @enderror" required>
                        <option value="" disabled {{ old('currency') ? '' : 'selected' }}>-- Select Currency --</option>
                        <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>INR</option>
                    </select>
                    @error('currency')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                    <input type="text" min="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Enter amount" value="{{ old('amount') }}" required>
                    @error('amount')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="payment_service" class="form-label">Payment Service <span class="text-danger">*</span></label>
                    <select name="payment_service" id="payment_service" class="form-select @error('payment_service') is-invalid @enderror" required>
                        <option value="" {{ $selectedService === '' ? 'selected' : '' }}>-- Select Service --</option>
                        <option value="p23" {{ $selectedService === 'p23' ? 'selected' : '' }}>P-23 UPI</option>
                    </select>
                    @error('payment_service')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="commission" class="form-label">Commission</label>
                    <input type="text" name="commission" class="form-control @error('commission') is-invalid @enderror" id="commission" placeholder="Commission" value="{{ old('commission') }}">
                    @error('commission')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description" value="{{ old('description') }}" required>
                    @error('description')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="submit-btn">Settle Amount</button>
                </div>
            </div>
        </form>
    </div>

    @if (count($settlements) == 0)
    <div class="row mt-4 pt-2">
        <h5 class="text-center col-12">No Settlements!</h5>
    </div>
    @else
    <div class="table-container d-none d-lg-block">
        <table class="table custom-table align-middle all-trans-table">
            <thead>
                <tr>
                    <th class="table-header text-center p-3">Date</th>
                    <th class="table-header text-center p-3">Account</th>
                    <th class="table-header text-center p-3">Amount</th>
                    <th class="table-header text-center p-3">Checkout ID</th>
                    <th class="table-header text-center p-3">Pay Service</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settlements as $settlement)
                @php
                $company = $companiesByAccountId->get($settlement->accountId);
                $provider = match ($settlement->payment_service) {
                    'p23' => 'UPI',
                    default => $settlement->payment_service ?: '-',
                };
                @endphp
                <tr class="table-row" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $settlement->id }}">
                    <td class="table-data text-center text-nowrap">
                        {{ \Carbon\Carbon::parse($settlement->created_at)->format('d M Y') }}
                    </td>
                    <td class="table-data text-center text-nowrap">
                        {{ $company->company_name ?? $settlement->accountId }}
                    </td>
                    <td class="table-data text-center text-nowrap">
                        {{ $settlement->currency }} {{ number_format($settlement->amount, 2) }}
                    </td>
                    <td class="table-data text-center text-nowrap">
                        {{ $settlement->checkout_id }}
                    </td>
                    <td class="table-data text-center text-nowrap">
                        {{ $provider }}
                    </td>
                </tr>
                @endforeach
                @for ($i = count($settlements); $i < 9; $i++)
                <tr class="pad">
                    <td colspan="5" style="border:none !important;">&nbsp;</td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div class="row d-lg-none d-flex justify-content-center">
        @foreach ($settlements as $settlement)
        @php
        $company = $companiesByAccountId->get($settlement->accountId);
        $provider = match ($settlement->payment_service) {
            'p23' => 'UPI',
            default => $settlement->payment_service ?: '-',
        };
        @endphp
        <div class="col-12 col-sm-6 p-2">
            <div class="col-12 ind-card" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $settlement->id }}" style="cursor: pointer;">
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">Account</div>
                    <span class="col-6">{{ $company->company_name ?? $settlement->accountId }}</span>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">Amount</div>
                    <div class="col-6">{{ $settlement->currency }} {{ number_format($settlement->amount, 2) }}</div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">Date</div>
                    <div class="col-6">{{ \Carbon\Carbon::parse($settlement->created_at)->format('d M Y') }}</div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">Pay Service</div>
                    <div class="col-6">{{ $provider }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if (method_exists($settlements, 'links'))
    <div class="d-flex justify-content-end align-items-center gap-4 lg:gap-14 mt-4 pr-3 lg:py-5">
        <div class="d-flex align-items-center">
            <span class="footer-text">Showing {{ $settlements->firstItem() ?? 0 }} to
                {{ $settlements->lastItem() ?? 0 }} of
                {{ $settlements->total() }} results
            </span>
        </div>

        <div class="">
            <ul class="pagination d-flex align-items-center gap-2">
                @if (!$settlements->onFirstPage())
                <li class="">
                    <a class="d-flex align-items-center" href="{{ $settlements->previousPageUrl() ?? '#' }}">
                        <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 7L7 1M1 7L7 13M1 7L15 7" stroke="#827F7F" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </li>
                @endif
                @php
                $current = $settlements->currentPage();
                $last = $settlements->lastPage();
                $window = 3;
                $start = max(1, $current - $window);
                $end = min($last, $current + $window);
                @endphp
                @if ($start > 1)
                <li class=""><a href="{{ $settlements->url(1) }}">1</a></li>
                @if ($start > 2)
                <li class=""><span>…</span></li>
                @endif
                @endif
                @for ($page = $start; $page <= $end; $page++)
                <li class="{{ $page === $current ? 'pagination-box-active' : 'pagination-box-inactive' }}">
                    @if ($page === $current)
                    <span class="pagination-box">{{ $page }}</span>
                    @else
                    <a class="pagination-box" href="{{ $settlements->url($page) }}">{{ $page }}</a>
                    @endif
                </li>
                @endfor
                @if ($end < $last)
                @if ($end < $last - 1)
                <li class=""><span>…</span></li>
                @endif
                <li class=""><a href="{{ $settlements->url($last) }}">{{ $last }}</a></li>
                @endif
                @if ($settlements->hasMorePages())
                <li class="">
                    <a class="d-flex align-items-center" href="{{ $settlements->nextPageUrl() ?? '#' }}">
                        <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 7L9 13M15 7L9 1M15 7L1 7" stroke="#827F7F" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
    @endif
    @endif
</div>

@foreach ($settlements as $settlement)
@php
$company = $companiesByAccountId->get($settlement->accountId);
@endphp
<div class="modal fade" id="transactionModal-{{ $settlement->id }}" tabindex="-1" aria-labelledby="settlementModalLabel-{{ $settlement->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="settlementModalLabel-{{ $settlement->id }}">
                    Settlement Details for {{ $settlement->checkout_id }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <table class="table table-striped mb-0">
                    <tbody>
                        <tr>
                            <th scope="row">Company</th>
                            <td>{{ $company->company_name ?? $settlement->accountId }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Description</th>
                            <td>{{ ucfirst($settlement->description) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Via Service</th>
                            <td>{{ ucfirst($settlement->payment_service) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Amount</th>
                            <td>{{ $settlement->currency }} {{ number_format($settlement->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Commission</th>
                            <td>{{ $settlement->currency }} {{ number_format($settlement->commission ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Checkout ID</th>
                            <td>{{ $settlement->checkout_id }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Initiated At</th>
                            <td>{{ \Carbon\Carbon::parse($settlement->created_at)->format('H:i, d/M/Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
@endsection
