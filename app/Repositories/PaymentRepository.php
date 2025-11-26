<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function findByTransactionId(string $transactionId): ?Payment
    {
        return Payment::where('transaction_id', $transactionId)->first();
    }

    public function markAsPaid(Payment $payment, array $data)
    {
        $payment->update([
            'status'            => 'paid',
            'payment_method'    => $data['payment_method'] ?? null,
            'payment_gateway'   => $data['payment_gateway'] ?? null,
            'payment_operator'  => $data['payment_operator'] ?? null,
            'payment_reference' => $data['payment_reference'] ?? null,
        ]);
    }

    public function saveWebhookPayload(Payment $payment, array $payload)
    {
        $payment->update(['webhook_payload' => $payload]);
    }
}
