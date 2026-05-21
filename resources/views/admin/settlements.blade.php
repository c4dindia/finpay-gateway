@extends('layouts.adminMaster')

@section('title')
Settlements
@php
$currentPage = 'Settlements';
@endphp
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/adminside/p1services.css') }}">
<link rel="stylesheet" href="{{ asset('css/clientside/transactions.css') }}">

<style>
    .btn {
        padding: 6px 12px;
        text-decoration: none;
        border: 1px solid var(--navItem-bgColor);
        color: var(--navItem-bgColor);
        border-radius: 4px;
    }

    .btn:hover {
        background-color: var(--navItem-bgColor);
        color: white;
    }

    .btn-outline-primary {
        background-color: transparent;
    }
</style>
@endsection

@section('page-content')

<div class="p-2 p-lg-4 gap-4">
    <h3 class="text-center" style="Font-family:Poppins; font-weight: 500; line-height: 48px; font-size: 24px;">
        Settlements of All Companies </h3>

    <div class="d-flex justify-content-center mt-3 mb-5">
        <div class="input-group">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: var(--navItem-bgColor);">
                    <h5 class="mb-0">Create Amount Settlement</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('addSettlement') }}" method="POST">
                        @csrf

                        @php
                        $selectedService = old('payment_service', request('payment_service', 'all'));
                        @endphp

                        <div class="row g-3">

                            <!-- Account -->
                            <div class="col-md-6">
                                <label for="accountId" class="form-label">
                                    Account <span class="text-danger">*</span>
                                </label>

                                <select name="accountId" id="accountId"
                                    class="form-select @error('accountId') is-invalid @enderror" required>
                                    <option value="">-- Select Account --</option>

                                    @foreach ($companies as $company)
                                    <option value="{{ $company->accountId }}" {{ old('accountId') == $company->accountId ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('accountId')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Currency -->
                            <div class="col-md-3">
                                <label for="currency" class="form-label">
                                    Currency <span class="text-danger">*</span>
                                </label>
                                <select name="currency" id="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                    <option value="" disabled selected>-- Select Currency --</option>
                                    <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>INR</option>
                                </select>
                                @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Amount -->
                            <div class="col-md-3">
                                <label for="amount" class="form-label">
                                    Amount <span class="text-danger">*</span>
                                </label>

                                <input type="text" min="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Enter amount" value="{{ old('amount') }}" required>

                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Payment Service -->
                            <div class="col-md-3">
                                <label for="payment_service" class="form-label">
                                    Payment Service
                                </label>

                                <select name="payment_service" id="payment_service" class="form-select @error('payment_service') is-invalid @enderror" required>
                                    <option value="" {{ $selectedService === 'all' ? 'selected' : '' }}>--- Select Service --- </option>
                                    <option value="p23" {{ $selectedService === 'p23' ? 'selected' : '' }}>P-23 UPI </option>
                                </select>

                                @error('payment_service')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="payment_service" class="form-label">
                                    Commission
                                </label>
                                <input type="text" name="commission" class="form-control" id="commission" placeholder="Commission">
                                @error('commission')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Description -->
                            <div class="col-md-6">
                                <label for="description" class="form-label">
                                    Description
                                </label>

                                <textarea name="description" id="description" rows="1"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Description" required>{{ old('description') }}</textarea>

                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Submit Button -->
                            <div class="col-12 text-end mt-3">
                                <button type="submit" class="btn btn-primary text-white px-4">
                                    Settle Amount
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (count($settlements) == 0)
    <div class="w-100 text-center">
        <h5>No Settlements!</h5>
    </div>
    @else
    <div class="row justify-content-center">
        <div class="col-md-12 summary-card2 summary-card-transactions-client d-none d-lg-block">

            <div class="scrollable table-wrapper">
                <table class="table text-center trans-table-client" style="border:none">
                    <thead class="table-header-transactions text-white">
                        <tr class="">
                            <th scope="col" class="top-left text-nowrap">Date</th>
                            <th scope="col" class="text-nowrap">Account</th>
                            {{-- <th scope="col" class="text-nowrap">Description</th> --}}
                            <th scope="col" class="text-nowrap">Amount</th>
                            <th scope="col" class="text-nowrap">CheckoutId</th>
                            <th scope="col" class="n-amt top-right text-nowrap">Pay Service</th>
                        </tr>
                    </thead>
                    <tbody class="">

                        @foreach ($settlements as $settlement)
                        @php
                        $status = 'Completed';
                        $statusClasses = match ($status) {
                        'Created', 'Initialized', 'Started', 'Confirming' => 'createdBg-color',
                        'Completed', 'Approved', 'Succeeded', 'Success', 'Complete', 'Captured', 'Paid', 'Allocated' => 'donebg-color',
                        'Pending', 'Attempting', 'Processing', 'Waiting', 'Awaiting' => 'pendingBg-color',
                        'Failed', 'Rejected', 'Cancelled', 'Declined', 'Expired' => 'failedBg-color',
                        default => '',
                        };
                        $type = preg_replace('/_+/', ' ', (string) $settlement->type) ?: '-';
                        @endphp

                        <tr style="cursor: pointer;">

                            <td scope="row" class="text-nowrap first-column px-2" data-bs-toggle="modal"
                                data-bs-target="#transactionModal-{{ $settlement->id }}">
                                {{-- $loop->iteration + ($settlement->currentPage() - 1) * $settlement->perPage() --}}
                                {{ \Carbon\Carbon::parse($settlement->created_at)->format('d/m/y') }}
                            </td>
                            <td scope="row" class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $settlement->id }}">
                                {{ \App\Models\Company::where('accountId', $settlement->accountId)->first()->company_name }}
                            </td>
                            <td scope="row" class="text-nowrap" data-bs-toggle="modal"
                                data-bs-target="#transactionModal-{{ $settlement->id }}">
                                {{ $settlement->currency }} {{ number_format($settlement->amount, 2) }}
                            </td>
                            <td scope="row" class="text-nowrap" data-bs-toggle="modal"
                                data-bs-target="#transactionModal-{{ $settlement->id }}">
                                {{ $settlement->checkout_id }}
                            </td>
                            <td scope="row" class="last-column text-nowrap" data-bs-toggle="modal"
                                data-bs-target="#transactionModal-{{ $settlement->id }}">
                                @php
                                $provider = match ($settlement->payment_service) {
                                'p23' => 'UPI',
                                default => '',
                                };
                                @endphp

                                {{ $provider }}
                            </td>
                        </tr>
                        @endforeach
                        @if (count($settlements) < 15)
                            @for ($i=0; $i < 15 - count($settlements); $i++)
                            <tr class="no-hover">
                            <td colspan="8"
                                style="{{ $i == 0 ? 'border:0;border-top:1px solid #B8B8B8' : 'border: none;' }}">
                                &nbsp;
                            </td>
                            </tr>
                            @endfor
                            @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- for mobile and tablet  -->

    <!-- card section  -->
    <div class="row  d-lg-none">
        <!-- individual card -->
        @foreach ($settlements as $settlement)
        @php
        $status = 'Completed';
        $statusClasses = match ($status) {
        'Created', 'Initialized', 'Started', 'Confirming' => 'statusCreated',
        'Completed', 'Approved', 'Succeeded', 'Success', 'Complete', 'Captured', 'Paid' => 'statusSuccess',
        'Pending', 'Attempting', 'Processing', 'Waiting', 'Awaiting' => 'statuswarning',
        'Failed', 'Rejected', 'Cancelled', 'Declined', 'Expired' => 'statusDanger',
        default => '',
        };
        $type = preg_replace('/_+/', ' ', (string) $settlement->type) ?: '-';
        @endphp
        <div class="col-12 col-sm-6 p-2">
            <div class="d-flex flex-column ind-card gap-3" data-bs-toggle="modal"
                data-bs-target="#transactionModal-{{ $settlement->id }}" style="cursor: pointer;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex flex-column justify-content-center text-start">
                        <span class="card-el-head">
                            Receiver
                        </span>
                        <span class="card-el-content">
                            {{ \App\Models\Company::where('accountId', $settlement->accountId)->first()->company_name }}
                        </span>
                    </div>
                    <div class="currency">
                        {{ $settlement->currency }} {{ number_format($settlement->amount, 2) }}
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex flex-column justify-content-center text-start">
                        <span class="card-el-head">
                            Date
                        </span>
                        <span class="card-el-content">
                            {{ \Carbon\Carbon::parse($settlement->created_at)->format('d/m/y') }}
                        </span>
                    </div>
                    <div class="{{ $statusClasses }}">
                        {{ $settlement->payment_service }}
                    </div>
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
                {{-- Prev --}}
                @if (!$settlements->onFirstPage())
                <li class="">
                    <a class="d-flex align-items-center" href=" {{ $settlements->previousPageUrl() ?? '#' }}">
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
                $window = 3; // change to 2 or 3 to show more numbers on each side
                $start = max(1, $current - $window);
                $end = min($last, $current + $window);
                @endphp
                {{-- First + leading ellipsis --}}
                @if ($start > 1)
                <li class=""><a class="" href=" {{ $settlements->url(1) }}" style="color: black">1</a> </li>
                @if ($start > 2)
                <li class=" "><span class=" " style="color: black">…</span></li>
                @endif
                @endif
                {{-- Page range --}}
                @for ($page = $start; $page <= $end; $page++)
                    <li class=" {{ $page === $current ? 'pagination-box-active' : 'pagination-box-inactive' }}">
                    @if ($page === $current)
                    <span class="pagination-box">{{ $page }}</span>
                    @else
                    <a class="pagination-box" href="{{ $settlements->url($page) }}" style="color: black">{{ $page }}</a>
                    @endif
                    </li>
                    @endfor
                    {{-- Trailing ellipsis + last --}}
                    @if ($end < $last)
                        @if ($end < $last - 1)
                        <li class=" "><span class="rounded-[5px]">…</span></li>
                        @endif
                        <li class=""><a class="" href=" {{ $settlements->url($last) }}" style="color: black">{{ $last }}</a>
                        </li>
                        @endif
                        {{-- Next --}}
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

{{-- Modal for transaction details --}}
@foreach ($settlements as $settlement)
<div class="modal fade" id="transactionModal-{{ $settlement->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Settlement Details for
                    {{ $settlement->checkout_id }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th scope="row">Company </th>
                            <td>
                                {{ \App\Models\Company::where('accountId', $settlement->accountId)->first()->company_name  }}
                            </td>
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
                            <td>{{ $settlement->currency }} {{ number_format($settlement->commission, 2) }}</td>
                        </tr>

                        <tr>
                            <th scope="row">Checkout ID:</th>
                            <td>{{ $settlement->checkout_id }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Initiated At</th>
                            <td>{{ \Carbon\Carbon::parse($settlement->created_at)->format('H:m , d/M/Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div> --}}
        </div>
    </div>
</div>
@endforeach


<script>
    const input = document.querySelector('input[name=q]');
    const btn = document.querySelector('#searchbtn');

    // Function to update button state
    function updateBtnState() {
        btn.disabled = (input.value.trim() === '');
    }

    // 1. Run once on page load (for persisted values)
    updateBtnState();

    // 2. Listen forever for any input changes
    input.addEventListener('input', updateBtnState);
</script>

@endsection

@section('scripts')
@endsection