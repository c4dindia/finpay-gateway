@extends('layouts.adminMaster')

@section('title')
    Add Payment Service
    @php
        $currentPage = 'Add Payment Service';
    @endphp
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/adminside/addpayment.css') }}">
@endsection

@section('page-content')
    <div class="d-flex flex-column px-2 py-2 py-lg-4 gap-0  gap-md-3 gap-xl-4 ">
        <h3 class="text-center add-heading">Add Payment method for Company </h3>
        <div class="d-flex flex-column flex-xl-row align-items-center gap-lg-5 add-form-img">
            <div class="col-lg-12 col-xl-5 add-form">
                <form action="{{ route('assignPMtoComapany') }}" method="POST">
                    @csrf
                    <label for="selectCompany" class="form-label mb-0 mt-2 mt-md-4 p-2">Select company</label>
                    <div class="input-group">
                        <select class="form-select add-select-company p-3" id="selectCompany" name="selectCompany" required>
                            <option value="" disabled selected>Select Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="selectPaymentPartner" class="form-label mb-0 mt-2 mt-md-4 p-2">Payment partner</label>
                    <div class="input-group">
                        <select class="form-select p-3" name="selectPaymentPartner" id="selectPaymentPartner" required>
                            <option value="" disabled selected>Select Payment partner</option>
                            {{-- <option value="PGTechPay">PG Tech Pay - P12</option>
                            <option value="Direpay">Direpay P-17</option>
                            <option value="UniqoPay">Uniqo Pay P-22</option> --}}
                            <option value="Upipay">P-23 UPI</option>
                        </select>
                    </div>
                    <label for="companyRedirectURL" class="form-label mb-0 mt-2 mt-md-4 p-2">Redirect URL</label>
                    <input type="url" name="companyRedirectURL" id="companyRedirectURL" placeholder="https://www.sitename.com" class="form-control p-3" aria-describedby="passwordHelpBlock" required>
                    <button type="submit" class="submit-btn mt-4">Submit</button>
                </form>
            </div>

            <div class="col-xl-6 d-flex justify-content-center align-items-start">
                <img class="right-img-add" src="{{ asset('assets/rightImg.png') }}" alt="">
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
