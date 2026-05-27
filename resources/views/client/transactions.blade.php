@extends('layouts.clientMaster')

@section('title')
Transactions
@php
$currentPage = 'Transactions';
@endphp
@endsection

@section('css')
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
<div class="fd-trans-page">
<div class="row mx-0 mx-lg-4 mt-0">
  @include('client.partials.transactions-panel', [
      'isFailedPage' => false,
      'showDownload' => true,
      'formAction' => route('showTransactions'),
      'clearUrl' => route('showTransactions'),
  ])

  <div class="col-md-12 summary-card2 summary-card-transactions-client fd-trans-table-card d-none d-lg-block">
    <!-- <div class="d-flex justify-content-between align-items-center pb-2">
                                                                                                <h4 class="text-uppercase">Transactions</h4>
                                                                                               <form action="{{ route('showTransactions') }}" method="GET" id="myForm">

                                                                                                <select name="name" id="name" class="form-select" onchange="this.form.submit()" required>
                                                                                                    <option value="total" @if($select == 'total') selected @endif>Total</option>
                                                                                                    <option value="thisMonth" @if($select == 'thisMonth') selected @endif>This month</option>
                                                                                                    <option value="lastMonth" @if($select == 'lastMonth') selected @endif>Last month</option>
                                                                                                    <option value="lastFewMonths" @if($select == 'lastFewMonths') selected @endif>Last 3 month</option>
                                                                                                </select>
                                                                                               </form>
                                                                                            </div> -->
    <div class="scrollable table-wrapper">
      <table class="table text-center trans-table-client" style="border:none">
        <thead class="table-header-transactions text-white">
          <tr class="">
            <th scope="col" class="top-left text-nowrap">Checkout ID</th>
            <th scope="col" class="text-nowrap">Payment ID</th>
            <th scope="col" class="text-nowrap">Service Mode</th>
            <th scope="col" class="text-nowrap">Status</th>
            <th scope="col" class="text-nowrap">Date</th>
            <th scope="col" class="text-nowrap">Amount</th>
            <th scope="col" class="n-amt top-right text-nowrap">N. Amt + Fee</th>
          </tr>
        </thead>
        <tbody class="">
          @if (count($transactions) == 0)
          <tr class="trans-table-empty">
            <td scope="row" colspan="7" class="text-nowrap text-center border-0">No transactions found</td>
          </tr>
          @else
          @foreach ($transactions as $trans)

          <tr style="cursor: pointer;">
            <td scope="row" class="text-nowrap first-column" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">{{ $trans->checkout_id }}</td>
            <td scope="row" class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">{{ strtoupper($trans->payment_id) }}</td>
            <td scope="row" class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">
              @if ($trans->status == 'p1')
              P1-Card
              @elseif ($trans->status == 'p2')
              P2-Crypto
              @elseif ($trans->status == 'p3')
              P3-Card
              @elseif ($trans->status == 'p4')
              P4-Card
              @elseif ($trans->status == 'p5')
              P5-Card
              @elseif ($trans->status == 'p6')
              P6-(TrV)
              @elseif ($trans->status == 'p7')
              P7-(SPZ)
              @elseif ($trans->status == 'p8')
              P8-(LQP)
              @elseif ($trans->status == 'p9')
              P9-(TrP)
              @elseif ($trans->status == 'p10')
              P10-(INB)
              @elseif ($trans->status == 'p11')
              P11-(PYT)
              @elseif ($trans->status == 'p12')
              P12-(PGT)
              @elseif ($trans->status == 'p13')
              P13-(Alz)
              @elseif ($trans->status == 'p14')
              P14-(Nbi)
              @elseif ($trans->status == 'p15')
              P15-(Sml)
              @elseif ($trans->status == 'p16')
              P16-(Trst)
              @elseif ($trans->status == 'p17')
              P17-(Dire)
              @elseif ($trans->status == 'p18')
              P18-(KeyNex)
              @elseif ($trans->status == 'p19')
              P19-(Valens)
              @elseif ($trans->status == 'p20')
              P20-(Ysp)
              @elseif ($trans->status == 'p21')
              P21-(Alikassa)
              @elseif ($trans->status == 'p22')
              P22-(Uniqo)
              @elseif ($trans->status == 'p23')
              P23-(UPI)
              @endif
            </td>


            @if (in_array($trans->payment_status, ['Succeeded', 'Completed', 'Complete', 'Approved', 'Captured', 'Undercharged', 'Overcharged', 'Allocated', 'Paid']))
            <td class="donebg-color " title="{{ $trans->description }}">
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
              @if($trans->status == 'p17')
              <a href="{{ url('/p17/update/'. $trans->payment_id .'/transaction') }}" title="Refresh" class="ps-1">
                <i class="fa fa-refresh"></i>
              </a>
              @endif
              @if($trans->status == 'p18')
              <a href="{{ url('/p18/update/'. $trans->payment_id .'/transaction') }}" title="Refresh" class="ps-1">
                <i class="fa fa-refresh"></i>
              </a>
              @endif
            </td>
            @elseif ($trans->payment_status == 'Declined' || $trans->payment_status == 'Cancelled' || $trans->payment_status == 'Failed')
            <td class="failedBg-color " title="{{ $trans->description }}">
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
              @if($trans->status == 'p17')
              <a href="{{ url('/p17/update/'. $trans->payment_id .'/transaction') }}" title="Refresh" class="ps-1">
                <i class="fa fa-refresh"></i>
              </a>
              @endif
              @if($trans->status == 'p18')
              <a href="{{ url('/p18/update/'. $trans->payment_id .'/transaction') }}" title="Refresh" class="ps-1">
                <i class="fa fa-refresh"></i>
              </a>
              @endif
            </td>
            @else
            <td class="pendingBg-color " title="{{ $trans->description }}">
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
              @if($trans->status == 'p17')
              <a href="{{ url('/p17/update/'. $trans->payment_id .'/transaction') }}" title="Refresh" class="ps-1">
                <i class="fa fa-refresh"></i>
              </a>
              @endif
              @if($trans->status == 'p18')
              <a href="{{ url('/p18/update/'. $trans->payment_id .'/transaction') }}" title="Refresh" class="ps-1">
                <i class="fa fa-refresh"></i>
              </a>
              @endif

              @if($trans->status == 'p23')
              <a href="{{ url('/p23/update/'. $trans->checkout_id .'/transaction') }}" title="Refresh" class="ps-1">
                <i class="fa fa-refresh"></i>
              </a>
              @endif
            </td>
            @endif

            <td class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">{{ \Carbon\Carbon::parse($trans->created_at)->format('d M Y') }}</td>
            <td class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">
              @include('client.partials.currency-amount', ['currency' => $trans->currency, 'amount' => $trans->settled_amount ?? $trans->amount])
            </td>
            <td class="last-column text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">
              <small>
                @if($trans->net_amount)
                (<span class="fd-currency-amount">@include('client.partials.currency-icon', ['code' => $trans->from_currency])<span class="fd-currency-amount__value">{{ number_format($trans->net_amount, 2) }}</span></span> + {{ number_format($trans->fees, 2) }})
                @else
                -
                @endif
              </small>
            </td>

          </tr>

          @endforeach
        
            @endif

        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="row  mx-0">
  <!-- for smaller screens  -->
  <div class="d-lg-none card-section text-center">
    @if (count($transactions) == 0)
    <span class="text-center ">No Transactions !</span>
    @else
    <div class="row mx-0 text-start">
      @foreach ($transactions as $trans)
      <div class="col-12 {{  $transactions->count() == 1 ? 'd-flex justify-content-center' : 'col-md-6' }} py-2 px-0">
        <div class=" individual-card d-flex flex-column gap-4" data-bs-toggle="modal"
          data-bs-target="#transactionModal-{{ $trans->id }}">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex flex-column cid-mob-container">
              <div class="cid-mob">
                Checkout ID
              </div>
              <div class="cid-mob-value">
                {{ $trans->checkout_id }}
              </div>
            </div>
            <div class="currency-mob">
              @include('client.partials.currency-amount', ['currency' => $trans->currency, 'amount' => $trans->settled_amount ?? $trans->amount])
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex flex-column ">
              <div class="date-mob">
                Date
              </div>
              <div class="date-mob-value">
                {{ \Carbon\Carbon::parse($trans->created_at)->format('d M Y') }}
              </div>
            </div>
            <div>
              @if (in_array($trans->payment_status, ['Succeeded', 'Completed', 'Complete', 'Approved', 'Captured', 'Undercharged', 'Overcharged', 'Allocated', 'Paid']))
              <div class="donebg-color" title="{{ $trans->description }}">
                <span class="p-2 px-3 status-text">{{ ucfirst($trans->payment_status) }}</span>
              </div>
              @elseif ($trans->payment_status == 'Declined' || $trans->payment_status == 'Cancelled' || $trans->payment_status == 'Failed')
              <div class="failedBg-color" title="{{ $trans->description }}">
                <span class="p-2 px-3 status-text">{{ ucfirst($trans->payment_status) }}</span>
              </div>
              @else
              <div class="pendingBg-color" title="{{ $trans->description }}">
                <span class="p-2 px-3 status-text">{{ ucfirst($trans->payment_status) }}</span>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</div>

@if(method_exists($transactions, 'links'))
<div>
  <div class="table-foot-cell">
    <div class="pager-bar mt-2">
      <div class="pager-info">
        <h6>Showing {{ $transactions->firstItem() ?? 0 }} to
          {{ $transactions->lastItem() ?? 0 }} of
          {{ $transactions->total() }} results
        </h6>
      </div>

      <div class="pager-controls">
        <ul class="pagination">
          {{-- Prev --}}
          @if (!$transactions->onFirstPage())
          <li class="page-item page-arrow">
            <a class="page-link" href="{{ $transactions->previousPageUrl() ?? '#' }}">
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
          $window = 1; // show more by increasing to 2 or 3
          $start = max(1, $current - $window);
          $end = min($last, $current + $window);
          @endphp

          {{-- First + leading ellipsis --}}
          @if ($start > 1)
          <li class="page-item"><a class="page-link" href="{{ $transactions->url(1) }}">1</a></li>
          @if ($start > 2)
          <li class="page-item"><span class="page-ellipsis">…</span></li>
          @endif
          @endif

          {{-- Page range --}}
          @for ($page = $start; $page <= $end; $page++)
            <li class="page-item">
            @if ($page === $current)
            <span class="page-num--current">{{ $page }}</span>
            @else
            <a class="page-link page-num" href="{{ $transactions->url($page) }}">{{ $page }}</a>
            @endif
            </li>
            @endfor

            {{-- Trailing ellipsis + last --}}
            @if ($end < $last)
              @if ($end < $last - 1)
              <li class="page-item"><span class="page-ellipsis">…</span></li>
              @endif
              <li class="page-item"><a class="page-link" href="{{ $transactions->url($last) }}">{{ $last }}</a></li>
              @endif

              {{-- Next --}}
              @if ($transactions->hasMorePages())
              <li class="page-item page-arrow">
                <a class="page-link" href="{{ $transactions->nextPageUrl() ?? '#' }}">
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
  </div>
</div>
@endif

</div>




{{-- Modal for transaction details --}}
@foreach ($transactions as $trans)
<div class="modal fade" id="transactionModal-{{ $trans->id }}" tabindex="-1"
  aria-labelledby="transactionLabel-{{ $trans->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h1 class="modal-title fs-5" id="transactionLabel-{{ $trans->id }}">
          Transaction Details for {{ $trans->payment_id }}
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-3">
        <table class="table table-striped">
          <tbody>
            <tr>
              <th scope="row">Description</th>
              <td>{{ ucfirst($trans->description) }}</td>
            </tr>

            <tr>
              <th scope="row">Status</th>
              <td>
                <span
                  class="@if(strtoupper($trans->payment_status) == 'PENDING') text-warning @elseif(in_array($trans->payment_status, ['FAILED', 'DECLINED', 'CANCELLED'])) text-danger @else text-success @endif">
                  <strong>{{ strtoupper($trans->payment_status) }}</strong>
                </span>
              </td>
            </tr>

            <tr>
              <th scope="row">Via Service</th>
              <td>{{ ucfirst($trans->status) }}</td>
            </tr>

            @if($trans->status != 'p6')
            <tr>
              <th scope="row">Amount</th>
              <td>@include('client.partials.currency-amount', ['currency' => $trans->currency, 'amount' => $trans->amount])</td>
            </tr>
            @endif

            @if($trans->settled_amount)
            <tr>
              <th scope="row">Amount Settled</th>
              <td>@include('client.partials.currency-amount', ['currency' => $trans->currency, 'amount' => $trans->settled_amount])</td>
            </tr>
            @endif

            <tr>
              <th scope="row">Net Amount & Fees</th>
              <td>
                @if ($trans->net_amount)
                  <span class="fd-currency-amount">@include('client.partials.currency-icon', ['code' => $trans->from_currency])<span class="fd-currency-amount__value">{{ number_format($trans->net_amount, 2) }}</span></span>
                  {{ $trans->fees ? '(+' . number_format($trans->fees, 2) . ')' : '' }}
                @else
                  -
                @endif
              </td>
            </tr>

            <tr>
              <th scope="row">Checkout ID</th>
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
              <td>{{ \Carbon\Carbon::parse($trans->created_at)->format('H:i , d/M/Y') }}</td>
            </tr>

            <tr>
              <th scope="row">Updated At</th>
              <td>{{ \Carbon\Carbon::parse($trans->updated_at)->format('H:i , d/M/Y') }}</td>
            </tr>
          </tbody>
        </table>
      </div>

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