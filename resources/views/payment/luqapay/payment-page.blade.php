{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Payment - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/ryzen-fav-logo.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .payment-section {
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .payment-section input {
            padding: 10px 14px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        #qr-image {
            max-width: 100%;
            height: auto;
        }

        #proceed-btn {
            padding: 12px 24px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            max-width: 300px;
            margin: auto;
            display: block;
            transition: background-color 0.3s;
        }

        #proceed-btn:hover {
            background-color: #0056b3;
        }

        #transaction-success {
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-r from-blue-500 to-teal-400 min-h-screen flex items-center justify-center">

<div id="particles-js"></div>

<div class="bg-white shadow-lg rounded-lg w-full max-w-3xl p-8 m-4">
    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/Rayzen-Pay-logo.png') }}" alt="Company Logo" class="h-16">
    </div>

    <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">Complete Your Payment</h2>
    <p class="text-center text-xl text-blue-700 font-semibold mb-6">Amount to be paid: <span class="text-teal-600">{{ $checkout->currency }} {{ number_format((float) $checkout->amount, 2) }}</span></p>

    <form method="POST" action="{{ route('makeLuqapayPayment' ,['checkout_id' => $checkout->checkout_id]) }}">
        @csrf

        <!-- Payment Tabs -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Choose Payment Method: @error('payment_method') <small class="text-red-600 font-normal">* {{ $message }}</small> @enderror</h3>
            <div class="flex flex-wrap border  @error('payment_method') border-red-500 @else border-gray-200 @enderror rounded-lg overflow-hidden mb-4" id="payment-tabs">
           <!-- CARD TAB -->
           <input type="radio" id="tab-card" name="payment_method" value="CARD" class="hidden peer/card" {{ old('payment_method') == 'CARD' ? 'checked' : '' }}>
           <label for="tab-card"
                  class="tab-label flex-1 text-center cursor-pointer py-3 px-4 border-r border-gray-200 bg-white text-gray-700 transition-all duration-200 peer-checked/card:bg-blue-600 peer-checked/card:text-white peer-checked/card:font-bold">
               Pay by Card
           </label>

        </div>
            <!-- Payment Sections -->
            <div  class="payment-section bg-gray-50 p-6 rounded-lg border mb-4 shadow-sm">
                <!-- Card Holder Name & Card Number Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Card Holder Name -->


                    <!-- Card Number -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Card Number</label>
                        <input type="text" name="card_number" maxlength="19" value="{{ old('card_number') }}"
                               class="w-full p-3 border rounded-md @error('card_number') border-red-500 @else border-gray-300 @enderror">
                        <span class="text-sm text-gray-500 mt-1 block">Enter 16-digit card number without spaces.</span>
                        @error('card_number')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Expiry Month, Year, CVV -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Expiry Month</label>
                        <input type="text" name="expiry_month" maxlength="2" value="{{ old('expiry_month') }}"
                               class="w-full p-3 border rounded-md @error('expiry_month') border-red-500 @else border-gray-300 @enderror">
                        <span class="text-sm text-gray-500 mt-1 block">Format: 01 - 12</span>
                        @error('expiry_month')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Expiry Year</label>
                        <input type="text" name="expiry_year" maxlength="2" value="{{ old('expiry_year') }}"
                               class="w-full p-3 border rounded-md @error('expiry_year') border-red-500 @else border-gray-300 @enderror">
                        <span class="text-sm text-gray-500 mt-1 block">Last two digits of year</span>
                        @error('expiry_year')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">CVV</label>
                        <input type="password" name="cvv" maxlength="6"
                               class="w-full p-3 border rounded-md @error('cvv') border-red-500 @else border-gray-300 @enderror">
                        <span class="text-sm text-gray-500 mt-1 block">3 or 4-digit code</span>
                        @error('cvv')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <button id="proceed-btn" type="submit"
                    class="w-full max-w-xs mx-auto bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition hidden">
                Proceed to Pay
            </button>
        </div>
    </form>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@if (session('success'))
<script>
    Swal.fire({
        title: 'Success!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        title: 'Oops!',
        text: "{{ session('error') }}",
        icon: 'info',
        confirmButtonText: 'OK'
    });
</script>
@endif

<script>
    particlesJS("particles-js", {
        "particles": {
            "number": { "value": 80 },
            "color": { "value": "#ffffff" },
            "shape": { "type": "circle" },
            "opacity": { "value": 0.5 },
            "size": { "value": 3 },
            "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#ffffff",
                "opacity": 0.4,
                "width": 1
            },
            "move": { "enable": true, "speed": 2 }
        },
        "interactivity": {
            "events": {
                "onhover": { "enable": true, "mode": "repulse" }
            }
        },
        "retina_detect": true
    });
</script>

</body>
</html> --}}


<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Secure Payment - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/ryzen-fav-logo.png') }}" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        .btn-border-wrapper {
            position: relative;
            display: inline-block;
            border-radius: 0.5rem;
        }

        .btn-border-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        /* static border */
        .btn-border-svg rect {
            fill: none;
            stroke: rgba(255, 255, 255, 0.35);
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: none;
        }

        /* animated trail */
        .btn-border-wrapper.active .btn-border-svg rect {
            stroke: url(#borderGradient);
            stroke-width: 2;
            stroke-dasharray: 12 88;
            /* 12% light, 88% gap along the 100-length path */
            stroke-dashoffset: 0;
            animation: borderRun 2.2s linear infinite;
        }

        @keyframes borderRun {
            to {
                stroke-dashoffset: -100;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-b to-white from-black min-h-screen overflow-y-hidden">
    <main class="gap-0 container px-4 md:mx-auto h-screen py-40  xl:w-3/5 ">
        <section class="shadow-xl rounded-xl grid grid-cols-1 xl:grid-cols-2 min-h-full">
            <section class="flex justify-center bg-[url({{ asset('images/p8-credit-card.png') }})] bg-no-repeat bg-cover bg-center rounded-l-xl  max-xl:hidden">

            </section>
            <section
                class="flex flex-col items-center justify-center rounded-r-xl max-xl:rounded-l-xl bg-gradient-to-b from-black via-[#020617] to-[#050816]">
                <form id="paymentForm" action="{{ route('makeLuqapayPayment' ,['checkout_id' => $checkout->checkout_id]) }}" method="POST" class="xl:h-full w-full text-white rounded-r-xl px-10 py-10 flex flex-col gap-10">
                    @csrf
                    <!-- Logo + small heading -->
                    <div class="flex flex-col items-center gap-2">
                        <img src="{{ asset('images/Rayzen-Pay-logo.png') }}" alt="logo">

                    </div>

                    <!-- Amount -->
                    <div class="flex justify-center items-end gap-2 font-semibold">
                        <span class="text-sm text-slate-400 uppercase tracking-wide">Amount</span>
                        <span class="text-xl text-[#facc15]">USD 34</span>
                    </div>

                    <!-- Card block -->
                    <div class="w-full flex justify-center">
                        <div class="relative w-full max-w-md rounded-2xl border border-white/10
                        bg-gradient-to-br from-neutral-900 via-slate-900 to-black
                        shadow-[0_18px_40px_rgba(0,0,0,0.8)] p-5">

                            <!-- EMV chip -->
                            <div class="absolute top-5 left-5">
                                <div
                                    class="w-10 h-8 rounded-md bg-gradient-to-br from-[#facc15] to-[#a16207] shadow-lg">
                                </div>
                            </div>

                            <div class="mt-16 space-y-4">
                                <!-- Card number -->
                                <div class="flex flex-col gap-1">
                                    <label for="card_number" class="ml-1 text-[11px] tracking-[0.2em] uppercase text-slate-400">
                                        Card Number
                                    </label>
                                    <input type="text" name="card_number" id="card_number" inputmode="numeric" value="{{ old('card_number') }}"
                                        autocomplete="cc-number" maxlength="23" placeholder="1234 5678 9012 3456" class="w-full rounded-xl bg-black/40 border border-slate-600/70
                                   px-3 py-2.5 text-sm text-slate-100 font-mono tracking-[0.25em]
                                   placeholder:text-slate-500 focus:outline-none
                                   focus:border-[#22d3ee] focus:ring-1 focus:ring-[#22d3ee]" required>
                                </div>

                                <!-- Expiry + PIN row -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-1">
                                        <label for="exp"
                                            class="ml-1 text-[11px] tracking-[0.2em] uppercase text-slate-400">
                                            Expiry
                                        </label>
                                        <input type="text" name="exp" id="exp" placeholder="MM-YY" maxlength="5" value="{{ old('exp') }}"
                                            pattern="^(0[1-9]|1[0-2])\-\d{2}$" class="w-full rounded-xl bg-black/40 border border-slate-600/70
                                       px-3 py-2.5 text-sm text-slate-100
                                       placeholder:text-slate-500 focus:outline-none
                                       focus:border-[#22d3ee] focus:ring-1 focus:ring-[#22d3ee]" required>
                                    </div>

                                    <div class="flex flex-col gap-1">
                                        <label for="pin" class="ml-1 text-[11px] tracking-[0.2em] uppercase text-slate-400">
                                            PIN
                                        </label>
                                        <input type="text" name="cvv" id="pin" maxlength="4" inputmode="numeric" value="{{ old('cvv') }}" class="w-full rounded-xl bg-black/40 border border-slate-600/70
                                       px-3 py-2.5 text-sm text-slate-100
                                       placeholder:text-slate-500 focus:outline-none
                                       focus:border-[#22d3ee] focus:ring-1 focus:ring-[#22d3ee]" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    @if (session('error'))
                        <div id="toast-error" class="fixed top-5 right-5 z-50 transition-all duration-300">
                            <div class="flex items-center w-full max-w-xs p-4 mb-4 text-sm text-red-800 bg-red-100 border border-red-300 rounded-lg shadow-lg"
                                 role="alert">
                                {{-- Icon --}}
                                <span class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-full bg-red-200 mr-3">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 9v4m0 4h.01M4.93 4.93l14.14 14.14M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                    </svg>
                                </span>

                                {{-- Message --}}
                                <div class="flex-1 font-medium">
                                    {{ session('error') }}
                                </div>

                                {{-- Close button --}}
                                <button type="button"
                                        onclick="document.getElementById('toast-error')?.remove()"
                                        class="ml-3 text-red-700 hover:text-red-900 focus:outline-none">
                                    ✕
                                </button>
                            </div>
                        </div>

                        <script>
                            // Auto-hide after 3 seconds
                            setTimeout(() => {
                                const toast = document.getElementById('toast-error');
                                if (toast) {
                                    toast.classList.add('opacity-0', 'translate-x-3');
                                    toast.addEventListener('transitionend', () => toast.remove());
                                }
                            }, 3000);
                        </script>
                    @endif


                    <!-- Button with animated border -->
                    <div class="w-full flex justify-center mt-4">
                        <div class="btn-border-wrapper mt-2 w-full max-w-md">
                            <svg class="btn-border-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 40" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="borderGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#22d3ee" />
                                        <stop offset="100%" stop-color="#3b82f6" />
                                    </linearGradient>
                                </defs>

                                <!-- 🔑 normalized pathLength + non-scaling stroke -->
                                <rect id="borderRect" x="2" y="2" width="96" height="36" rx="8" ry="8" pathLength="27"
                                    vector-effect="non-scaling-stroke" />
                            </svg>


                            <button id="submitBtn" type="submit" disabled class="relative z-10 w-full bg-transparent border-0
                           px-6 py-2 md:py-2.5 text-sm md:text-base lg:text-lg
                           font-semibold rounded-lg transition-colors duration-150
                           text-white cursor-not-allowed opacity-60">
                                Pay Securely
                            </button>
                        </div>
                    </div>
                </form>
            </section>

        </section>
    </main>
    <script>
        const expInput = document.getElementById("exp");

        expInput.addEventListener("input", (e) => {
            let value = e.target.value.replace(/\D/g, ""); // remove non-digits
            if (value.length >= 3) {
                value = value.slice(0, 2) + "-" + value.slice(2, 4);
            }
            e.target.value = value;
        });
    </script>

    <script>
        const cardInput = document.getElementById('card_number');

        cardInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, ''); // digits only

            // limit to 19 digits (max length for PAN)
            value = value.slice(0, 19);

            // group digits in blocks of 4 for display
            const groups = [];
            for (let i = 0; i < value.length; i += 4) {
                groups.push(value.slice(i, i + 4));
            }
            e.target.value = groups.join(' ');
        });
    </script>
    <script>

        function setBorderPathLength() {
            const rect = document.getElementById('borderRect');
            if (!rect) return;

            const width = window.innerWidth;

            // phone: width < 768px (Tailwind 'md' breakpoint style)
            if (width < 768) {
                rect.setAttribute('pathLength', '38');  // phone
            } else {
                rect.setAttribute('pathLength', '27');  // tablet + desktop
            }
        }

        // run on load
        setBorderPathLength();

        // run on resize (so when user rotates or resizes window)
        window.addEventListener('resize', setBorderPathLength);
        const form = document.getElementById('paymentForm'); // or querySelector('form')
        const submitBtn = document.getElementById('submitBtn');
        const borderWrapper = document.querySelector('.btn-border-wrapper');

        const requiredFields = Array.from(
            form.querySelectorAll('input[required], select[required], textarea[required]')
        );

        function updateButtonState() {
            const allFilled = requiredFields.every(field => field.value.trim() !== '');

            if (allFilled) {
                // stop border animation
                borderWrapper.classList.remove('active');

                // enable & fill button
                submitBtn.disabled = false;
                submitBtn.classList.remove('cursor-not-allowed', 'opacity-60');
                submitBtn.classList.add('cursor-pointer');
                submitBtn.style.backgroundImage = 'none';
                submitBtn.style.backgroundColor = '#09cae3';
                submitBtn.style.color = '#020617';
            } else {
                // run border animation
                borderWrapper.classList.add('active');

                submitBtn.disabled = true;
                submitBtn.classList.add('cursor-not-allowed', 'opacity-60');
                submitBtn.classList.remove('cursor-pointer');

                submitBtn.style.backgroundColor = '';
                submitBtn.style.backgroundImage = '';
                submitBtn.style.color = 'white';
            }
        }

        requiredFields.forEach(field => {
            field.addEventListener('input', updateButtonState);
            field.addEventListener('change', updateButtonState);
        });
        updateButtonState();

    </script>

</body>
</html>

