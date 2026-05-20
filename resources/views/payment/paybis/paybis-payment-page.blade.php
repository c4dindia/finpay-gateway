<!doctype html>
<html lang="en">
  <head>
    <title>Crypto Exchange Payment</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body onload="openWidget()">
    <div id="paybis-widget-container">
        <!-- This will be where the widget is shown -->
    </div>

    <div class="text-center justify-contents-center py-5 my-5">
        <a href="#" class="text-center btn btn-primary p-2 my-5">Redirect Back To Website</a>
    </div>

    <!-- Paybis Sandbox Widget JavaScript Integration -->
    <script>
        ! function() {
            if (window.PartnerExchangeWidget = window.PartnerExchangeWidget || {
                    open(e) {
                        window.partnerWidgetSettings = {
                            immediateOpen: e
                        }
                    }
                }, "PartnerExchangeWidget" !== window.PartnerExchangeWidget.constructor.name) {
                (() => {
                    const e = document.createElement("script");
                    e.type = "text/javascript";
                    e.defer = true;
                    e.src = "https://widget.sandbox.paybis.com/partner-exchange-widget.js"; // Sandbox URL
                    const t = document.getElementsByTagName("script")[0];
                    t.parentNode.insertBefore(e, t);
                })();
            }
        }();
    </script>

    <script>
        function openWidget() {
            const requestId = '{{ $requestId }}'; // Replace with your unique request ID
            window.PartnerExchangeWidget.open({
                requestId: requestId,
            });
        }
    </script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
