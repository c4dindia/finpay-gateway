@extends('layouts.clientMaster')

@section('title')
    Documentation > P19-Service
    @php
        $currentPage = 'Documentations';
    @endphp
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/clientside/p2service.css') }}">
@endsection

@section('page-content')
    <div class="p-3">
        <div class="col-xl-12 mt-3 d-flex flex-column flex-lg-row gap-5">
            <div class="left-section progress-vertical">
                <ol class="steps">

                    <!-- Step 1: Create Customer -->
                    <li id="step1-li" class="step completed">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 1 </h4>
                            <p class="pl-3"> API for creating a new customer. <br>
                                <span class="fw-bold"> NOTE:</span> All parameters in request are required.
                            </p>
                            <p><span class="fw-bold">POST: </span> <a href="#">
                                    https://payment.ryzen-pay.com/api/payment/{accId}/p19/create-customer </a></p>

                            <!-- --------------------Mobile Content:------------------ -->
                            <div id="step1-mobile" class="d-lg-none">

                                <div class="header-wrapper">
                                    <div class="box-header d-flex justify-content-between">
                                        <p class="text-center mb-0 fw-bold">Headers:</p>
                                        <div class="d-flex gap-2 align-items-center">
                                            <span>copy</span>
                                            <button type="button" class="copy-btn" aria-label="Copy response">
                                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                                        fill="black" />
                                                    <path
                                                        d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                                        fill="black" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-content">
                                        <p>
                                            <span class="pink-text">Authorization</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> [ Bearer-Token ]</span>
                                        </p>
                                        <p>
                                            <span class="pink-text">Content-Type</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> 'application/json'</span>
                                        </p>
                                    </div>

                                    <!-- Request Body -->
                                    <div class="header-wrapper mt-4">
                                        <div class="box-header d-flex justify-content-between">
                                            <p class="mb-0 font-500">Request Body: </p>
                                            <div class="d-flex gap-2 align-items-center">
                                                <span>copy</span>
                                                <button type="button" class="copy-btn" aria-label="Copy response">
                                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                                            fill="black" />
                                                        <path
                                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                                            fill="black" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="box-content">
                                            <p>
                                                <span class="pink-text">'json'</span>
                                                <span class="pink-text">=></span>
                                                <span class="blue-text-color">{<br>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'firstName'</span>
                                                        <span class="pink-text me-2">:</span>"Bobby",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'lastName'</span>
                                                        <span class="pink-text me-2">:</span>"Poonia",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'email'</span>
                                                        <span
                                                            class="pink-text me-2">:</span>"bobby@connect4digital.co.uk",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                            class="pink-text">'phoneNumber'</span> <span
                                                            class="pink-text me-2">:</span>"+447712134620",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'gender'</span>
                                                        <span class="pink-text me-2">:</span>"Male",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                            class="pink-text">'dateOfBirth'</span> <span
                                                            class="pink-text me-2">:</span>"2000-12-23",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                            class="pink-text">'countryOfResidence'</span> <span
                                                            class="pink-text me-2">:</span>"GB",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'address'</span>
                                                        <span class="pink-text me-2">:</span>{<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                            class="pink-text">'addressDetail'</span> <span
                                                            class="pink-text me-2">:</span>"7 Oadby",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                            class="pink-text">'city'</span> <span
                                                            class="pink-text me-2">:</span>"Leicester",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                            class="pink-text">'zipCode'</span> <span
                                                            class="pink-text me-2">:</span>"LE22EB"<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;}<br></span>
                                                    <span>}</span><br>
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Response: 200 OK -->
                                    <div class="header-wrapper mt-4">
                                        <div class="box-header d-flex justify-content-between">
                                            <p class="mb-0 font-500">Response: 200 OK</p>
                                            <div class="d-flex gap-2 align-items-center">
                                                <span>copy</span>
                                                <button type="button" class="copy-btn" aria-label="Copy response">
                                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                                            fill="black" />
                                                        <path
                                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                                            fill="black" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="box-content">
                                            <p>
                                                <span class="blue-text-color">{<br>
                                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"success":</span><span
                                                        class="blue-text-color"> true,</span><br>
                                                    <span
                                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"customer_id":</span><span
                                                        class="blue-text-color">
                                                        "ad4e6fca-12f8-4595-a22e-04992dc0ea2f"</span><br>
                                                    <span>}</span><br>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Step 2: Checkout -->
                    <li id="step2-li" class="step completed mt-5 mt-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 2 </h4>
                            <p class="pl-3"> API for creating a checkout session and payment link using the customer ID
                                obtained from Step 1. <br>
                                <span class="fw-bold"> NOTE:</span> All parameters in request are required.
                            </p>
                            <p><span class="fw-bold">POST: </span> <a href="#">
                                    https://payment.ryzen-pay.com/api/payment/{accId}/p19/checkout/{customerId} </a></p>

                            <!-- --------------------Mobile Content:------------------ -->
                            <div id="step2-mobile" class="d-lg-none">

                                <div class="header-wrapper">
                                    <div class="box-header d-flex justify-content-between">
                                        <p class="text-center mb-0 fw-bold">Headers:</p>
                                        <div class="d-flex gap-2 align-items-center">
                                            <span>copy</span>
                                            <button type="button" class="copy-btn" aria-label="Copy response">
                                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                                        fill="black" />
                                                    <path
                                                        d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                                        fill="black" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-content">
                                        <p>
                                            <span class="pink-text">Authorization</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> [ Bearer-Token ]</span>
                                        </p>
                                        <p>
                                            <span class="pink-text">Content-Type</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> 'application/json'</span>
                                        </p>
                                    </div>

                                    <!-- Request Body -->
                                    <div class="header-wrapper mt-4">
                                        <div class="box-header d-flex justify-content-between">
                                            <p class="mb-0 font-500">Request Body: </p>
                                            <div class="d-flex gap-2 align-items-center">
                                                <span>copy</span>
                                                <button type="button" class="copy-btn" aria-label="Copy response">
                                                    <svg width="25" height="25" viewBox="0 0 25 25"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                                            fill="black" />
                                                        <path
                                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                                            fill="black" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="box-content">
                                            <p>
                                                <span class="pink-text">'json'</span>
                                                <span class="pink-text">=></span>
                                                <span class="blue-text-color">{<br>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'amount'</span>
                                                        <span class="pink-text me-2">:</span>"19.5",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'currency'</span>
                                                        <span class="pink-text me-2">:</span>"USD",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                            class="pink-text">'description'</span> <span
                                                            class="pink-text me-2">:</span>"making first payment
                                                        attempt",&nbsp;<br></span>
                                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                            class="pink-text">'returnUrl'</span> <span
                                                            class="pink-text me-2">:</span>"https://www.instagram.com"&nbsp;<br></span>
                                                    <span>}</span><br>
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Response: 200 OK -->
                                    <div class="header-wrapper mt-4">
                                        <div class="box-header d-flex justify-content-between">
                                            <p class="mb-0 font-500">Response: 200 OK</p>
                                            <div class="d-flex gap-2 align-items-center">
                                                <span>copy</span>
                                                <button type="button" class="copy-btn" aria-label="Copy response">
                                                    <svg width="25" height="25" viewBox="0 0 25 25"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                                            fill="black" />
                                                        <path
                                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                                            fill="black" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="box-content">
                                            <p>
                                                <span class="blue-text-color">{<br>
                                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"success":</span><span
                                                        class="blue-text-color"> true,</span><br>
                                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"amount":</span><span
                                                        class="blue-text-color"> 19.5,</span><br>
                                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"currency":</span><span
                                                        class="blue-text-color"> "USD",</span><br>
                                                    <span
                                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"checkout_id":</span><span
                                                        class="blue-text-color">
                                                        "151b3e4d-4149-4e8c-894a-46431d6986dc",</span><br>
                                                    <span
                                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"customerId":</span><span
                                                        class="blue-text-color">
                                                        "ad4e6fca-12f8-4595-a22e-04992dc0ea2f",</span><br>
                                                    <span
                                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"payment_id":</span><span
                                                        class="blue-text-color">
                                                        "72f73546-dbb9-43a7-8d36-d009d8035bff",</span><br>
                                                    <span
                                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"payment_status":</span><span
                                                        class="blue-text-color"> "New",</span><br>
                                                    <span
                                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"paymentUrl":</span><span
                                                        class="blue-text-color">
                                                        "https://banking.valenspay.com/payment-gateway?orderId=72f73546-dbb9-43a7-8d36-d009d8035bff"</span><br>
                                                    <span>}</span><br>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li id="step3-li" class="step completed mt-5 mt-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 3: </h4><br>When transaction process is completed, we will
                            send a
                            Webhook request to you, containing the {checkout_id} of the transaction . The
                            webhook
                            request will be sent in the following format:</p>
                            <p><span class="fw-bold">GET: </span> <a
                                    href="#">https://&lt;your-base-url&gt;/api/RyzenPay/p19/{checkout_id}</a></p>
                            <p><span class="fw-bold">EXAMPLE: </span>
                                https://www.examplesite.com/api/RyzenPay/p19/1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1
                            </p>
                            <p><span class="fw-bold">In Headers, </span> </p>
                            <div class="header-wrapper mt-4">
                                <div class="box-content">
                                    <p>
                                        <span class="pink-text">Authorization</span>
                                        <span class="pink-text">=</span>
                                        <span class="blue-text-color"> [ Bearer-Token ] </span>
                                    </p>
                                    <p>
                                        <span class="pink-text">Content-Type</span>
                                        <span class="pink-text">=</span>
                                        <span class="blue-text-color"> 'application/json'</span>
                                    </p>
                                </div> <br>
                                <p> In this request, you can call our GetPaymentStatus API to retrieve Transaction
                                    Details
                                    for the checkout_id. Make sure, the request should always return <span
                                        class="fw-bold">200
                                        STATUS.</span></p>
                            </div>
                    </li>

                    <!-- Step 4: GetPaymentStatus -->
                    <li id="step4-li" class="step completed mt-5 mt-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">GetPaymentStatus API </h4>
                            <p class="mb-0">Get the status and details of the transaction using the
                                <span class="fw-bold">{customerId}</span> and <span class="fw-bold">{payment_id}</span>.
                            </p>
                            <p><span class="fw-bold">GET: </span> <a
                                    href="#">https://payment.ryzen-pay.com/api/payment/{accId}/p19/getPaymentStatus/{customerId}/{payment_id}</a>
                            </p>
                        </div>

                        <!-- --------------------Mobile Content:------------------ -->
                        <div id="step4-mobile" class="d-lg-none">
                            <div class="header-wrapper mt-4">
                                <div class="box-header d-flex justify-content-between">
                                    <p class="mb-0 font-500">Headers:</p>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span>copy</span>
                                        <button type="button" class="copy-btn" aria-label="Copy response">
                                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                                    fill="black" />
                                                <path
                                                    d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                                    fill="black" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-content">
                                    <p>
                                        <span class="pink-text">Authorization</span>
                                        <span class="pink-text">=</span>
                                        <span class="blue-text-color"> [ Bearer-Token ] </span>
                                    </p>
                                    <p>
                                        <span class="pink-text">Content-Type</span>
                                        <span class="pink-text">=</span>
                                        <span class="blue-text-color"> 'application/json'</span>
                                    </p>
                                    <p>
                                        <span class="pink-text">Accept</span>
                                        <span class="pink-text">=</span>
                                        <span class="blue-text-color"> 'application/json'</span>
                                    </p>
                                </div>
                            </div>

                            <div class="header-wrapper mt-4">
                                <div class="box-header d-flex justify-content-between">
                                    <p class="mb-0 font-500">Response: 200 OK</p>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span>copy</span>
                                        <button type="button" class="copy-btn" aria-label="Copy response">
                                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                                    fill="black" />
                                                <path
                                                    d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                                    fill="black" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-content">
                                    <p>
                                        <span class="blue-text-color">{<br>
                                            <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"success":</span><span
                                                class="blue-text-color"> true,</span><br>
                                            <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"amount":</span><span
                                                class="blue-text-color"> 19.99,</span><br>
                                            <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"currency":</span><span
                                                class="blue-text-color"> "USD",</span><br>
                                            <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"checkout_id":</span><span
                                                class="blue-text-color"> "5fb1afdd-a2c8-4cbb-b0e6-6782404949f7",</span><br>
                                            <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"customerId":</span><span
                                                class="blue-text-color"> "22d2dd45-b580-4454-852c-d6ef8de788c8",</span><br>
                                            <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"payment_id":</span><span
                                                class="blue-text-color"> "3e354e41-3f04-47f3-a08d-41e5c0eaf114",</span><br>
                                            <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"payment_status":</span><span
                                                class="blue-text-color"> "New",</span><br>
                                            <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"paymentUrl":</span><span
                                                class="blue-text-color"> null,</span><br>
                                            <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"data":</span><span
                                                class="blue-text-color"> {</span><br>
                                            <span
                                                class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"customerId":</span><span
                                                class="blue-text-color"> "22d2dd45-b580-4454-852c-d6ef8de788c8",</span><br>
                                            <span
                                                class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"orderId":</span><span
                                                class="blue-text-color"> "3e354e41-3f04-47f3-a08d-41e5c0eaf114",</span><br>
                                            <span
                                                class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"currency":</span><span
                                                class="blue-text-color"> "USD",</span><br>
                                            <span
                                                class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"amount":</span><span
                                                class="blue-text-color"> 19.99,</span><br>
                                            <span
                                                class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"correlationId":</span><span
                                                class="blue-text-color"> "5fb1afdd-a2c8-4cbb-b0e6-6782404949f7",</span><br>
                                            <span
                                                class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"status":</span><span
                                                class="blue-text-color"> "New",</span><br>
                                            <span
                                                class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"description":</span><span
                                                class="blue-text-color"> "Kahn",</span><br>
                                            <span
                                                class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"transferDate":</span><span
                                                class="blue-text-color"> "2026-02-25T11:12:16.318844"</span><br>
                                            <span>&nbsp;&nbsp;&nbsp;&nbsp;}</span><br>
                                            <span>}</span><br>
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="header-wrapper mt-4">
                                <div class="box-header d-flex justify-content-between">
                                    <p class="mb-0 font-500">Response: 401 Unauthorized</p>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span>copy</span>
                                        <button type="button" class="copy-btn" aria-label="Copy response">
                                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                                    fill="black" />
                                                <path
                                                    d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                                    fill="black" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-content">
                                    <p>
                                        <span class="blue-text-color">{<br>
                                            <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"message":</span><span
                                                class="blue-text-color"> "Unauthorized Checkout Id or Transaction is not
                                                completed.",</span><br>
                                            <span>}</span><br>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- extra div for step line extension -->
                    <div></div>
                </ol>
            </div>

            <!-- ===================== RIGHT SECTION (Desktop) ===================== -->
            <div class="right-section">

                <!-- Step 1: Create Customer (Desktop) -->
                <div id="step1" class="d-none d-lg-block pb-5">
                    <p class="px-2"><span class="fw-bold">POST: </span> <a href="#">
                            https://payment.ryzen-pay.com/api/payment/{accId}/p19/create-customer </a></p>

                    <!-- Headers -->
                    <div class="header-wrapper">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Headers:</p>
                            <div class="d-flex gap-2 align-items-center">
                                <span>copy</span>
                                <button type="button" class="copy-btn" aria-label="Copy response">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                            fill="black" />
                                        <path
                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                            fill="black" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="box-content">
                            <p>
                                <span class="pink-text">Authorization</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> [ Bearer-Token ]</span>
                            </p>
                            <p>
                                <span class="pink-text">Content-Type</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> 'application/json'</span>
                            </p>
                        </div>
                    </div>

                    <!-- Request Body -->
                    <div class="header-wrapper mt-4">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Request Body: </p>
                            <div class="d-flex gap-2 align-items-center">
                                <span>copy</span>
                                <button type="button" class="copy-btn" aria-label="Copy response">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                            fill="black" />
                                        <path
                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                            fill="black" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="box-content">
                            <p>

                                <span class="blue-text-color">{<br>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'firstName'</span> <span
                                            class="pink-text me-2">:</span>"Bobby",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'lastName'</span> <span
                                            class="pink-text me-2">:</span>"Poonia",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'email'</span> <span
                                            class="pink-text me-2">:</span>"bobby@connect4digital.co.uk",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'phoneNumber'</span> <span
                                            class="pink-text me-2">:</span>"+447712134620",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'gender'</span> <span
                                            class="pink-text me-2">:</span>"Male",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'dateOfBirth'</span> <span
                                            class="pink-text me-2">:</span>"2000-12-23",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'countryOfResidence'</span> <span
                                            class="pink-text me-2">:</span>"GB",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'address'</span> <span
                                            class="pink-text me-2">:</span>{<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            class="pink-text">'addressDetail'</span> <span
                                            class="pink-text me-2">:</span>"7 Oadby",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            class="pink-text">'city'</span> <span
                                            class="pink-text me-2">:</span>"Leicester",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            class="pink-text">'zipCode'</span> <span
                                            class="pink-text me-2">:</span>"LE22EB"<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;}<br></span>
                                    <span>}</span><br>
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Response: 200 OK -->
                    <div class="header-wrapper mt-4">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Response: 200 OK</p>
                            <div class="d-flex gap-2 align-items-center">
                                <span>copy</span>
                                <button type="button" class="copy-btn" aria-label="Copy response">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                            fill="black" />
                                        <path
                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                            fill="black" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="box-content">
                            <p>
                                <span class="blue-text-color">{<br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"success":</span><span
                                        class="blue-text-color"> true,</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"customer_id":</span><span
                                        class="blue-text-color"> "ad4e6fca-12f8-4595-a22e-04992dc0ea2f"</span><br>
                                    <span>}</span><br>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Checkout (Desktop) -->
                <div id="step2" class="d-none d-lg-block pb-5">
                    <p class="px-2"><span class="fw-bold">POST: </span> <a href="#">
                            https://payment.ryzen-pay.com/api/payment/{accId}/p19/checkout/{customerId} </a></p>

                    <!-- Headers -->
                    <div class="header-wrapper">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Headers:</p>
                            <div class="d-flex gap-2 align-items-center">
                                <span>copy</span>
                                <button type="button" class="copy-btn" aria-label="Copy response">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                            fill="black" />
                                        <path
                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                            fill="black" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="box-content">
                            <p>
                                <span class="pink-text">Authorization</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> [ Bearer-Token ]</span>
                            </p>
                            <p>
                                <span class="pink-text">Content-Type</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> 'application/json'</span>
                            </p>
                        </div>
                    </div>

                    <!-- Request Body -->
                    <div class="header-wrapper mt-4">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Request Body: </p>
                            <div class="d-flex gap-2 align-items-center">
                                <span>copy</span>
                                <button type="button" class="copy-btn" aria-label="Copy response">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                            fill="black" />
                                        <path
                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                            fill="black" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="box-content">
                            <p>

                                <span class="blue-text-color">{<br>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'amount'</span> <span
                                            class="pink-text me-2">:</span>"19.5",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'currency'</span> <span
                                            class="pink-text me-2">:</span>"USD",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'description'</span> <span
                                            class="pink-text me-2">:</span>"making first payment attempt",&nbsp;<br></span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'returnUrl'</span> <span
                                            class="pink-text me-2">:</span>"https://www.instagram.com"&nbsp;<br></span>
                                    <span>}</span><br>
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Response: 200 OK -->
                    <div class="header-wrapper mt-4">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Response: 200 OK</p>
                            <div class="d-flex gap-2 align-items-center">
                                <span>copy</span>
                                <button type="button" class="copy-btn" aria-label="Copy response">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                            fill="black" />
                                        <path
                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                            fill="black" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="box-content">
                            <p>
                                <span class="blue-text-color">{<br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"success":</span><span
                                        class="blue-text-color"> true,</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"amount":</span><span
                                        class="blue-text-color"> 19.5,</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"currency":</span><span
                                        class="blue-text-color"> "USD",</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"checkout_id":</span><span
                                        class="blue-text-color"> "151b3e4d-4149-4e8c-894a-46431d6986dc",</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"customerId":</span><span
                                        class="blue-text-color"> "ad4e6fca-12f8-4595-a22e-04992dc0ea2f",</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"payment_id":</span><span
                                        class="blue-text-color"> "72f73546-dbb9-43a7-8d36-d009d8035bff",</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"payment_status":</span><span
                                        class="blue-text-color"> "New",</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"paymentUrl":</span><span
                                        class="blue-text-color">
                                        "https://banking.valenspay.com/payment-gateway?orderId=72f73546-dbb9-43a7-8d36-d009d8035bff"</span><br>
                                    <span>}</span><br>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Empty spacer to align with step3-li -->
                <div id="step3" class="d-none d-lg-block"></div>

                <!-- Step 4: GetPaymentStatus (Desktop) -->
                <div id="step4" class="d-none d-lg-block pb-5">
                    <p class="px-2"><span class="fw-bold">GET: </span> <a
                            href="#">https://payment.ryzen-pay.com/api/payment/{accId}/p19/getPaymentStatus/{customerId}/{payment_id}</a>
                    </p>

                    <!-- Headers -->
                    <div class="header-wrapper mt-4">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Headers:</p>
                            <div class="d-flex gap-2 align-items-center">
                                <span>copy</span>
                                <button type="button" class="copy-btn" aria-label="Copy response">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                            fill="black" />
                                        <path
                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                            fill="black" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="box-content">
                            <p>
                                <span class="pink-text">Authorization</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> [ Bearer-Token ] </span>
                            </p>
                            <p>
                                <span class="pink-text">Content-Type</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> 'application/json'</span>
                            </p>
                            <p>
                                <span class="pink-text">Accept</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> 'application/json'</span>
                            </p>
                        </div>
                    </div>

                    <!-- Response: 200 OK -->
                    <div class="header-wrapper mt-4">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Response: 200 OK</p>
                            <div class="d-flex gap-2 align-items-center">
                                <span>copy</span>
                                <button type="button" class="copy-btn" aria-label="Copy response">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                            fill="black" />
                                        <path
                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                            fill="black" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="box-content">
                            <p>
                                <span class="blue-text-color">{<br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"success":</span><span
                                        class="blue-text-color"> true,</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"amount":</span><span
                                        class="blue-text-color"> 19.99,</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"currency":</span><span
                                        class="blue-text-color"> "USD",</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"checkout_id":</span><span
                                        class="blue-text-color"> "5fb1afdd-a2c8-4cbb-b0e6-6782404949f7",</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"customerId":</span><span
                                        class="blue-text-color"> "22d2dd45-b580-4454-852c-d6ef8de788c8",</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"payment_id":</span><span
                                        class="blue-text-color"> "3e354e41-3f04-47f3-a08d-41e5c0eaf114",</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"payment_status":</span><span
                                        class="blue-text-color"> "New",</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"paymentUrl":</span><span
                                        class="blue-text-color"> null,</span><br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"data":</span><span
                                        class="blue-text-color"> {</span><br>
                                    <span
                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"customerId":</span><span
                                        class="blue-text-color"> "22d2dd45-b580-4454-852c-d6ef8de788c8",</span><br>
                                    <span
                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"orderId":</span><span
                                        class="blue-text-color"> "3e354e41-3f04-47f3-a08d-41e5c0eaf114",</span><br>
                                    <span
                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"currency":</span><span
                                        class="blue-text-color"> "USD",</span><br>
                                    <span
                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"amount":</span><span
                                        class="blue-text-color"> 19.99,</span><br>
                                    <span
                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"correlationId":</span><span
                                        class="blue-text-color"> "5fb1afdd-a2c8-4cbb-b0e6-6782404949f7",</span><br>
                                    <span
                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"status":</span><span
                                        class="blue-text-color"> "New",</span><br>
                                    <span
                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"description":</span><span
                                        class="blue-text-color"> "Kahn",</span><br>
                                    <span
                                        class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"transferDate":</span><span
                                        class="blue-text-color"> "2026-02-25T11:12:16.318844"</span><br>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;}</span><br>
                                    <span>}</span><br>
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Response: 401 Unauthorized -->
                    <div class="header-wrapper mt-4">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Response: 401 Unauthorized</p>
                            <div class="d-flex gap-2 align-items-center">
                                <span>copy</span>
                                <button type="button" class="copy-btn" aria-label="Copy response">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.8416 2.60449H11.9476C10.1836 2.60449 8.78556 2.60449 7.69256 2.75249C6.56656 2.90449 5.65556 3.22449 4.93756 3.94549C4.21856 4.66649 3.89956 5.58149 3.74856 6.71149C3.60156 7.80949 3.60156 9.21249 3.60156 10.9835V16.8215C3.60156 18.3295 4.52156 19.6215 5.82856 20.1635C5.76156 19.2535 5.76156 17.9785 5.76156 16.9165V11.9065C5.76156 10.6255 5.76156 9.52049 5.87956 8.63649C6.00656 7.68849 6.29256 6.78049 7.02656 6.04349C7.76056 5.30649 8.66556 5.01949 9.60956 4.89149C10.4896 4.77349 11.5896 4.77349 12.8666 4.77349H15.9366C17.2126 4.77349 18.3106 4.77349 19.1916 4.89149C18.9277 4.21779 18.4669 3.63923 17.8693 3.23128C17.2717 2.82332 16.5651 2.6049 15.8416 2.60449Z"
                                            fill="black" />
                                        <path
                                            d="M7.20312 12.0013C7.20312 9.27526 7.20312 7.91226 8.04712 7.06526C8.89012 6.21826 10.2471 6.21826 12.9631 6.21826H15.8431C18.5581 6.21826 19.9161 6.21826 20.7601 7.06526C21.6041 7.91226 21.6031 9.27526 21.6031 12.0013V16.8213C21.6031 19.5473 21.6031 20.9103 20.7601 21.7573C19.9161 22.6043 18.5581 22.6043 15.8431 22.6043H12.9631C10.2481 22.6043 8.89012 22.6043 8.04712 21.7573C7.20312 20.9103 7.20312 19.5473 7.20312 16.8213V12.0013Z"
                                            fill="black" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="box-content">
                            <p>
                                <span class="blue-text-color">{<br>
                                    <span class="pink-text">&nbsp;&nbsp;&nbsp;&nbsp;"message":</span><span
                                        class="blue-text-color"> "Unauthorized Checkout Id or Transaction is not
                                        completed.",</span><br>
                                    <span>}</span><br>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script>
        (function() {
            document.addEventListener('click', async function(e) {
                const btn = e.target.closest('.copy-btn');
                if (!btn) return;

                const wrapper = btn.closest('.header-wrapper');
                const content = wrapper && wrapper.querySelector('.box-content');
                if (!content) return;

                let textToCopy = content.innerText
                    .replace(/\u00A0/g, ' ')
                    .replace(/[ \t]+$/gm, '')
                    .trim();

                try {
                    await copyText(textToCopy);
                } catch (err) {
                    console.error('Copy failed:', err);
                }
            });

            async function copyText(text) {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                }
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.setAttribute('readonly', '');
                ta.style.position = 'fixed';
                ta.style.top = '-9999px';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.focus();
                ta.select();
                ta.setSelectionRange(0, ta.value.length);
                const ok = document.execCommand('copy');
                document.body.removeChild(ta);
                if (!ok) throw new Error('execCommand failed');
            }
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function updateMinHeight() {
                const step1 = document.getElementById('step1');
                const step2 = document.getElementById('step2');
                const step3 = document.getElementById('step3');
                const step4 = document.getElementById('step4');
                const target1 = document.getElementById('step1-li');
                const target2 = document.getElementById('step2-li');
                const target3 = document.getElementById('step3-li');
                const target4 = document.getElementById('step4-li');

                if (!step1 || !step2 || !step3 || !step4 || !target1 || !target2 || !target3 || !target4) return;

                if (window.innerWidth > 1024) {
                    target1.style.minHeight = step1.offsetHeight + 'px';
                    target2.style.minHeight = step2.offsetHeight + 'px';
                    step3.style.minHeight = target3.offsetHeight + 'px';
                    target4.style.minHeight = step4.offsetHeight + 'px';
                } else {
                    target1.style.minHeight = '';
                    target2.style.minHeight = '';
                    step3.style.minHeight = '';
                    target4.style.minHeight = '';
                }
            }

            window.addEventListener('resize', updateMinHeight);
            window.addEventListener('load', updateMinHeight);

        });
    </script>
@endsection

@section('scripts')
@endsection
