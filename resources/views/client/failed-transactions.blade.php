@extends('layouts.clientMaster')

@section('title')
Failed Transactions
@php
$currentPage = 'Failed-Transactions';
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
<div class="row mx-0 mx-lg-4 mt-2">
  <div class="col-12 mb-4 ps-lg-2 pe-lg-0">
    <div class="d-flex flex-column flex-xxl-row align-items-center justify-content-end">
      <div class="d-flex flex-column flex-xxl-row align-items-center gap-2 gap-xxl-3">
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
          <form action="{{ route('showFailed-Transactions') }}" method="GET"
            class="d-flex flex-column flex-xxl-row align-items-center gap-2 gap-xxl-3">
            <div class="input-group d-flex gap-2 flex-nowrap justify-content-center">
              <input type="text" name="q" class="form-control trans-search ps-md-5 rounded-4 w-100"
                placeholder="Search (txn Id, checkout Id)" value="{{ request('q') }}">
              <div class="d-flex gap-2 justify-content-center">
                <button type="button" onclick="window.location='{{ route('showFailed-Transactions') }}'"
                  class="btn btn-outline-primary rounded-4 {{ request('q') == '' ? 'd-none' : 'd-block' }}">
                  Cancel
                </button>
                <button id="searchbtn" class="btn btn-outline-primary trans-search-btn rounded-4" type="submit"
                  disabled>
                  Search
                </button>
              </div>
            </div>
            <div>
              @php $selectedName = request('name', 'total'); @endphp
              <select name="name" id="name" class="form-select trans-select" onchange="this.form.submit()" required>
                <option value="total" {{ $selectedName === 'total' ? 'selected' : '' }}>Total</option>
                <option value="thisMonth" {{ $selectedName === 'thisMonth' ? 'selected' : '' }}>This month</option>
                <option value="lastMonth" {{ $selectedName === 'lastMonth' ? 'selected' : '' }}>Last month</option>
                <option value="lastFewMonths" {{ $selectedName === 'lastFewMonths' ? 'selected' : '' }}>Last 3 month
                </option>
              </select>
            </div>
            <div class="">
              @php
              $company = Auth::user()->company()->first();
              $selectedService = request('service', 'all');
              $services = [
              'p12' => ['label' => '2D/3D', 'model' => \App\Models\PTwelvePaymentMethod::class],
              'p17' => ['label' => 'P-17 Dire', 'model' => \App\Models\Direpay::class],
              'p22' => ['label' => 'P-22 Uniqo', 'model' => \App\Models\UniqoPay::class],
              'p23' => ['label' => 'P-23 UPI', 'model' => \App\Models\UPIPayment::class],
              ];
              @endphp
              <select name="service" class="form-select trans-select" onchange="this.form.submit()">
                <option value="all" {{ $selectedService === 'all' ? 'selected' : '' }}>All Services</option>
                @foreach($services as $key => $service)
                @if($company && $service['model']::where('company_id', $company->id)->where('status', 1)->exists())
                <option value="{{ $key }}" {{ $selectedService === $key ? 'selected' : '' }}>
                  {{ $service['label'] }}
                </option>
                @endif
                @endforeach
              </select>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 summary-card2 summary-card-transactions-client d-none d-lg-block">
    <!-- <div class="d-flex justify-content-between align-items-center pb-2">
                                        <h4 class="text-uppercase">Failed-Transactions</h4>
                                       <form action="{{ route('showFailed-Transactions') }}" method="GET" id="myForm">

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
          <tr class="">
            <td scope="row" colspan="7" class="text-center">No Transactions !</td>
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

            @if (in_array($trans->payment_status, ['Succeeded', 'Completed', 'Complete', 'Approved', 'Captured', 'Undercharged', 'Allocated', 'Paid']))
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

            </td>
            @endif

            <td class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">{{ \Carbon\Carbon::parse($trans->created_at)->format('d M Y') }}</td>
            <td class="text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">{{ $trans->currency }} {{ number_format($trans->amount, 2) }} </td>
            <td class="last-column text-nowrap" data-bs-toggle="modal" data-bs-target="#transactionModal-{{ $trans->id }}">
              <small>
                @if($trans->net_amount)
                ({{$trans->from_currency}} {{ number_format($trans->net_amount, 2) }} + {{ number_format($trans->fees, 2) }})
                @else
                -
                @endif
              </small>
            </td>

          </tr>


          @endforeach
          @if (count($transactions) < 15)
            @for ($i=0; $i < 15 - count($transactions); $i++)
            <tr class="no-hover">
            <td colspan="7" style="{{ $i == 0 ? 'border:0;border-top:1px solid #B8B8B8' : 'border: none;' }}">&nbsp;
            </td> {{-- assuming your table has 7 columns --}}
            </tr>
            @endfor
            @endif
            @endif

        </tbody>

      </table>
    </div>

  </div>
</div>
<div class="row mx-0">

  <!-- for smaller screens  -->
  <div class="col-12 d-lg-none">
    @if (count($transactions) == 0)
    <span class="text-center">No Transactions !</span>
    @else
    <div class="row mx-0">
      @foreach ($transactions as $trans)
      <div class="col-12 {{  $transactions->count() == 1 ? 'd-flex justify-content-center' : 'col-md-6' }} py-2">
        <div class="individual-card d-flex flex-column gap-4" data-bs-toggle="modal"
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
              {{ $trans->currency }} {{ number_format($trans->amount, 2) }}
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
              @if ($trans->payment_status == 'Succeeded' || $trans->payment_status == 'Completed' || $trans->payment_status == 'Complete' || $trans->payment_status == 'Approved' || $trans->payment_status == 'Captured')
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