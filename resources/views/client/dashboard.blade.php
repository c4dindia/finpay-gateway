@extends('layouts.clientMaster')

@section('title')
Dashboard
@php
$currentPage = 'Home';
@endphp
@endsection

@section('css')
@endsection

@section('page-content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<div class="d-flex flex-column">

    <div class="card-scroller mt-3 pb-3 mx-2 mx-md-0">

        <div class="row mb-2">
            <form id="serviceForm" method="GET" action="{{ route('showHome') }}" class="d-flex align-items-end">
                <label for="service" class="form-label">Select Service:</label>
                <select name="service" id="service" class="form-select w-25 mx-2">
                    <option value="all" {{ session('service','all') == 'all' ? 'selected' : '' }}>All</option>
                    @php
                        $company = Auth::user()->company()->first();
                        $services = [
                            'p12' => ['label' => '2D/3D',           'model' => \App\Models\PTwelvePaymentMethod::class],
                            'p17' => ['label' => 'P-17 Dire',       'model' => \App\Models\Direpay::class],
                            'p22' => ['label' => 'P-22 Uniqo',       'model' => \App\Models\UniqoPay::class],
                            'p23' => ['label' => 'P-23 UPI',       'model' => \App\Models\UPIPayment::class],
                        ];
                    @endphp
                    @foreach($services as $key => $service)
                        @if($company && $service['model']::where('company_id', $company->id)->where('status', 1)->exists())
                            <option value="{{ $key }}" {{ session('service') == $key ? 'selected' : '' }}>
                                {{ $service['label'] }}
                            </option>
                        @endif
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-primary">GO</button>
            </form>
        </div>

        <!-- INNER: the track that lays cards horizontally -->
        <div class="card-track mt-3">
            @foreach ($totalTransactions->unique('currency') as $txn)
            <div class="card-container-dashboard bg-blue-gradient" {{ $txn->currency != 'USD' ? 'style=display:none;' : '' }}>
                <div class="d-flex justify-content-between card-top">
                    <div class="curr-sign-container">
                        <svg class="curr-sign" width="25" height="25" viewBox="0 0 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7188 23.4375V21.6592C7.83838 21.4355 5.48096 19.4365 5.46875 16.4062H8.98438C9.0708 17.6924 10.1494 18.6743 11.7188 18.8477V14.0625L10.4116 13.7207C7.43311 13.0283 5.83936 11.3091 5.83936 8.73633C5.83936 5.70361 8.01172 3.71484 11.7188 3.41797V1.5625H13.2812V3.41797C17.0601 3.72559 19.0918 5.74902 19.1406 8.59375H15.625C15.5879 7.41895 14.8521 6.4751 13.2812 6.34766V10.8398L14.7861 11.1953C17.9497 11.8877 19.5312 13.5254 19.5312 16.2109C19.5312 19.3525 17.3955 21.3809 13.2812 21.6465V23.4375H11.7188ZM11.7188 10.5469V6.34766C10.3716 6.42188 9.41064 7.24951 9.41064 8.42432C9.41064 9.51318 10.2109 10.2251 11.7188 10.5469ZM13.2812 14.3555V18.8477C15.144 18.7725 16.0342 17.9238 16.0342 16.6128C16.0342 15.4126 15.144 14.6045 13.2812 14.3555Z"
                                fill="#0050B1" />
                        </svg>
                    </div>
                    <div class="time-selection">
                        <form action="" class="ts-form form-select form-select-sm">
                            <select class="ts-select" id="usdTotal-select">
                                <option value="total" selected>Total</option>
                                <option value="thisMonth">This month</option>
                                <option value="lastMonth">Last month</option>
                                <option value="lastFewMonths">Last 3 months</option>
                            </select>

                            <svg width="14" height="14" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.53044 8.02994C6.38981 8.1704 6.19919 8.24929 6.00044 8.24929C5.80169 8.24929 5.61106 8.1704 5.47044 8.02994L2.64144 5.20194C2.50081 5.06125 2.42183 4.87045 2.42188 4.67152C2.42192 4.47259 2.50099 4.28183 2.64169 4.14119C2.78239 4.00056 2.97319 3.92158 3.17212 3.92163C3.37104 3.92168 3.56181 4.00075 3.70244 4.14144L6.00044 6.43944L8.29844 4.14144C8.43983 4.00476 8.62924 3.92907 8.82589 3.93069C9.02254 3.9323 9.21069 4.01109 9.34981 4.15008C9.48893 4.28907 9.5679 4.47715 9.5697 4.67379C9.5715 4.87044 9.49599 5.05993 9.35944 5.20144L6.53094 8.03045L6.53044 8.02994Z"
                                    fill="white" />
                            </svg>
                        </form>

                    </div>
                </div>
                <div class="px-4 pt-1">
                    <div class="amount-text" id="amount-text1">USD {{ round($usdTotal, 2) }}</div>
                    <div class="amount-subtext">Total USD</div>
                </div>
            </div>
            <div class="card-container-dashboard bg-cyan-gradient" {{ $txn->currency != 'GBP' ? 'style=display:none;' : '' }}>
                <div class="d-flex justify-content-between card-top">
                    <div class="curr-sign-container">
                        <svg class="curr-sign" width="23" height="23" viewBox="0 0 23 23" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.9577 0C14.9739 1.27778e-05 16.99 0.423928 19.0062 1.27175L17.5639 4.83882C15.9406 4.17712 14.5344 3.84626 13.3454 3.84623C12.5389 3.84625 11.9186 4.07888 11.4843 4.54415C11.05 4.99911 10.8329 5.65567 10.8329 6.51381V9.50707H16.6488V12.9036H10.8329V15.1214C10.8329 16.8791 10.0523 18.1612 8.49105 18.9676H19.6266V23H3.37305V19.1537C4.43801 18.6988 5.16694 18.1767 5.55983 17.5873C5.96307 16.998 6.1647 16.1863 6.16471 15.1524V12.9036H3.40406V9.50705H6.16469V6.48284C6.16469 4.40463 6.75404 2.80719 7.93274 1.69052C9.12176 0.563506 10.7967 0 12.9577 0Z"
                                fill="#07B3C3" />
                        </svg>

                    </div>
                    <div class="time-selection">
                        <form action="" class="ts-form form-select form-select-sm">
                            <select class="ts-select" id="gbpTotal-select">
                                <option value="total" selected>Total</option>
                                <option value="thisMonth">This month</option>
                                <option value="lastMonth">Last month</option>
                                <option value="lastFewMonths">Last 3 months</option>
                            </select>

                            <svg width="14" height="14" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.53044 8.02994C6.38981 8.1704 6.19919 8.24929 6.00044 8.24929C5.80169 8.24929 5.61106 8.1704 5.47044 8.02994L2.64144 5.20194C2.50081 5.06125 2.42183 4.87045 2.42188 4.67152C2.42192 4.47259 2.50099 4.28183 2.64169 4.14119C2.78239 4.00056 2.97319 3.92158 3.17212 3.92163C3.37104 3.92168 3.56181 4.00075 3.70244 4.14144L6.00044 6.43944L8.29844 4.14144C8.43983 4.00476 8.62924 3.92907 8.82589 3.93069C9.02254 3.9323 9.21069 4.01109 9.34981 4.15008C9.48893 4.28907 9.5679 4.47715 9.5697 4.67379C9.5715 4.87044 9.49599 5.05993 9.35944 5.20144L6.53094 8.03045L6.53044 8.02994Z"
                                    fill="white" />
                            </svg>
                        </form>

                    </div>
                </div>
                <div class="px-4 pt-1">
                    <div class="amount-text" id="amount-text2">GBP {{ round($gbpTotal, 2) }}</div>
                    <div class="amount-subtext">Total GBP</div>
                </div>
            </div>
            <div class="card-container-dashboard bg-green-gradient" {{ $txn->currency != 'USDT' ? 'style=display:none;' : '' }}>
                <div class="d-flex justify-content-between card-top">
                    <div class="curr-sign-container">
                        <svg class="curr-sign" width="25" height="25" viewBox="0 0 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7188 23.4375V21.6592C7.83838 21.4355 5.48096 19.4365 5.46875 16.4062H8.98438C9.0708 17.6924 10.1494 18.6743 11.7188 18.8477V14.0625L10.4116 13.7207C7.43311 13.0283 5.83936 11.3091 5.83936 8.73633C5.83936 5.70361 8.01172 3.71484 11.7188 3.41797V1.5625H13.2812V3.41797C17.0601 3.72559 19.0918 5.74902 19.1406 8.59375H15.625C15.5879 7.41895 14.8521 6.4751 13.2812 6.34766V10.8398L14.7861 11.1953C17.9497 11.8877 19.5312 13.5254 19.5312 16.2109C19.5312 19.3525 17.3955 21.3809 13.2812 21.6465V23.4375H11.7188ZM11.7188 10.5469V6.34766C10.3716 6.42188 9.41064 7.24951 9.41064 8.42432C9.41064 9.51318 10.2109 10.2251 11.7188 10.5469ZM13.2812 14.3555V18.8477C15.144 18.7725 16.0342 17.9238 16.0342 16.6128C16.0342 15.4126 15.144 14.6045 13.2812 14.3555Z"
                                fill="#0050B1" />
                        </svg>
                    </div>
                    <div class="time-selection">
                        <form action="" class="ts-form form-select form-select-sm">
                            <select class="ts-select" id="usdtTotal-select">
                                <option value="total" selected>Total</option>
                                <option value="thisMonth">This month</option>
                                <option value="lastMonth">Last month</option>
                                <option value="lastFewMonths">Last 3 months</option>
                            </select>

                            <svg width="14" height="14" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.53044 8.02994C6.38981 8.1704 6.19919 8.24929 6.00044 8.24929C5.80169 8.24929 5.61106 8.1704 5.47044 8.02994L2.64144 5.20194C2.50081 5.06125 2.42183 4.87045 2.42188 4.67152C2.42192 4.47259 2.50099 4.28183 2.64169 4.14119C2.78239 4.00056 2.97319 3.92158 3.17212 3.92163C3.37104 3.92168 3.56181 4.00075 3.70244 4.14144L6.00044 6.43944L8.29844 4.14144C8.43983 4.00476 8.62924 3.92907 8.82589 3.93069C9.02254 3.9323 9.21069 4.01109 9.34981 4.15008C9.48893 4.28907 9.5679 4.47715 9.5697 4.67379C9.5715 4.87044 9.49599 5.05993 9.35944 5.20144L6.53094 8.03045L6.53044 8.02994Z"
                                    fill="white" />
                            </svg>
                        </form>

                    </div>
                </div>
                <div class="px-4 pt-1">
                    <div class="amount-text" id="amount-text3">USDT {{ round($usdtTotal, 2) }}</div>
                    <div class="amount-subtext">Total USDT</div>
                </div>
            </div>
            <div class="card-container-dashboard bg-purple-gradient" {{ $txn->currency != 'ETH' ? 'style=display:none;' : '' }}>
                <div class="d-flex justify-content-between card-top">
                    <div class="curr-sign-container">
                        <svg class="curr-sign" width="25" height="25" viewBox="0 0 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7188 23.4375V21.6592C7.83838 21.4355 5.48096 19.4365 5.46875 16.4062H8.98438C9.0708 17.6924 10.1494 18.6743 11.7188 18.8477V14.0625L10.4116 13.7207C7.43311 13.0283 5.83936 11.3091 5.83936 8.73633C5.83936 5.70361 8.01172 3.71484 11.7188 3.41797V1.5625H13.2812V3.41797C17.0601 3.72559 19.0918 5.74902 19.1406 8.59375H15.625C15.5879 7.41895 14.8521 6.4751 13.2812 6.34766V10.8398L14.7861 11.1953C17.9497 11.8877 19.5312 13.5254 19.5312 16.2109C19.5312 19.3525 17.3955 21.3809 13.2812 21.6465V23.4375H11.7188ZM11.7188 10.5469V6.34766C10.3716 6.42188 9.41064 7.24951 9.41064 8.42432C9.41064 9.51318 10.2109 10.2251 11.7188 10.5469ZM13.2812 14.3555V18.8477C15.144 18.7725 16.0342 17.9238 16.0342 16.6128C16.0342 15.4126 15.144 14.6045 13.2812 14.3555Z"
                                fill="#0050B1" />
                        </svg>
                    </div>
                    <div class="time-selection">
                        <form action="" class="ts-form form-select form-select-sm">
                            <select class="ts-select" id="ethTotal-select">
                                <option value="total" selected>Total</option>
                                <option value="thisMonth">This month</option>
                                <option value="lastMonth">Last month</option>
                                <option value="lastFewMonths">Last 3 months</option>
                            </select>

                            <svg width="14" height="14" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.53044 8.02994C6.38981 8.1704 6.19919 8.24929 6.00044 8.24929C5.80169 8.24929 5.61106 8.1704 5.47044 8.02994L2.64144 5.20194C2.50081 5.06125 2.42183 4.87045 2.42188 4.67152C2.42192 4.47259 2.50099 4.28183 2.64169 4.14119C2.78239 4.00056 2.97319 3.92158 3.17212 3.92163C3.37104 3.92168 3.56181 4.00075 3.70244 4.14144L6.00044 6.43944L8.29844 4.14144C8.43983 4.00476 8.62924 3.92907 8.82589 3.93069C9.02254 3.9323 9.21069 4.01109 9.34981 4.15008C9.48893 4.28907 9.5679 4.47715 9.5697 4.67379C9.5715 4.87044 9.49599 5.05993 9.35944 5.20144L6.53094 8.03045L6.53044 8.02994Z"
                                    fill="white" />
                            </svg>
                        </form>

                    </div>
                </div>
                <div class="px-4 pt-1">
                    <div class="amount-text" id="amount-text4">ETH {{ round($ethTotal, 2) }}</div>
                    <div class="amount-subtext">Total ETHEREUM</div>
                </div>
            </div>

            <div class="card-container-dashboard bg-blue-gradient" {{ $txn->currency != 'EUR' ? 'style=display:none;' : '' }}>
                <div class="d-flex justify-content-between card-top">
                    <div class="curr-sign-container">
                        <svg class="curr-sign" fill="#0050B1" height="25" width="25" version="1.1" id="Capa_1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 310.75 310.75" xml:space="preserve">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M183.815,265.726c-32.444,0-60.868-21.837-76.306-54.325h102.101v-45.023H95.643c-0.284-3.642-0.437-7.29-0.437-11.016 c0-3.691,0.152-7.384,0.437-10.977h113.969V99.353H107.51c15.438-32.485,43.861-54.315,76.306-54.315 c31.01,0,60.21,20.759,76.2,54.152l40.626-19.418C277.091,30.554,232.329,0,183.815,0c-36.47,0-70.51,16.665-95.851,46.966 C75.219,62.209,65.481,79.995,59.079,99.353H10.108v45.031h40.39c-0.217,3.617-0.329,7.311-0.329,10.977 c0,3.704,0.112,7.351,0.329,11.016h-40.39V211.4h48.971c6.402,19.356,16.14,37.122,28.886,52.351 c25.341,30.303,59.381,46.999,95.851,46.999c48.515,0,93.275-30.55,116.826-79.767l-40.626-19.454 C244.025,244.965,214.825,265.726,183.815,265.726z">
                                </path>
                            </g>
                        </svg>


                    </div>
                    <div class="time-selection">
                        <form action="" class="ts-form form-select form-select-sm">
                            <select class="ts-select" id="eurTotal-select">
                                <option value="total" selected>Total</option>
                                <option value="thisMonth">This month</option>
                                <option value="lastMonth">Last month</option>
                                <option value="lastFewMonths">Last 3 months</option>
                            </select>

                            <svg width="14" height="14" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.53044 8.02994C6.38981 8.1704 6.19919 8.24929 6.00044 8.24929C5.80169 8.24929 5.61106 8.1704 5.47044 8.02994L2.64144 5.20194C2.50081 5.06125 2.42183 4.87045 2.42188 4.67152C2.42192 4.47259 2.50099 4.28183 2.64169 4.14119C2.78239 4.00056 2.97319 3.92158 3.17212 3.92163C3.37104 3.92168 3.56181 4.00075 3.70244 4.14144L6.00044 6.43944L8.29844 4.14144C8.43983 4.00476 8.62924 3.92907 8.82589 3.93069C9.02254 3.9323 9.21069 4.01109 9.34981 4.15008C9.48893 4.28907 9.5679 4.47715 9.5697 4.67379C9.5715 4.87044 9.49599 5.05993 9.35944 5.20144L6.53094 8.03045L6.53044 8.02994Z"
                                    fill="white" />
                            </svg>
                        </form>

                    </div>
                </div>
                <div class="px-4 pt-1">
                    <div class="amount-text" id="amount-text5">EUR {{ number_format($eurTotal, 2, '.', '')}}
                    </div>
                    <div class="amount-subtext">Total EUR</div>
                </div>
            </div>

            <div class="card-container-dashboard bg-mint-gradient" {{ $txn->currency != 'CAD' ? 'style=display:none;' : '' }}>
                <div class="d-flex justify-content-between card-top">
                    <div class="curr-sign-container">
                        <svg class="curr-sign" fill="#0050B1" width="25" height="25" viewBox="0 0 320 320" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M115 40c-45 0-85 40-85 120s40 120 85 120c25 0 44-12 58-32l-31-18c-7 10-17 17-27 17-30 0-55-33-55-87s25-87 55-87c10 0 20 6 27 17l31-18c-14-20-33-32-58-32zm125 40h-30v40c-35 5-60 25-60 55 0 32 25 48 60 56v49h30v-47c38-4 65-27 65-58 0-35-27-51-65-60V80zm0 96c20 6 35 14 35 32s-15 28-35 32v-64z"/>
                            </g>

                        </svg>

                    </div>
                    <div class="time-selection">
                        <form action="" class="ts-form form-select form-select-sm">
                            <select class="ts-select" id="cadTotal-select">
                                <option value="total" selected>Total</option>
                                <option value="thisMonth">This month</option>
                                <option value="lastMonth">Last month</option>
                                <option value="lastFewMonths">Last 3 months</option>
                            </select>

                            <svg width="14" height="14" viewBox="0 0 12 12" fill="white"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.53044 8.02994C6.38981 8.1704 6.19919 8.24929 6.00044 8.24929C5.80169 8.24929 5.61106 8.1704 5.47044 8.02994L2.64144 5.20194C2.50081 5.06125 2.42183 4.87045 2.42188 4.67152C2.42192 4.47259 2.50099 4.28183 2.64169 4.14119C2.78239 4.00056 2.97319 3.92158 3.17212 3.92163C3.37104 3.92168 3.56181 4.00075 3.70244 4.14144L6.00044 6.43944L8.29844 4.14144C8.43983 4.00476 8.62924 3.92907 8.82589 3.93069C9.02254 3.9323 9.21069 4.01109 9.34981 4.15008C9.48893 4.28907 9.5679 4.47715 9.5697 4.67379C9.5715 4.87044 9.49599 5.05993 9.35944 5.20144L6.53094 8.03045L6.53044 8.02994Z"
                                    fill="white" />
                            </svg>
                        </form>

                    </div>
                </div>
                <div class="px-4 pt-1">
                    <div class="amount-text" id="amount-text6">CAD {{ number_format($cadTotal, 2, '.', '')}}
                    </div>
                    <div class="amount-subtext">Total CAD</div>
                </div>
            </div>

            <div class="card-container-dashboard bg-brown-gradient" {{ $txn->currency != 'INR' ? 'style=display:none;' : '' }}>
                <div class="d-flex justify-content-between card-top">
                    <div class="curr-sign-container">
                        <svg class="curr-sign" width="25" height="25" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <text x="4" y="18" font-size="18" font-family="Arial, sans-serif" fill="#0050B1">₹</text>
                        </svg>
                    </div>
                    <div class="time-selection">
                        <form action="" class="ts-form form-select form-select-sm">
                            <select class="ts-select" id="inrTotal-select">
                                <option value="total" selected>Total</option>
                                <option value="thisMonth">This month</option>
                                <option value="lastMonth">Last month</option>
                                <option value="lastFewMonths">Last 3 months</option>
                            </select>

                            <svg width="14" height="14" viewBox="0 0 12 12" fill="white"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.53044 8.02994C6.38981 8.1704 6.19919 8.24929 6.00044 8.24929C5.80169 8.24929 5.61106 8.1704 5.47044 8.02994L2.64144 5.20194C2.50081 5.06125 2.42183 4.87045 2.42188 4.67152C2.42192 4.47259 2.50099 4.28183 2.64169 4.14119C2.78239 4.00056 2.97319 3.92158 3.17212 3.92163C3.37104 3.92168 3.56181 4.00075 3.70244 4.14144L6.00044 6.43944L8.29844 4.14144C8.43983 4.00476 8.62924 3.92907 8.82589 3.93069C9.02254 3.9323 9.21069 4.01109 9.34981 4.15008C9.48893 4.28907 9.5679 4.47715 9.5697 4.67379C9.5715 4.87044 9.49599 5.05993 9.35944 5.20144L6.53094 8.03045L6.53044 8.02994Z"
                                    fill="white" />
                            </svg>
                        </form>
                    </div>
                </div>
                <div class="px-4 pt-1">
                    <div class="amount-text" id="amount-text7">INR {{ number_format($inrTotal, 2, '.', '')}}
                    </div>
                    <div class="amount-subtext">Total INR</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="row mt-4  mx-2 mx-md-4">

        <div class="col-xxl-6 pb-4 pb-md-5 ps-md-2 pb-xxl-0 ps-md-0 pe-xxl-4 pe-xl-0 pe-md-2 p-0">
            <div class=" chart px-2 px-md-5">
                <div class="d-flex justify-content-end align-items-center mb-3">
                    <select id="currencySelect" class="form-select chart-select w-auto">
                        @php
                            $currencies = $totalTransactions
                                ->sortBy(fn($txn) => [$txn->currency === 'INR' ? 0 : 1, $txn->currency])
                                ->unique('currency');
                        @endphp
                        
                        @foreach ($currencies as $txn)
                            <option value="{{ $txn->currency }}">{{ $txn->currency }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="amountChart" class="chart-body"></canvas>
                </div>

            </div>
        </div>
        <!-- Table -->
        <div class="col-xxl-6 p-0 pb-2 pb-md-0 ps-xxl-4 pe-xl-0 pe-xxl-0">
            <div class="dashboard-table h-100 table-responsive">
                <div class="d-flex justify-content-between align-items-center px-3 px-md-5 pt-4 mb-2">
                    <span class="dashboard-table-top-left">
                        Transactions
                    </span>
                    <a href="{{ route('showTransactions') }}" class="dashboard-table-top-right">
                        View all
                    </a>
                </div>
                <table class="text-center px-1 px-md-5 table align-middle">
                    <thead>
                        <tr>
                            <th scope="col" class="cid">Checkout ID</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $recentTransactions = $totalTransactions->sortByDesc('created_at')->take(5);
                        @endphp
                        @foreach ($recentTransactions as $trans)
                        <tr>
                            <td class="text-truncate text-md-nowrap cid-td" scope="row">{{ $trans->checkout_id }}</td>
                            <td class="text-nowrap">
                                @php
                                $status = strtolower($trans->payment_status);
                                if (in_array($status, ['approved', 'succeeded', 'completed', 'done'])) {
                                $color = 'bg-successColor'; // green
                                } elseif (in_array($status, ['declined', 'failed', 'rejected', 'cancelled', 'canceled', 'payment-error'])) {
                                $color = 'bg-dangerColor'; // red
                                } elseif (in_array($status, ['pending', 'in-progress'])) {
                                $color = 'bg-warningColor'; // orange
                                } else {
                                $color = 'bg-secondary'; // default gray
                                }
                                @endphp

                                <span class="dash-status text-white {{ $color }}">{{ ucfirst($trans->payment_status) }}</span>
                            </td>
                            <td class="text-nowrap">{{ \Carbon\Carbon::parse($trans->created_at)->format('d/m/Y') }}</td>
                            <td class="text-nowrap">{{ $trans->currency }} {{ number_format($trans->settled_amount ?? $trans->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <!-- Cards -->

    <div class="d-flex flex-column mt-3">
        <div class="card-row1 d-flex flex-column flex-xl-row">
            <div class="r1c1 flex-fill px-2 px-md-5">
                <h5>
                    CAPTURED
                </h5>
                @php
                $capturedTransactions = $totalTransactions->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid']);
                $capturedPercentage = $totalTransactions->count() > 0 ? ($capturedTransactions->count() / $totalTransactions->count()) * 100 : 0;
                $capturedCount = count($capturedTransactions);
                @endphp
                <div class="progress-bar d-flex flex-column gap-1 align-items-start">
                    <span>{{ $capturedCount }}</span>
                    <div class="bar">
                        <div class="progress"
                            style="width: {{ $capturedPercentage }}%; height: 100%; background-color:white">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end" style="width:100%;">
                        <span>
                            {{ number_format($capturedPercentage, 2) }}%
                        </span>
                    </div>
                </div>
            </div>
            <div class="r1c2 flex-fill px-2 px-md-5">
                <h5>
                    AWAITING
                </h5>
                @php
                $awaitingTransactions = $totalTransactions->whereIn('payment_status', ['Pending', 'Processing', 'Attempting', 'Waiting']);
                $awaitingPercentage = $totalTransactions->count() > 0 ? ($awaitingTransactions->count() / $totalTransactions->count()) * 100 : 0;
                $awaitingCount = count($awaitingTransactions);
                @endphp
                <div class="progress-bar d-flex flex-column gap-1 align-items-start">
                    <span>{{ $awaitingCount }}</span>
                    <div class="bar" style="width:100%;">
                        <div class="progress" style="width:{{ $awaitingPercentage }}%; height: 100%; background-color:white"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-end" style="width:100%;">
                    <span>
                        {{ number_format($awaitingPercentage, 2) }}%
                    </span>
                </div>
            </div>
        </div>


    </div>
    <div class="card-row2 d-flex flex-column flex-xl-row mb-5">
        <div class="r2c1 flex-fill px-2 px-md-5">
            <h5>
                FAILED
            </h5>
            @php
            $failedTransactions = $totalTransactions->whereIn('payment_status', ['Declined', 'Failed', 'Rejected', 'Cancelled', 'Canceled', 'Expired']);
            $failedPercentage = $totalTransactions->count() > 0 ? ($failedTransactions->count() / $totalTransactions->count()) * 100 : 0;
            $failedCount = count($failedTransactions);
            @endphp
            <div class="progress-bar d-flex flex-column gap-1 align-items-start">
                <span>{{ $failedCount }}</span>
                <div class="bar">
                    <div class="progress" style="width:{{ $failedPercentage }}%; height: 100%; background-color:white"></div>

                </div>
                <div class="d-flex justify-content-end" style="width:100%;">
                    <span>
                        {{ number_format($failedPercentage, 2) }}%
                    </span>
                </div>
            </div>
        </div>
        <div class="r2c2 flex-fill px-2 px-md-5">
            <h5>
                TOTAL
            </h5>
            @php
            $totalCount = $totalTransactions->count() > 0 ? $totalTransactions->count() : 0;
            $totalPercentage = $totalTransactions->count() > 0 ? 100 : 0;
            @endphp
            <div class="progress-bar d-flex flex-column gap-1 align-items-start">
                <span>{{ $totalCount }}</span>
                <div class="bar">
                    <div class="progress" style="width:{{ $totalPercentage }}%; height: 100%; background-color:white"></div>

                </div>
                <div class="d-flex justify-content-end" style="width:100%;">
                    <span>
                        {{ number_format($totalPercentage, 2) }}%
                    </span>
                </div>
            </div>
        </div>

    </div>

</div>
</div>






@endsection

@section('scripts')
{{-- chart.js script --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const months = @json($months);
        const amounts = @json($amounts);
        // console.log(months,amounts);

        // Get context
        const ctx = document.getElementById('amountChart').getContext('2d');

        // Initialize chart
        const amountChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: "Amount",
                    data: amounts,
                    borderWidth: 1,
                    backgroundColor: '#0050B1',
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8
                    }
                }]
            },
            options: {

                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {

                        grid: {
                            display: false // ❌ hide vertical grid lines

                        },
                        categoryPercentage: 0.3, // more space between categories
                        barPercentage: 0.5, // thinner bars
                        ticks: {
                            padding: 15,
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45,
                        },

                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: "rgba(0,0,0,0.3)", // horizontal grid line color
                            lineWidth: 0.5,
                            borderDashOffset: 0 // alignment
                        }
                    }
                }
            }
        });


        // Currency change event → live fetch
        document.getElementById('currencySelect').addEventListener('change', function() {
            updateChart(this.value, amountChart);
        });
    });

    // Fetch + update function
    function updateChart(currency, chart) {
        fetch(`/user/home/updated-chart-data/${currency}`)
            .then(response => response.json())
            .then(data => {
                console.log('response back', data);

                chart.data.labels = data.months;
                chart.data.datasets[0].data = data.amounts;
                chart.data.datasets[0].label = `Amount (${currency})`;
                chart.update();
            })
            .catch(error => {
                console.error('Error fetching updated chart data:', error);
            });
    }
</script>

<!-- script for cards -->
<script>
    const transactions = @json($totalTransactionsJS);

    const currencyMap = {
        'usdTotal-select': 'USD',
        'gbpTotal-select': 'GBP',
        'usdtTotal-select': 'USDT',
        'ethTotal-select': 'ETH',
        'eurTotal-select': 'EUR',
        'cadTotal-select': 'CAD',
        'inrTotal-select': 'INR',
    };

    const amountMap = {
        'usdTotal-select': 'amount-text1',
        'gbpTotal-select': 'amount-text2',
        'usdtTotal-select': 'amount-text3',
        'ethTotal-select': 'amount-text4',
        'eurTotal-select': 'amount-text5',
        'cadTotal-select': 'amount-text6',
        'inrTotal-select': 'amount-text7',
    };

    document.addEventListener("DOMContentLoaded", function() {

        Object.keys(currencyMap).forEach(selectId => {

            const currency = currencyMap[selectId];
            const amountDiv = document.getElementById(amountMap[selectId]);
            const select = document.getElementById(selectId);
            const subtextDiv = amountDiv.nextElementSibling;

            function filterByDate(range) {
                const now = new Date();
                const currentMonth = now.getMonth();
                const currentYear = now.getFullYear();

                return transactions.filter(t => {
                    if (t.currency !== currency) return false;

                    const date = new Date(t.created_at);
                    const month = date.getMonth();
                    const year = date.getFullYear();

                    if (range === "total") {
                        return true;
                    }

                    if (range === "thisMonth") {
                        return month === currentMonth && year === currentYear;
                    }

                    if (range === "lastMonth") {
                        const lastMonth = currentMonth - 1;
                        const lastMonthYear = lastMonth < 0 ? currentYear - 1 : currentYear;
                        const normalizedLastMonth = (lastMonth + 12) % 12;

                        return month === normalizedLastMonth && year === lastMonthYear;
                    }

                    if (range === "lastFewMonths") {
                        const cutoff = new Date();
                        cutoff.setMonth(cutoff.getMonth() - 3);
                        return date >= cutoff;
                    }

                    return true;
                });
            }

            function updateAmount() {
                const range = select.value;

                const subtextLabels = {
                    'total': `Total ${currency}`,
                    'thisMonth': 'This month',
                    'lastMonth': 'Last month',
                    'lastFewMonths': 'Last 3 months'
                };

                subtextDiv.innerText = subtextLabels[range];

                const filtered = filterByDate(range);

                const sum = filtered.reduce((acc, t) => acc + parseFloat(t.amount), 0);

                amountDiv.innerText = `${currency} ${sum.toFixed(2)}`;
            }

            select.addEventListener("change", updateAmount);

            updateAmount();
        });
    });
</script>


@endsection
