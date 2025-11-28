<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // IMPORT NECESSAIRE
use Illuminate\Support\Facades\Mail;
use App\Repositories\PaymentRepository;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json([
            "data" => Payment::with('user')->orderByDesc('id')->get()
        ]);
    }

    public function webhook(Request $request, PaymentRepository $repo){
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
            $user = User::find($userId);

            if ($user) {
                $user->update(['certify_payment_status' => true]);

                // 🔹 Appel de ta fonction pour envoyer l'email et la facture PDF
                $this->sendPaymentSuccessEmail($user, [
                    'amount' => $data['amount'] ?? 'N/A',
                    'currency' => $data['currency'] ?? 'XOF',
                    'transaction_id' => $transactionId
                ]);

                // 🔹 SMS si tu veux
                $smsMessage = "Bonjour {$user->name}, votre paiement LaCasa a été effectué avec succès. Consultez votre email pour plus de détails et la facture.";
                $number = $user->phone1 ?: $user->phone2;
                $this->sendSms($number, $smsMessage);
            }
        }
        return response()->json(['status' => 'ok']);
    }

    public function sendSms($number, $message){
        $smsService = new SmsService ();
        $response = $smsService->send($number, $message);
        // Log::info($response);
        return response()->json($response);
    }

    // composer require barryvdh/laravel-dompdf
    public function sendPaymentSuccessEmail($user, $paymentData){
        // Générer PDF facture
        $pdf = PDF::loadView('emails.payment.invoice', [
            'user' => $user,
            'payment' => $paymentData
        ]);

        // Préparer et envoyer le mail
        Mail::send('emails.payment.success', [
            'user_name' => $user->name,
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'],
            'transaction_id' => $paymentData['transaction_id'],
            'date' => now()->format('d/m/Y H:i'),
        ], function ($message) use ($user, $pdf) {
            $message->to($user->email)
                    ->subject('Confirmation de votre paiement')
                    ->attachData($pdf->output(), 'facture.pdf', [
                        'mime' => 'application/pdf',
                    ]);
        });
    }
}

