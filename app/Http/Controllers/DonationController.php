<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use App\Services\KPrimePayService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Repositories\PaymentRepository;

class DonationController extends Controller
{
    public function pay(Request $request, KPrimePayService $kpp, PaymentRepository $repo)
    {
        $request->validate([
            "amount" => "required|numeric|min:100"
        ]);

        $user = Auth::user();
        $user_id = $user ? $user->id : null;
        $amount = (int) $request->amount;

        // 🔹 1 - Créer une transaction interne
        $transaction_id = 'DON' . strtoupper(uniqid());

        $repo->create([
            "transaction_id" => $transaction_id,
            "user_id"        => $user_id,
            "amount"         => $amount,
            "currency"       => "XOF",
            "status"         => "pending",
        ]);

        // 🔹 2 - Appel API KPrimePay
        $checkout = $kpp->createCheckout([
            "transaction_id" => $transaction_id,
            "currency"       => "XOF",
            "amount"         => $amount,
            "with_fees"      => 1,
            "mode"           => 1,
            "description"    => "Don",
            "return_url"     => config('app.url') . '/review',
            "metadata" => ["user_id" => $user_id ?? 0],
        ]);

        if (!is_array($checkout) || !isset($checkout["success"]) || !$checkout["success"]) {
            return response()->json([
                "status" => false,
                "message" => "Erreur KPrimePay",
                "details"  => $checkout
            ], 500);
        }

        return response()->json([
            "status" => true,
            "payment_url" => $checkout["checkout_url"],
        ]);
    }
}