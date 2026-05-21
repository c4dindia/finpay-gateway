@extends('layouts.adminMaster')

@section('title')
P23 Services
@php
$currentPage = 'P23 Services';
@endphp
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/adminside/p1services.css') }}">
@endsection

@section('page-content')

<div class="p-2 p-lg-4 gap-4">
    <h3 class="text-center" style="Font-family:Poppins; font-weight: 500; font-size: 22px; line-height: 32px;">Companies for UPI Pay Services </h3>
    <div class="d-flex flex-column flex-lg-row gap-lg-5 align-items-center justify-content-center">
        <h6 class="text-center mb-md-4" style="Font-family:Poppins; font-weight: 500; line-height: 20px;"><a href="{{ route('showUpipayMerchants') }}" class="text-break">View UPI All Merchants</a></h6>
    </div>

    @if (count($p23co) == 0)
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
                    <th class="table-header text-center p-3">MID</th>
                    <th class="table-header text-center p-3">VPA</th>
                    <th class="table-header text-center p-3">MIDv2</th>
                    <th class="table-header text-center p-3">Status</th>
                    <th class="table-header text-center p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($p23co as $company)
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
                        {{ $company->mid ?? '-' }}
                    </td>
                    <td class="table-data text-center">
                        {{ $company->vpa ?? '-' }}
                    </td>
                    <td class="table-data text-center">
                        {{ $company->midv2 ?? '-' }}
                    </td>
                    <td class="table-data text-center @if($company->status == '1')text-success @else text-danger @endif">@if($company->status == '1') Active @else Deactivated @endif</td>
                    <td class="table-data text-center">
                        <a href="#" class="btn btn-sm edit-btn" title="Edit" data-bs-toggle="modal" data-bs-target="#edit-modal-{{ $company->id }}"><i class="fa fa-edit"></i></a>
                    </td>

                        <div class="modal fade" id="edit-modal-{{ $company->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Edit Company Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form action="{{ route('edit-p23service') }}" method="POST">
                                            @csrf

                                            <input type="hidden" name="company_id" value="{{ $company->id }}">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="companyName" class="form-label">Company Name</label>
                                                    <input type="text" class="form-control" id="companyName" value="{{ $company->company->company_name }}" disabled>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="accountId" class="form-label">Account ID</label>
                                                    <input type="text" class="form-control" id="accountId" value="{{ $company->company->accountId }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="mid-{{ $company->id }}" class="form-label">MID</label>
                                                    <select name="mid" class="form-control mid-select" id="mid-{{ $company->id }}" required>
                                                        <option value="">Select MID</option>
                                                        @foreach($merchants as $merchant)
                                                            <option value="{{ $merchant['mid'] }}"
                                                                {{ $company->mid == $merchant['mid'] ? 'selected' : '' }}>
                                                                {{ $merchant['mid'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="vpa-{{ $company->id }}" class="form-label">VPA</label>
                                                    <select name="vpa" class="form-control vpa-select" id="vpa-{{ $company->id }}" required>
                                                        <option value="">Select VPA</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Added mid v2 & vpa v2 -->
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="midv2" class="form-label">MID .v2</label>
                                                    <input type="text" class="form-control" id="midv2" name="midv2" value="{{ $company->midv2 }}">
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-select" name="status" id="status" required>
                                                        <option value="1" @if($company->status == '1') selected @endif>Active</option>
                                                        <option value="0" @if($company->status == '0') selected @endif>Deactivate</option>
                                                    </select>
                                                </div>
                                            </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                initVpaSelector(
                                    {{ $company->id }},
                                    @json($company->mid),
                                    @json($company->vpa)
                                );
                            });
                        </script>

                </tr>
                @endforeach
                @for ($i = count($p23co); $i < 15; $i++)
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
        @foreach ($p23co as $company)

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
            @if(method_exists($p23co, 'links'))

                <div class=" d-flex justify-content-end align-items-center gap-4 lg:gap-14 mt-4 pr-3 lg:py-5">
                        <div class="d-flex align-items-center">
                            <span class="footer-text">Showing {{ $p23co->firstItem() ?? 0 }} to
                                {{ $p23co->lastItem() ?? 0 }} of
                                {{ $p23co->total() }} results
                            </span>
                        </div>

                        <div class="">
                            <ul class="pagination d-flex align-items-center gap-2">
                                {{-- Prev --}}
                                @if (!$p23co->onFirstPage())
                                <li class="">
                                    <a class="d-flex align-items-center" href=" {{ $p23co->previousPageUrl() ?? '#' }}">
                                        <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 7L7 1M1 7L7 13M1 7L15 7" stroke="#827F7F" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </a>
                                </li>
                                @endif
                                @php
                                $current = $p23co->currentPage();
                                $last = $p23co->lastPage();
                                $window = 3; // change to 2 or 3 to show more numbers on each side
                                $start = max(1, $current - $window);
                                $end = min($last, $current + $window);
                                @endphp
                                {{-- First + leading ellipsis --}}
                                @if ($start > 1)
                                <li class=""><a class=" href=" {{ $p23co->url(1) }}" style="color: black">1</a>
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
                                    <a class="pagination-box" href="{{ $p23co->url($page) }}"
                                        style="color: black">{{ $page }}</a>
                                    @endif
                                    </li>
                                    @endfor
                                    {{-- Trailing ellipsis + last --}}
                                    @if ($end < $last) @if ($end < $last - 1)
                                        <li class=" "><span class="rounded-[5px]">…</span></li>
                                        @endif
                                        <li class=""><a class="" href=" {{ $p23co->url($last) }}" style="color: black">{{ $last }}</a>
                                        </li>
                                        @endif
                                        {{-- Next --}}
                                        @if ($p23co->hasMorePages())
                                        <li class="">
                                            <a class="d-flex align-items-center" href="{{ $p23co->nextPageUrl() ?? '#' }}">
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
                <script>
                    const merchants = @json($merchants);

                    function initVpaSelector(companyId, selectedMid, selectedVpa) {
                        const midSelect = document.getElementById('mid-' + companyId);
                        const vpaSelect = document.getElementById('vpa-' + companyId);

                        if (!midSelect || !vpaSelect) return;

                        function loadVpa(mid, currentVpa = null) {
                            vpaSelect.innerHTML = '<option value="">Select VPA</option>';

                            const merchant = merchants.find(item => item.mid === mid);

                            if (!merchant) return;

                            merchant.vpa.forEach(vpa => {
                                const option = document.createElement('option');
                                option.value = vpa;
                                option.textContent = vpa;

                                if (currentVpa === vpa) {
                                    option.selected = true;
                                }

                                vpaSelect.appendChild(option);
                            });
                        }

                        midSelect.addEventListener('change', function () {
                            loadVpa(this.value);
                        });

                        loadVpa(selectedMid, selectedVpa);
                    }

                </script>
                @endsection