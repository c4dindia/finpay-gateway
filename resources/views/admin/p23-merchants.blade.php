@extends('layouts.adminMaster')

@section('title')
Manage VPAs
@php
$currentPage = 'Manage VPAs';
@endphp
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/adminside/p1services.css') }}">
@endsection

@section('page-content')

<div class="p-2 p-lg-4 gap-4">
    <h3 class="text-center" style="Font-family:Poppins; font-weight: 500; font-size: 22px; line-height: 32px;">Merchants for UPI Pay Services </h3>
    <div class="d-flex flex-column flex-lg-row gap-lg-5 align-items-center justify-content-center">
        <h6 class="text-center mb-md-4" style="Font-family:Poppins; font-weight: 500; line-height: 20px;">
            <a href="#" class="text-break me-4" data-bs-toggle="modal" title="Add Merchant" data-bs-target="#add-modal"><i class="fa fa-plus"></i> Add Merchants</a>
            <a href="#" class="text-break me-4" data-bs-toggle="modal" title="Import Merchant" data-bs-target="#import-modal"><i class="fa fa-plus"></i> Import Merchants</a>

            <a href="#"
                id="delete-selected-link"
                class="text-danger text-break d-none"
                onclick="event.preventDefault(); 
            if(confirm('Are you sure you want to delete selected deactivated merchants?')) {
                document.getElementById('bulk-delete-form').submit();
            }">
                <i class="fa fa-trash"></i> Delete
            </a>
        </h6>

        <div class="modal fade" id="add-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Add Merchant Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('add-p23merchant') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mid" class="form-label">MID</label>
                                    <input type="text" name="mid" class="form-control" id="mid" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="vpa" class="form-label">VPA</label>
                                    <input type="text" name="vpa" class="form-control" id="vpa" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="limitPerDay" class="form-label">Daily Limit</label>
                                    <input type="text" name="limitPerDay" class="form-control" id="limitPerDay" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="limitPerMonth" class="form-label">Monthly Limit</label>
                                    <input type="text" name="limitPerMonth" class="form-control" id="limitPerMonth">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="limitPerYear" class="form-label">Yearly Limit</label>
                                    <input type="text" name="limitPerYear" class="form-control" id="limitPerYear">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" name="status" id="status" required>
                                        <option value="1">Active</option>
                                        <option value="0">Deactivate</option>
                                    </select>
                                </div>
                            </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="import-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Import Merchant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('import-p23merchants') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="file" class="form-label">Choose File</label>
                                    <input type="file" name="file" class="form-control" id="file" accept=".xls, .xlsx" required>
                                </div>
                            </div>

                            <p class="text-muted mb-0" style="font-size: 14px;">
                                Please upload a file in
                                <strong>.xls</strong> or
                                <strong>.xlsx</strong> format.

                                <a href="{{ asset('/docs/merchant_sample_file.xlsx') }}" class="fw-semibold text-primary text-decoration-none ms-1">
                                    Download Sample File
                                </a>
                            </p>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Import </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (count($merchants) == 0)
    <div class="row mt-5 pt-5">
        <h5 class="text-center col-12">No Companies to Show!</h5>
    </div>
    @else
    <div class="table-container d-none d-lg-block">
        <form action="{{ route('deleteAll-p23merchants') }}" method="POST" id="bulk-delete-form">
            @csrf
            <table class="table custom-table align-middle all-trans-table">
                <thead>
                    <tr>
                        <th class="table-header text-center p-3">
                            <input type="checkbox" id="select-all-deactivated">
                        </th>
                        <th class="table-header text-center p-3">MID</th>
                        <th class="table-header text-center p-3">VPA</th>
                        <th class="table-header text-center p-3">Daily Limit</th>
                        <th class="table-header text-center p-3">Total Spends</th>
                        <th class="table-header text-center p-3">Start Date</th>
                        <th class="table-header text-center p-3">End Date</th>
                        <th class="table-header text-center p-3">Success Rate</th>
                        <th class="table-header text-center p-3">Status</th>
                        <th class="table-header text-center p-3">Action</th>
                    </tr>
                </thead>
                <tbody style="background: white !important">
                    @foreach ($merchants as $company)
                    <tr class="table-row">
                        <td class="table-data text-center" style="border:1px solid #B8B8B8; border-left:0">
                            @if($company->status == '0')
                            <input type="checkbox" name="merchant_ids[]" value="{{ $company->id }}" class="merchant-checkbox">
                            @else
                            <input type="checkbox" disabled>
                            @endif
                        </td>
                        <td class="table-data text-center" style="border:1px solid #B8B8B8;">
                            {{ $company->mid }}
                        </td>
                        <td class="table-data text-center d-flex justify-content-around align-items-center" style="border:1px solid #B8B8B8;">
                            {{ $company->vpa }}
                            <a href="{{ url('/admin/p23-merchants/generate-link/'.$company->id) }}" target="_blank" class="btn btn-sm link-generate-btn" title="Generate Link"><i class="fa fa-up-right-from-square"></i></a>
                        </td>
                        <td class="table-data text-center" style="border:1px solid #B8B8B8; border-right:0">
                            {{ number_format($company->limitPerDay, 2) ?? '-' }}
                        </td>
                        <td class="table-data text-center" style="border:1px solid #B8B8B8; border-right:0">
                            {{ number_format($company->totalSpends, 2) ?? 0 }}
                        </td>
                        <td class="table-data text-center" style="border:1px solid #B8B8B8; border-right:0">
                            {{ $company->startDate ? \Carbon\Carbon::parse($company->startDate)->format('d M Y') : '-' }}
                        </td>

                        <td class="table-data text-center" style="border:1px solid #B8B8B8; border-right:0">
                            {{ $company->endDate ? \Carbon\Carbon::parse($company->endDate)->format('d M Y') : '-' }}
                        </td>
                        <td class="table-data text-center" style="border:1px solid #B8B8B8; border-right:0">
                            {{ number_format($company->successRate, 2) ?? 0 }} %
                        </td>
                        <td style="border:1px solid #B8B8B8; border-right:0;" class="table-data text-center @if($company->status == '1')text-success @else text-danger @endif">@if($company->status == '1') Active @else Deactivated @endif</td>
                        <td class="table-data text-center" style="border:1px solid #B8B8B8; border-right:0">
                            <a href="#" class="btn btn-sm edit-btn" title="Edit Merchant" data-bs-toggle="modal" data-bs-target="#edit-modal-{{ $company->id }}"><i class="fa fa-edit"></i></a>

                            @if($company->status == '0')
                            <a href="{{ route('delete-p23merchant', $company->id) }}" class="btn btn-sm text-danger" title="Delete Merchant" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-trash"></i></a>
                            @endif
                        </td>

                        <div class="modal fade" id="edit-modal-{{ $company->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Edit Merchant Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form action="{{ route('edit-p23merchant') }}" method="POST">
                                            @csrf

                                            <input type="hidden" name="merchant_id" value="{{ $company->id }}">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="mid" class="form-label">MID</label>
                                                    <input type="text" name="mid" class="form-control" id="mid" value="{{ $company->mid }}" disabled>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="vpa" class="form-label">VPA</label>
                                                    <input type="text" name="vpa" class="form-control" id="vpa" value="{{ $company->vpa }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="limitPerDay" class="form-label">Daily Limit</label>
                                                    <input type="text" name="limitPerDay" class="form-control" id="limitPerDay" value="{{ $company->limitPerDay }}" required>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="limitPerMonth" class="form-label">Monthly Limit</label>
                                                    <input type="text" name="limitPerMonth" class="form-control" id="limitPerMonth" value="{{ $company->limitPerMonth }}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="limitPerYear" class="form-label">Yearly Limit</label>
                                                    <input type="text" name="limitPerYear" class="form-control" id="limitPerYear" value="{{ $company->limitPerYear }}">
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

</tr>
@endforeach
@for ($i = count($merchants); $i < 15; $i++)
    <tr class="pad">
    <td colspan="5" style="border:none !important;">&nbsp;</td>
    </tr>
    @endfor
    </tbody>
    </table>
    </form>
    </div>

    <!-- for mobile and tab -->
    <!-- card section  -->
    <div class="row d-lg-none d-flex justify-content-center">
        <!-- individual card -->
        @foreach ($merchants as $company)

        <div class="col-12 col-sm-6 p-2">
            <div class="col-12 ind-card">
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        MID
                    </div>
                    <span class="col-6">
                        {{ $company->mid }}
                    </span>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        VPA
                    </div>
                    <div class="col-6 break">
                        {{ $company->vpa }}
                    </div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Daily Limit
                    </div>
                    <div class="col-6 break">
                        {{ number_format($company->limitPerDay, 2) ?? '-' }}
                    </div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Total Spends
                    </div>
                    <div class="col-6 break">
                        {{ number_format($company->totalSpends, 2) ?? 0 }}
                    </div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Start Date
                    </div>
                    <div class="col-6 break">
                        {{ $company->startDate ? \Carbon\Carbon::parse($company->startDate)->format('d M Y') : '-' }}
                    </div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        End Date
                    </div>
                    <div class="col-6 break">
                        {{ $company->endDate ? \Carbon\Carbon::parse($company->endDate)->format('d M Y') : '-' }}
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
            @if(method_exists($merchants, 'links'))

                <div class=" d-flex justify-content-end align-items-center gap-4 lg:gap-14 mt-4 pr-3 lg:py-5">
                        <div class="d-flex align-items-center">
                            <span class="footer-text">Showing {{ $merchants->firstItem() ?? 0 }} to
                                {{ $merchants->lastItem() ?? 0 }} of
                                {{ $merchants->total() }} results
                            </span>
                        </div>

                        <div class="">
                            <ul class="pagination d-flex align-items-center gap-2">
                                {{-- Prev --}}
                                @if (!$merchants->onFirstPage())
                                <li class="">
                                    <a class="d-flex align-items-center" href=" {{ $merchants->previousPageUrl() ?? '#' }}">
                                        <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 7L7 1M1 7L7 13M1 7L15 7" stroke="#827F7F" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </a>
                                </li>
                                @endif
                                @php
                                $current = $merchants->currentPage();
                                $last = $merchants->lastPage();
                                $window = 3; // change to 2 or 3 to show more numbers on each side
                                $start = max(1, $current - $window);
                                $end = min($last, $current + $window);
                                @endphp
                                {{-- First + leading ellipsis --}}
                                @if ($start > 1)
                                <li class=""><a class=" href=" {{ $merchants->url(1) }}" style="color: black">1</a>
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
                                    <a class="pagination-box" href="{{ $merchants->url($page) }}"
                                        style="color: black">{{ $page }}</a>
                                    @endif
                                    </li>
                                    @endfor
                                    {{-- Trailing ellipsis + last --}}
                                    @if ($end < $last) @if ($end < $last - 1)
                                        <li class=" "><span class="rounded-[5px]">…</span></li>
                                        @endif
                                        <li class=""><a class="" href=" {{ $merchants->url($last) }}" style="color: black">{{ $last }}</a>
                                        </li>
                                        @endif
                                        {{-- Next --}}
                                        @if ($merchants->hasMorePages())
                                        <li class="">
                                            <a class="d-flex align-items-center" href="{{ $merchants->nextPageUrl() ?? '#' }}">
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
                    function toggleDeleteLink() {
                        const anyChecked = document.querySelectorAll('.merchant-checkbox:checked').length > 0;
                        document.getElementById('delete-selected-link')?.classList.toggle('d-none', !anyChecked);
                    }

                    document.querySelectorAll('.merchant-checkbox').forEach(function(checkbox) {
                        checkbox.addEventListener('change', toggleDeleteLink);
                    });

                    document.getElementById('select-all-deactivated')?.addEventListener('change', function() {
                        document.querySelectorAll('.merchant-checkbox').forEach(function(checkbox) {
                            checkbox.checked = this.checked;
                        }, this);

                        toggleDeleteLink();
                    });
                </script>
                @endsection