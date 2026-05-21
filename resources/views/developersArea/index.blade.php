@extends('layouts.clientMaster')

@section('title')
Credential Details
@php
$currentPage = 'developers-area';
@endphp
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/clientside/documentation.css') }}">
@endsection

@section('page-content')

@section('page-content')
<div class="row mx-0">
    <div class="col-12">
        <div class="row da-header mt-lg-3 mt-xxl-5">
            <div class="col-12 col-xxl-6 px-4">
                <div class="d-flex gap-1 justify-content-center justify-content-xxl-start">
                    <span class="da-header-text text-start ">Company Name: </span>
                    <p class="da-header-subtext ">{{ Auth::User()->name }}</p>
                </div>

            </div>
            <div class="col-12 col-xxl-6 px-4 px-xxl-0">
                <div class="d-flex gap-1 justify-content-center justify-content-xxl-start">
                    <span class="da-header-text  text-start">Account ID : </span>
                    <p class="da-header-subtext">{{ $accId }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row text-center doc-grid mx-0">
        @if (!empty($p12detail->b_token))
        <!-- host to host -->
        <div class="col-12 col-sm-6 col-xl-4 py-3">
            <button type="button" class="btn doc-card w-100" data-bs-toggle="modal" data-bs-target="#exampleModal14"  data-bs-whatever="pbl-x">
                <span class="font-500 grid-item-title">Host-to-Host (2D/3D)</span>
                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.1875 17.5C2.1875 20.5285 3.08556 23.489 4.76812 26.0072C6.45068 28.5253 8.84217 30.4879 11.6402 31.6469C14.4382 32.8059 17.517 33.1091 20.4873 32.5183C23.4577 31.9274 26.1861 30.4691 28.3276 28.3276C30.4691 26.1861 31.9274 23.4577 32.5183 20.4873C33.1091 17.517 32.8059 14.4382 31.6469 11.6402C30.4879 8.84217 28.5253 6.45068 26.0072 4.76812C23.489 3.08556 20.5285 2.1875 17.5 2.1875C13.4389 2.1875 9.54408 3.80078 6.67243 6.67243C3.80078 9.54408 2.1875 13.4389 2.1875 17.5ZM8.75 16.4062H22.0391L15.9359 10.2736L17.5 8.75L26.25 17.5L17.5 26.25L15.9359 24.6892L22.0391 18.5938H8.75V16.4062Z"
                        fill="black" />
                </svg>
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal14" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered da-modal">
                <div class="modal-content da-modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Host-to-Host (2D/3D)</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body da-modal-body">
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 ">Notification URL :
                            </span><span class="font-500">{{ $p12detail->redirect_url }}/api/finpay/p12/{checkout_id}</span>
                        </p>
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 ">Bearer Token : </span>
                            <span class="font-500">{{ $p12detail->b_token }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (!empty($p17detail->b_token))
        <!-- host to host DIREPAY -->
        <div class="col-12 col-sm-6 col-xl-4 py-3">
            <button type="button" class="btn doc-card w-100" data-bs-toggle="modal" data-bs-target="#exampleModalDirepay"  data-bs-whatever="pbl-x">
                <span class="font-500 grid-item-title">Pay-By-Link (Dire)</span>
                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.1875 17.5C2.1875 20.5285 3.08556 23.489 4.76812 26.0072C6.45068 28.5253 8.84217 30.4879 11.6402 31.6469C14.4382 32.8059 17.517 33.1091 20.4873 32.5183C23.4577 31.9274 26.1861 30.4691 28.3276 28.3276C30.4691 26.1861 31.9274 23.4577 32.5183 20.4873C33.1091 17.517 32.8059 14.4382 31.6469 11.6402C30.4879 8.84217 28.5253 6.45068 26.0072 4.76812C23.489 3.08556 20.5285 2.1875 17.5 2.1875C13.4389 2.1875 9.54408 3.80078 6.67243 6.67243C3.80078 9.54408 2.1875 13.4389 2.1875 17.5ZM8.75 16.4062H22.0391L15.9359 10.2736L17.5 8.75L26.25 17.5L17.5 26.25L15.9359 24.6892L22.0391 18.5938H8.75V16.4062Z"
                        fill="black" />
                </svg>
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalDirepay" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered da-modal">
                <div class="modal-content da-modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pay-By-Link (Dire)</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body da-modal-body">
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 ">Notification URL :
                            </span><span class="font-500">{{ $p17detail->redirect_url }}/api/finpay/p17/{checkout_id}</span>
                        </p>
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 ">Bearer Token : </span>
                            <span class="font-500">{{ $p17detail->b_token }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if (!empty($p22detail->b_token))
        <!-- host to host DIREPAY -->
        <div class="col-12 col-sm-6 col-xl-4 py-3">
            <button type="button" class="btn doc-card w-100" data-bs-toggle="modal" data-bs-target="#exampleModalUniqo"  data-bs-whatever="pbl-x">
                <span class="font-500 grid-item-title">Pay-By-Link (Uniqo)</span>
                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.1875 17.5C2.1875 20.5285 3.08556 23.489 4.76812 26.0072C6.45068 28.5253 8.84217 30.4879 11.6402 31.6469C14.4382 32.8059 17.517 33.1091 20.4873 32.5183C23.4577 31.9274 26.1861 30.4691 28.3276 28.3276C30.4691 26.1861 31.9274 23.4577 32.5183 20.4873C33.1091 17.517 32.8059 14.4382 31.6469 11.6402C30.4879 8.84217 28.5253 6.45068 26.0072 4.76812C23.489 3.08556 20.5285 2.1875 17.5 2.1875C13.4389 2.1875 9.54408 3.80078 6.67243 6.67243C3.80078 9.54408 2.1875 13.4389 2.1875 17.5ZM8.75 16.4062H22.0391L15.9359 10.2736L17.5 8.75L26.25 17.5L17.5 26.25L15.9359 24.6892L22.0391 18.5938H8.75V16.4062Z"
                        fill="black" />
                </svg>
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalUniqo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered da-modal">
                <div class="modal-content da-modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pay-By-Link (Uniqo)</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body da-modal-body">
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 ">Notification URL :
                            </span><span class="font-500">{{ $p22detail->redirect_url }}/api/finpay/p22/{checkout_id}</span>
                        </p>
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 ">Bearer Token : </span>
                            <span class="font-500">{{ $p22detail->b_token }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if (!empty($p23detail->b_token))
        <!-- host to host DIREPAY -->
        <div class="col-12 col-sm-6 col-xl-4 py-3">
            <button type="button" class="btn doc-card w-100" data-bs-toggle="modal" data-bs-target="#exampleModalUpipay"  data-bs-whatever="pbl-x">
                <span class="font-500 grid-item-title">Pay-By-Link (Upi)</span>
                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.1875 17.5C2.1875 20.5285 3.08556 23.489 4.76812 26.0072C6.45068 28.5253 8.84217 30.4879 11.6402 31.6469C14.4382 32.8059 17.517 33.1091 20.4873 32.5183C23.4577 31.9274 26.1861 30.4691 28.3276 28.3276C30.4691 26.1861 31.9274 23.4577 32.5183 20.4873C33.1091 17.517 32.8059 14.4382 31.6469 11.6402C30.4879 8.84217 28.5253 6.45068 26.0072 4.76812C23.489 3.08556 20.5285 2.1875 17.5 2.1875C13.4389 2.1875 9.54408 3.80078 6.67243 6.67243C3.80078 9.54408 2.1875 13.4389 2.1875 17.5ZM8.75 16.4062H22.0391L15.9359 10.2736L17.5 8.75L26.25 17.5L17.5 26.25L15.9359 24.6892L22.0391 18.5938H8.75V16.4062Z"
                        fill="black" />
                </svg>
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalUpipay" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered da-modal">
                <div class="modal-content da-modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pay-By-Link (Upi)</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body da-modal-body">
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 ">Notification URL :
                            </span><span class="font-500">{{ $p23detail->redirect_url }}/api/finpay/p23/{checkout_id}</span>
                        </p>
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 ">Bearer Token : </span>
                            <span class="font-500">{{ $p23detail->b_token }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        

    </div>

</div>

@endsection
