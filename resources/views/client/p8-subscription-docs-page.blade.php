@extends('layouts.clientMaster')

@section('title')
    Documentation > P8-Subscription
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

                    <!-- Step 1 -->
                    <li class="step completed" id="step1-li">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 1 </h4>
                            <p class="pl-3"> API for creating checkout ID and link. <br>
                                <span class="fw-bold"> NOTE:</span>
                            <ul style="list-style-type: disc;">
                                <li class="fw-semibold">All parameters in request are required.</li>
                                <li class="fw-semibold">Verify card API is required only once for a card.</li>
                                <li class="fw-semibold">Once you get the stored card ID, you can use it in the subscription
                                    pay API.</li>

                            </ul>
                            </p>
                            <p><span class="fw-bold">POST: </span> <a href="#">
                                    https://payment.ryzen-pay.com/api/payment/{accId}/p8/subscription/verify-card </a></p>



                            <!-- --------------------Headers:------------------ -->
                            <div id="step1-mob" class="d-lg-none">


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
                                            <span class="pink-text">Accept</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> 'application/json'</span>
                                        </p>
                                        <p>
                                            <span class="pink-text">Content-Type</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> 'application/json'</span>
                                        </p>
                                    </div>


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
                                                    <span> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"currency"</span> <span
                                                            class="pink-text">:</span>
                                                        "EUR",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"number"</span> <span
                                                            class="pink-text">:</span>
                                                        "4012888888881881",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"expiryMonth"</span> <span
                                                            class="pink-text">:</span> "12",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"expiryYear"</span> <span
                                                            class="pink-text">:</span> "2030",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"cvv"</span> <span
                                                            class="pink-text">:</span>
                                                        "123",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"email"</span> <span
                                                            class="pink-text">:</span>
                                                        "bobby@c4g.co.uk",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"birthday"</span> <span
                                                            class="pink-text">:</span> "1988-06-28",<span
                                                            class="comment-text">
                                                            //yyyy-MM-dd</span><br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"billingFirstName"</span> <span
                                                            class="pink-text">:</span>
                                                        "Bobby",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"billingLastName"</span> <span
                                                            class="pink-text">:</span>
                                                        "Poonia",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"billingAddress1"</span> <span
                                                            class="pink-text">:</span> "8 bankart avenue",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"billingCity"</span> <span
                                                            class="pink-text">:</span> "oadby",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text"> "billingPostcode"</span> <span
                                                            class="pink-text">:</span> "LE22DB",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"billingCountry"</span> <span
                                                            class="pink-text">:</span> "GB",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"billingAddress"</span> <span
                                                            class="pink-text">:</span> "second street",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"state"</span> <span
                                                            class="pink-text">:</span>
                                                        "London",<br></span>
                                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span class="pink-text">"returnUrl"</span> <span
                                                            class="pink-text">:</span> "https://www.abc.com",<br></span>
                                                    <span class="comment-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span>//"customerIp"</span> <span>:</span>
                                                        "192.168.254.23",// optional,<br></span>
                                                    <span class="comment-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp; &nbsp;
                                                        &nbsp;<span>//"customerUserAgent"</span> <span
                                                            class="comment-text">:</span> "string",//
                                                        optional,<br></span>

                                                    }
                                                </span><br>

                                            </p>
                                        </div>
                                    </div>

                                    <!-- ------------------Response: for 200 ok------------------- -->

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
                                                <!-- <span class="pink-text">'json'</span> -->
                                                <!-- <span class="pink-text">:</span>  -->
                                                <span class="blue-text-color">{<br>
                                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp; &nbsp;
                                                        &nbsp;"success":</span><span class="blue-text-color">
                                                        "true",</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp;
                                                        &nbsp;"message":</span><span class="blue-text-color">
                                                        "Waiting",</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp;
                                                        &nbsp;"form3d":</span><span class="blue-text-color">
                                                        "https://payment.ryzen-pay.com/card/verify/redirect-url"</span><br>
                                                    <span>}
                                                    </span><br>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3">
                                <span>
                                    Redirect to the link provided in reponse for proceeding to payment page. You will be prompted to enter a
                                    <span class="fw-semibold">One Time Password (OTP).</span>
                                </span>
                            </p>
                    </li>

                    <!-- step-2  -->
                    <li class="step completed mt-5 mt-lg-0" id="step2-li">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 2: </h4><br>Upon verification, use the following API for Payment. You have to call this PAY api to charge each time for charging subscription</p>
                            <p><span class="fw-bold">POST: </span> <a href="#">
                                    https://&lt;your-base-url&gt;/api/RyzenPay/{accId}/p8/subscription/pay </a></p>
                            <p><span class="fw-bold">EXAMPLE: </span>
                                https://www.examplesite.com/api/RyzenPay/1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1/p8/subscription/pay
                            </p>
                            <div class="d-none d-lg-block">
                                <p><span class="fw-bold">In Headers, </span> </p>
                                <div class="header-wrapper mt-4">
                                    <div class="box-content">
                                        <p>
                                            <span class="pink-text">Authorization</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> [ Bearer-Token ] </span>
                                        </p>
                                        <p>
                                            <span class="pink-text">Accept</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> 'application/json'</span>
                                        </p>
                                        <p>
                                            <span class="pink-text">Content-Type</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> 'application/json'</span>
                                        </p>
                                    </div> <br>

                                </div>
                            </div>
                            <div id="step2-mob" class="d-lg-none">
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
                                            <span class="pink-text">Accept</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> 'application/json'</span>
                                        </p>
                                        <p>
                                            <span class="pink-text">Content-Type</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> 'application/json'</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="header-wrapper mt-4">

                                    <div class="box-header d-flex justify-content-between">


                                        <p class="mb-0 font-500">Request Body:</p>
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
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"amount"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color"> 1.75,</span><br>
                                                <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"currency"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color"> "EUR",</span><br>
                                                {{-- <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp; &nbsp;"country"</span><span class="pink-text">
                                        :
                                    </span><span class="blue-text-color">  "GB",</span><br> --}}
                                                <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"dateOfBirth"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color"> "1988-06-28",</span><br>
                                                <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"email"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color"> "bobby@c4g.co.uk",</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"firstName" </span><span class="pink-text"><span> :
                                                    </span><span class="blue-text-color"> "Bobby",</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"lastName"</span><span class="pink-text"> :
                                                    </span><span class="blue-text-color">"Anderson",</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"successRedirectUrl" </span><span class="pink-text"> :
                                                    </span><span
                                                        class="blue-text-color">"https://www.instagram.com",</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"failRedirectUrl" </span><span class="pink-text"> :
                                                    </span><span
                                                        class="blue-text-color">"https://www.youtube.com",</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"address" </span><span class="pink-text"> :
                                                    </span><span class="blue-text-color">"8 bankart avenue",</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"city" </span><span class="pink-text"> :
                                                    </span><span class="blue-text-color">"London",</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"postCode" </span><span class="pink-text"> :
                                                    </span><span class="blue-text-color">"LE22DB",</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"storedCardId" </span><span class="pink-text"> :
                                                    </span><span
                                                        class="blue-text-color">"d64446fb-406e-4935-b820-ca87911fb12d",</span>
                                                    &nbsp; &nbsp; <span class="comment-text">// required only if cardNumber
                                                        and
                                                        expDate
                                                        not available</span><br>
                                                    <span class="comment-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"cardNumber" </span><span class="comment-text"> :
                                                    </span><span class="comment-text">"4012888888881881",</span> &nbsp;
                                                    &nbsp;
                                                    <span class="comment-text">// required only if storedCardId not
                                                        available</span><br>
                                                    <span class="comment-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"expDate" </span><span class="comment-text"> :
                                                    </span><span class="comment-text">"12-30",</span> &nbsp; &nbsp; <span
                                                        class="comment-text">// required only if storedCardId not
                                                        available</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"pin" </span><span class="pink-text"> :
                                                    </span><span class="blue-text-color">"123",</span> &nbsp; &nbsp; <span
                                                        class="comment-text">// CVV or PIN for the stored card</span><br>

                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"ip" </span><span class="pink-text"> :
                                                    </span><span class="blue-text-color">"149.154.2.14",</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"agent" </span><span class="pink-text"> :
                                                    </span><span class="blue-text-color">"Mozilla/5.0 (Windows NT 10.0;
                                                        Win64;
                                                        x64)
                                                        AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124
                                                        Safari/537.36",</span><br>

                                                    <span class="blue-text-color">
                                                        &nbsp;}
                                                    </span><br>
                                                    <!-- <span> &nbsp; &nbsp;Today</span> -->
                                                </span>
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
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"success"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color">true,</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"message"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color"> "WAITING",</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"providerStatus"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color"> "WAITING",</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"type"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color"> "DIRECT",</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"redirectUrl"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color">
                                                    "https://sandbox-checkout.rpdpymnt.com/pca/PA-1247969-1768396215-1213-N",</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"transactionId"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color">
                                                    "af1dd0f0de464727b357e3e78caef7e8",</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"reference"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color">
                                                    "a19bdcba-945d-428c-8d7b-7953877407d7",</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"storedCardId"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color">
                                                    "d64446fb-406e-4935-b820-ca87911fb12d",</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"actualAmount"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color"> "1.75",</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"actualCurrency"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color"> "EUR"</span><br>
                                                <span class="blue-text-color">
                                                    &nbsp;}
                                                </span><br>
                                                <!-- <span> &nbsp; &nbsp;Today</span> -->
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mt-lg-1"><span> Redirect to the <span class="fw-semibold">redirect URL</span>
                                    provided in reponse.
                                    You will once again be prompted to enter a <span class="fw-semibold">One Time Password
                                        (OTP).</span></span></p>
                        </div>
                    </li>

                    {{-- -------------------step #3------------------- --}}
                    <li class="step completed mt-5 mt-lg-0" id="step3-li">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 3: </h4><br>We will
                            send a
                            Webhook request to you, containing the {checkout_id} of the transaction . The
                            webhook
                            request will be sent in the following format:</p>
                            <p><span class="fw-bold">POST: </span> <a
                                    href="#">https://&lt;your-base-url&gt;/api/RyzenPay/p8/{checkout_id}</a>
                            </p>


                        </div>

                    </li>
                    <li class="step completed mt-5 mt-lg-0" id="step4-li">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">GetPaymentStatus API </h4>
                            </p>
                            <p class="mb-0">Get the status and details of the transaction using the
                                <span>{checkout_id}</span> .
                            </p>
                            <p><span class="fw-bold">GET: </span> <a
                                    href=""><your-base-url>https://payment.ryzen-pay.com/api/payment/{accId}/p8/getPaymentStatus/{checkout_id}</a>
                            </p>
                        </div>
                        <div id="step3-mob" class="d-lg-none">
                            <div class="header-wrapper mt-4">

                                <div class="box-header d-flex justify-content-between">


                                    <p class="mb-0 font-500">Headers: </p>
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
                                </div>
                            </div>

                            <div class="header-wrapper mt-4">

                                <div class="box-header d-flex justify-content-between">


                                    <p class="mb-0 font-500">Response: 200 OK (If Transaction process is
                                        completed)</p>
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
                                        <!-- <span class="pink-text">'data'</span> -->
                                        <!-- <span class="pink-text">:</span>  -->
                                        <span class="blue-text-color">{<br>
                                            <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;
                                                &nbsp; &nbsp;"amount"</span><span class="pink-text"> :
                                            </span><span class="blue-text-color"> string,</span><br>
                                            <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;
                                                &nbsp; &nbsp;"currency"</span><span class="pink-text"> :
                                            </span><span class="blue-text-color"> string,</span><br>
                                            {{-- <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp; &nbsp; &nbsp;"accountId"</span><span class="pink-text">
                                                    :
                                                </span><span class="blue-text-color"> string,</span><br> --}}
                                            <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;
                                                &nbsp; &nbsp;"checkout_id"</span><span class="pink-text"> : </span><span
                                                class="blue-text-color">string,</span><br>
                                            <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;
                                                &nbsp; &nbsp;"payment_id"</span><span class="pink-text"> :
                                            </span><span class="blue-text-color"> string,</span><br>
                                            <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;
                                                &nbsp; &nbsp;"payment_status" </span><span class="pink-text"><span> :
                                                </span><span class="blue-text-color"> string,</span><br>
                                                <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"description"</span><span class="pink-text"> :
                                                </span><span class="blue-text-color">string,</span><br>
                                                <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"created_at" </span><span class="pink-text"> :
                                                </span><span class="blue-text-color">timestamp,</span><br>

                                                <span class="blue-text-color">
                                                    &nbsp;}
                                                </span><br>
                                                <!-- <span> &nbsp; &nbsp;Today</span> -->
                                            </span>
                                    </p>
                                </div>
                            </div>



                            <div class="header-wrapper mt-4">

                                <div class="box-header d-flex justify-content-between">


                                    <p class="mb-0 font-500">Response: 401 Unauthorized (If Transaction
                                        process
                                        didn't complete)</p>
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
                                        <!-- <span class="pink-text">'data'</span> -->
                                        <!-- <span class="pink-text">:</span>  -->
                                        <span class="blue-text-color">{<br>
                                            <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;
                                                &nbsp; &nbsp;"message"</span><span class="pink-text"> :
                                            </span><span class="blue-text-color"> "Unauthorized Checkout Id or
                                                Transaction is not
                                                completed.",</span><br>
                                            <span class="blue-text-color">
                                                &nbsp;}
                                            </span><br>
                                            <!-- <span> &nbsp; &nbsp;Today</span> -->
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- extra div for step line extension -->

                    <div>

                    </div>
                </ol>
            </div>
            <div class="right-section">
                <div id="step1" class="d-none d-lg-block pb-5">
                    <p class="px-2"><span class="fw-bold">POST: </span> <a href="#">
                            https://payment.ryzen-pay.com/api/payment/{accId}/p8/subscription/verify-card </a></p>

                    <!-- --------------------Headers:------------------ -->


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
                                <span class="pink-text">Accept</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> 'application/json'</span>
                            </p>
                            <p>
                                <span class="pink-text">Content-Type</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> 'application/json'</span>
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
                                <span class="blue-text-color">{<br>
                                    <span> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"currency"</span> <span class="pink-text">:</span>
                                        "EUR",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"number"</span> <span class="pink-text">:</span>
                                        "4012888888881881",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"expiryMonth"</span> <span
                                            class="pink-text">:</span> "12",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"expiryYear"</span> <span class="pink-text">:</span>
                                        "2030",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"cvv"</span> <span class="pink-text">:</span>
                                        "123",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"email"</span> <span class="pink-text">:</span>
                                        "bobby@c4g.co.uk",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"birthday"</span> <span class="pink-text">:</span>
                                        "1988-06-28",<span class="comment-text">
                                            //yyyy-MM-dd</span><br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"billingFirstName"</span> <span
                                            class="pink-text">:</span>
                                        "Bobby",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"billingLastName"</span> <span
                                            class="pink-text">:</span>
                                        "Poonia",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"billingAddress1"</span> <span
                                            class="pink-text">:</span> "8 bankart avenue",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"billingCity"</span> <span
                                            class="pink-text">:</span> "oadby",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text"> "billingPostcode"</span> <span
                                            class="pink-text">:</span> "LE22DB",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"billingCountry"</span> <span
                                            class="pink-text">:</span> "GB",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"billingAddress"</span> <span
                                            class="pink-text">:</span> "second street",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"state"</span> <span class="pink-text">:</span>
                                        "London",<br></span>
                                    <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span class="pink-text">"returnUrl"</span> <span class="pink-text">:</span>
                                        "https://www.abc.com",<br></span>
                                    <span class="comment-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span>//"customerIp"</span> <span>:</span> "192.168.254.23",//
                                        optional,<br></span>
                                    <span class="comment-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
                                        &nbsp;<span>//"customerUserAgent"</span> <span class="comment-text">:</span>
                                        "string",// optional,<br></span>

                                    }
                                </span><br>

                            </p>
                        </div>
                    </div>
                    <!-- ------------------Response: for 200 ok------------------- -->
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
                                <!-- <span class="pink-text">'json'</span> -->
                                <!-- <span class="pink-text">:</span>  -->
                                <span class="blue-text-color">{<br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp;
                                        &nbsp;"success":</span><span class="blue-text-color"> "true",</span><br>
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"message":</span><span class="blue-text-color">
                                        "Waiting",</span><br>
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                        &nbsp;
                                        &nbsp;"form3d":</span><span class="blue-text-color">
                                        "https://payment.ryzen-pay.com/card/verify/redirect-url"</span><br>
                                    <span>}
                                    </span><br>
                            </p>
                        </div>
                    </div>
                </div>
                <div id="step2" class="d-none d-lg-block pb-5">
                    <p><span class="fw-bold">POST: </span> <a
                            href="#">https://&lt;your-base-url&gt;/api/RyzenPay/{accId}/p8/subscription/pay</a>
                    </p>

                    <!-- --------------------Headers:------------------ -->
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
                                <span class="pink-text">Accept</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> 'application/json'</span>
                            </p>
                            <p>
                                <span class="pink-text">Content-Type</span>
                                <span class="pink-text">=</span>
                                <span class="blue-text-color"> 'application/json'</span>
                            </p>
                        </div>
                    </div>

                    <div class="header-wrapper mt-4">

                        <div class="box-header d-flex justify-content-between">


                            <p class="mb-0 font-500">Request Body:</p>
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
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"amount"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> 1.75,</span><br>
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"currency"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> "EUR",</span><br>
                                    {{-- <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp; &nbsp;"country"</span><span class="pink-text">
                                        :
                                    </span><span class="blue-text-color">  "GB",</span><br> --}}
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"dateOfBirth"</span><span class="pink-text"> : </span><span
                                        class="blue-text-color"> "1988-06-28",</span><br>
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"email"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> "bobby@c4g.co.uk",</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"firstName" </span><span class="pink-text"><span> :
                                        </span><span class="blue-text-color"> "Bobby",</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"lastName"</span><span class="pink-text"> :
                                        </span><span class="blue-text-color">"Anderson",</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"successRedirectUrl" </span><span class="pink-text"> :
                                        </span><span class="blue-text-color">"https://www.instagram.com",</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"failRedirectUrl" </span><span class="pink-text"> :
                                        </span><span class="blue-text-color">"https://www.youtube.com",</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"address" </span><span class="pink-text"> :
                                        </span><span class="blue-text-color">"8 bankart avenue",</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"city" </span><span class="pink-text"> :
                                        </span><span class="blue-text-color">"London",</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"postCode" </span><span class="pink-text"> :
                                        </span><span class="blue-text-color">"LE22DB",</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"storedCardId" </span><span class="pink-text"> :
                                        </span><span class="blue-text-color">"d64446fb-406e-4935-b820-ca87911fb12d",</span>
                                        &nbsp; &nbsp; <span class="comment-text">// required only if cardNumber and expDate
                                            not available</span><br>
                                        <span class="comment-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"cardNumber" </span><span class="comment-text"> :
                                        </span><span class="comment-text">"4012888888881881",</span> &nbsp; &nbsp; <span
                                            class="comment-text">// required only if storedCardId not available</span><br>
                                        <span class="comment-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"expDate" </span><span class="comment-text"> :
                                        </span><span class="comment-text">"12-30",</span> &nbsp; &nbsp; <span
                                            class="comment-text">// required only if storedCardId not available</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"pin" </span><span class="pink-text"> :
                                        </span><span class="blue-text-color">"123",</span> &nbsp; &nbsp; <span
                                            class="comment-text">// CVV or PIN for the stored card</span><br>

                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"ip" </span><span class="pink-text"> :
                                        </span><span class="blue-text-color">"149.154.2.14",</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"agent" </span><span class="pink-text"> :
                                        </span><span class="blue-text-color">"Mozilla/5.0 (Windows NT 10.0; Win64; x64)
                                            AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124
                                            Safari/537.36",</span><br>

                                        <span class="blue-text-color">
                                            &nbsp;}
                                        </span><br>
                                        <!-- <span> &nbsp; &nbsp;Today</span> -->
                                    </span>
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
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"success"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color">true,</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"message"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> "WAITING",</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"providerStatus"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> "WAITING",</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"type"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> "DIRECT",</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"redirectUrl"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color">
                                        "https://sandbox-checkout.rpdpymnt.com/pca/PA-1247969-1768396215-1213-N",</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"transactionId"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color">
                                        "af1dd0f0de464727b357e3e78caef7e8",</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"reference"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color">
                                        "a19bdcba-945d-428c-8d7b-7953877407d7",</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"storedCardId"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color">
                                        "d64446fb-406e-4935-b820-ca87911fb12d",</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"actualAmount"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> "1.75",</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"actualCurrency"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> "EUR"</span><br>
                                    <span class="blue-text-color">
                                        &nbsp;}
                                    </span><br>
                                    <!-- <span> &nbsp; &nbsp;Today</span> -->
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div id="step3" class="d-none d-lg-block pb-5
                    <p><span class="fw-bold">GET: </span> <a
                            href=""><your-base-url>https://payment.ryzen-pay.com/api/payment/{accId}/p8/getPaymentStatus/{checkout_id}</a>
                    </p>

                    <!-- --------------------Headers:------------------ -->
                    <div class="header-wrapper mt-4">

                        <div class="box-header d-flex justify-content-between">


                            <p class="mb-0 font-500">Headers: </p>
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
                        </div>
                    </div>

                    <div class="header-wrapper mt-4">

                        <div class="box-header d-flex justify-content-between">


                            <p class="mb-0 font-500">Response: 200 OK (If Transaction process is
                                completed)</p>
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
                                <!-- <span class="pink-text">'data'</span> -->
                                <!-- <span class="pink-text">:</span>  -->
                                <span class="blue-text-color">{<br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"amount"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> string,</span><br>
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"currency"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> string,</span><br>
                                    {{-- <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp; &nbsp;"accountId"</span><span class="pink-text">
                                        :
                                    </span><span class="blue-text-color"> string,</span><br> --}}
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"checkout_id"</span><span class="pink-text"> : </span><span
                                        class="blue-text-color">string,</span><br>
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"payment_id"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> string,</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"payment_status" </span><span class="pink-text"><span> :
                                        </span><span class="blue-text-color"> string,</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"description"</span><span class="pink-text"> :
                                        </span><span class="blue-text-color">string,</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"created_at" </span><span class="pink-text"> :
                                        </span><span class="blue-text-color">timestamp,</span><br>

                                        <span class="blue-text-color">
                                            &nbsp;}
                                        </span><br>
                                        <!-- <span> &nbsp; &nbsp;Today</span> -->
                                    </span>
                            </p>
                        </div>
                    </div>



                    <div class="header-wrapper mt-4">

                        <div class="box-header d-flex justify-content-between">


                            <p class="mb-0 font-500">Response: 401 Unauthorized (If Transaction
                                process
                                didn't complete)</p>
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
                                <!-- <span class="pink-text">'data'</span> -->
                                <!-- <span class="pink-text">:</span>  -->
                                <span class="blue-text-color">{<br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"message"</span><span class="pink-text"> :
                                    </span><span class="blue-text-color"> "Unauthorized Checkout Id or
                                        Transaction is not
                                        completed.",</span><br>
                                    <span class="blue-text-color">
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
    </div>
    </div>
    </div>
    </div>
    <script>
        (function() {
            // Delegate clicks for all .copy-btn (even if duplicated)
            document.addEventListener('click', async function(e) {
                const btn = e.target.closest('.copy-btn');
                if (!btn) return;

                const wrapper = btn.closest('.header-wrapper');
                const content = wrapper && wrapper.querySelector('.box-content');
                if (!content) return;

                // Plain text with original line breaks/spacing
                let textToCopy = content.innerText
                    .replace(/\u00A0/g, ' ') // NBSP -> space
                    .replace(/[ \t]+$/gm, '') // trim line-end spaces
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
                const ok = document.execCommand('copy'); // legacy path
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
                const target1 = document.getElementById('step1-li');
                const target2 = document.getElementById('step2-li');
                const target3 = document.getElementById('step4-li');

                var height1 = step1.offsetHeight;
                var height2 = step2.offsetHeight;
                var height3 = step3.offsetHeight;

                if (window.innerWidth > 1024) {
                    target1.style.minHeight = height1 + 'px';
                    target2.style.minHeight = height2 + 'px';
                    target3.style.minHeight = height3 + 'px';
                }

            }

            window.addEventListener('resize', updateMinHeight);
            window.addEventListener('load', updateMinHeight);

        });
    </script>
@endsection

@section('scripts')
@endsection
