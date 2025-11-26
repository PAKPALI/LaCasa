<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\PaymentRepository;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json([
            "data" => Payment::with('user')->orderByDesc('id')->get()
        ]);
    }

    public function webhook(Request $request, PaymentRepository $repo)
    {
        $payload = $request->all();
        Log::info( $payload );

        // KPrimePay envoie le status dans payload['status']
        $kppStatus = $payload['status'] ?? null;
        $data      = $payload['data'] ?? [];
        $transactionId = $data['transaction_id'] ?? null;

        if (!$transactionId || !$kppStatus) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Sauvegarder la transaction si elle existe
        $payment = $repo->findByTransactionId($transactionId);
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // 🔹 1 - Sauvegarder payload complet
        $repo->saveWebhookPayload($payment, $payload);

        // 🔹 2 - Si KPrimePay indique success, marquer comme payé
        if ($kppStatus === 'success' && $data['payment_status'] === 'TRANSACTION-COMPLETED') {
            $repo->markAsPaid($payment, $data);

            // On récupère l'utilisateur via la metadata envoyée à la création du checkout
            $userId = $data['custom_meta_data']['user_id'] ?? $payment->user_id;

            User::where('id', $userId)->update(['certify_payment_status' => true]);
        }

        return response()->json(['status' => 'ok']);
    }
}

