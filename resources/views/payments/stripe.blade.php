@extends('layouts.app')

@section('content')

@include('includes._flash_messages')

<div class="container-fluid">
  <div class="row">
      @include('includes._side_nav')
      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4"> 
          <div class="row">
              <div class="col-md-12 order-md-1">
                  <h4 class="mb-3">Details</h4>
                  <form id="payment-form" action="#" method="POST">
                    @csrf               
                    
                    <div class="row mb-3">  
                      <div class="col-md-6">
                        <label for="currency">Currency:</label>
                        <select class="form-control" id="currency" name="currency" required>
                          <option value="">Please select</option>
                          <option value="aud">AUD</option>
                          <option value="usd">USD</option>    
                          <!-- Add other currency options as needed -->
                      </select>
                      </div>
                    </div>
                    
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <label for="amount">Amount:</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                      </div>
                    </div>
      
                    <label for="card">Card Details:</label>
                    <div id="card-element" class="mb-3">
                      <!-- A Stripe Element will be inserted here. -->
                    </div> 
      
                    <input type="hidden" id="metadata" name="metadata" value="{{json_encode(['transaction_type' => 'Deposit', 'email' => 'test'])}}">
      
                    <!-- Used to display form payment success. -->
                    <div id="success-message" role="alert" class="mb-3"></div>
                
                    <!-- Used to display form errors. -->
                    <div id="error-message" role="alert"></div>
                
                    <button class="btn btn-primary btn-lg btn-block" id="submit">Submit Payment</button>
                  </form>
              </div>
          </div>             
      </main>
  </div>
</div>    
@endsection

@push('scripts')
  <script>

    // Create a Stripe client.
    var stripe = Stripe('{{ config('services.stripe.key') }}');

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Create an instance of the card Element.
    // var cardElement = elements.create('card');

    var cardElement = elements.create('card', {
      style: {
        base: {
          fontSize: '16px',
          fontFamily: 'Arial, sans-serif',
          color: '#333', // Add your desired text color here
        },
      },
    });

    // Add an instance of the card Element into the `card-element` div.
    cardElement.mount('#card-element');

    var form = document.getElementById('payment-form');
    var successElement = document.getElementById('success-message');
    var errorElement = document.getElementById('error-message');

    form.addEventListener('submit', function(event) {
          event.preventDefault();

          var amount = document.getElementById('amount').value;
          var currency = document.getElementById('currency').value;
          var metadata = document.getElementById('metadata').value;

          // Show loading pop-up
          Swal.fire({
              title: 'Processing Payment...',
              allowOutsideClick: false,
              onBeforeOpen: () => {
                  Swal.showLoading();
              }
          });

          stripe.createPaymentMethod({
              type: 'card',
              card: cardElement
          }).then(function(result) {
            // console.log(result);
              if (result.error) {
                  errorElement.textContent = result.error.message;
                  Swal.close(); // Close the loading pop-up
              } else {
                  // Send the payment method ID to your server
                  fetch('{{ route('process_payment') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-Token': '{{ csrf_token() }}'
                      },
                      body: JSON.stringify({ 
                        payment_method: result.paymentMethod.id,
                        amount: amount,
                        currency: currency,
                        metadata: metadata 
                      })
                  }).then(function(response) {
                      return response.json();
                  }).then(function(data) {
                      Swal.close(); // Close the loading pop-up
                      if (data.status === true) {
                          // Payment successful
                          // successElement.textContent = data.message;
                          Swal.fire({
                              icon: 'success',
                              title: 'Payment Successful',
                              text: 'Your payment was successfully processed.' + data.message,
                              confirmButtonText: 'OK'
                          }).then((result) => {
                              if (result.isConfirmed) {
                                  // Redirect to the success page using JavaScript
                                  window.location.href = "{{ route('home') }}";
                              }
                          });
                      } else {
                          // errorElement.textContent = 'An error occurred while processing the payment.';
                          console.log(data.message);
                          Swal.fire({
                              icon: 'error',
                              title: 'Payment Error',
                              text: 'An error occurred while processing the payment. ' + data.message,
                              confirmButtonText: 'OK'
                          });

                      }
                  });
              }
          });
    }); 
    
    
  </script>  
@endpush