<?php

namespace App\Http\Controllers;

use App\Models\WalletBalance;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $customer_wallet = WalletBalance::where('customer_id', Auth::user()->id)->first();
        $wallet_transactions = WalletTransaction::where('customer_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10, ['*'], 'wallet_transactions');

        $data = [
            'customer_wallet' => $customer_wallet,
            'wallet_transactions' => $wallet_transactions
        ];
        return view('dashboard.home')->with($data);
    }
}