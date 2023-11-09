<?php

namespace App\Http\Controllers;

use App\Models\WalletBalance;
use App\Models\WalletTransaction;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Throwable;
use Unicodeveloper\Paystack\Facades\Paystack;

class CallbackController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function paystack()
    {
        $paymentDetails = Paystack::getPaymentData();


        $end_to_end = isset($paymentDetails['data']['metadata']['esecure']) ? $paymentDetails['data']['metadata']['esecure'] : 'Not Verified';

        try {
            $end_to_end = Crypt::decrypt($end_to_end);
        } catch (Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        $esecure = Cache::get('esecure');
        if ($end_to_end !== $esecure) {
            return redirect()->back()->with('error', 'esecure has verified that this is not a secure transaction');
        }


        $customer_wallet = WalletBalance::where([
            'customer_id' => $paymentDetails['data']['metadata']['user']['id'],
            'currency' => $paymentDetails['data']['currency']
        ])->first();

        if (!$customer_wallet) {
            $customer_wallet = new WalletBalance();
            $customer_wallet->customer_id = $paymentDetails['data']['metadata']['user']['id'];
            $customer_wallet->currency = $paymentDetails['data']['currency'];
            $customer_wallet->amount = 0;
            $customer_wallet->save();
        }

        // return $paymentDetails;

        try {
            // Update customer's wallet balance
            $amount = floatval($paymentDetails['data']['amount']) / 100;
            $newBalance = (floatval($customer_wallet['amount']) + $amount);
            $customer_wallet->update(['amount' => $newBalance]);

            // Convert ISO 8601 to MySQL datetime format
            $paidAt = (new DateTime($paymentDetails['data']['paid_at']))->format('Y-m-d H:i:s');

            // Record the transaction
            WalletTransaction::create([
                'customer_id' => $paymentDetails['data']['metadata']['user']['id'],
                'currency' => $paymentDetails['data']['currency'],
                'reference' => $paymentDetails['data']['reference'],
                'transaction_type' => $paymentDetails['data']['metadata']['transaction_type'],
                'status' => 'success',
                'amount' => $amount,
                'paid_at' => $paidAt
            ]);
        } catch (Throwable $th) {
            return to_route('paystack_pay')->with("error", $th->getMessage());
        }
        return to_route('home')->with('success', 'Wallet Funded Successfully');
    }
}