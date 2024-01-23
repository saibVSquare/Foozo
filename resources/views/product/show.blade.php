<!DOCTYPE html>
<html>

<head>
    <style>
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>
</head>

<body>
    <div>
        <h1>Make a One-Time Payment</h1>

        <form action="{{route('payment.process')}}" method="post" id="payment-form">
            @csrf
            <div id="card-element" style="width: 400px;">
                <!-- A Stripe Element will be inserted here. -->
            </div>
            <!-- Used to display form errors. -->
            <div id="card-errors" role="alert"></div>
            <button type="submit" style="margin-top:10px">Submit Payment</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // Set your Stripe public key
        var stripe = Stripe('{{ $stripePublicKey }}');
        // Create an instance of Elements
        var elements = stripe.elements();
        // Create an instance of the card Element
        var card = elements.create('card');

        // Add an instance of the card Element into the `card-element` div
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element
        card.addEventListener('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Disable the submit button to prevent repeated clicks
            document.querySelector('button').disabled = true;

            var returnUrl = 'https://your-website.com/checkout/success'; // Replace with your actual success URL
            // Create payment method
            stripe.createPaymentMethod({
                type: 'card',
                card: card,
            }).then(function (result) {
                if (result.error) {
                    // Inform the user if there was an error
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;

                    // Enable the submit button
                    document.querySelector('button').disabled = false;
                } else {
                    // Tokenize the payment method ID
                    var paymentMethodId = result.paymentMethod.id;
                    // Add the payment method ID to the form
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'payment_method');
                    hiddenInput.setAttribute('value', paymentMethodId);
                    form.appendChild(hiddenInput);

                    // Add the return_url to the form
                    var returnUrlInput = document.createElement('input');
                    returnUrlInput.setAttribute('type', 'hidden');
                    returnUrlInput.setAttribute('name', 'return_url');
                    returnUrlInput.setAttribute('value', 'http://127.0.0.1:8000/payment'); // Replace with your actual return URL
                    form.appendChild(returnUrlInput);

                    // Submit the form
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>