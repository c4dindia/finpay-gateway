<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Payment - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            display: none;
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
    <p class="text-center text-xl text-blue-700 font-semibold mb-6">Amount to be paid: <span class="text-teal-600">{{ $checkoutData->currency }} {{ number_format((float) $checkoutData->amount, 2) }}</span></p>

    <form method="POST" action="{{ route('makeSZPayment' ,['checkout_id' => $checkoutData->checkout_id]) }}">
        @csrf

        <!-- User Info -->
        <div class="grid md:grid-cols-2 gap-4 mb-6">
            <!-- First Name -->
            <div>
                <label class="block text-gray-700 mb-1">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}"
                       class="w-full border p-2 rounded @error('first_name') border-red-500 @else border-gray-300 @enderror">
                @error('first_name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label class="block text-gray-700 mb-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}"
                       class="w-full border p-2 rounded @error('last_name') border-red-500 @else border-gray-300 @enderror">
                @error('last_name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border p-2 rounded @error('email') border-red-500 @else border-gray-300 @enderror">
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Mobile -->
            <div>
                <label class="block text-gray-700 mb-1">Mobile</label>
                <input type="text" name="mobile" value="{{ old('mobile') }}"
                       class="w-full border p-2 rounded @error('mobile') border-red-500 @else border-gray-300 @enderror">
                @error('mobile')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Address -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 mb-1">Address</label>
                <input type="text" name="address" value="{{ old('address') }}"
                       class="w-full border p-2 rounded @error('address') border-red-500 @else border-gray-300 @enderror">
                @error('address')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- City -->
            <div>
                <label class="block text-gray-700 mb-1">City</label>
                <input type="text" name="city" value="{{ old('city') }}"
                       class="w-full border p-2 rounded @error('city') border-red-500 @else border-gray-300 @enderror">
                @error('city')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- State -->
            <div>
                <label class="block text-gray-700 mb-1">State</label>
                <input type="text" name="state" value="{{ old('state') }}"
                       class="w-full border p-2 rounded @error('state') border-red-500 @else border-gray-300 @enderror">
                @error('state')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Country -->
            <div>
                <label class="block text-gray-700 mb-1">Country</label>
                <select name="country_code"
                        class="w-full border p-2 rounded @error('country_code') border-red-500 @else border-gray-300 @enderror">
                    <option value="" {{ old('country_code') == '' ? 'selected' : '' }}>*Please Select</option>
                    @php
                        $countries = [
                            'US' => 'United States',
                            'IN' => 'India',
                            'GB' => 'United Kingdom',
                            'CY' => 'Cyprus',
                            'CA' => 'Canada',
                            'AU' => 'Australia',
                            'DE' => 'Germany',
                            'FR' => 'France',
                            'IT' => 'Italy',
                            'ES' => 'Spain',
                            'JP' => 'Japan',
                            'CN' => 'China',
                            'BR' => 'Brazil',
                            'ZA' => 'South Africa',
                            'AE' => 'United Arab Emirates',
                            'SG' => 'Singapore'
                        ];
                    @endphp
                    @foreach ($countries as $code => $name)
                        <option value="{{ $code }}" {{ old('country_code') == $code ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('country_code')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Postal Code -->
            <div>
                <label class="block text-gray-700 mb-1">Postal Code</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                       class="w-full border p-2 rounded @error('postal_code') border-red-500 @else border-gray-300 @enderror">
                @error('postal_code')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

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

           <!-- UPI TAB -->
           {{-- <input type="radio" id="tab-upi" name="payment_method" value="UPI" class="hidden peer/upi" {{ old('payment_method') == 'UPI' ? 'checked' : '' }}>
           <label for="tab-upi"
                  class="tab-label flex-1 text-center cursor-pointer py-3 px-4 border-r border-gray-200 bg-white text-gray-700 transition-all duration-200 peer-checked/upi:bg-blue-600 peer-checked/upi:text-white peer-checked/upi:font-bold">
               Enter UPI / VPA
           </label> --}}

           <!-- QR TAB -->
           {{-- <input type="radio" id="tab-qr" name="payment_method" value="QR" class="hidden peer/qr" {{ old('payment_method') == 'QR' ? 'checked' : '' }}>
           <label for="tab-qr"
                  class="tab-label flex-1 text-center cursor-pointer py-3 px-4 bg-white text-gray-700 transition-all duration-200 peer-checked/qr:bg-blue-600 peer-checked/qr:text-white peer-checked/qr:font-bold">
               Scan to Pay (QR Code)
           </label> --}}
        </div>


            <!-- Payment Sections -->
            <div id="card-section" class="payment-section bg-gray-50 p-6 rounded-lg border mb-4 shadow-sm">
                <!-- Card Holder Name & Card Number Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Card Holder Name -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Card Holder Name</label>
                        <input type="text" name="card_holder_name" value="{{ old('card_holder_name') }}"
                               class="w-full p-3 border rounded-md @error('card_holder_name') border-red-500 @else border-gray-300 @enderror">
                        <span class="text-sm text-gray-500 mt-1 block">Enter the full name as on your card.</span>
                        @error('card_holder_name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

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
                        <input type="text" name="expiry_year" maxlength="4" value="{{ old('expiry_year') }}"
                               class="w-full p-3 border rounded-md @error('expiry_year') border-red-500 @else border-gray-300 @enderror">
                        <span class="text-sm text-gray-500 mt-1 block">Last two digits of year</span>
                        @error('expiry_year')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">CVV</label>
                        <input type="password" name="cvv" maxlength="4"
                               class="w-full p-3 border rounded-md @error('cvv') border-red-500 @else border-gray-300 @enderror">
                        <span class="text-sm text-gray-500 mt-1 block">3 or 4-digit code</span>
                        @error('cvv')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="upi-section" class="payment-section bg-gray-50 p-4 rounded-lg border mb-4">
                <label class="block text-gray-700 font-medium mb-2">UPI ID (or VPA)</label>
                <input type="text" name="upi_id" value="{{ old('upi_id') }}"
                       class="w-full p-2 border rounded-md @error('upi_id') border-red-500 @else border-gray-300 @enderror" placeholder="example@upi">
                @error('upi_id')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <div id="qr-section" class="payment-section bg-gray-50 p-4 rounded-lg border mb-4">
                <div id="qr-loader" class="text-center text-gray-600">Loading QR...</div>
                <div id="qr-result" class="text-center hidden">
                    <img id="qr-image" src="" alt="QR Code" class="mx-auto mb-3 w-48 h-48 object-contain">
                    <p><a id="upi-link" href="#" target="_blank" class="text-blue-500 underline">Pay using UPI App</a></p>
                </div>
            </div>

            <button id="proceed-btn" type="submit"
                    class="w-full max-w-xs mx-auto bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition hidden">
                Proceed to Pay
            </button>
        </div>
    </form>

    <!-- Transaction Status Panel -->
    <div id="transaction-panel"
         class="hidden text-center p-6 rounded-lg shadow-md max-w-2xl mx-auto mt-8 border transition-all duration-300">
      <div id="tx-icon" class="text-4xl mb-3">ℹ️</div>
      <h3 id="tx-title" class="text-2xl font-bold mb-2">Transaction</h3>
      <p id="tx-desc" class="text-lg font-medium"></p>
    </div>
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

    // 🔹 Apply UI dynamically based on status
    function applyStatusUI(status, message) {
        const panel = $('#transaction-panel');
        const icon  = $('#tx-icon');
        const title = $('#tx-title');
        const desc  = $('#tx-desc');

        const s = (status || '').toString().trim().toLowerCase();

        // reset styles
        panel.removeClass(
          'bg-green-50 border-green-200 text-green-800 ' +
          'bg-yellow-50 border-yellow-200 text-yellow-800 ' +
          'bg-red-50 border-red-200 text-red-800'
        );

        const map = {
          awaiting: {
            panel: 'bg-yellow-50 border-yellow-200 text-yellow-800',
            icon: 'ℹ️', // info
            title: 'Awaiting Confirmation',
            desc: 'Your payment is created and awaiting confirmation.',
          },
          captured: {
            panel: 'bg-green-50 border-green-200 text-green-800',
            icon: '✅', // correct
            title: 'Payment Captured',
            desc: 'Your payment has been successfully captured.',
          },
          failed: {
            panel: 'bg-red-50 border-red-200 text-red-800',
            icon: '❌', // cross
            title: 'Payment Failed',
            desc: 'Your payment did not go through. Please try again.',
          },
          default: {
            panel: 'bg-yellow-50 border-yellow-200 text-yellow-800',
            icon: 'ℹ️',
            title: `Transaction ${status || ''}`.trim(),
            desc: 'Current payment status updated.',
          }
        };

        const conf = map[s] || map.default;

        panel.addClass(conf.panel);
        icon.text(conf.icon);
        title.text(conf.title);
        desc.text(message || conf.desc);

        panel.removeClass('hidden');
    }

    // ⬇️ Stage-aware polling: pre-watcher (form showing) and post-watcher (panel showing)
    const FINAL_STATES = ['captured', 'failed'];
    let preWatcher = null;
    let postWatcher = null;

    function startPreWatcher(){
      if (preWatcher) return;
      preWatcher = setInterval(checkTransactionStatus, 2000);
    }
    function stopPreWatcher(){
      if (!preWatcher) return;
      clearInterval(preWatcher);
      preWatcher = null;
    }
    function startPostWatcher(){
      if (postWatcher) return;
      postWatcher = setInterval(checkTransactionStatus, 2000);
    }
    function stopPostWatcher(){
      if (!postWatcher) return;
      clearInterval(postWatcher);
      postWatcher = null;
    }

    // ⬇️ REPLACE your checkTransactionStatus with this version
    function checkTransactionStatus() {
      $.ajax({
        url: `/api/p7/get-status/{{ $checkoutData->checkout_id }}?_=${Date.now()}`, // cache-buster
        method: 'GET',
        cache: false, // jQuery hint
        success: function (response) {
          let s = (response.status || '').toString().trim().toLowerCase();

          // If your API sometimes returns "processed" before a tx row exists,
          // treat it as "awaiting" for UI so the icon shows ℹ️
          if (s === 'processed') s = 'awaiting';

          if (!s) return;

          if (s === 'created') {
            $('#transaction-panel').addClass('hidden');
            $('form').show();
            stopPostWatcher(); startPreWatcher();
            return;
          }

          $('form').hide();
          applyStatusUI(s, response.message);
          stopPreWatcher(); startPostWatcher();

          if (['captured','failed','expired','cancelled','refunded'].includes(s)) {
            stopPostWatcher();
          }
        },
        error: function () {
          console.error('Failed to check transaction status.');
        }
      });
    }

    $(document).ready(function () {
      @if (!old('payment_method'))
        $('input[name="payment_method"]').prop('checked', false);
      @endif
      $('.payment-section').hide();
      $('#proceed-btn').hide();

      $('input[name="payment_method"]').on('change', function () {
        const method = $(this).val();

        $('.payment-section').hide();
        $('#proceed-btn').hide();

        if (method === 'CARD') {
          $('#card-section').show();
          $('#proceed-btn').show();
        } else if (method === 'UPI') {
          $('#upi-section').show();
          $('#proceed-btn').show();
        } else if (method === 'QR') {
          $('#qr-section').show();
          $('#qr-loader').show();
          $('#qr-result').hide();

          $.ajax({
            url: '/api/p7/get-qr-code',
            method: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              checkout_id: '{{ $checkoutData->checkout_id }}',
              first_name: $('input[name="first_name"]').val(),
              last_name: $('input[name="last_name"]').val(),
              email: $('input[name="email"]').val(),
              mobile: $('input[name="mobile"]').val(),
              country_code: $('select[name="country_code"]').val(),
            },
            success: function (response) {
              $('#qr-image').attr('src', "data:image/png;base64," + response.payment_html);
              $('#upi-link').attr('href', response.payment_link);
              $('#qr-loader').hide();
              $('#qr-result').show();
            },
            error: function (xhr) {
              $('#qr-loader').text("Fill above form first.");
              if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let messages = Object.values(errors).flat().join('\n');
                Swal.fire({ title: 'Validation Error', text: messages, icon: 'warning' });
              } else if (xhr.responseJSON?.error) {
                Swal.fire({ title: 'Error', text: xhr.responseJSON.error, icon: 'error' });
              } else {
                Swal.fire({ title: 'Unexpected Error', text: 'Something went wrong. Please try again.', icon: 'error' });
              }
            }
          });
        }
      });

      // restore old selection
      const selectedMethod = '{{ old('payment_method') }}';
      if (selectedMethod) {
        $(`input[name="payment_method"][value="${selectedMethod}"]`).trigger('change');
      }

      // Start initial watcher (poll while form is visible)
      startPreWatcher();

      // Optional: run an immediate check so UI is up-to-date instantly
      checkTransactionStatus();

      // Optional: tidy up on navigation
      $(window).on('beforeunload', function(){
        stopPreWatcher();
        stopPostWatcher();
      });
    });

</script>

</body>
</html>
