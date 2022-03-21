<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\Wallet;
use App\Models\WalletType;

class WalletRepository
{
    public function getBalance(UserInterface $user, float $value): float
    {
        return $this->getPayeeBalance($user) - $this->getPayerBalance($user) - $value;
    }

    public function registerTransfer(UserInterface $payer, UserInterface $payee, float $value): bool
    {
        $wallet = new Wallet();
        $wallet->payer_id = $payer->id;
        $wallet->payee_id = $payee->id;
        $wallet->value = $value;
        $wallet->type = WalletType::success();

        return $wallet->save();
    }

    private function getPayeeBalance(UserInterface $user): float
    {
        return floatval(Wallet::where('payee_id', $user->id)
            ->where('type', WalletType::success())
            ->sum('value'));
    }

    private function getPayerBalance(UserInterface $user): float
    {
        return floatval(Wallet::where('payer_id', $user->id)
            ->where('type', '!=', WalletType::fail())
            ->sum('value'));
    }
}
