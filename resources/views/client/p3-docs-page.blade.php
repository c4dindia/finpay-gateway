@extends('layouts.clientMaster')

@section('title')
    Documentation > P3-Service
    @php
        $currentPage = 'Documentations';
    @endphp
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/clientside/p2service.css') }}">
@endsection

@section('page-content')
    <!-- <div>
                                                                                                    <h4 class="text-center"
                                                                                                        style="Font-family:Poppins; font-weight: 400; font-size:40px; line-height: 60px; letter-spacing: 0%;"> <i
                                                                                                            class="fa-regular fa-credit-card"></i>&nbsp;P3 Services</h4>

                                                                                                </div> -->
    <div class="p-3 overflow-x-hidden">
        <div class="col-xl-12 mt-3 d-flex flex-column flex-lg-row gap-5">
            <div class="left-section progress-vertical">
                <ol class="steps">

                    <!-- Step 1 -->
                    <li class="step completed">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 1</h4>
                            <p class="pl-3">API for creating checkout ID and link. <br>
                                <span class="fw-bold">NOTE:</span> All parameters in request are required.
                            </p>
                            <p><span class="fw-bold">POST: </span>
                                <a href="#">https://payment.ryzen-pay.com/api/payment/{accId}/p3/checkout</a>
                            </p>
                            <div id="step1" class="d-lg-none">


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
                                            <span class="pink-text font-500">Authorization</span>
                                            <span class="pink-text font-500">=</span>
                                            <span class="blue-text-color font-500"> [ Bearer-Token ]</span>
                                        </p>
                                        <p>
                                            <span class="pink-text font-500">Content-Type</span>
                                            <span class="pink-text font-500">=</span>
                                            <span class="blue-text-color font-500"> 'application/json'</span>
                                        </p>
                                    </div>
                                </div>
                                <!-- ------------------req body:------------------- -->
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
                                            <span class="pink-text font-500">'json'</span>
                                            <span class="pink-text font-500">=></span>
                                            <span class="blue-text-color font-500">[<br>
                                                <span> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                    &nbsp;<span class="pink-text font-500">'amount'</span>   <span
                                                        class="pink-text font-500">=></span> 7.99,<br></span>
                                                <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                    &nbsp;<span class="pink-text font-500">'currency'</span> <span
                                                        class="pink-text font-500">=></span>
                                                    'GBP',<br></span>
                                                <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                    &nbsp;<span class="pink-text font-500">'email'</span> <span
                                                        class="pink-text font-500">=></span>
                                                    'monster288@gmail.com',<br></span>
                                                <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                    &nbsp;<span class="pink-text font-500">'first_name'</span> <span
                                                        class="pink-text font-500">=></span>
                                                    'Poppy',<br></span>
                                                <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                    &nbsp;<span class="pink-text font-500">'last_name'</span> <span
                                                        class="pink-text font-500">=></span>
                                                    'Chao',<br></span>
                                                <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                    &nbsp;<span class="pink-text font-500">'nationality'</span> <span
                                                        class="pink-text font-500">=></span>
                                                    'IND',<br></span>
                                                <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                    &nbsp;<span class="pink-text font-500">'country_of_residence'</span>
                                                    <span class="pink-text font-500">=></span>
                                                    'GBR',<br></span>
                                                <span>
                                                    &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;]
                                                </span><br>
                                            </span>
                                        </p>
                                    </div>

                                </div>
                                <!-- ------------------Response: for 200 ok------------------- -->

                                <div class="header-wrapper mt-4">
                                    <div class="box-header d-flex justify-content-between">
                                        <p class="mb-0 font-500">Response: 200 OK </p>
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
                                            <!-- <span class="pink-text font-500">'json'</span> -->
                                            <!-- <span class="pink-text font-500">=></span>  -->
                                            <span class="blue-text-color font-500">{<br>
                                                <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp;
                                                    &nbsp;"amount":</span><span class="blue-text-color font-500">
                                                    "7.99",</span><br>
                                                <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp;
                                                    &nbsp;"currency":</span><span class="blue-text-color font-500">
                                                    "GBP",</span><br>
                                                <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp;
                                                    &nbsp;"checkout_id":</span><span class="blue-text-color font-500">
                                                    "1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1"</span><br>
                                                <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp;
                                                    &nbsp;"link":</span><span class="blue-text-color font-500">
                                                    "https://payment.ryzen-pay.com/payment/p3/payment-page/1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1",</span><br>

                                                <span>
                                                    &nbsp; &nbsp;}
                                                </span><br>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Step 2 -->
                    <li class="step completed mt-5 mt-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 2</h4>
                            <p>Redirect to the link provided in response for proceeding to payment page in Step 1.</p>
                            <!--
                                                                                                                                                                  <p><span class="fw-bold"></span>
                                                                                                                                                                    <a href="#">https://payment.ryzen-pay.com/payment/p2/payment-page/{checkout_id}</a>
                                                                                                                                                                  </p>
                                                                                                                                                                  -->
                        </div>
                    </li>

                    <!-- Step 3 (Webhook) -->
                    <li class="step completed">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 3</h4>
                            <p>When transaction process is completed, we will send a Webhook request to you containing the
                                <code>{checkout_id}</code> of the transaction. The webhook request will be sent in the
                                following format:
                            </p>
                            <p><span class="fw-bold">GET: </span>
                                <a href="#">https://&lt;your-base-url&gt;/api/RyzenPay/p3/{checkout_id}</a>
                            </p>
                            <p><span class="fw-bold">EXAMPLE: </span>
                                https://www.examplesite.com/api/RyzenPay/p3/1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1
                            </p>
                            <p><span class="fw-bold">In Headers,</span></p>
                            <div class="box-content">
                                <p>
                                    <span class="pink-text font-500">Authorization</span>
                                    <span class="pink-text font-500">=</span>
                                    <span class="blue-text-color font-500">[ Bearer-Token ]</span>
                                </p>
                                <p>
                                    <span class="pink-text font-500">Content-Type</span>
                                    <span class="pink-text font-500">=</span>
                                    <span class="blue-text-color font-500">'application/json'</span>
                                </p>
                            </div>
                            <p class="mt-3">In this request, you can call our GetPaymentStatus API to retrieve Transaction
                                Details
                                for the <code>{checkout_id}</code>. Make sure the request always returns
                                <span class="fw-bold">200 STATUS</span>.
                            </p>
                        </div>
                    </li>

                    <!-- GetPaymentStatus -->
                    <li class="step completed mt-5 mt-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">GetPaymentStatus API</h4>
                            <p class="mb-0">Get the status and details of the transaction using the
                                <code>{checkout_id}</code>.
                            </p>
                            <p><span class="fw-bold">GET: </span>
                                <a href="#">
                                    https://payment.ryzen-pay.com/api/payment/{accId}/p3/getPaymentStatus/{checkout_id}
                                </a>

                            </p>
                            <div id="step2" class="d-lg-none ">
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
                                            <span class="pink-text font-500">Authorization</span>
                                            <span class="pink-text font-500">=</span>
                                            <span class="blue-text-color font-500"> [ Bearer-Token ] </span>
                                        </p>
                                        <p>
                                            <span class="pink-text font-500">Content-Type</span>
                                            <span class="pink-text font-500">=</span>
                                            <span class="blue-text-color font-500"> 'application/json'</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- response 200 ok if transaction is completed -->
                                <div class="header-wrapper mt-4">
                                    <div class="box-header d-flex justify-content-between">
                                        <p class="mb-0 font-500">Response: 200 OK (If Transaction process is completed)</p>
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
                                            <!-- <span class="pink-text font-500">'data'</span> -->
                                            <!-- <span class="pink-text font-500">=></span>  -->
                                            <span class="blue-text-color font-500">{<br>
                                                <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp;
                                                    &nbsp;"amount"</span><span class="pink-text font-500"> => </span><span
                                                    class="blue-text-color font-500">
                                                    string,</span><br>
                                                <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp; &nbsp;
                                                    &nbsp;"currency"</span><span class="pink-text font-500"> =>
                                                </span><span class="blue-text-color font-500">
                                                    string,</span><br>
                                                {{-- <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp;
                                                    &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp;
                                                    &nbsp;"accountId"</span><span class="pink-text font-500"> =>
                                                </span><span class="blue-text-color font-500">
                                                    string,</span><br> --}}
                                                <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp; &nbsp;
                                                    &nbsp;"checkout_id"</span><span class="pink-text font-500"> =
                                                    ></span><span class="blue-text-color font-500">string,</span><br>
                                                <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp; &nbsp;
                                                    &nbsp;"payment_id"</span><span class="pink-text font-500"> =>
                                                </span><span class="blue-text-color font-500"> string,</span><br>
                                                <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp;
                                                    &nbsp;"payment_status" </span><span class="pink-text font-500"><span>
                                                        =>
                                                    </span><span class="blue-text-color font-500"> string,</span><br>
                                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp;
                                                        &nbsp;"description"</span><span class="pink-text font-500"> =>
                                                    </span><span class="blue-text-color font-500">string,</span><br>
                                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp;
                                                        &nbsp;"created_at" </span><span class="pink-text font-500"> =>
                                                    </span><span class="blue-text-color font-500">timestamp,</span><br>

                                                    <span class="blue-text-color font-500">
                                                        &nbsp;}
                                                    </span><br>
                                                    <!-- <span> &nbsp; &nbsp;Today</span> -->
                                                </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- response 401 -->
                                <div class="header-wrapper mt-4">
                                    <div class="box-header d-flex justify-content-between">
                                        <p class="mb-0 font-500">Response: 401 Unauthorized (If Transaction process didn't
                                            complete)
                                        </p>
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
                                            <!-- <span class="pink-text font-500">'data'</span> -->
                                            <!-- <span class="pink-text font-500">=></span>  -->
                                            <span class="blue-text-color font-500">{<br>
                                                <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp;
                                                    &nbsp;"message"</span><span class="pink-text font-500"> =>
                                                </span><span class="blue-text-color font-500">
                                                    "Unauthorized Checkout Id or Transaction is not completed.",</span><br>
                                                <span class="blue-text-color font-500">
                                                    &nbsp;}
                                                </span><br>
                                                <!-- <span> &nbsp; &nbsp;Today</span> -->
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- list of countries -->

                    <div>
                        <h4 class="fw-bold mt-4 ">List of Valid Country Codes for Rationality & Residence </h4>
                        <div class="text-center">
                            <button class="expand-button font-500" id="expand-btn">Click to Expand Country List</button>
                            <button class="hidden font-500" id="close-btn">Click to Close Country List</button>
                        </div>


                        <div class="d-flex w-100 justify-content-center align-items-center">

                            <div class="country-grid collapse-content ms-5 pe-4 pe-sm-0 ps-3 ms-sm-0 ps-sm-0"
                                id="country-list">
                                <!-- Column 1 -->
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="font-500">ALB:</td>
                                            <td>Albania</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">DZA:</td>
                                            <td>Algeria</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">AND:</td>
                                            <td>Andorra</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">AGO:</td>
                                            <td>Angola</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">AIA:</td>
                                            <td>Anguilla</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">ATG:</td>
                                            <td>Antigua and Barbuda</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">ARG:</td>
                                            <td>Argentina</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">ARM:</td>
                                            <td>Armenia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">AUS:</td>
                                            <td>Australia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">AUT:</td>
                                            <td>Austria</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">AZE:</td>
                                            <td>Azerbaijan</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BHS:</td>
                                            <td>Bahamas</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BHR:</td>
                                            <td>Bahrain</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BGD:</td>
                                            <td>Bangladesh</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BRB:</td>
                                            <td>Barbados</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BEL:</td>
                                            <td>Belgium</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BLZ:</td>
                                            <td>Belize</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BEN:</td>
                                            <td>Benin</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BMU:</td>
                                            <td>Bermuda</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BTN:</td>
                                            <td>Bhutan</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BOL:</td>
                                            <td>Bolivia, Plurinational State of</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BIH:</td>
                                            <td>Bosnia and Herzegovina</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BWA:</td>
                                            <td>Botswana</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BVT:</td>
                                            <td>Bouvet Island</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BRA:</td>
                                            <td>Brazil</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">IOT:</td>
                                            <td>British Indian Ocean Territory</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BRN:</td>
                                            <td>Brunei Darussalam</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">BGR:</td>
                                            <td>Bulgaria</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">KHM:</td>
                                            <td>Cambodia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">CPV:</td>
                                            <td>Cabo Verde</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">CYM:</td>
                                            <td>Cayman Islands</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Column 2 -->
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="font-500">CHL:</td>
                                            <td>Chile</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">CHN:</td>
                                            <td>China</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">CXR:</td>
                                            <td>Christmas Island</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">CCK:</td>
                                            <td>Cocos (Keeling) Islands</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">COL:</td>
                                            <td>Colombia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">COK:</td>
                                            <td>Cook Islands</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">CRI:</td>
                                            <td>Costa Rica</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">HRV:</td>
                                            <td>Croatia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">CYP:</td>
                                            <td>Cyprus</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">CZE:</td>
                                            <td>Czechia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">DNK:</td>
                                            <td>Denmark</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">DJI:</td>
                                            <td>Djibouti</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">DMA:</td>
                                            <td>Dominica</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">DOM:</td>
                                            <td>Dominican Republic</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">ECU:</td>
                                            <td>Ecuador</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">EGY:</td>
                                            <td>Egypt</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">SLV:</td>
                                            <td>El Salvador</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GNQ:</td>
                                            <td>Equatorial Guinea</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">EST:</td>
                                            <td>Estonia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">ETH:</td>
                                            <td>Ethiopia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">FLK:</td>
                                            <td>Falkland Islands (Malvinas)</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">FRO:</td>
                                            <td>Faroe Islands</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">FJI:</td>
                                            <td>Fiji</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">FIN:</td>
                                            <td>Finland</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">FRA:</td>
                                            <td>France</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">FXX:</td>
                                            <td>Unknown</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GUF:</td>
                                            <td>French Guiana</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">ATF:</td>
                                            <td>French Southern Territories</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GAB:</td>
                                            <td>Gabon</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GEO:</td>
                                            <td>Georgia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">DEU:</td>
                                            <td>Germany</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Column 3 -->
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="font-500">GHA:</td>
                                            <td>Ghana</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GIB:</td>
                                            <td>Gibraltar</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GGY:</td>
                                            <td>Guernsey</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GRC:</td>
                                            <td>Greece</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GRL:</td>
                                            <td>Greenland</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GRD:</td>
                                            <td>Grenada</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GLP:</td>
                                            <td>Guadeloupe</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GTM:</td>
                                            <td>Guatemala</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GIN:</td>
                                            <td>Guinea</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">GUY:</td>
                                            <td>Guyana</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">HMD:</td>
                                            <td>Heard Island and McDonald Islands</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">HND:</td>
                                            <td>Honduras</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">HKG:</td>
                                            <td>Hong Kong</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">HUN:</td>
                                            <td>Hungary</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">ISL:</td>
                                            <td>Iceland</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">IND:</td>
                                            <td>India</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">IMN:</td>
                                            <td>Isle of Man</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">IDN:</td>
                                            <td>Indonesia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">IRL:</td>
                                            <td>Ireland</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">ISR:</td>
                                            <td>Israel</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">ITA:</td>
                                            <td>Italy</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">JEY:</td>
                                            <td>Jersey</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">JAM:</td>
                                            <td>Jamaica</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">JOR:</td>
                                            <td>Jordan</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">KAZ:</td>
                                            <td>Kazakhstan</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">KEN:</td>
                                            <td>Kenya</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">KOR:</td>
                                            <td>Korea, Republic of</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">XKX:</td>
                                            <td>Unknown</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">KWT:</td>
                                            <td>Kuwait</td>
                                        </tr>
                                        <tr>
                                            <td class="font-500">KGZ:</td>
                                            <td>Kyrgyzstan</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>



                        </div>
                    </div>
                </ol>
            </div>



            <div class="right-section">
                <!-- ------------   --------Headers:------------------ -->

                <div id="step1" class="d-none d-lg-block ">

                    <div class="px-2 group-1">
                        <p><span class="fw-bold">POST: </span>
                            <a href="#">https://payment.ryzen-pay.com/api/payment/{accId}/p3/checkout</a>
                        </p>
                    </div>
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
                                <span class="pink-text font-500">Authorization</span>
                                <span class="pink-text font-500">=</span>
                                <span class="blue-text-color font-500"> [ Bearer-Token ]</span>
                            </p>
                            <p>
                                <span class="pink-text font-500">Content-Type</span>
                                <span class="pink-text font-500">=</span>
                                <span class="blue-text-color font-500"> 'application/json'</span>
                            </p>
                        </div>
                    </div>
                    <!-- ------------------req body:------------------- -->
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
                                <span class="pink-text font-500">'json'</span>
                                <span class="pink-text font-500">=></span>
                                <span class="blue-text-color font-500">[<br>
                                    <span> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<span
                                            class="pink-text font-500">'amount'</span>   <span
                                            class="pink-text font-500">=></span> 7.99,<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<span
                                            class="pink-text font-500">'currency'</span> <span
                                            class="pink-text font-500">=></span>
                                        'GBP',<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<span
                                            class="pink-text font-500">'email'</span> <span
                                            class="pink-text font-500">=></span>
                                        'monster288@gmail.com',<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<span
                                            class="pink-text font-500">'first_name'</span> <span
                                            class="pink-text font-500">=></span>
                                        'Poppy',<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<span
                                            class="pink-text font-500">'last_name'</span> <span
                                            class="pink-text font-500">=></span>
                                        'Chao',<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<span
                                            class="pink-text font-500">'nationality'</span> <span
                                            class="pink-text font-500">=></span>
                                        'IND',<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<span
                                            class="pink-text font-500">'country_of_residence'</span> <span
                                            class="pink-text font-500">=></span>
                                        'GBR',<br></span>
                                    <span>
                                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;]
                                    </span><br>
                                </span>
                            </p>
                        </div>

                    </div>
                    <!-- ------------------Response: for 200 ok------------------- -->

                    <div class="header-wrapper mt-4">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Response: 200 OK </p>
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
                                <!-- <span class="pink-text font-500">'json'</span> -->
                                <!-- <span class="pink-text font-500">=></span>  -->
                                <span class="blue-text-color font-500">{<br>
                                    <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"amount":</span><span class="blue-text-color font-500"> "7.99",</span><br>
                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"currency":</span><span class="blue-text-color font-500"> "GBP",</span><br>
                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"checkout_id":</span><span class="blue-text-color font-500">
                                        "1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1"</span><br>
                                    <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"link":</span><span class="blue-text-color font-500">
                                        "https://payment.ryzen-pay.com/payment/p3/payment-page/1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1",</span><br>

                                    <span>
                                        &nbsp; &nbsp;}
                                    </span><br>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- step-2  -->
                <!-- <div class="mt-4">
                                                                                                                                                                                            <p>
                                                                                                                                                                                            <h4 class="fw-bold">Step 2: </h4><br> Redirect to the link provided in reponse for proceeding to payment
                                                                                                                                                                                            page in Step 1</p>
                                                                                                                                                                                            {{-- <p><span class="fw-bold"> </span> <a href="#">
                                                                                                                                                                                                    https://payment.ryzen-pay.com/payment/p2/payment-page/{checkout_id}</a></p> --}}
                                                                                                                                                                                        </div> -->

                <!-- <div class="mt-4">
                                                                                                                                                                                            <p>
                                                                                                                                                                                            <h4 class="fw-bold">Step 3: </h4><br>When transaction process is completed, we will send a Webhook request
                                                                                                                                                                                            to you, containing the {checkout_id} of the transaction . The webhook request will be sent in the following
                                                                                                                                                                                            format:</p>
                                                                                                                                                                                            <p><span class="fw-bold">GET: </span> <a
                                                                                                                                                                                                    href="#">https://&lt;your-base-url&gt;/api/RyzenPay/p3/{checkout_id}</a></p>
                                                                                                                                                                                            <p><span class="fw-bold">EXAMPLE: </span>
                                                                                                                                                                                                https://www.examplesite.com/api/RyzenPay/p3/1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1</p>
                                                                                                                                                                                            <p><span class="fw-bold">In Headers, </span> </p>
                                                                                                                                                                                            <div class="header-wrapper">
                                                                                                                                                                                                <p>
                                                                                                                                                                                                    <span class="pink-text font-500">Authorization</span>
                                                                                                                                                                                                    <span class="pink-text font-500">=</span>
                                                                                                                                                                                                    <span class="blue-text-color font-500"> [ Bearer-Token ] </span>
                                                                                                                                                                                                </p>
                                                                                                                                                                                                <p>
                                                                                                                                                                                                    <span class="pink-text font-500">Content-Type</span>
                                                                                                                                                                                                    <span class="pink-text font-500">=</span>
                                                                                                                                                                                                    <span class="blue-text-color font-500"> 'application/json'</span>
                                                                                                                                                                                                </p>
                                                                                                                                                                                            </div> <br>
                                                                                                                                                                                            <p> In this request, you can call our GetPaymentStatus API to retrieve Transaction Details for the
                                                                                                                                                                                                checkout_id. Make sure, the request should always return <span class="fw-bold">200 STATUS.</span></p>
                                                                                                                                                                                        </div> -->
                <!-- <div class="mt-4">
                                                                                                                                                                                        <p>
                                                                                                                                                                                        <h4 class="fw-bold">GetPaymentStatus API </h4>
                                                                                                                                                                                        </p>
                                                                                                                                                                                        <p class="mb-0">Get the status and details of the transaction using the <span>{checkout_id}</span> .</p>
                                                                                                                                                                                        <p><span class="fw-bold">GET: </span> <a
                                                                                                                                                                                                href=""><your-base-url>https://payment.ryzen-pay.com/api/payment/{accId}/p3/getPaymentStatus/{checkout_id}</a>
                                                                                                                                                                                        </p>
                                                                                                                                                                                    </div> -->

                <!-- --------------------Headers 2:------------------ -->

                <div id="step2" class="d-none d-lg-block ">


                    <div class="px-2 bottom-headers">
                        <p><span class="fw-bold">GET: </span>
                            <a href="#">https://&lt;your-base-url&gt;/api/RyzenPay/p3/{checkout_id}</a>
                        </p>
                    </div>
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
                                <span class="pink-text font-500">Authorization</span>
                                <span class="pink-text font-500">=</span>
                                <span class="blue-text-color font-500"> [ Bearer-Token ] </span>
                            </p>
                            <p>
                                <span class="pink-text font-500">Content-Type</span>
                                <span class="pink-text font-500">=</span>
                                <span class="blue-text-color font-500"> 'application/json'</span>
                            </p>
                        </div>
                    </div>

                    <!-- response 200 ok if transaction is completed -->
                    <div class="header-wrapper mt-4">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Response: 200 OK (If Transaction process is completed)</p>
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
                                <!-- <span class="pink-text font-500">'data'</span> -->
                                <!-- <span class="pink-text font-500">=></span>  -->
                                <span class="blue-text-color font-500">{<br>
                                    <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"amount"</span><span class="pink-text font-500"> => </span><span
                                        class="blue-text-color font-500">
                                        string,</span><br>
                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp;
                                        &nbsp;"currency"</span><span class="pink-text font-500"> => </span><span
                                        class="blue-text-color font-500">
                                        string,</span><br>
                                    {{-- <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"accountId"</span><span class="pink-text font-500"> => </span><span
                                        class="blue-text-color font-500">
                                        string,</span><br> --}}
                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp;
                                        &nbsp;"checkout_id"</span><span class="pink-text font-500"> = ></span><span
                                        class="blue-text-color font-500">string,</span><br>
                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp;
                                        &nbsp;"payment_id"</span><span class="pink-text font-500"> => </span><span
                                        class="blue-text-color font-500"> string,</span><br>
                                    <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"payment_status" </span><span class="pink-text font-500"><span> =>
                                        </span><span class="blue-text-color font-500"> string,</span><br>
                                        <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp;
                                            &nbsp;"description"</span><span class="pink-text font-500"> =>
                                        </span><span class="blue-text-color font-500">string,</span><br>
                                        <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp;
                                            &nbsp;"created_at" </span><span class="pink-text font-500"> =>
                                        </span><span class="blue-text-color font-500">timestamp,</span><br>

                                        <span class="blue-text-color font-500">
                                            &nbsp;}
                                        </span><br>
                                        <!-- <span> &nbsp; &nbsp;Today</span> -->
                                    </span>
                            </p>
                        </div>
                    </div>

                    <!-- response 401 -->
                    <div class="header-wrapper mt-4">
                        <div class="box-header d-flex justify-content-between">
                            <p class="mb-0 font-500">Response: 401 Unauthorized (If Transaction process didn't complete)
                            </p>
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
                                <!-- <span class="pink-text font-500">'data'</span> -->
                                <!-- <span class="pink-text font-500">=></span>  -->
                                <span class="blue-text-color font-500">{<br>
                                    <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"message"</span><span class="pink-text font-500"> => </span><span
                                        class="blue-text-color font-500">
                                        "Unauthorized Checkout Id or Transaction is not completed.",</span><br>
                                    <span class="blue-text-color font-500">
                                        &nbsp;}
                                    </span><br>
                                    <!-- <span> &nbsp; &nbsp;Today</span> -->
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <script>
            let expandBtn = document.getElementById('expand-btn');
            let closeBtn = document.getElementById('close-btn');
            let countryList = document.getElementById('country-list');
            expandBtn.addEventListener('click', () => {
                expandBtn.classList.toggle('hidden');
                expandBtn.classList.toggle('expand-button');
                closeBtn.classList.toggle('hidden');
                closeBtn.classList.toggle('expand-button');
                countryList.classList.toggle('show');

            })
            closeBtn.addEventListener('click', () => {
                expandBtn.classList.toggle('hidden');
                expandBtn.classList.toggle('expand-button');
                closeBtn.classList.toggle('hidden');
                closeBtn.classList.toggle('expand-button');
                countryList.classList.toggle('show');
            })
        </script>


        <script>
                (function () {
                    // Delegate clicks for all .copy-btn (even if duplicated)
                    document.addEventListener('click', async function (e) {
                        const btn = e.target.closest('.copy-btn');
                        if (!btn) return;

                        const wrapper = btn.closest('.header-wrapper');
                        const content = wrapper && wrapper.querySelector('.box-content');
                        if (!content) return;

                        // Plain text with original line breaks/spacing
                        let textToCopy = content.innerText
                            .replace(/\u00A0/g, ' ')       // NBSP -> space
                            .replace(/[ \t]+$/gm, '')      // trim line-end spaces
                            .trim();

                        try {
                            await copyText(textToCopy);
                            // Optional: toast/snackbar
                            // showToast('Copied!');
                        } catch (err) {
                            console.error('Copy failed:', err);
                            // showToast('Copy failed. Long-press to select.');
                        }
                    });

                    async function copyText(text) {
                        // Modern path (requires HTTPS + user gesture; iOS 16+ works)
                        if (navigator.clipboard && window.isSecureContext) {
                            return navigator.clipboard.writeText(text);
                        }
                        // Fallback: hidden textarea (works on iOS/Safari)
                        const ta = document.createElement('textarea');
                        ta.value = text;
                        ta.setAttribute('readonly', '');
                        ta.style.position = 'fixed';
                        ta.style.top = '-9999px';
                        ta.style.opacity = '0';
                        document.body.appendChild(ta);
                        ta.focus();
                        ta.select();
                        ta.setSelectionRange(0, ta.value.length); // iOS
                        const ok = document.execCommand('copy');   // legacy path
                        document.body.removeChild(ta);
                        if (!ok) throw new Error('execCommand failed');
                    }
                })();
        </script>


@endsection

    @section('scripts')
    @endsection