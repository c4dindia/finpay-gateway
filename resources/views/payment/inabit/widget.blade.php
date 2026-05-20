<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>RyzenPay – Customer Widget Demo</title>
    <link rel="icon" href="{{ asset('images/Rayzen-Pay-logo.png') }}" type="image/x-icon">
    <script src="https://www.inabit.biz/widget.js"></script>

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

        /* ---------------- BUTTON ---------------- */
        button {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            padding: 14px 26px;
            margin: 10px auto;
            cursor: pointer;
            border-radius: 8px;
            font-size: 17px;
            display: block;
            width: 260px;
            text-align: center;
            font-weight: 600;
            transition: 0.3s ease;
            box-shadow: 0 0 20px rgba(0, 200, 255, 0.6);
        }

        button:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 0 30px rgba(0, 200, 255, 0.9);
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
    <h2>RyzenPay – Customer Widget</h2>

    @if(!$tokenId)
        <p style="color:#ff6b6b;">⚠️ No tokenId found. Use <strong>?token=YOUR_TOKEN</strong> in URL</p>
    @else
        <p><strong>Token ID:</strong> {{ $tokenId }}</p>
    @endif


    <!-- WIDGET BOX -->
    <div class="widget-box">

        <!-- Only Redirect Button -->
        <button onclick="openRedirect()">🚀 Open Redirect Mode</button>

        <!-- Iframe widget -->
        <div id="terminal-payment"></div>
    </div>


    <script>
        const tokenId = "{{ $tokenId }}";

        function openRedirect() {
            if (!tokenId) return alert("Missing tokenId!");
            window.openWidget(tokenId, { isToken: true });
        }

        // AUTO-LOAD IFRAME
        document.addEventListener("DOMContentLoaded", () => {
            if (!tokenId) return;
            window.openWidget(tokenId, {
                mode: 'iframe',
                fullWidth: true,
                isToken: true,
                container: '#terminal-payment'
            });
        });
    </script>

</body>
</html>
