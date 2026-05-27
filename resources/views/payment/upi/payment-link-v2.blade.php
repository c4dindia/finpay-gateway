<html lang="en">

<head>
    <meta charset="utf-8">
    <title>UPI Payment V2 Link</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/fin-group-logo.svg') }}" sizes="any">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-blue: #11A9FF;
            --brand-green: #2FBF71;
            --surface: rgba(255, 255, 255, .72);
            --surface-border: rgba(255, 255, 255, .5);
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --radius: 20px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html{
             background: radial-gradient(ellipse 900px 700px at 75% -5%, rgba(17, 169, 255, .10), transparent),
                radial-gradient(ellipse 800px 600px at 10% 105%, rgba(47, 191, 113, .10), transparent),
                #f4f8fc;
        }

        .main {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            background: radial-gradient(ellipse 900px 700px at 75% -5%, rgba(17, 169, 255, .10), transparent),
                radial-gradient(ellipse 800px 600px at 10% 105%, rgba(47, 191, 113, .10), transparent),
                #f4f8fc;
            color: var(--text-primary);
            
        }

        .page-wrap {
            width: 100%;
            max-width: 720px;
            padding: 3rem 1rem;
        }

        /* ── Logo ── */
        .logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .logo-img {
            width: 72px;
            height: 72px;
            border-radius: 22px;
            object-fit: contain;

            animation: float 5s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        /* ── Header text ── */
        .page-title {
            font-size: 1.65rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: .35rem;
            letter-spacing: -.02em;
        }

        .page-subtitle {
            text-align: center;
            color: var(--text-secondary);
            font-size: .9rem;
            margin-bottom: 2rem;
        }

        /* ── Card ── */
        .main-card {
            backdrop-filter: blur(16px) saturate(1.6);
            -webkit-backdrop-filter: blur(16px) saturate(1.6);
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius);
            box-shadow:
                0 1px 2px rgba(0, 0, 0, .04),
                0 8px 32px rgba(17, 169, 255, .08);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: box-shadow .3s;
        }

        .main-card:hover {
            box-shadow:
                0 1px 2px rgba(0, 0, 0, .04),
                0 12px 40px rgba(17, 169, 255, .13);
        }

        .card-header-row {
            display: flex;
            align-items: center;
            gap: .85rem;
            margin-bottom: 1.75rem;
        }

        .card-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--brand-blue), var(--brand-green));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(17, 169, 255, .25);
        }

        .card-icon i {
            color: #fff;
            font-size: 1.15rem;
        }

        .card-header-text h2 {
            font-size: 1.05rem;
            font-weight: 600;
            margin: 0 0 .1rem;
        }

        .card-header-text p {
            font-size: .8rem;
            color: var(--text-secondary);
            margin: 0;
        }

        /* ── Input groups ── */
        .input-group-custom {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-group-custom label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .4rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: #94a3b8;
            transition: color .2s;
            pointer-events: none;
        }

        .input-wrap input,
        .input-wrap select {
            width: 100%;
            padding: .7rem .85rem .7rem .85rem;
            font-size: .92rem;
            font-weight: 500;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            color: var(--text-primary);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .input-wrap input::placeholder,
        .input-wrap select::placeholder {
            color: #b0bec5;
            font-weight: 400;
        }

        .input-wrap input:focus,
        .input-wrap select:focus {
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 3px rgba(17, 169, 255, .12);
        }

        .input-wrap input:focus+i,
        .input-wrap input:focus~i,
        .input-wrap select:focus+i,
        .input-wrap select:focus~i {
            color: var(--brand-blue);
        }

        /* number spinner hide */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* ── Generate button ── */
        .gen-btn {
            width: 100%;
            padding: .8rem;
            font-size: .95rem;
            font-weight: 600;
            border: none;
            border-radius: 14px;
            color: #fff;
            background: linear-gradient(135deg, var(--brand-blue), var(--brand-green));
            cursor: pointer;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 16px rgba(17, 169, 255, .22);
            margin-top: .5rem;
        }

        .gen-btn:hover:not(:disabled) {
            opacity: .92;
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(17, 169, 255, .30);
        }

        .gen-btn:active:not(:disabled) {
            transform: scale(.985);
        }

        .gen-btn:disabled {
            opacity: .45;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* ── Loader ── */
        .loader-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 0 1.5rem;
            animation: slideUp .3s ease;
        }

        .loader-ring {
            position: relative;
            width: 52px;
            height: 52px;
        }

        .loader-ring::before,
        .loader-ring::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 3px solid transparent;
        }

        .loader-ring::before {
            border-top-color: var(--brand-blue);
            border-right-color: var(--brand-green);
            animation: spin .9s linear infinite;
        }

        .loader-ring::after {
            inset: 6px;
            border-bottom-color: var(--brand-blue);
            border-left-color: var(--brand-green);
            opacity: .4;
            animation: spin .9s linear infinite reverse;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loader-pulse {
            position: absolute;
            inset: 14px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(17, 169, 255, .15), rgba(47, 191, 113, .15));
            animation: pulse 1.6s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(.85);
                opacity: .5;
            }

            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }

        .loader-text {
            margin-top: .9rem;
            font-size: .8rem;
            font-weight: 500;
            color: var(--text-secondary);
            letter-spacing: .02em;
        }

        .loader-dots::after {
            content: '';
            animation: dots 1.4s steps(4, end) infinite;
        }

        @keyframes dots {
            0% {
                content: '';
            }

            25% {
                content: '.';
            }

            50% {
                content: '..';
            }

            75% {
                content: '...';
            }
        }

        /* ── Result section ── */
        .results-wrap {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            animation: slideUp .4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-card {
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
            transition: box-shadow .2s;
        }

        .result-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .05);
        }

        .result-card--pay {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .result-card--status {
            background: linear-gradient(135deg, rgba(17, 169, 255, .05), rgba(47, 191, 113, .05));
            border: 1px solid #bfe8f7;
        }

        .result-header {
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-bottom: .6rem;
        }

        .result-header i {
            font-size: .85rem;
        }

        .result-header span {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .result-card--pay .result-header {
            color: #16a34a;
        }

        .result-card--status .result-header {
            color: var(--brand-blue);
        }

        .link-input {
            width: 100%;
            padding: .55rem .75rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: .78rem;
            font-weight: 500;
            line-height: 1.4;
            color: var(--text-primary);
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            outline: none;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .link-input:focus {
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 3px rgba(17, 169, 255, .08);
            white-space: normal;
            text-overflow: unset;
        }

        .link-actions {
            display: flex;
            gap: .5rem;
            margin-top: .6rem;
        }

        .link-action-btn {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .4rem .9rem;
            border-radius: 9px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: var(--text-secondary);
            font-size: .76rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: background .15s, color .15s, border-color .15s, transform .1s;
        }

        .link-action-btn:hover {
            border-color: var(--brand-blue);
            color: var(--brand-blue);
            background: #f0f7ff;
            transform: translateY(-1px);
        }

        .link-action-btn:active {
            transform: scale(.97);
        }

        .link-action-btn.copied {
            background: #16a34a;
            border-color: #16a34a;
            color: #fff;
        }

        .link-action-btn i {
            font-size: .8rem;
        }

        /* ── Error ── */
        .err-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 14px;
            padding: 1rem 1.15rem;
            font-size: .85rem;
            font-weight: 500;
            margin-top: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            animation: slideUp .3s ease;
        }

        .err-box i {
            font-size: 1.1rem;
            margin-top: 1px;
            flex-shrink: 0;
        }

        /* ── Footer ── */
        .page-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: .78rem;
            color: #94a3b8;
        }

        /* ── Responsive ── */
        @media (max-width: 575.98px) {
            .main-card {
                padding: 1.5rem 1.15rem;
            }

            .page-title {
                font-size: 1.35rem;
            }

            .input-row {
                flex-direction: column;
            }

            .link-actions {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

    <div class="main">
        <div class="page-wrap">
            <div class="logo-wrap">
                <img src="{{ asset('images/fin-group-logo.svg') }}" alt="FinPay" class="logo-img" loading="eager" decoding="async">
            </div>

            <h1 class="page-title">Payment Link Generator</h1>
            <p class="page-subtitle">Create a secure, shareable payment link in seconds.</p>

            <div class="main-card">
                <div class="card-header-row">
                    <div class="card-icon">
                        <i class="bi bi-link-45deg"></i>
                    </div>
                    <div class="card-header-text">
                        <h2>UPI Payment V2 Link</h2>
                        <p>One link to share by SMS, email, or chat.
                        </p>
                    </div>
                </div>

                <form id="generate-form" method="POST">
                    @csrf

                    <input type="hidden" name="accId" id="accId" value="{{ $accId }}">
                    <div class="d-flex gap-3 input-row">
                        <div class="input-group-custom flex-fill">
                            <label for="input-currency">Currency</label>
                            <div class="input-wrap">
                                <select name="currency" id="input-currency" required>
                                    <option value="" disabled selected>Select currency</option>
                                    <option value="INR">INR</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 input-row">
                        <div class="input-group-custom flex-fill">
                            <label for="input-amount">Amount</label>
                            <div class="input-wrap">
                                <input type="number" name="amount" id="input-amount" placeholder="0.00" min="10" step="any" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 input-row">
                        <div class="input-group-custom flex-fill">
                            <label for="input-amount">Description</label>
                            <div class="input-wrap">
                                <input type="text" name="description" id="description" placeholder="Enter description" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 input-row">
                        <div class="input-group-custom flex-fill">
                            <label for="input-amount">Phone</label>
                            <div class="input-wrap">
                                <input type="number" name="phone" id="phone" placeholder="Enter phone" required maxlength="10"
                                    minlength="10"
                                    pattern="[0-9]{10}"
                                    inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 input-row">
                        <div class="input-group-custom flex-fill">
                            <label for="input-amount">URL</label>
                            <div class="input-wrap">
                                <input type="url" name="url" id="url" placeholder="https://" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="generate-link" class="gen-btn" disabled>
                        <i class="bi bi-magic me-2"></i>Generate Link
                    </button>
                </form>

                <div id="loading-spinner" class="loader-wrap d-none" aria-live="polite">
                    <div class="loader-ring">
                        <div class="loader-pulse"></div>
                    </div>
                    <span class="loader-text">Generating your link<span class="loader-dots"></span></span>
                </div>

                <div id="link-section" class="results-wrap d-none">
                    <div class="result-card result-card--pay">
                        <div class="result-header">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Generated Link</span>
                        </div>
                        <input type="text" id="payment-link" class="link-input" readonly value="">
                        <div class="link-actions">
                            <a href="" target="_blank" class="link-action-btn" id="open-link">
                                <i class="bi bi-box-arrow-up-right"></i>Open
                            </a>
                            <button type="button" class="link-action-btn copy-btn" data-target="payment-link">
                                <i class="bi bi-clipboard"></i>Copy
                            </button>
                        </div>
                    </div>
                </div>

                <div id="error-box" class="err-box d-none" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span id="error-msg"></span>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            var $btn = $('#generate-link');
            var $amount = $('#input-amount');
            var $currency = $('#input-currency');
            var description = $('#description');
            var phone = $('#phone');
            var url = $('#url');
            var $form = $('#generate-form');
            var $spinner = $('#loading-spinner');
            var $errorBox = $('#error-box');
            var $errorMsg = $('#error-msg');

            function checkInputs() {
                var amountValue = parseFloat($amount.val());
                var hasAmount = !isNaN(amountValue) && amountValue >= 10;
                var hasCurrency = $currency.val() !== null && $currency.val().trim() !== '';
                var hasDescription = description.val().trim() !== '';
                var phoneValue = phone.val().trim();
                var hasPhone = /^[0-9]{10}$/.test(phoneValue);
                var hasUrl = url.val().trim() !== '' && url[0].checkValidity();

                $btn.prop('disabled', !(hasAmount && hasCurrency && hasDescription && hasPhone && hasUrl));
            }

            function clearError() {
                $errorBox.addClass('d-none');
                $errorMsg.text('');
            }

            $amount.on('input', checkInputs);
            $currency.on('change', checkInputs);
            description.on('input', checkInputs);
            phone.on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
                checkInputs();
            });
            url.on('input', checkInputs);

            $form.on('submit', function(e) {
                e.preventDefault();

                clearError(); // remove previous error
                $btn.prop('disabled', true);
                $('#link-section').addClass('d-none');
                $spinner.removeClass('d-none');

                generateLink();
            });

            function generateLink() {
                var amount = $amount.val().trim();
                var currency = $currency.val().trim();
                var accountId = $('#accId').val().trim();
                var description = $('#description').val().trim();
                var phone = $('#phone').val().trim();
                var url = $('#url').val().trim();

                $.ajax({
                    url: '/p23/generate-payment-link/v2',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        amount: amount,
                        currency: currency,
                        accId: accountId,
                        description: description,
                        phone: phone,
                        url: url
                    },
                    success: function(response) {
                        clearError();

                        setTimeout(function() {
                            $spinner.addClass('d-none');
                            if (response.success === true) {
                                $('#payment-link').val(response.link);
                                $('#open-link').attr('href', response.link);

                                $('#link-section').removeClass('d-none');
                                $form[0].reset();
                                checkInputs();
                            } else {
                                $errorBox.removeClass('d-none');
                                $errorMsg.text(response.error);
                                checkInputs();
                            }
                        }, 1500);

                    },
                    error: function(xhr) {
                        clearError();

                        $errorBox.removeClass('d-none');
                        $errorMsg.text(xhr.responseJSON?.message || 'Something went wrong.');
                        checkInputs();
                    }
                });
            }

            checkInputs();

            $(document).on('click', '.copy-btn', async function() {
                var $this = $(this);
                var $input = $('#' + $this.data('target'));
                var text = $input.val().trim();
                if (!text) return;
                try {
                    await navigator.clipboard.writeText(text);
                    $this.addClass('copied').html('<i class="bi bi-check-lg"></i> Copied');
                    setTimeout(function() {
                        $this.removeClass('copied').html('<i class="bi bi-clipboard"></i> Copy');
                    }, 1500);
                } catch (e) {
                    $input[0].select();
                }
            });
        });
    </script>

</body>

</html>