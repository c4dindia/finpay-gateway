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

<div class="p-2 p-lg-4 gap-4 vpa-merchants-page">
    <h3 class="text-center admin-db-heading mb-3">Merchants for UPI Pay Services</h3>

    <div class="vpa-toolbar">
        <a href="#" class="vpa-add-merchant-btn" data-bs-toggle="modal" title="Add Merchant" data-bs-target="#add-modal">
            <i class="fa fa-plus"></i> Add Merchants
        </a>
        <a href="#" class="vpa-import-merchant-btn" data-bs-toggle="modal" title="Import Merchant" data-bs-target="#import-modal">
            <i class="fa fa-file-import"></i> Import Merchants
        </a>
        <a href="#"
            id="delete-selected-link"
            class="vpa-bulk-delete-btn d-none"
            onclick="event.preventDefault();
            if(confirm('Are you sure you want to delete selected deactivated merchants?')) {
                document.getElementById('bulk-delete-form').submit();
            }">
            <i class="fa fa-trash"></i> Delete Selected
        </a>
    </div>

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

        <div class="modal fade" id="import-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Merchant</h5>
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

    @if (count($merchants) == 0)
    <div class="row mt-5 pt-5">
        <h5 class="text-center col-12">No Companies to Show!</h5>
    </div>
    @else
    <form action="{{ route('deleteAll-p23merchants') }}" method="POST" id="bulk-delete-form">
        @csrf
    <div class="table-container d-none d-lg-block">
            <table class="table custom-table align-middle all-trans-table vpa-merchants-table" data-custom-mobile-cards="1">
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
                <tbody>
                    @foreach ($merchants as $company)
                    <tr class="table-row">
                        <td class="table-data text-center">
                            @if($company->status == '0')
                            <input type="checkbox" name="merchant_ids[]" value="{{ $company->id }}" class="merchant-checkbox">
                            @else
                            <input type="checkbox" disabled>
                            @endif
                        </td>

                        <td class="table-data text-center">
                            {{ $company->mid }}
                        </td>

                        <td class="table-data text-center">
                            <div class="vpa-cell">
                                <span class="vpa-cell-text">{{ $company->vpa }}</span>
                                <a href="{{ url('/admin/p23-merchants/generate-link/'.$company->id) }}" target="_blank" class="btn btn-sm link-generate-btn" title="Generate Link">
                                    <i class="fa fa-up-right-from-square"></i>
                                </a>
                            </div>
                        </td>

                        <td class="table-data text-center">
                            {{ number_format($company->limitPerDay, 2) ?? '-' }}
                        </td>

                        <td class="table-data text-center">
                            {{ number_format($company->totalSpends, 2) ?? 0 }}
                        </td>

                        <td class="table-data text-center">
                            {{ $company->startDate ? \Carbon\Carbon::parse($company->startDate)->format('d M Y') : '-' }}
                        </td>

                        <td class="table-data text-center">
                            {{ $company->endDate ? \Carbon\Carbon::parse($company->endDate)->format('d M Y') : '-' }}
                        </td>

                        <td class="table-data text-center">
                            {{ number_format($company->successRate, 2) ?? 0 }} %
                        </td>

                        <td class="table-data text-center @if($company->status == '1') text-success @else text-danger @endif">
                            @if($company->status == '1') Active @else Deactivated @endif
                        </td>

                        <td class="table-data text-center">
                            <div class="action-cell">
                                <a href="#" class="btn btn-sm edit-btn merchant-icon-btn" title="Edit Merchant" data-bs-toggle="modal" data-bs-target="#edit-modal-{{ $company->id }}">
                                    <i class="fa fa-edit"></i>
                                </a>

                                @if($company->status == '0')
                                <a href="{{ route('delete-p23merchant', $company->id) }}" class="btn btn-sm merchant-delete-btn" title="Delete Merchant" onclick="return confirm('Are you sure you want to delete?');">
                                    <i class="fa fa-trash"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @for ($i = count($merchants); $i < 13; $i++)
                    <tr class="pad">
                        <td colspan="10" style="border:none !important;">&nbsp;</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
    </div>

    <!-- Mobile layout -->
    <div class="vpa-mobile-list d-lg-none">
        <div class="vpa-mobile-select-bar">
            <label class="vpa-mobile-select-all">
                <input type="checkbox" id="select-all-deactivated-mobile" aria-label="Select all deactivated merchants">
                <span>Select all deactivated</span>
            </label>
        </div>

        <div class="vpa-mobile-cards">
            @foreach ($merchants as $company)
            <div class="vpa-mobile-card ind-card">
                <div class="vpa-mobile-card-top">
                    @if($company->status == '0')
                    <input type="checkbox" name="merchant_ids[]" value="{{ $company->id }}" class="merchant-checkbox" aria-label="Select merchant {{ $company->mid }}">
                    @else
                    <input type="checkbox" disabled aria-label="Active merchant cannot be selected">
                    @endif
                    <div class="vpa-mobile-card-actions">
                        <a href="#" class="btn btn-sm edit-btn merchant-icon-btn" title="Edit Merchant" data-bs-toggle="modal" data-bs-target="#edit-modal-{{ $company->id }}">
                            <i class="fa fa-edit"></i>
                        </a>
                        @if($company->status == '0')
                        <a href="{{ route('delete-p23merchant', $company->id) }}" class="btn btn-sm merchant-delete-btn" title="Delete Merchant" onclick="return confirm('Are you sure you want to delete?');">
                            <i class="fa fa-trash"></i>
                        </a>
                        @endif
                        <a href="{{ url('/admin/p23-merchants/generate-link/'.$company->id) }}" target="_blank" class="btn btn-sm link-generate-btn" title="Generate Link">
                            <i class="fa fa-up-right-from-square"></i>
                        </a>
                    </div>
                </div>

                <div class="vpa-mobile-field">
                    <span class="card-el-head">MID</span>
                    <span class="card-el-content">{{ $company->mid }}</span>
                </div>
                <div class="vpa-mobile-field">
                    <span class="card-el-head">VPA</span>
                    <span class="card-el-content break">{{ $company->vpa }}</span>
                </div>
                <div class="vpa-mobile-field">
                    <span class="card-el-head">Daily Limit</span>
                    <span class="card-el-content">{{ number_format($company->limitPerDay, 2) ?? '-' }}</span>
                </div>
                <div class="vpa-mobile-field">
                    <span class="card-el-head">Total Spends</span>
                    <span class="card-el-content">{{ number_format($company->totalSpends, 2) ?? 0 }}</span>
                </div>
                <div class="vpa-mobile-field">
                    <span class="card-el-head">Start Date</span>
                    <span class="card-el-content">{{ $company->startDate ? \Carbon\Carbon::parse($company->startDate)->format('d M Y') : '-' }}</span>
                </div>
                <div class="vpa-mobile-field">
                    <span class="card-el-head">End Date</span>
                    <span class="card-el-content">{{ $company->endDate ? \Carbon\Carbon::parse($company->endDate)->format('d M Y') : '-' }}</span>
                </div>
                <div class="vpa-mobile-field">
                    <span class="card-el-head">Success Rate</span>
                    <span class="card-el-content">{{ number_format($company->successRate, 2) ?? 0 }} %</span>
                </div>
                <div class="vpa-mobile-field">
                    <span class="card-el-head">Status</span>
                    <span class="card-el-content @if($company->status == '1') statusSuccess @else statusdanger @endif">
                        @if($company->status == '1') Active @else Deactivated @endif
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </form>

    @foreach ($merchants as $company)
    <div class="modal fade vpa-edit-modal" id="edit-modal-{{ $company->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('edit-p23merchant') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Merchant Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="merchant_id" value="{{ $company->id }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mid-{{ $company->id }}" class="form-label">MID</label>
                                <input type="text" name="mid" class="form-control" id="mid-{{ $company->id }}" value="{{ $company->mid }}" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="vpa-{{ $company->id }}" class="form-label">VPA</label>
                                <input type="text" name="vpa" class="form-control" id="vpa-{{ $company->id }}" value="{{ $company->vpa }}" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="limitPerDay-{{ $company->id }}" class="form-label">Daily Limit</label>
                                <input type="text" name="limitPerDay" class="form-control" id="limitPerDay-{{ $company->id }}" value="{{ $company->limitPerDay }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="limitPerMonth-{{ $company->id }}" class="form-label">Monthly Limit</label>
                                <input type="text" name="limitPerMonth" class="form-control" id="limitPerMonth-{{ $company->id }}" value="{{ $company->limitPerMonth }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="limitPerYear-{{ $company->id }}" class="form-label">Yearly Limit</label>
                                <input type="text" name="limitPerYear" class="form-control" id="limitPerYear-{{ $company->id }}" value="{{ $company->limitPerYear }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status-{{ $company->id }}" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status-{{ $company->id }}" required>
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
    @endforeach

    @if(method_exists($merchants, 'links'))
    <div class="d-flex justify-content-end align-items-center gap-4 lg:gap-14 mt-4 pr-3 lg:py-5">
        <div class="d-flex align-items-center">
            <span class="footer-text">Showing {{ $merchants->firstItem() ?? 0 }} to
                {{ $merchants->lastItem() ?? 0 }} of
                {{ $merchants->total() }} results
            </span>
        </div>

        <div>
            <ul class="pagination d-flex align-items-center gap-2">
                @if (!$merchants->onFirstPage())
                <li>
                    <a class="d-flex align-items-center" href="{{ $merchants->previousPageUrl() ?? '#' }}">
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
                $window = 3;
                $start = max(1, $current - $window);
                $end = min($last, $current + $window);
                @endphp
                @if ($start > 1)
                <li><a href="{{ $merchants->url(1) }}" style="color: black">1</a></li>
                @if ($start > 2)
                <li><span style="color: black">…</span></li>
                @endif
                @endif
                @for ($page = $start; $page <= $end; $page++)
                <li class="{{ $page === $current ? 'pagination-box-active' : 'pagination-box-inactive' }}">
                    @if ($page === $current)
                    <span class="pagination-box">{{ $page }}</span>
                    @else
                    <a class="pagination-box" href="{{ $merchants->url($page) }}" style="color: black">{{ $page }}</a>
                    @endif
                </li>
                @endfor
                @if ($end < $last)
                @if ($end < $last - 1)
                <li><span>…</span></li>
                @endif
                <li><a href="{{ $merchants->url($last) }}" style="color: black">{{ $last }}</a></li>
                @endif
                @if ($merchants->hasMorePages())
                <li>
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
    function isMobileVpaView() {
        return window.matchMedia('(max-width: 991.98px)').matches;
    }

    function getActiveMerchantCheckboxes() {
        const rootSelector = isMobileVpaView() ? '.vpa-mobile-list' : '.table-container';
        return Array.from(document.querySelectorAll(rootSelector + ' .merchant-checkbox:not(:disabled)'));
    }

    function toggleDeleteLink() {
        const anyChecked = getActiveMerchantCheckboxes().some(function (cb) { return cb.checked; });
        document.getElementById('delete-selected-link')?.classList.toggle('d-none', !anyChecked);
    }

    function syncSelectAllState() {
        const checkboxes = getActiveMerchantCheckboxes();
        const checkedCount = checkboxes.filter(function (cb) { return cb.checked; }).length;
        const allChecked = checkboxes.length > 0 && checkedCount === checkboxes.length;
        const selectAllId = isMobileVpaView() ? 'select-all-deactivated-mobile' : 'select-all-deactivated';
        const el = document.getElementById(selectAllId);

        if (el) {
            el.checked = allChecked;
            el.indeterminate = checkedCount > 0 && !allChecked;
        }
    }

    function setAllMerchantCheckboxes(checked) {
        getActiveMerchantCheckboxes().forEach(function (checkbox) {
            checkbox.checked = checked;
        });
        toggleDeleteLink();
        syncSelectAllState();
    }

    document.querySelectorAll('.merchant-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            toggleDeleteLink();
            syncSelectAllState();
        });
        checkbox.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });

    document.getElementById('select-all-deactivated')?.addEventListener('change', function () {
        if (!isMobileVpaView()) setAllMerchantCheckboxes(this.checked);
    });

    document.getElementById('select-all-deactivated-mobile')?.addEventListener('change', function () {
        if (isMobileVpaView()) setAllMerchantCheckboxes(this.checked);
    });

    window.addEventListener('resize', function () {
        toggleDeleteLink();
        syncSelectAllState();
    });

    syncSelectAllState();
</script>
@endsection
