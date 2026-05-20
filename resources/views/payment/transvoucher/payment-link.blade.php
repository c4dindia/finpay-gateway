{{-- resources/views/p6-payment-link.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }} </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Classic ICO for widest compatibility -->
  <link rel="icon" href="{{ asset('images/ryzen-fav-logo.png') }}" sizes="any">

  {{-- Bootstrap 5.3 & Icons (CDN). If you already include via Vite, you can remove these CDNs. --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --brand-blue: #11A9FF;   /* light blue */
      --brand-green:#2FBF71;   /* medium green */
      --brand-white:#ffffff;
    }
    body{
      background: radial-gradient(1200px 1200px at 80% -20%, rgba(17,169,255,.08), transparent 60%),
                  radial-gradient(1000px 1000px at -10% 120%, rgba(47,191,113,.10), transparent 60%),
                  #f7fbff;
    }
    .glass-card{
      backdrop-filter: blur(10px);
      background: rgba(255,255,255,.82);
      border: 1px solid rgba(255,255,255,.6);
      box-shadow: 0 10px 30px rgba(17,169,255,.12);
    }
    .brand-gradient{
      background: linear-gradient(135deg, var(--brand-blue), var(--brand-green));
    }
    .brand-btn{
      background: linear-gradient(135deg, var(--brand-blue), var(--brand-green));
      border: none;
    }
    .brand-btn:hover,
    .brand-btn:focus{
      filter: brightness(.95);
    }
    .mono-link{
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
      word-break: break-all;
    }
    .logo-ring{
      animation: floaty 6s ease-in-out infinite;
    }
    @keyframes floaty{
      0%,100% { transform: translateY(0); }
      50%     { transform: translateY(-6px); }
    }
  </style>
</head>
<body>

  <main class="container py-5 py-xxl-6">
    <div class="row justify-content-center" style="height: 100vh;">
      <div class="col-12 col-lg-10 col-xxl-8">

        {{-- Header / Logo --}}
        <div class="text-center mb-4 mb-lg-5">
          <!-- Inline SVG logo with light blue, medium green, white -->
          <img src="{{ asset('images/ryzen-fav-logo.png') }}" alt="Brand Logo" class="mb-3 brand-logo"  width="120" height="120" loading="eager" decoding="async">
          <h1 class="fw-bold lh-sm">Payment Link Generator</h1>
          <p class="text-secondary mb-0">Create a secure, shareable payment link in a single click.</p>
        </div>

        {{-- Card --}}
        <div class="glass-card rounded-4 p-4 p-md-5">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
              <span class="d-inline-flex align-items-center justify-content-center rounded-circle brand-gradient" style="width:48px;height:48px;">
                <i class="bi bi-link-45deg text-white fs-4"></i>
              </span>
              <div>
                <h2 class="h4 fw-semibold mb-0">Generate Payment Link</h2>
                <small class="text-muted">One link to share by SMS, email, or chat.</small>
              </div>
            </div>

            <div class="d-flex align-items-center gap-2">
              <button type="button" id="generate-link" class="btn brand-btn text-white btn-lg px-4">
                <i class="bi bi-magic me-2"></i>Generate
              </button>
            </div>
          </div>

          {{-- Loading --}}
          <div id="loading-spinner" class="d-none text-center py-4" aria-live="polite">
            <div class="spinner-border" role="status" aria-hidden="true"></div>
            <p class="text-muted mt-3 mb-0 small">Generating your link…</p>
          </div>

          {{-- Result / Link Section --}}
          <div id="link-section" class="d-none">
            <div class="alert alert-light border shadow-sm rounded-3 mb-3">
              <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                  <div>
                    <div class="fw-semibold text-secondary">Generated Link</div>
                    <div class="mono-link" id="generated-link">-</div>
                  </div>
                </div>
                <div class="d-flex gap-2">
                  <a id="payment-link" href="#" target="_blank" class="btn btn-outline-success">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Open
                  </a>
                  <button type="button" id="copy-link" class="btn btn-outline-primary">
                    <i class="bi bi-clipboard me-1"></i>Copy
                  </button>
                </div>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-12 col-md-12">
                <div class="p-3 rounded-3" style="background: linear-gradient(135deg, rgba(17,169,255,.08), rgba(47,191,113,.08));">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-shield-lock"></i>
                    <span class="fw-semibold">Check your payment status here:</span>
                  </div>
                  <small class="text-muted"><a href="" id="checkoutStatusLink" target="_blank"></a></small>
                </div>
              </div>
            </div>
          </div>

          {{-- Inline error (optional) --}}
          <div id="error-box" class="alert alert-danger d-none mt-4" role="alert"></div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-4">
          <small class="text-muted">Need help? Contact support.</small>
        </div>

      </div>
    </div>
  </main>

  {{-- jQuery + Bootstrap JS (keep jQuery because the provided code uses it) --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  $(document).ready(function() {
      const accId         = "{{ $accId }}";
      const $btn          = $('#generate-link');
      const $spinner      = $('#loading-spinner');
      const $linkSection  = $('#link-section');
      const $genLinkSpan  = $('#generated-link');
      const $openLinkBtn  = $('#payment-link');
      const $copyBtn      = $('#copy-link');
      const $errorBox     = $('#error-box');
      const $checkoutLinkTag = $('#checkoutStatusLink');

      function resetUI(){
        $genLinkSpan.text('-');
        $openLinkBtn.attr('href', '#');
        $linkSection.addClass('d-none');
        $errorBox.addClass('d-none').empty();
        $checkoutLinkTag.attr('href', '#');
      }

      $btn.on('click', function() {
          resetUI();
          $btn.prop('disabled', true);
          $spinner.removeClass('d-none');

          $.ajax({
              url: `/api/p6/${accId}/openPaymentLinkGenrator`,
              method: 'GET',
              success: function(response) {
                  if (response && response.success && response.link) {
                      $genLinkSpan.text(response.link);
                      $openLinkBtn.attr('href', response.link);
                      $linkSection.removeClass('d-none');
                      $checkoutLinkTag.attr('href', 'https://payment.ryzen-pay.com/p6/thank-you-page/'+ response.checkout_id);
                      $checkoutLinkTag.text('https://payment.ryzen-pay.com/p6/thank-you-page/'+ response.checkout_id);
                  } else {
                      $errorBox.removeClass('d-none').text('Failed to generate payment link.');
                  }
              },
              error: function(xhr) {
                  console.error(xhr.responseText || xhr);
                  $errorBox.removeClass('d-none').text('An error occurred while generating the payment link.');
              },
              complete: function() {
                  $btn.prop('disabled', false);
                  $spinner.addClass('d-none');
              }
          });
      });

      $copyBtn.on('click', async function(){
        const text = $genLinkSpan.text().trim();
        if (!text || text === '-') return;
        try {
          await navigator.clipboard.writeText(text);
          $(this).html('<i class="bi bi-clipboard-check me-1"></i>Copied');
          setTimeout(() => $(this).html('<i class="bi bi-clipboard me-1"></i>Copy'), 1500);
        } catch (e) {
          console.warn('Clipboard failed, selecting text...');
          const range = document.createRange();
          range.selectNodeContents($genLinkSpan[0]);
          const sel = window.getSelection();
          sel.removeAllRanges();
          sel.addRange(range);
        }
      });
  });
  </script>
</body>
</html>
