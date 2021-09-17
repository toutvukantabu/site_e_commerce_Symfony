<?php

namespace App\Stripe;

use App\Entity\Purchase;

class StripeService

{
    protected $secretKey;
    protected $publickey;

    public function __construct(string $secretKey, string $publicKey)
    {

        $this->secretKey = $secretKey;
        $this->publickey = $publicKey;
    }

    public function getPublicKey(): string
    {

        return $this->publickey;
    }

    public function getPaymentIntent(Purchase $purchase)
    {
        \Stripe\Stripe::setApiKey($this->secretKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $purchase->getTotal(),
            'currency' => 'eur'
        ]);
    }
}
