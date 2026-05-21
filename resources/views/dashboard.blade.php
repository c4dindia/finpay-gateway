@extends('layouts.adminMaster')
@section('title')
Dashboard
@php
$currentPage = 'Dashboard';
@endphp
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('css/adminside/p1services.css') }}">
@endsection
@section('page-content')

<div class="p-2 p-lg-4 gap-4">
    <h3 class="text-center admin-db-heading">Admin Dashboard for Hosted Payment Page</h3>
    <h6 class="text-center admin-db-subheading">List of Registered Companies</h6>
    @if (count($companies) == 0)
    <div class="row mt-5 pt-5">
        <h5 class="text-center col-12">No Companies to Show!</h5>
    </div>
    @else
    <div class="table-container d-none d-lg-block">
        <table class="table custom-table align-middle all-trans-table">
            <thead>
                <tr>
                    <th class="table-header text-center p-3">#</th>
                    <th class="table-header text-start p-3">Company Name</th>
                    <th class="table-header text-start p-3">Email</th>
                    <th class="table-header text-start p-3">Password</th>
                    <th class="table-header text-center p-3">Date Added</th>
                    <th class="table-header text-center p-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                <tr class="table-row">
                    <td class="table-data text-center">{{ $loop->iteration }}</td>
                    <td class="table-data text-start">{{ $company->company_name }}</td>
                    <td class="table-data text-start">{{ $company->email }}</td>
                    <td class="table-data text-start">{{ $company->password }}</td>
                    <td class="table-data text-center">{{ \Carbon\Carbon::parse($company->created_at)->format('d M Y') }}</td>
                    <td class="table-data text-center @if($company->status == '1')text-success @else text-danger @endif">@if($company->status == '1') Active @else Deactivated @endif</td>
                </tr>
                @endforeach
                @for ($i = count($companies); $i < 15; $i++)
                    <tr class="pad">
                    <td colspan="4" style="border:none !important;">&nbsp;</td>
                    </tr>
                    @endfor
            </tbody>
        </table>
    </div>

    <!-- for mobile and tab -->
    <!-- card section  -->
    <div class="row  d-lg-none d-flex justify-content-center">
        <!-- individual card -->
        @foreach ($companies as $company)

        <div class="col-12 col-sm-6 p-2">
            <div class="col-12 ind-card">
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Company Name
                    </div>
                    <span class="col-6">
                        {{ $company->company_name }}
                    </span>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Email
                    </div>
                    <div class="col-6 break">
                        {{$company->email }}
                    </div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Password
                    </div>
                    <div class="col-6 break">
                        {{ $company->password }}
                    </div>
                </div>
                <div class="col-12 d-flex align-items-start py-1">
                    <div class="col-6 fw-semibold">
                        Date Added
                    </div>
                    <div class="col-6 break">
                        {{ \Carbon\Carbon::parse($company->created_at)->format('d M Y') }}
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
        @endif
      </div>

@endsection
@section('scripts')
@endsection