<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        #apple-pay-button {
            display: none;
            background-color: black;
            background-image: -webkit-named-image(apple-pay-logo-white);
            background-size: 100% 100%;
            background-origin: content-box;
            background-repeat: no-repeat;
            width: 100%;
            height: 44px;
            padding: 10px 0;
            border-radius: 10px;
        }
    </style>

    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<button id="apple-pay-button"></button>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>
    Stripe.setPublishableKey('pk_test_pyAji6er6sj1KeM06MlYOTsy00dkDuHTU2');

    Stripe.applePay.checkAvailability(function(available) {
        if (available) {
            document.getElementById('apple-pay-button').style.display = 'block';
        } else {
            alert('Apple Pay not Availabale');
        }
    });

    document.getElementById('apple-pay-button').addEventListener('click', beginApplePay);

    function beginApplePay() {
        var paymentRequest = {
            countryCode: 'US',
            currencyCode: 'USD',
            total: {
                label: 'reserved4you.de',
                amount: '19.99'
            }
        };
        var session = Stripe.applePay.buildSession(paymentRequest,
            function(result, completion) {

                $.post('/charges', { token: result.token.id }).done(function() {
                    completion(ApplePaySession.STATUS_SUCCESS);
                    // You can now redirect the user to a receipt page, etc.
                    window.location.href = '/success.html';
                }).fail(function() {
                    completion(ApplePaySession.STATUS_FAILURE);
                });

            }, function(error) {
                console.log(error.message);
            });

        session.oncancel = function() {
            console.log("User hit the cancel button in the payment window");
        };

        session.begin();
    }
</script>
</body>
</html>
