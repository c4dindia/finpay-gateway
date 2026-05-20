<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Crypto POS Purchase Widget</title>
    <link rel="icon" href="{{ asset('images/Rayzen-Pay-logo.png') }}" type="image/x-icon">
    {{-- <script src="https://stage.inabit.biz/widget.js" ></script>  sandbox --}}
    <script src="https://www.inabit.biz/widget.js" ></script> {{-- production --}}

    <style>
        :root {
            --primary: #00d4ff;
            --secondary: #0066ff;
            --bg-dark: #04070d;
            --grid-line: rgba(0, 225, 255, 0.06);
            --text-light: #e9e9e9;
        }

        /* ---------------- BACKGROUND NEON GRID ---------------- */
        body {
            margin: 0;
            padding: 40px 20px;
            font-family: "Poppins", Arial, sans-serif;
            background: var(--bg-dark);
            color: var(--text-light);
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(to right, var(--grid-line) 1px, transparent 1px),
                linear-gradient(to bottom, var(--grid-line) 1px, transparent 1px);
            background-size: 45px 45px;
            pointer-events: none;
            z-index: -3;
        }

        h2 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 15px;
            animation: fadeInDown 1s ease-out;
        }

        p {
            text-align: center;
            animation: fadeIn 1.2s ease-out;
        }

        /* ---------------- WIDGET BOX ---------------- */
        .widget-box {
            width: 100%;
            max-width: 850px;
            margin: 40px auto;
            padding: 25px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(12px);
            animation: fadeIn 1.6s ease-out;
        }

        #terminal-payment {
            margin-top: 20px;
            width: 100%;
            min-height: 600px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.03);
            animation: fadeInUp 1.6s ease-out;
        }

        /* ---------------- CRYPTO FLOATING ICONS ---------------- */
        .floating-icon {
            position: fixed;
            width: 110px; /* bigger */
            opacity: 0.18;
            z-index: -1;
            filter: drop-shadow(0 0 20px rgba(0, 255, 255, 0.6));
            animation: drift 12s infinite ease-in-out,
                       rotate3d 20s infinite linear;
        }

        /* Random drifting motion */
        @keyframes drift {
            0% { transform: translate(0px, 0px) rotate(0deg); }
            25% { transform: translate(25px, -40px) rotate(5deg); }
            50% { transform: translate(-20px, -10px) rotate(-5deg); }
            75% { transform: translate(30px, 30px) rotate(3deg); }
            100%{ transform: translate(0px, 0px) rotate(0deg); }
        }

        /* Slow 3D-like rotation */
        @keyframes rotate3d {
            from { transform: rotateY(0deg); }
            to { transform: rotateY(360deg); }
        }

        /* ---------------- ANIMATIONS ---------------- */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>

<body>

    <!-- BIG FLOATING CRYPTO ICONS -->
    <img src="https://cryptologos.cc/logos/bitcoin-btc-logo.png"
         class="floating-icon"
         style="top:10%; left:6%; animation-delay:0s;">

    <img src="https://cryptologos.cc/logos/ethereum-eth-logo.png"
         class="floating-icon"
         style="top:75%; left:10%; animation-delay:1s;">

    <img src="https://cryptologos.cc/logos/tether-usdt-logo.png"
         class="floating-icon"
         style="top:40%; right:10%; animation-delay:2s;">

    <img src="https://cryptologos.cc/logos/binance-coin-bnb-logo.png"
         class="floating-icon"
         style="top:15%; right:6%; animation-delay:3s;">

    <img src="https://cryptologos.cc/logos/solana-sol-logo.png"
         class="floating-icon"
         style="top:55%; left:45%; animation-delay:4s;">

    <img src="https://cryptologos.cc/logos/cardano-ada-logo.png"
         class="floating-icon"
         style="top:80%; right:30%; animation-delay:5s;">

    <img src="https://cryptologos.cc/logos/dogecoin-doge-logo.png"
         class="floating-icon"
         style="top:25%; left:35%; animation-delay:6s;">


    <!-- TITLE -->
    <h2>RyzenPay – Crypto POS Purchase Widget</h2>

    @if(!$tokenId)
        <p style="color:#ff6b6b;">⚠️ No tokenId found. Use <strong>?token=YOUR_TOKEN</strong> in URL</p>
    @else
        <p><strong>Token:</strong> {{ $tokenId }}</p>
    @endif

    <!-- WIDGET BOX -->
    <div class="widget-box">
        <!-- Iframe widget -->
        <div id="terminal-payment"></div>
    </div>

    <script>
      const purchaseId = @json($tokenId);
      document.addEventListener("DOMContentLoaded", () => {
        if (!purchaseId) return;

        window.openWidget(purchaseId, {
          mode: 'iframe',
          fullWidth: true,
          container: '#terminal-payment'
        });
      });
    </script>


</body>
</html>

{{--
Instructions for Widget Implementation
To implement the widget on your site, please adhere to the following instructions.

Step 1: Implement Server-Side Terminal Call
 curl --location https://api.inabit.biz/v1/purchase
 --header Content-Type: application/json
 --Authorization: Bearer a907b8f22e306d618e1df3c686225e8c2c736c002ff92029dcec32c77a429cda
 data-row-{ title: <Your title goes here>,
    subTitle: <your subtitle goes here>,
        siteName: <your siteName goes here>,
            purchaseIdentifier: <your purchase identifier goes here>,
                fiatAmount: <fiat amount goes here>,//number type
                    fiatCurrency: <fiat currency goes here> }
 Response: The API will return a JSON object containing the purchaseId which will be required in subsequent steps
    { "data": {
     "id": "purchaseId",
      }
    }

 Widget API Key: a907b8f22e306d618e1df3c686225e8c2c736c002ff92029dcec32c77a429cda

Step 2: Add Script Tag to page Header a.
 Add the following script tag to your page header:
    <script src="https://www.inabit.biz/widget.js" ></script>

Step 3: Call open.widget Function Call openwidget function with purchase id, should open in popup After the customer clicks on the button (buy with crypto for example )
// Open the widget using the purchase ID returned in the previous step: window.openWidget(purchaseId);
// To open the widget in a popup, you can use: window.openWidget(purchaseId, { mode: 'popup' });
--}}
