@extends('layouts.adminMaster')

@section('title')
    Register Company
    @php
        $currentPage = 'Register Company';
    @endphp
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/adminside/registercompany.css') }}">
@endsection

@section('page-content')
    <div class="d-flex flex-column p-lg-4 gap-0 gap-md-3 gap-xl-4">
        <h3 class="text-center reg-heading">Add a new
            Company </h3>
        <div class="d-flex flex-column flex-xl-row align-items-center reg-form-img">
            <div class="col-lg-12 col-xl-5 reg-form">
                <form action="{{ route('addCompany') }}" method="POST">
                    @csrf
                    <label for="companyName" class="form-label mb-0 mt-lg-4 p-2 rc-cstm-label">Company Name</label>
                    <input type="text" id="companyName" name="companyName" placeholder="Enter your Company Name"
                        class="form-control p-3" aria-describedby="passwordHelpBlock" required>
                    <label for="companyEmail" class="form-label mb-0 mt-2 mt-lg-4 p-2 rc-cstm-label">Company Email</label>
                    <input type="email" id="companyEmail" name="companyEmail" placeholder="Enter your Company Email"
                        class="form-control p-3" aria-describedby="passwordHelpBlock" required>
                    <label for="companyPassword" class="form-label mb-0 mt-2 mt-lg-4 p-2 rc-cstm-label">Company Password</label>
                    <input type="password" id="companyPassword" name="companyPassword" placeholder="Enter your Password"
                        class="form-control p-3" aria-describedby="passwordHelpBlock" required>
                    <button type="submit" class="submit-btn mt-4"> Add company</button>
                </form>
            </div>
            <div class="col-lg-6 d-flex justify-content-center align-items-start">
                <img class="right-img" src="{{ asset('assets/rightImg.png') }}" alt="">
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection