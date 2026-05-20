<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            background: #f5f7fa;
            z-index: 0;
        }

        .thank-you-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 1rem;
        }

        .card {
            padding: 3rem;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.015);
        }

        .checkmark {
            font-size: 3rem;
            color: green;
        }

        @media (max-width: 768px) {
            .card {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    @php
        $status = strtolower(trim($transaction->payment_status ?? ''));

        $isGreen = in_array($status, ['completed', 'success', 'succeeded']);
        $isRed   = in_array($status, ['rejected', 'cancelled', 'canceled', 'declined', 'expired', 'failed']);

        $statusStyle = $isGreen
            ? 'color:#15803d;background:#dcfce7;border:1px solid #86efac;'
            : ($isRed
                ? 'color:#b91c1c;background:#fee2e2;border:1px solid #fca5a5;'
                : 'color:#a16207;background:#fef9c3;border:1px solid #fde047;');
    @endphp

    <div id="particles-js"></div>

    <div class="thank-you-container">
        <div class="card">
            <div class="checkmark"></div>
            <h2 class="mt-3">Thank You!</h2>

            <p class="text-muted">
                Your transaction's Current Status:
                <span class="px-2 py-1 rounded-3 fw-semibold align-middle"
                      style="{{ $statusStyle }}">
                    {{ $transaction->payment_status }}
                </span>
            </p>
            <p>Thank you for choosing <strong>Ryzen-Pay</strong> as your smart payment service.</p>
            <a href="https://ryzen-pay.com/" class="btn btn-primary mt-3">Return to Home</a>
        </div>
    </div>

    <!-- Initialize Particles.js -->
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 60,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#00aaff"
                },
                "shape": {
                    "type": "circle"
                },
                "opacity": {
                    "value": 0.3,
                    "random": true
                },
                "size": {
                    "value": 12,
                    "random": true
                },
                "line_linked": {
                    "enable": false
                },
                "move": {
                    "enable": true,
                    "speed": 4.0,
                    "direction": "none",
                    "out_mode": "out"
                }
            },
            "interactivity": {
                "events": {
                    "onhover": {
                        "enable": false,
                        "mode": "bubble"
                    }
                }
            },
            "retina_detect": true
        });
    </script>
</body>
</html>
