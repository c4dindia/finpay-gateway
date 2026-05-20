@extends('layouts.clientMaster')

@section('title')
    Documentation > p17-Service
    @php
        $currentPage = 'Documentations';
    @endphp
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/clientside/p2service.css') }}">
@endsection

@section('page-content')
    <div class=" p-3">
        <div class="col-xl-12 mt-3 d-flex flex-column flex-lg-row gap-5">
            <div class="left-section progress-vertical">
                <ol class="steps">

                    <!-- Step 1 -->
                    <li class="step completed">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 1 </h4>
                            <p class="pl-3"> API for creating checkout ID and link. <br>
                                <span class="fw-bold"> NOTE:</span> All parameters in request are required.
                            </p>
                            <p><span class="fw-bold">POST: </span> <a href="#">
                                    https://payzone.finpay.group/api/payment/{accId}/p17/checkout </a></p>

                            <!-- --------------------Headers:------------------ -->
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
                                            <span class="pink-text">Authorization</span>
                                            <span class="pink-text">=</span>
                                            <span class="blue-text-color"> [ Bearer-Token ]</span>
                                        </p>
                                        <p>
                                            <span class="pink-text">Accept</span>
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
                                            <span class="pink-text">'json'</span> <span class="pink-text">=></span> <span
                                                class="blue-text-color">[</span><br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'amount'</span> <span
                                                class="pink-text">=></span> <span
                                                class="blue-text-color">99.99,</span>&nbsp;&nbsp;&nbsp;&nbsp;<br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'currency'</span> <span
                                                class="pink-text">=></span> <span
                                                class="blue-text-color">'INR',</span>&nbsp;&nbsp;&nbsp;&nbsp;<br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'method'</span> <span
                                                class="pink-text">=></span> <span
                                                class="blue-text-color">'qr',</span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                class="comment-text">// Required</span><br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'redirect_url'</span> <span
                                                class="pink-text">=></span> <span
                                                class="blue-text-color">'https://yourapp.com/p17/thank-you-page',</span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                class="comment-text">// OPTIONAL</span><br>


                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'depositor_name'</span> <span
                                                class="pink-text">=></span> <span
                                                class="blue-text-color">'Corey Watson',</span>&nbsp;&nbsp;&nbsp;&nbsp;<br>

                                    

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'txid'</span> <span
                                                class="pink-text">=></span> <span
                                                class="blue-text-color">'TX124778',</span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                class="comment-text">//Optional ,also known payment_id, if omitted will be automatically assigned from our side</span><br>

                                            <span class="blue-text-color">]</span>
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
                                            <span class="blue-text-color">{</span><br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"success":</span>
                                            <span class="blue-text-color">true,</span><br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"amount":</span>
                                            <span class="blue-text-color">99.99,</span><br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"currency":</span>
                                            <span class="blue-text-color">"INR",</span><br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"checkout_id":</span>
                                            <span class="blue-text-color">"014504dc-016d-43f7-3358-451cc70ea024",</span><br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"payment_id":</span>
                                            <span class="blue-text-color">"txn_abc123...",</span><br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"status":</span>
                                            <span class="blue-text-color">"Pending",</span><br>

                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"link":</span>
                                            <span class="blue-text-color">"https://wwww.paymentpage.com/site/1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1"</span><br>

                                            <span class="blue-text-color">}</span><br>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- step-2  -->
                    <li class="step completed mt-5 mt-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 2: </h4><br> Redirect to the link provided in reponse for
                            proceeding to payment page in Step 1
                        </div>
                    </li>

                    <li class="step completed mt-5 mt-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">Step 3: </h4><br>When transaction process is completed, we will
                            send a
                            Webhook request to you, containing the {checkout_id} of the transaction . The
                            webhook
                            request will be sent in the following format:
                            <p><span class="fw-bold">GET: </span> <a
                                    href="#">https://&lt;your-base-url&gt;/api/finpay/p17/{checkout_id}</a></p>
                            <p><span class="fw-bold">EXAMPLE: </span>
                                https://www.examplesite.com/api/finpay/p17/1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1
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
                                        <span class="pink-text">Accept</span>
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
                    <li class="step completed mt-5 mt-lg-0">
                        <div class="marker"></div>
                        <div class="body">
                            <h4 class="fw-bold">GetPaymentStatus API </h4>
                            <p class="mb-0">Get the status and details of the transaction using the
                                <span>{checkout_id}</span> .
                            </p>
                            <p><span class="fw-bold">GET: </span> <a
                                    href=""><your-base-url>https://payzone.finpay.group/api/payment/{accId}/p17/getPaymentStatus/{checkout_id}</a>
                            </p>

                            <div>
                                <h5 class="fw-bold mt-4">
                                    Note : Currencies & [their Available method] :
                                </h5>
                                <ul class="fw-semibold" style="list-style-type: disc;">
                                    <li>INR [ qr ,qr2]</li>
                                </ul>
                            </div>

                            <!-- --------------------Headers:------------------ -->
                            <div id="step2" class="d-lg-none">
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
                                            <span class="pink-text">Accept</span>
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
                                            <!-- <span class="pink-text">=></span>  -->
                                            <span class="blue-text-color">{<br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"amount"</span><span class="pink-text"> =>
                                                </span><span class="blue-text-color"> string,</span><br>
                                                <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"currency"</span><span class="pink-text"> =>
                                                </span><span class="blue-text-color"> string,</span><br>
                                                {{-- <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp; &nbsp; &nbsp;"accountId"</span><span class="pink-text">
                                                    =>
                                                </span><span class="blue-text-color"> string,</span><br> --}}
                                                <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"checkout_id"</span><span class="pink-text"> =
                                                    ></span><span class="blue-text-color">string,</span><br>
                                                <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"payment_id"</span><span class="pink-text"> =>
                                                </span><span class="blue-text-color"> string,</span><br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"payment_status" </span><span class="pink-text"><span> =>
                                                    </span><span class="blue-text-color"> string,</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"description"</span><span class="pink-text"> =>
                                                    </span><span class="blue-text-color">string,</span><br>
                                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;
                                                        &nbsp; &nbsp;"created_at" </span><span class="pink-text"> =>
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


                                        <p class="mb-0 font-500">Response: 401 Unauthorized (If Transaction process didn't complete)</p>
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
                                            <!-- <span class="pink-text">=></span>  -->
                                            <span class="blue-text-color">{<br>
                                                <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;
                                                    &nbsp; &nbsp;"message"</span><span class="pink-text"> =>
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
                    </li>
                    <!-- extra div for step line extension -->

                    <div>

                    </div>
                </ol>
            </div>
            <div class="right-section">
                <div id="step1" class="d-none d-lg-block">
                    <p class="px-2"><span class="fw-bold">POST: </span> <a href="#"> https://payzone.finpay.group/api/payment/{accId}/p17/checkout </a></p>

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
                                <span class="pink-text">'json'</span> <span class="pink-text">=></span> <span
                                    class="blue-text-color">[</span><br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'amount'</span> <span
                                    class="pink-text">=></span> <span
                                    class="blue-text-color">99.99,</span>&nbsp;&nbsp;&nbsp;&nbsp;<br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'currency'</span> <span
                                    class="pink-text">=></span> <span
                                    class="blue-text-color">'INR',</span>&nbsp;&nbsp;&nbsp;&nbsp;<br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'method'</span> <span
                                    class="pink-text">=></span> <span
                                    class="blue-text-color">'qr',</span>&nbsp;&nbsp;&nbsp;&nbsp;<br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'redirect_url'</span> <span
                                    class="pink-text">=></span> <span
                                    class="blue-text-color">'https://yourapp.com/p17/thank-you-page',</span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                    class="comment-text">// OPTIONAL</span><br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'depositor_name'</span> <span
                                    class="pink-text">=></span> <span
                                    class="blue-text-color">'Corey Watson',</span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                    class="comment-text"></span><br>


                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">'txid'</span> <span
                                    class="pink-text">=></span> <span
                                    class="blue-text-color">'TX124778',</span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                    class="comment-text">//Optional ,also known payment_id, if omitted will be automatically assigned from our side</span><br>

                                <span class="blue-text-color">]</span>
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
                                <span class="blue-text-color">{</span><br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"success":</span>
                                <span class="blue-text-color">true,</span><br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"amount":</span>
                                <span class="blue-text-color">99.99,</span><br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"currency":</span>
                                <span class="blue-text-color">"INR",</span><br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"checkout_id":</span>
                                <span class="blue-text-color">"014504dc-016d-43f7-3358-451cc70ea024",</span><br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"payment_id":</span>
                                <span class="blue-text-color">"txn_abc123...",</span><br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"customer_uid":</span>
                                <span class="blue-text-color">"Cust_891698",</span><br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"status":</span>
                                <span class="blue-text-color">"Pending",</span><br>

                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="pink-text">"link":</span>
                                <span class="blue-text-color">"https://wwww.paymentpage.com/site/1d68304e-xxxx-xxxx-xxxx-xxxxdc2c89e1"</span><br>

                                <span class="blue-text-color">}</span><br>
                            </p>
                        </div>

                    </div>
                </div>
                <div id="step2" class="d-none d-lg-block bottom-headers">
                    <p><span class="fw-bold">GET: </span> <a
                            href=""><your-base-url>https://payzone.finpay.group/api/payment/{accId}/p17/getPaymentStatus/{checkout_id}</a>
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
                                <span class="pink-text">Accept</span>
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
                                <!-- <span class="pink-text">=></span>  -->
                                <span class="blue-text-color">{<br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"amount"</span><span class="pink-text"> =>
                                    </span><span class="blue-text-color"> string,</span><br>
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"currency"</span><span class="pink-text"> =>
                                    </span><span class="blue-text-color"> string,</span><br>
                                    {{-- <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp; &nbsp; &nbsp;"accountId"</span><span class="pink-text">
                                        =>
                                    </span><span class="blue-text-color"> string,</span><br> --}}
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"checkout_id"</span><span class="pink-text"> =
                                        ></span><span class="blue-text-color">string,</span><br>
                                    <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"payment_id"</span><span class="pink-text"> =>
                                    </span><span class="blue-text-color"> string,</span><br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"payment_status" </span><span class="pink-text"><span> =>
                                        </span><span class="blue-text-color"> string,</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"description"</span><span class="pink-text"> =>
                                        </span><span class="blue-text-color">string,</span><br>
                                        <span class="pink-text">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;
                                            &nbsp; &nbsp;"created_at" </span><span class="pink-text"> =>
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
                                <!-- <span class="pink-text">=></span>  -->
                                <span class="blue-text-color">{<br>
                                    <span class="pink-text"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        &nbsp; &nbsp;"message"</span><span class="pink-text"> =>
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
@endsection

@section('scripts')
@endsection
