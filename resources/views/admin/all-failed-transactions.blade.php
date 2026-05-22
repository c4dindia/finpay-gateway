@extends('layouts.adminMaster')

@section('title')
All Declined Transactions
@php
$currentPage = 'All Declined Transactions';
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
  <h3 class="text-center" style="Font-family:Poppins; font-weight: 500; font-size: 24px; line-height: 48px;">
    Transactions of All Companies </h3>

  <div class="d-flex justify-content-center">
    <div class="input-group">
      <span class="trans-search-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path
            d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
            stroke="#0050B1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          <path d="M21.0004 20.9999L16.6504 16.6499" stroke="#0050B1" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" />
        </svg>
      </span>
      <form action="{{ route('showAllFailedTransactions') }}" method="GET" id="searchForm" class="d-flex flex-column flex-xxl-row align-items-center gap-3 gap-xxl-5 pb-md-4">
        <div class="input-group d-flex gap-3 justify-content-center">

          <input type="text" name="q" class="form-control trans-search p-1 ps-5 rounded-4"
            placeholder="Search (txn Id, checkout Id)" value="{{ request('q') }}">
          <button type="button"
            onclick="window.location='{{ route('showAllFailedTransactions') }}'"
            class="btn btn-outline-primary rounded-4 {{ request('q')=='' ? 'd-none' : 'd-block' }}">
            Cancel
          </button>

          <!-- Search button -->
          <button class="btn btn-outline-primary trans-search-btn rounded-4" type="submit">
            Search
          </button>
        </div>

        <div class="">
          @php $selectedService = request('service', 'all'); @endphp
          <select name="service" class="form-select trans-select" onchange="this.form.submit()">
            <option value="all" {{ $selectedService === 'all' ? 'selected' : '' }}> All Services</option>
            {{-- <option value="p12" {{ $selectedService === 'p12' ? 'selected' : '' }}>P-12 2D/3D</option>
            <option value="p17" {{ $selectedService === 'p17' ? 'selected' : '' }}>P-17 Dire</option>
            <option value="p22" {{ $selectedService === 'p22' ? 'selected' : '' }}>P-22 Uniqo Pay</option> --}}
            <option value="p23" {{ $selectedService === 'p23' ? 'selected' : '' }}>P-23 UPI</option>
          </select>
        </div>
      </form>
    </div>
  </div>
  @if (count($transactions) == 0)
  <div class="row mt-5 pt-5">
    <h5 class="text-center col-12">No Declined Transactions to Show!</h5>
  </div>

  @else

  <div class="row">
    <div class="col-md-12 summary-card2 summary-card-transactions-client d-none d-lg-block">

      <div class="scrollable table-wrapper">
        <table class="table text-center trans-table-client" style="border:none">
          <thead class="table-header-transactions text-white">
            <tr class="">
              <th scope="col" class="top-left text-nowrap">#</th>
              <th scope="col" class="text-nowrap">Receiver</th>
              <th scope="col" class="text-nowrap">Description</th>
              <th scope="col" class="text-nowrap">Amount</th>
              <th scope="col" class="text-nowrap">N.Amt+Fee</th>
              <th scope="col" class="text-nowrap">Status</th>
              <th scope="col" class="text-nowrap">Date</th>
              <th scope="col" class="n-amt top-right text-nowrap">Pay Service</th>
            </tr>
          </thead>
          <tbody class="">

            @foreach ($transactions as $trans)
            @php
            $status = $trans->payment_status ?? '-';
            $statusClasses = match ($status) {
            'Completed', 'Approved','Succeeded', 'Complete' => 'donebg-color',
            'Pending', 'Attempting', 'Processing', 'Waiting' => 'pendingBg-color',
            'Failed', 'Rejected', 'Cancelled','Declined', 'Expired' => 'failedBg-color',
            default => ''
            };
            $type = preg_replace('/_+/', ' ', (string) $trans->type) ?: '-';
            @endphp

            <tr style="cursor: pointer;">

              <td scope="row" class="text-nowrap first-column px-2" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">{{ $loop->iteration + ($transactions->currentPage()
                - 1)
                *
                $transactions->perPage() }}</td>
              <td scope="row" class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}"> {{ \App\Models\Company::where('accountId',
                $trans->account_id)->first()->company_name ?? str_replace('/',
                ' ', ucfirst($trans->account_id)) }}</td>
              <td scope="row" class="limiter" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">
                {{ ucfirst($trans->description) }}
              </td>
              <td scope="row" class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">
                {{ $trans->currency }} {{ number_format($trans->amount, 2) }}
              </td>
              <td scope="row" class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">
                @if($trans->net_amount)
                ({{$trans->from_currency}} {{ number_format($trans->net_amount, 2) }} + {{ number_format($trans->fees, 2) }})
                @else
                -
                @endif
              </td>
              <td scope="row" class="text-nowrap {{ $statusClasses }}">
                <span>{{ ucfirst($trans->payment_status) }}</span>
                @if($trans->status == 'p6')
                <a href="{{ url('/p6/update/'. $trans->payment_id .'/transaction') }}" title="Refresh" class="ps-1">
                  <i class="fa fa-refresh"></i>
                </a>
                @endif
                @if($trans->status == 'p10')
                <a href="{{ url('/p10/update/'. $trans->payment_id .'/transaction') }}" title="Refresh" class="ps-1">
                  <i class="fa fa-refresh"></i>
                </a>
                @endif
                @if ($trans->status == 'p17')
                <a href="{{ url('/p17/update/' . $trans->payment_id . '/transaction') }}" title="Refresh" class="ps-1">
                  <i class="fa fa-refresh"></i>
                </a>
                @endif
                @if ($trans->status == 'p18')
                <a href="{{ url('/p18/update/' . $trans->payment_id . '/transaction') }}"
                  title="Refresh" class="ps-1">
                  <i class="fa fa-refresh"></i>
                </a>
                @endif
              </td>
              <td scope="row" class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">
                {{ \Carbon\Carbon::parse($trans->created_at)->format('d/m/y') }}
              </td>
              <td scope="row" class="last-column text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">
                @php
                $provider = match ($trans->status) {
                'p12'=> 'PG Tech Pay',
                'p17'=> 'Direpay',
                'p22' => 'Uniqo Pay',
                'p23' => 'UPI',
                default => '',
                };
                @endphp

                {{ $provider }}


              </td>


            </tr>

            @endforeach
            @if (count($transactions) < 15) @for ($i=0; $i < 15 - count($transactions); $i++) <tr class="no-hover">
              <td colspan="8" style="{{ $i == 0 ? 'border:0;border-top:1px solid #B8B8B8' : 'border: none;' }}">&nbsp;
              </td> {{--
              assuming your table has 7 columns --}}
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
    @foreach ($transactions as $trans)
    @php
    $status = $trans->payment_status ?? '-';
    $statusClasses = match ($status) {
    'Created', 'Initialized', 'Started' => 'statusCreated',
    'Completed', 'Approved','Succeeded', 'Success', 'Complete', 'Captured', 'Paid' => 'statusSuccess',
    'Pending', 'Attempting', 'Processing', 'Waiting' => 'statuswarning',
    'Failed', 'Rejected', 'Cancelled','Declined', 'Expired' => 'statusDanger',
    default => ''
    };
    $type = preg_replace('/_+/', ' ', (string) $trans->type) ?: '-';
    @endphp
    <div class="col-12 col-sm-6 p-2">
      <div class="d-flex flex-column ind-card gap-3" data-bs-toggle="modal"
        data-bs-target="#transactionModal-{{ $trans->id }}" style="cursor: pointer;">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex flex-column justify-content-center text-start">
            <span class="card-el-head">
              Receiver
            </span>
            <span class="card-el-content">
              {{ \App\Models\Company::where('accountId', $trans->account_id)->first()->company_name ?? str_replace('/',
              ' ', ucfirst($trans->account_id)) }}
            </span>
          </div>
          <div class="currency">
            {{ $trans->currency }} {{ number_format($trans->amount, 2) }}
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex flex-column justify-content-center text-start">
            <span class="card-el-head">
              Date
            </span>
            <span class="card-el-content">
              {{ \Carbon\Carbon::parse($trans->created_at)->format('d/m/y') }}
            </span>
          </div>
          <div class="{{ $statusClasses }}">
            {{ $trans->payment_status }}
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>


  @if(method_exists($transactions, 'links'))

  <div class="d-flex justify-content-end align-items-center gap-4 lg:gap-14 mt-4 pr-3 lg:py-5">
    <div class="d-flex align-items-center">
      <span class="footer-text">Showing {{ $transactions->firstItem() ?? 0 }} to
        {{ $transactions->lastItem() ?? 0 }} of
        {{ $transactions->total() }} results
      </span>
    </div>

    <div class="">
      <ul class="pagination d-flex align-items-center gap-2">
        {{-- Prev --}}
        @if (!$transactions->onFirstPage())
        <li class="">
          <a class="d-flex align-items-center" href=" {{ $transactions->previousPageUrl() ?? '#' }}">
            <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M1 7L7 1M1 7L7 13M1 7L15 7" stroke="#827F7F" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
            </svg>

          </a>
        </li>
        @endif
        @php
        $current = $transactions->currentPage();
        $last = $transactions->lastPage();
        $window = 3; // change to 2 or 3 to show more numbers on each side
        $start = max(1, $current - $window);
        $end = min($last, $current + $window);
        @endphp
        {{-- First + leading ellipsis --}}
        @if ($start > 1)
        <li class=""><a class="" href=" {{ $transactions->url(1) }}" style="color: black">1</a>
        </li>
        @if ($start > 2)
        <li class=" "><span class=" style=" color: black">…</span></li>
        @endif
        @endif
        {{-- Page range --}}
        @for ($page = $start; $page <= $end; $page++) <li
          class=" {{ $page === $current ? 'pagination-box-active' : 'pagination-box-inactive'}}">
          @if ($page === $current)
          <span class="pagination-box">{{ $page }}</span>
          @else
          <a class="pagination-box" href="{{ $transactions->url($page) }}" style="color: black">{{ $page }}</a>
          @endif
          </li>
          @endfor
          {{-- Trailing ellipsis + last --}}
          @if ($end < $last) @if ($end < $last - 1) <li class=" "><span class="rounded-[5px]">…</span></li>
            @endif
            <li class=""><a class="" href=" {{ $transactions->url($last) }}" style="color: black">{{ $last }}</a></li>
            @endif
            {{-- Next --}}
            @if ($transactions->hasMorePages())
            <li class="">
              <a class="d-flex align-items-center" href="{{ $transactions->nextPageUrl() ?? '#' }}">
                <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15 7L9 13M15 7L9 1M15 7L1 7" stroke="#827F7F" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
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
@foreach ($transactions as $trans)
<div class="modal fade" id="transactionModal-{{ $trans->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Transaction Details for {{ $trans->payment_id }}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3">
        <table class="table table-striped">
          <tbody>
            <tr>
              <th scope="row">Company </th>
              <td>{{ \App\Models\Company::where('accountId',$trans->account_id)->first()->company_name ??
                str_replace('/',' ', ucfirst($trans->account_id) ) }}</td>
            </tr>
            <tr>
              <th scope="row">Description</th>
              <td>{{ ucfirst($trans->description) }}</td>
            </tr>
            <tr>
              <th scope="row">Status</th>
              <td class="text-danger"><strong>{{ strtoupper($trans->payment_status) }}</strong></td>
            </tr>
            <tr>
              <th scope="row">Via Service</th>
              <td>{{ ucfirst($trans->status) }}</td>
            </tr>
            <tr>
              <th scope="row">Amount</th>
              <td>{{ $trans->currency }} {{ number_format($trans->amount, 2) }}</td>
            </tr>
            <tr>
              <th scope="row">Net Amount & Fees</th>
              <td>{{ $trans->net_amount ? $trans->from_currency : '-' }}
                {{ $trans->net_amount ?: '' }}
                {{ $trans->fees ? '(+' . $trans->fees . ')' : '' }}
              </td>
            </tr>
            <tr>
              <th scope="row">Checkout ID:</th>
              <td>{{ $trans->checkout_id }}</td>
            </tr>
            <tr>
              <th scope="row">Transaction ID</th>
              <td>{{ $trans->payment_id }}</td>
            </tr>
            @if($trans->customer_details)
            <tr>
              <th scope="row">Customer Ref:</th>
              <td>{!! nl2br(e(str_replace(' , ', "\n", $trans->customer_details ?? '-'))) !!}</td>
            </tr>
            @endif

            @if($trans->payer_details)
            @php
            $payerDetails = $trans->payer_details;

            $hasPayerDetails = is_array($payerDetails) && collect($payerDetails)->filter(function ($value) {
            return !is_null($value) && $value !== '';
            })->isNotEmpty();
            @endphp

            <tr>
              <th scope="row">Payer Details</th>
              <td>
                @if(!$hasPayerDetails)
                -
                @else
                @foreach($payerDetails as $key => $value)
                @if(!is_null($value) && $value !== '')
                <div>
                  {{ ucfirst(str_replace('_', ' ', $key)) }} :
                  {{ $value }}
                </div>
                @endif
                @endforeach
                @endif
              </td>
            </tr>
            @endif

            @if($trans->status == "p6")
            <tr>
              <th scope="row">Blockchain Trxn Hash</th>
              <td>{{ $trans->transvoucher_blockchainHashTrxn ?? '-' }}</td>
            </tr>
            @endif
            @if($trans->transvoucher_card_brand != null)
            <tr>
              <th scope="row">Card Brand</th>
              <td>{{ $trans->transvoucher_card_brand ?? '-' }}</td>
            </tr>
            @endif
            <tr>
              <th scope="row">Initiated At</th>
              <td>{{ \Carbon\Carbon::parse($trans->created_at)->format('H:m , d/M/Y') }}</td>
            </tr>
            <tr>
              <th scope="row">Updated At</th>
              <td>{{ \Carbon\Carbon::parse($trans->updated_at)->format('H:m , d/M/Y') }}</td>
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


@endsection

@section('scripts')

@endsection