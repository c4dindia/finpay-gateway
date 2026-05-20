@extends('layouts.clientMaster')

@section('title')
    Documentation > P1-Service
    @php
        $currentPage = 'Documentations';
    @endphp
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/clientside/p2service.css') }}">
@endsection

@section('page-content')
    <div class=" p-3">
        <!-- <h4 class="text-center"
                                                                                                                                                                                                                                                        style="Font-family:Poppins; font-weight: 400; font-size:40px; line-height: 60px; letter-spacing: 0%;"> <i
                                                                                                                                                                                                                                                            class="fa-regular fa-credit-card"></i>&nbsp;P1 Services</h4> -->
        <div class="col-xl-12 mt-3 d-flex flex-column flex-lg-row gap-5">
            <div class="left-section progress-vertical">
                <ol class="steps">
                    <li class="step completed">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 1 </h4>
                            <p class="pl-3 d-flex flex-column"><span>API for getting checkout ID</span> <br>
                                <span class="fw-bold">POST: </span> <a href="">
                                    https://payment.ryzen-pay.com/api/payment/{accId}/p1/checkout
                                </a>
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
                                            <span class="blue-text-color font-500"> [ Bearer Token ]</span>
                                        </p>
                                        <p>
                                            <span class="pink-text font-500">Content-Type</span>
                                            <span class="pink-text font-500">=</span>
                                            <span class="blue-text-color font-500"> 'application/json',</span>
                                        </p>
                                    </div>
                                </div>
                                <!-- ------------------req body:------------------- -->
                                <div class="header-wrapper mt-5">

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
                                            <span class="blue-text-color font-500">{<br>
                                                <span> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                    &nbsp;<span class="pink-text font-500">'amount'</span>   <span
                                                        class="pink-text font-500">=></span>
                                                    7.99,<br></span>
                                                <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                    &nbsp;<span class="pink-text font-500">'currency'</span> <span
                                                        class="pink-text font-500">=></span>
                                                    'GBP',<br></span>
                                                <span>
                                                    &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;}
                                                </span><br>
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- ------------------Response: for 200 ok------------------- -->
                                <div class="header-wrapper mt-5">

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
                                                <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp;
                                                    &nbsp;"accountId":</span><span class="blue-text-color font-500">
                                                    "ry6xxxxxxxxxxxxxxxxxxxxxXj4Aix2nWIkCVqi",</span><br>
                                                <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp;
                                                    &nbsp;"checkout_id":</span><span class="blue-text-color font-500">
                                                    "5B7F4F2863xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx4"</span><br>
                                                <span>
                                                    &nbsp;}
                                                </span><br>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>



                    <!-- step-2  -->
                    <li class="step completed mt-5 mt-lg-0 mb-5 mb-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 2: </h4><br> Redirect to this link for proceeding to payment page
                            with
                            checkout id
                            provided in step 1</p>
                            <p><span class="fw-bold">POST: </span> <a
                                    href="">https://payment.ryzen-pay.com/payment/p1/payment-page/{checkout_id} </a></p>
                        </div>
                    </li>

                    <li class="step completed mt-5 mt-lg-0 mb-5 mb-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 3: </h4><br>After the Payment is completed. The page will be
                            redirected
                            to this URL
                            ,i.e.,<span> <a href="#">
                                    &lt;your-base-url&gt;/RyzenPay/checkout-id/{checkout-id}</a></span>
                            </p>
                        </div>
                    </li>
                    <li class="step completed mt-5 mt-lg-0 mb-5 mb-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 4: </h4><br>You can call our GET Payment Status API to get
                            Transaction
                            Details.</p>
                            <p class="mb-0 fw-bold">Get Payment Status API:</p>
                            <p><span class="fw-bold">GET: </span> <a
                                    href=""><your-base-url>&lt;your-base-url&gt;api/payment/{accId}/p1/getPaymentStatus/{checkout_id}</a>
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
                                            <span class="blue-text-color font-500"> [ Bearer-Token ]</span>
                                        </p>
                                        <p>
                                            <span class="pink-text font-500">Content-Type</span>
                                            <span class="pink-text font-500">=</span>
                                            <span class="blue-text-color font-500"> 'application/json',</span>
                                        </p>
                                    </div>
                                </div>


                                <div class="header-wrapper mt-5">

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
                                </div>
                                <div class="box-content">
                                    <p>
                                        <span class="pink-text font-500">'data'</span>
                                        <span class="pink-text font-500">=></span>
                                        <span class="blue-text-color font-500">[<br>
                                            <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp; &nbsp;
                                                &nbsp;"amount"</span><span class="pink-text font-500">=></span><span
                                                class="blue-text-color font-500">
                                                string,</span><br>
                                            <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp; &nbsp;
                                                &nbsp;"currency"</span><span class="pink-text font-500">=></span><span
                                                class="blue-text-color font-500">
                                                string,</span><br>
                                            <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp; &nbsp;
                                                &nbsp;"accountId"</span><span class="pink-text font-500">=></span><span
                                                class="blue-text-color font-500">
                                                string,</span><br>
                                            <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp; &nbsp;
                                                &nbsp;"checkout_id"</span><span class="pink-text font-500">=></span><span
                                                class="blue-text-color font-500">string,</span><br>
                                            <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp; &nbsp;
                                                &nbsp;"payment_id"</span><span class="pink-text font-500">=></span><span
                                                class="blue-text-color font-500">
                                                string,</span><br>
                                            <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp; &nbsp;
                                                &nbsp;"payment_status" </span><span
                                                class="pink-text font-500"><span>=></span><span
                                                    class="blue-text-color font-500"> string,</span><br>
                                                <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp; &nbsp;
                                                    &nbsp;"description"</span><span
                                                    class="pink-text font-500">=></span><span
                                                    class="blue-text-color font-500">string,</span><br>
                                                <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp; &nbsp;
                                                    &nbsp;"created_at" </span><span
                                                    class="pink-text font-500">=></span><span
                                                    class="blue-text-color font-500">string,</span><br>

                                                <span class="blue-text-color font-500">
                                                    &nbsp; &nbsp;]
                                                </span><br>
                                            </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- extra div to extend progress line -->
                    <div>

                    </div>
                </ol>


            </div>

            <!-- Right section -->
            <div class="right-section">
                <!-- --------------------Headers:------------------ -->
                <div id="step1" class="d-none d-lg-block ">
                    <div class="px-2 group-1">
                        <p> <span class="fw-bold">POST: </span> <a href="">
                                https://payment.ryzen-pay.com/api/payment/{accId}/p1/checkout </a>
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
                                <span class="blue-text-color font-500"> [ Bearer Token ]</span>
                            </p>
                            <p>
                                <span class="pink-text font-500">Content-Type</span>
                                <span class="pink-text font-500">=</span>
                                <span class="blue-text-color font-500"> 'application/json',</span>
                            </p>
                        </div>
                    </div>
                    <!-- ------------------req body:------------------- -->
                    <div class="header-wrapper mt-5">

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
                                <span class="blue-text-color font-500">{<br>
                                    <span> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<span
                                            class="pink-text font-500">'amount'</span>   <span
                                            class="pink-text font-500">=></span>
                                        7.99,<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<span
                                            class="pink-text font-500">'currency'</span> <span
                                            class="pink-text font-500">=></span>
                                        'GBP',<br></span>
                                    <span>
                                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;}
                                    </span><br>
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- ------------------Response: for 200 ok------------------- -->
                    <div class="header-wrapper mt-5">

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
                                <span class="blue-text-color font-500">{<br>
                                    <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"amount":</span><span class="blue-text-color font-500"> "7.99",</span><br>
                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"currency":</span><span class="blue-text-color font-500"> "GBP",</span><br>
                                    <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"accountId":</span><span class="blue-text-color font-500">
                                        "ry6xxxxxxxxxxxxxxxxxxxxxXj4Aix2nWIkCVqi",</span><br>
                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"checkout_id":</span><span class="blue-text-color font-500">
                                        "5B7F4F2863xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx4"</span><br>
                                    <span>
                                        &nbsp;}
                                    </span><br>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- step2  -->
                <!-- --------------------Headers:------------------ -->
                <div id="step2" class="d-none d-lg-block ">
                    <div class="px-2 mt-5 pt-5">
                        <p> <span class="fw-bold">GET: </span> <a
                                href=""><your-base-url>&lt;your-base-url&gt;api/payment/{accId}/p1/getPaymentStatus/{checkout_id}</a>
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
                                <span class="blue-text-color font-500"> 'application/json',</span>
                            </p>
                        </div>
                    </div>


                    <div class="header-wrapper mt-5">

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
                                <span class="pink-text font-500">'data'</span>
                                <span class="pink-text font-500">=></span>
                                <span class="blue-text-color font-500">[<br>
                                    <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp;
                                        &nbsp;"amount"</span><span class="pink-text font-500">=></span><span
                                        class="blue-text-color font-500">
                                        string,</span><br>
                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp;
                                        &nbsp;"currency"</span><span class="pink-text font-500">=></span><span
                                        class="blue-text-color font-500">
                                        string,</span><br>
                                    <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp;
                                        &nbsp;"accountId"</span><span class="pink-text font-500">=></span><span
                                        class="blue-text-color font-500">
                                        string,</span><br>
                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp;
                                        &nbsp;"checkout_id"</span><span class="pink-text font-500">=></span><span
                                        class="blue-text-color font-500">string,</span><br>
                                    <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp;
                                        &nbsp;"payment_id"</span><span class="pink-text font-500">=></span><span
                                        class="blue-text-color font-500">
                                        string,</span><br>
                                    <span class="pink-text font-500"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp;
                                        &nbsp;"payment_status" </span><span class="pink-text font-500"><span>=></span><span
                                            class="blue-text-color font-500"> string,</span><br>
                                        <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp; &nbsp;
                                            &nbsp;"description"</span><span class="pink-text font-500">=></span><span
                                            class="blue-text-color font-500">string,</span><br>
                                        <span class="pink-text font-500">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp; &nbsp;
                                            &nbsp;"created_at" </span><span class="pink-text font-500">=></span><span
                                            class="blue-text-color font-500">string,</span><br>

                                        <span class="blue-text-color font-500">
                                            &nbsp; &nbsp;]
                                        </span><br>
                                    </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                } catch (err) {
                    console.error('Copy failed:', err);
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