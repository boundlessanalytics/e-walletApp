<div class="row mb-3">
    <!-- First Column -->
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">NGN Wallet</h5>
          <p class="card-text">₦ {{$customer_wallet !== null ? number_format($customer_wallet->where('currency', 'NGN')->value('amount')) : 0}}</p>
        </div>
      </div>
    </div>

    <!-- Second Column -->
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">USD Wallet</h5>
          <p class="card-text">$ {{$customer_wallet !== null ? number_format($customer_wallet->where('currency', 'USD')->value('amount')) : 0}}</p>
        </div>
      </div>
    </div>

    <!-- Third Column -->
    <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">AUD Wallet</h5>
            <p class="card-text">$ {{$customer_wallet !== null ? number_format($customer_wallet->where('currency', 'AUD')->value('amount')) : 0}}</p>
          </div>
        </div>
      </div>
</div>