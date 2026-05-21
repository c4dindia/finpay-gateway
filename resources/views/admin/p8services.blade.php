@extends('layouts.adminMaster')

@section('title')
P8 Services
@php
$currentPage = 'P8 Services';
@endphp
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/adminside/p1services.css') }}">
@endsection

@section('page-content')

<div class="p-2 p-lg-4 gap-4">
    <h3 class="text-center" style="Font-family:Poppins; font-weight: 500; font-size: 22px; line-height: 48px;">Companies for LuqaPay Services </h3>
    <div class="d-flex flex-column flex-lg-row gap-lg-5 align-items-center justify-content-center">
        <h6 class="text-center mb-md-4" style="Font-family:Poppins; font-weight: 500; line-height: 20px;">LOGIN : <a href="https://merchant.luqapay.com/public/login" target="_blank" class="text-break">https://merchant.luqapay.com/public/login</a></h6>
        <h6 class="text-center mb-md-4" style="Font-family:Poppins; font-weight: 500; line-height: 20px;">( U: bobby@ryzen-pay.com , Pass: Passw0rd!234 )</h6>
    </div>
    @if (count($p8co) == 0)
    <div class="row mt-5 pt-5">
        <h5 class="text-center col-12">No Companies to Show!</h5>
    </div>
    @else
    <div class="table-container d-none d-lg-block">
        <table class="table custom-table align-middle all-trans-table">
            <thead>
                <tr>
                    <th class="table-header text-center p-3">#</th>
                    <th class="table-header text-center p-3">Company Name</th>
                    <th class="table-header text-center p-3">Account ID</th>
                    <th class="table-header text-center p-3">Bearer Token</th>
                    <th class="table-header text-center p-3">MID</th>
                    <th class="table-header text-center p-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($p8co as $company)
                <tr class="table-row">
                    <td class="table-data text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="table-data text-center">
                        {{ $company->company->company_name }}
                    </td>
                    <td class="table-data text-center">
                        {{ $company->company->accountId }}
                    </td>
                    <td class="table-data text-center">
                        {{ $company->b_token }}
                    </td>
                    <td class="table-data text-center">
                        {{ $company->luqapay_mid }}
                    </td>
                    <td class="table-data text-center @if($company->status == '1')text-success @else text-danger @endif">@if($company->status == '1') Active @else Deactivated @endif</td>
                </tr>
                @endforeach
                @for ($i = count($p8co); $i < 15; $i++)
                    <tr class="pad">
                        <td colspan="5" style="border:none !important;">&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <!-- for mobile and tab -->
    <!-- card section  -->
    <div class="row d-lg-none d-flex justify-content-center">
        <!-- individual card -->
        @foreach ($p8co as $company)

        <div class="col-12 col-sm-6 p-2">
            <div class="col-12 ind-card">
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Company Name
                    </div>
                    <span class="col-6">
                        {{ $company->company->company_name }}
                    </span>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Account ID
                    </div>
                    <div class="col-6 break">
                        {{ $company->company->accountId }}
                    </div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Bearer Token
                    </div>
                    <div class="col-6 break">
                        {{ $company->b_token }}
                    </div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        MID
                    </div>
                    <div class="col-6 break">
                        {{ $company->luqapay_mid }}
                    </div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Status
                    </div>
                    <div class="col-2 @if($company->status == '1') statusSuccess @else statusdanger @endif"">
                                    @if($company->status == '1') Active @else Deactivated @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            @if(method_exists($p8co, 'links'))

                <div class=" d-flex justify-content-end align-items-center gap-4 lg:gap-14 mt-4 pr-3 lg:py-5">
                        <div class="d-flex align-items-center">
                            <span class="footer-text">Showing {{ $p8co->firstItem() ?? 0 }} to
                                {{ $p8co->lastItem() ?? 0 }} of
                                {{ $p8co->total() }} results
                            </span>
                        </div>

                        <div class="">
                            <ul class="pagination d-flex align-items-center gap-2">
                                {{-- Prev --}}
                                @if (!$p8co->onFirstPage())
                                <li class="">
                                    <a class="d-flex align-items-center" href=" {{ $p8co->previousPageUrl() ?? '#' }}">
                                        <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 7L7 1M1 7L7 13M1 7L15 7" stroke="#827F7F" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </a>
                                </li>
                                @endif
                                @php
                                $current = $p8co->currentPage();
                                $last = $p8co->lastPage();
                                $window = 3; // change to 2 or 3 to show more numbers on each side
                                $start = max(1, $current - $window);
                                $end = min($last, $current + $window);
                                @endphp
                                {{-- First + leading ellipsis --}}
                                @if ($start > 1)
                                <li class=""><a class=" href=" {{ $p8co->url(1) }}" style="color: black">1</a>
                                </li>
                                @if ($start > 2)
                                <li class=" "><span class=" style=" color: black">…</span></li>
                                @endif
                                @endif
                                {{-- Page range --}}
                                @for ($page = $start; $page <= $end; $page++)
                                    <li class=" {{ $page === $current ? 'pagination-box-active' : 'pagination-box-inactive'}}">
                                    @if ($page === $current)
                                    <span class="pagination-box">{{ $page }}</span>
                                    @else
                                    <a class="pagination-box" href="{{ $p8co->url($page) }}"
                                        style="color: black">{{ $page }}</a>
                                    @endif
                                    </li>
                                    @endfor
                                    {{-- Trailing ellipsis + last --}}
                                    @if ($end < $last) @if ($end < $last - 1)
                                        <li class=" "><span class="rounded-[5px]">…</span></li>
                                        @endif
                                        <li class=""><a class="" href=" {{ $p8co->url($last) }}" style="color: black">{{ $last }}</a>
                                        </li>
                                        @endif
                                        {{-- Next --}}
                                        @if ($p8co->hasMorePages())
                                        <li class="">
                                            <a class="d-flex align-items-center" href="{{ $p8co->nextPageUrl() ?? '#' }}">
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


                @endsection

                @section('scripts')

                @endsection
