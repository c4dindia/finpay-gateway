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
    <div class="row doc-grid mx-0">
        @if (!empty($p12detail->b_token))
        <!-- host to host -->
        <div class="col-12 col-sm-6 col-xl-4 py-3">
            <button type="button" class="btn doc-card w-100 fd-docTile" data-bs-toggle="modal" data-bs-target="#exampleModal14" data-bs-whatever="pbl-x">
                <div class="fd-docTile__top">
                    <span class="fd-docTile__icon" aria-hidden="true">
                        <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M19.5938 3.4375C19.5938 3.34633 19.5575 3.2589 19.4931 3.19443C19.4286 3.12997 19.3412 3.09375 19.25 3.09375H9.625C8.62215 3.09375 7.66037 3.49213 6.95125 4.20125C6.24213 4.91037 5.84375 5.87215 5.84375 6.875V26.125C5.84375 27.1278 6.24213 28.0896 6.95125 28.7987C7.66037 29.5079 8.62215 29.9062 9.625 29.9062H23.375C24.3778 29.9062 25.3396 29.5079 26.0487 28.7987C26.7579 28.0896 27.1562 27.1278 27.1562 26.125V12.5771C27.1562 12.486 27.12 12.3985 27.0556 12.3341C26.9911 12.2696 26.9037 12.2334 26.8125 12.2334H20.625C20.3515 12.2334 20.0892 12.1247 19.8958 11.9313C19.7024 11.7379 19.5938 11.4756 19.5938 11.2021V3.4375ZM20.625 16.8438C20.8985 16.8438 21.1608 16.9524 21.3542 17.1458C21.5476 17.3392 21.6562 17.6015 21.6562 17.875C21.6562 18.1485 21.5476 18.4108 21.3542 18.6042C21.1608 18.7976 20.8985 18.9062 20.625 18.9062H12.375C12.1015 18.9062 11.8392 18.7976 11.6458 18.6042C11.4524 18.4108 11.3438 18.1485 11.3438 17.875C11.3438 17.6015 11.4524 17.3392 11.6458 17.1458C11.8392 16.9524 12.1015 16.8438 12.375 16.8438H20.625ZM20.625 22.3438C20.8985 22.3438 21.1608 22.4524 21.3542 22.6458C21.5476 22.8392 21.6562 23.1015 21.6562 23.375C21.6562 23.6485 21.5476 23.9108 21.3542 24.1042C21.1608 24.2976 20.8985 24.4062 20.625 24.4062H12.375C12.1015 24.4062 11.8392 24.2976 11.6458 24.1042C11.4524 23.9108 11.3438 23.6485 11.3438 23.375C11.3438 23.1015 11.4524 22.8392 11.6458 22.6458C11.8392 22.4524 12.1015 22.3438 12.375 22.3438H20.625Z"
                                fill="black" />
                            <path
                                d="M21.6562 3.883C21.6562 3.63 21.9216 3.46913 22.1183 3.62725C22.2851 3.762 22.4331 3.91875 22.5624 4.0975L26.7053 9.86838C26.7988 10.0004 26.697 10.1709 26.5348 10.1709H22C21.9088 10.1709 21.8214 10.1347 21.7569 10.0702C21.6925 10.0057 21.6562 9.91829 21.6562 9.82713V3.883Z"
                                fill="black" />
                        </svg>
                    </span>
                    <span class="fd-docTile__arrow" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M10.8333 14.1668L15 10.0002L10.8333 5.8335M15 10.0002H5" stroke="#4A5565"
                                stroke-width="2.08333" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
                <div class="fd-docTile__title">Host-to-Host (2D/3D)</div>
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
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 mb-2 ">Notification URL :
                            </span><span class="font-500">{{ $p12detail->redirect_url }}/api/finpay/p12/{checkout_id}</span>
                        </p>
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 mb-2 ">Bearer Token : </span>
                            <span class="font-500">{{ $p12detail->b_token }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (!empty($p17detail->b_token))
        <!-- Pay By Link Direpay -->
        <div class="col-12 col-sm-6 col-xl-4 py-3">
            <button type="button" class="btn doc-card w-100 fd-docTile" data-bs-toggle="modal" data-bs-target="#exampleModalDirepay" data-bs-whatever="pbl-x">
                <div class="fd-docTile__top">
                    <span class="fd-docTile__icon" aria-hidden="true">
                        <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M19.5938 3.4375C19.5938 3.34633 19.5575 3.2589 19.4931 3.19443C19.4286 3.12997 19.3412 3.09375 19.25 3.09375H9.625C8.62215 3.09375 7.66037 3.49213 6.95125 4.20125C6.24213 4.91037 5.84375 5.87215 5.84375 6.875V26.125C5.84375 27.1278 6.24213 28.0896 6.95125 28.7987C7.66037 29.5079 8.62215 29.9062 9.625 29.9062H23.375C24.3778 29.9062 25.3396 29.5079 26.0487 28.7987C26.7579 28.0896 27.1562 27.1278 27.1562 26.125V12.5771C27.1562 12.486 27.12 12.3985 27.0556 12.3341C26.9911 12.2696 26.9037 12.2334 26.8125 12.2334H20.625C20.3515 12.2334 20.0892 12.1247 19.8958 11.9313C19.7024 11.7379 19.5938 11.4756 19.5938 11.2021V3.4375ZM20.625 16.8438C20.8985 16.8438 21.1608 16.9524 21.3542 17.1458C21.5476 17.3392 21.6562 17.6015 21.6562 17.875C21.6562 18.1485 21.5476 18.4108 21.3542 18.6042C21.1608 18.7976 20.8985 18.9062 20.625 18.9062H12.375C12.1015 18.9062 11.8392 18.7976 11.6458 18.6042C11.4524 18.4108 11.3438 18.1485 11.3438 17.875C11.3438 17.6015 11.4524 17.3392 11.6458 17.1458C11.8392 16.9524 12.1015 16.8438 12.375 16.8438H20.625ZM20.625 22.3438C20.8985 22.3438 21.1608 22.4524 21.3542 22.6458C21.5476 22.8392 21.6562 23.1015 21.6562 23.375C21.6562 23.6485 21.5476 23.9108 21.3542 24.1042C21.1608 24.2976 20.8985 24.4062 20.625 24.4062H12.375C12.1015 24.4062 11.8392 24.2976 11.6458 24.1042C11.4524 23.9108 11.3438 23.6485 11.3438 23.375C11.3438 23.1015 11.4524 22.8392 11.6458 22.6458C11.8392 22.4524 12.1015 22.3438 12.375 22.3438H20.625Z"
                                fill="black" />
                            <path
                                d="M21.6562 3.883C21.6562 3.63 21.9216 3.46913 22.1183 3.62725C22.2851 3.762 22.4331 3.91875 22.5624 4.0975L26.7053 9.86838C26.7988 10.0004 26.697 10.1709 26.5348 10.1709H22C21.9088 10.1709 21.8214 10.1347 21.7569 10.0702C21.6925 10.0057 21.6562 9.91829 21.6562 9.82713V3.883Z"
                                fill="black" />
                        </svg>
                    </span>
                    <span class="fd-docTile__arrow" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M10.8333 14.1668L15 10.0002L10.8333 5.8335M15 10.0002H5" stroke="#4A5565"
                                stroke-width="2.08333" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
                <div class="fd-docTile__title">Pay-By-Link (Dire)</div>
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
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 mb-2 ">Notification URL :
                            </span><span class="font-500">{{ $p17detail->redirect_url }}/api/finpay/p17/{checkout_id}</span>
                        </p>
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 mb-2 ">Bearer Token : </span>
                            <span class="font-500">{{ $p17detail->b_token }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (!empty($p22detail->b_token))
        <!-- Pay By Link Uniqo -->
        <div class="col-12 col-sm-6 col-xl-4 py-3">
            <button type="button" class="btn doc-card w-100 fd-docTile" data-bs-toggle="modal" data-bs-target="#exampleModalUniqo" data-bs-whatever="pbl-x">
                <div class="fd-docTile__top">
                    <span class="fd-docTile__icon" aria-hidden="true">
                        <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M19.5938 3.4375C19.5938 3.34633 19.5575 3.2589 19.4931 3.19443C19.4286 3.12997 19.3412 3.09375 19.25 3.09375H9.625C8.62215 3.09375 7.66037 3.49213 6.95125 4.20125C6.24213 4.91037 5.84375 5.87215 5.84375 6.875V26.125C5.84375 27.1278 6.24213 28.0896 6.95125 28.7987C7.66037 29.5079 8.62215 29.9062 9.625 29.9062H23.375C24.3778 29.9062 25.3396 29.5079 26.0487 28.7987C26.7579 28.0896 27.1562 27.1278 27.1562 26.125V12.5771C27.1562 12.486 27.12 12.3985 27.0556 12.3341C26.9911 12.2696 26.9037 12.2334 26.8125 12.2334H20.625C20.3515 12.2334 20.0892 12.1247 19.8958 11.9313C19.7024 11.7379 19.5938 11.4756 19.5938 11.2021V3.4375ZM20.625 16.8438C20.8985 16.8438 21.1608 16.9524 21.3542 17.1458C21.5476 17.3392 21.6562 17.6015 21.6562 17.875C21.6562 18.1485 21.5476 18.4108 21.3542 18.6042C21.1608 18.7976 20.8985 18.9062 20.625 18.9062H12.375C12.1015 18.9062 11.8392 18.7976 11.6458 18.6042C11.4524 18.4108 11.3438 18.1485 11.3438 17.875C11.3438 17.6015 11.4524 17.3392 11.6458 17.1458C11.8392 16.9524 12.1015 16.8438 12.375 16.8438H20.625ZM20.625 22.3438C20.8985 22.3438 21.1608 22.4524 21.3542 22.6458C21.5476 22.8392 21.6562 23.1015 21.6562 23.375C21.6562 23.6485 21.5476 23.9108 21.3542 24.1042C21.1608 24.2976 20.8985 24.4062 20.625 24.4062H12.375C12.1015 24.4062 11.8392 24.2976 11.6458 24.1042C11.4524 23.9108 11.3438 23.6485 11.3438 23.375C11.3438 23.1015 11.4524 22.8392 11.6458 22.6458C11.8392 22.4524 12.1015 22.3438 12.375 22.3438H20.625Z"
                                fill="black" />
                            <path
                                d="M21.6562 3.883C21.6562 3.63 21.9216 3.46913 22.1183 3.62725C22.2851 3.762 22.4331 3.91875 22.5624 4.0975L26.7053 9.86838C26.7988 10.0004 26.697 10.1709 26.5348 10.1709H22C21.9088 10.1709 21.8214 10.1347 21.7569 10.0702C21.6925 10.0057 21.6562 9.91829 21.6562 9.82713V3.883Z"
                                fill="black" />
                        </svg>
                    </span>
                    <span class="fd-docTile__arrow" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M10.8333 14.1668L15 10.0002L10.8333 5.8335M15 10.0002H5" stroke="#4A5565"
                                stroke-width="2.08333" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
                <div class="fd-docTile__title">Pay-By-Link (Uniqo)</div>
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
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 mb-2 ">Notification URL :
                            </span><span class="font-500">{{ $p22detail->redirect_url }}/api/finpay/p22/{checkout_id}</span>
                        </p>
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 mb-2 ">Bearer Token : </span>
                            <span class="font-500">{{ $p22detail->b_token }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (!empty($p23detail->b_token))
        <!-- Pay By Link Upipay -->
        <div class="col-12 col-sm-6 col-xl-4 py-3">
            <button type="button" class="btn doc-card w-100 fd-docTile" data-bs-toggle="modal" data-bs-target="#exampleModalUpipay" data-bs-whatever="pbl-x">
                <div class="fd-docTile__top">
                    <span class="fd-docTile__icon" aria-hidden="true">
                        <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M19.5938 3.4375C19.5938 3.34633 19.5575 3.2589 19.4931 3.19443C19.4286 3.12997 19.3412 3.09375 19.25 3.09375H9.625C8.62215 3.09375 7.66037 3.49213 6.95125 4.20125C6.24213 4.91037 5.84375 5.87215 5.84375 6.875V26.125C5.84375 27.1278 6.24213 28.0896 6.95125 28.7987C7.66037 29.5079 8.62215 29.9062 9.625 29.9062H23.375C24.3778 29.9062 25.3396 29.5079 26.0487 28.7987C26.7579 28.0896 27.1562 27.1278 27.1562 26.125V12.5771C27.1562 12.486 27.12 12.3985 27.0556 12.3341C26.9911 12.2696 26.9037 12.2334 26.8125 12.2334H20.625C20.3515 12.2334 20.0892 12.1247 19.8958 11.9313C19.7024 11.7379 19.5938 11.4756 19.5938 11.2021V3.4375ZM20.625 16.8438C20.8985 16.8438 21.1608 16.9524 21.3542 17.1458C21.5476 17.3392 21.6562 17.6015 21.6562 17.875C21.6562 18.1485 21.5476 18.4108 21.3542 18.6042C21.1608 18.7976 20.8985 18.9062 20.625 18.9062H12.375C12.1015 18.9062 11.8392 18.7976 11.6458 18.6042C11.4524 18.4108 11.3438 18.1485 11.3438 17.875C11.3438 17.6015 11.4524 17.3392 11.6458 17.1458C11.8392 16.9524 12.1015 16.8438 12.375 16.8438H20.625ZM20.625 22.3438C20.8985 22.3438 21.1608 22.4524 21.3542 22.6458C21.5476 22.8392 21.6562 23.1015 21.6562 23.375C21.6562 23.6485 21.5476 23.9108 21.3542 24.1042C21.1608 24.2976 20.8985 24.4062 20.625 24.4062H12.375C12.1015 24.4062 11.8392 24.2976 11.6458 24.1042C11.4524 23.9108 11.3438 23.6485 11.3438 23.375C11.3438 23.1015 11.4524 22.8392 11.6458 22.6458C11.8392 22.4524 12.1015 22.3438 12.375 22.3438H20.625Z"
                                fill="black" />
                            <path
                                d="M21.6562 3.883C21.6562 3.63 21.9216 3.46913 22.1183 3.62725C22.2851 3.762 22.4331 3.91875 22.5624 4.0975L26.7053 9.86838C26.7988 10.0004 26.697 10.1709 26.5348 10.1709H22C21.9088 10.1709 21.8214 10.1347 21.7569 10.0702C21.6925 10.0057 21.6562 9.91829 21.6562 9.82713V3.883Z"
                                fill="black" />
                        </svg>
                    </span>
                    <span class="fd-docTile__arrow" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M10.8333 14.1668L15 10.0002L10.8333 5.8335M15 10.0002H5" stroke="#4A5565"
                                stroke-width="2.08333" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
                <div class="fd-docTile__title">Pay-By-Link (Upi)</div>
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
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 mb-2 ">Notification URL :
                            </span><span class="font-500">{{ $p23detail->redirect_url }}/api/finpay/p23/{checkout_id}</span>
                        </p>
                        <p class="d-flex flex-column align-items-start"><span class="grey-heading font-400 mb-2 ">Bearer Token : </span>
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
