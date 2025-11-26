<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KPrimePayService
{
    protected string $merchantNumber;
    protected string $secretKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantNumber = env('KPRIME_MERCHANT_NUMBER');
        $this->secretKey      = env('KPRIME_SECRET_KEY');
        $this->baseUrl        = "https://api-kprimepay.ro-cobra.net/v1/checkout";
    }

    /**
     * Créer un checkout KPrimePay
     */
    public function createCheckout(array $params)
    {
        // payload requis par KPRIMEPAY
        $payload = [
            "merchant_number"  => $this->merchantNumber,
            "transaction_id"   => $params['transaction_id'],
            "currency"         => $params['currency'],     // XOF / USD / EUR
            "amount"           => $params['amount'],       // montant
            "with_fees"        => $params['with_fees'] ?? 0,
            "mode"             => $params['mode'] ?? 1,    // 1 ou 2
            "description"      => $params['description'] ?? null,
            "return_url"       => $params['return_url'],
            "locale"           => $params['locale'] ?? "fr",
            "custom_meta_data" => $params['metadata'] ?? [],
        ];

        $response = Http::withHeaders([
            "auth_token"   => $this->secretKey,
            "Content-Type" => "application/json",
        ])->post($this->baseUrl , $payload);

        if (!$response->successful()) {
            return [
                "success" => false,
                "message" => "KPrimePay error",
                "details" => $response->json(),
            ];
        }

        $data = $response->json();

        return [
            "success"      => isset($data['status']) && $data['status'] === true,
            "session_id"   => $data['data']['kpp_session_id'] ?? null,
            "checkout_url" => $data['data']['checkout_url'] ?? null,
            "raw"          => $data
        ];
    }
}