// Create a Stripe client.
var stripe = Stripe(publishable_key);
  
// Create an instance of Elements.
var elements = stripe.elements();
  
// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
    base: {
        color: '#32325d',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
    }
};
  
// Create an instance of the card Element.
var card = elements.create('card',{hidePostalCode:true,style: style});
  
// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');
  
// Handle real-time validation errors from the card Element.
card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});
  
// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('click', function(event) {
    event.preventDefault();
  
    stripe.createToken(card).then(function(result) {
        if (result.error) {
            // Inform the user if there was an error.
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
        } else {
            // Send the token to your server.
            stripeTokenHandler(result.token);
        }
    });
});
  
// Submit the form with the token ID.
function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    $.ajax({
        url: 'index.php?route=extension/payment/tmd_stripe_payment/send&stripeToken='+token.id,
        type: 'post',
        dataType: 'json',
        data:'',
        beforeSend: function() {
            $('#confirm-order').button('loading');
        },
        complete: function() {
            $('#confirm-order').button('reset');
        },
        success: function(json) {
            if(json['status']=='error') {
              alert('error responce');
            }
            if(json['redirect']) {
              location = json['redirect'];
            }
             if(json['action']) {
             stripe.confirmCardPayment(json['clientSecret']).then(function(result) {
                    if (result.error) {
                        console.error("Payment failed:", result.error.message);
                    } else if (result.paymentIntent.status === 'succeeded') {
                          $.ajax({
                         url: 'index.php?route=extension/payment/tmd_stripe_payment/callback&stripeToken='+token.id,
                        type: 'post',
                        dataType: 'json',
                        data:result.paymentIntent,
                        success: function(json) {

                                if(json['warning']) {
                                  alert(json['warning']);
                                }
                                if(json['redirect']) {
                                  location = json['redirect'];
                                }
                        }
                         });
                    } else {
                        console.log("Payment requires further action. Status:", result.paymentIntent.status);
                    }
                });
            }
        }
    });
}