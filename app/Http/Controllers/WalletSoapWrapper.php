<?php

namespace App\Http\Controllers;

class WalletSoapWrapper
{
    public function registerCustomer($document, $name, $email, $phone)
    {
        return app(WalletSoapController::class)->registerCustomer($document, $name, $email, $phone);
    }

    public function rechargeWallet($document, $phone, $amount)
    {
        return app(WalletSoapController::class)->rechargeWallet($document, $phone, $amount);
    }

    public function pay($document, $amount)
    {
        return app(WalletSoapController::class)->pay($document, $amount);
    }

    public function confirmPayment($sessionId, $token)
    {
        return app(WalletSoapController::class)->confirmPayment($sessionId, $token);
    }

    public function getBalance($document, $phone)
    {
        return app(WalletSoapController::class)->getBalance($document, $phone);
    }
}
