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
                    <form method="POST" action="{{route('processPaystackPayment')}}"   class="needs-validation" id="NGN">
                      @csrf
                      <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                          <label for="currency">Select currency</label>
                          <input type="text" class="form-control" value="NGN" readonly>              
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="row">
                              <div class="col-md-12 mb-3">
                                <label for="amount">Amount</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" placeholder="" required>                 
                              </div>
                              <input type="hidden" name="currency" value="NGN">                
                            </div>       
                            <input type="hidden" name="transaction_type" value="Deposit">
                        </div>
                        <div class="col-md-12 mb-3">
                          <hr class="mb-4">
                          <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now</button>                  
                        </div>
                      </div>
                    </form>
                  </div>
            </div>             
        </main>
    </div>
</div>
    
@endsection