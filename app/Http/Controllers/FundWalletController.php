<?php

namespace App\Http\Controllers;

use App\Http\Services\CustomHelpers;
use App\Models\WalletBalance;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Stripe\Exception\CardException;
use Stripe\InvoiceItem;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Unicodeveloper\Paystack\Facades\Paystack;

class FundWalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function initializePaystackPayment()
    {
        return view('payments.paystack');
    }

    public function processPaystackPayment(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required|string',
        ]);

        $currency = $request->currency;
        $amount = $request->amount;

        $request['amount'] = $amount * 100; // Amount in kobo to paystack
        $request['currency'] = $currency; // To paystack
        $request['email'] = Auth::user()->email;

        // End-to-end security implementation
        $random = app(CustomHelpers::class)->generateCode(4);
        $esecure = Auth::user()->id . $random . time();
        Cache::put('esecure', $esecure, now()->addMinutes(15));
        $end_to_end = Crypt::encrypt($esecure);

        $request['metadata'] = [
            'user' => Auth::user(),
            'esecure' => $end_to_end,
            'transaction_type' => $request->transaction_type
        ];

        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            return Redirect::back()->withMessage(['msg' => 'The paystack token has expired. Please refresh the page and try again.', 'type' => 'error']);
        }
    }

    public function initializeStripePayment(Request $request)
    {
        return view('payments.stripe');
    }

    public function processStripePayment(Request $request)
    {
        try {
            // Set your Stripe secret key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Get the user making the payment
            $user = $this->getUser($request);

            $amountInCents = (int) $request->input('amount') * 100; // Convert dollars to cents

            // Ensure the currency is lowercase
            $currency = strtolower($request->currency);

            // Create or retrieve the Stripe customer
            $customer = $this->getOrCreateStripeCustomer($user, $request);

            // Create a Payment Intent
            $paymentIntent = $this->createPaymentIntent($user, $request, $currency);

            // Confirm the Payment Intent with the attached payment method
            $this->confirmPaymentIntent($paymentIntent, $user);

            // Create an invoice item
            $this->createInvoiceItem($user, $amountInCents, $currency);

            // Update wallet balance
            $this->updateWalletBalance($user, $request, $amountInCents);

            return response()->json([
                'status' => true,
                'message' => 'Payment successful.',
            ], 201);
        } catch (CardException $e) {
            return $this->handleCardError($e);
        } catch (Exception $e) {
            return $this->handleOtherError($e);
        }
    }

    // Helper functions
    private function getUser(Request $request)
    {
        return $request->user();
    }

    private function getOrCreateStripeCustomer($user, $request)
    {
        if (!$user->stripe_id) {
            $customer = $user->createAsStripeCustomer();
            $this->attachPaymentMethodToCustomer($user, $request->payment_method);
            $user->stripe_id = $customer->id;
            $user->save();
        }

        return $user;
    }


    private function attachPaymentMethodToCustomer($user, $paymentMethod)
    {
        $paymentMethod = $user->addPaymentMethod($paymentMethod);
        if ($paymentMethod) {
            $user->updateDefaultPaymentMethod($paymentMethod->id);
        }
    }

    private function createPaymentIntent($user, $request, $currency)
    {
        $amountInCents = (int) $request->input('amount') * 100;
        $formMetadata = json_encode($request->metadata, true);

        return PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => $currency,
            'payment_method_types' => ['card'],
            'description' => 'One-time payment',
            'confirmation_method' => 'automatic',
            'customer' => $user->stripe_id,
            'metadata' => [
                'form_meta' => $formMetadata,
                'reference' => bin2hex(random_bytes(5)),
            ],
        ]);
    }

    private function confirmPaymentIntent($paymentIntent, $user)
    {
        $defaultPaymentMethod = $user->defaultPaymentMethod();
        if ($defaultPaymentMethod) {
            $paymentIntent->confirm(['payment_method' => $defaultPaymentMethod->id]);
        }
    }

    private function createInvoiceItem($user, $amountInCents, $currency)
    {
        InvoiceItem::create([
            'customer' => $user->stripe_id,
            'amount' => $amountInCents,
            'currency' => $currency,
            'description' => 'Invoice for one-time payment',
        ]);
    }

    private function updateWalletBalance($user, $request, $amountInCents)
    {
        $currency = strtoupper($request->currency);

        $customerWallet = WalletBalance::firstOrNew([
            'customer_id' => $user->id,
            'currency' => $currency,
        ]);

        $customerWallet->amount = $customerWallet->amount + ($amountInCents / 100);

        $customerWallet->save();

        WalletTransaction::create([
            'customer_id' => $user->id,
            'currency' => $currency,
            'reference' => bin2hex(random_bytes(5)),
            'transaction_type' => 'Deposit',
            'status' => 'success',
            'amount' => ($amountInCents / 100),
            'paid_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }


    private function handleCardError(CardException $e)
    {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], 422);
    }

    private function handleOtherError(Exception $e)
    {
        return response()->json([
            'status' => false,
            'message' => 'An error occurred: ' . $e->getMessage(),
        ], 500);
    }
}