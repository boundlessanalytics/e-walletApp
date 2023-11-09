@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('includes._side_nav')
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            @include('includes._wallet_display')
            <h2>Wallet Transactions</h2>
            @include('includes._flash_messages')
            <div class="table-responsive">
                @if ($wallet_transactions->count() > 0)
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Currency</th>
                                <th>Amount</th>
                                <th>Reference</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Paid At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wallet_transactions as $wallet_transaction)
                                <tr>
                                    <th>{{$wallet_transaction->currency}}</th>
                                    <th>{{$wallet_transaction->amount}}</th>
                                    <th>{{$wallet_transaction->reference}}</th>
                                    <th>{{$wallet_transaction->transaction_type}}</th>
                                    <th>{{$wallet_transaction->status}}</th>
                                    <th>{{$wallet_transaction->paid_at}}</th>
                                </tr>
                            @endforeach                            
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <div class="pt-3"></div>  
                    {{$wallet_transactions->appends($_GET)->links()}}               
                @else
                    <div class="alert alert-danger" role="alert">
                        There are no transactions
                    </div>
                @endif
                
            </div>
        </main>
    </div>
</div>
@endsection
