<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\NotAuthorizedTransferException;
use App\Exceptions\NotCantPayerException;
use App\Exceptions\NotFoundPayeeException;
use App\Exceptions\NotFoundPayerException;
use App\Exceptions\NotRegisterTransferException;
use App\Exceptions\WithoutBalanceException;
use App\Interfaces\UserInterface;
use App\Jobs\NotifyPayee;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;

class TransactionService
{
    public function __construct(
        private UserInterface $payee,
        private UserInterface $payer,
        private UserRepository $userRepository,
        private WalletRepository $walletRepository,
        private ExternalAuthorizationService $externalAuthorizationService
    ) {
    }

    /**
     * @throws NotFoundPayeeException
     * @throws NotFoundPayerException
     * @throws WithoutBalanceException
     * @throws NotCantPayerException
     * @throws NotAuthorizedTransferException
     * @throws NotRegisterTransferException
     */
    public function transferBalance(float $value, User $user, string $payee): bool
    {
        $this->getPayeeByEmail($payee)
            ->getPayerByEmail($user)
            ->hasBalance($value)
            ->canPayer()
            ->hasAuthorization()
            ->registerTransfer($value)
            ->notifyPayee();

        return true;
    }

    /**
     * @throws NotFoundPayeeException
     */
    private function getPayeeByEmail(string $payeeEmail): self
    {
        $payee = $this->userRepository->findByEmail($payeeEmail);

        if (is_null($payee)) {
            throw new NotFoundPayeeException();
        }

        $this->payee = $payee;

        return $this;
    }

    /**
     * @throws NotFoundPayerException
     */
    private function getPayerByEmail(User $user): self
    {
        $payer = $this->userRepository->findByEmail($user->email);

        if (is_null($payer)) {
            throw new NotFoundPayerException();
        }

        $this->payer = $payer;

        return $this;
    }

    /**
     * @throws WithoutBalanceException
     */
    private function hasBalance(float $value): self
    {
        if (0 >= $this->walletRepository->getBalance($this->payer, $value)) {
            throw new WithoutBalanceException();
        }

        return $this;
    }

    /**
     * @throws NotCantPayerException
     */
    private function canPayer(): self
    {
        if (!$this->payer->canPayer($this->payee)) {
            throw new NotCantPayerException();
        }

        return $this;
    }

    /**
     * @throws NotAuthorizedTransferException
     */
    private function hasAuthorization(): self
    {
        if (!$this->externalAuthorizationService->hasAuthorization($this->payer)) {
            throw new NotAuthorizedTransferException();
        }

        return $this;
    }

    /**
     * @throws NotRegisterTransferException
     */
    private function registerTransfer(float $value): self
    {
        if (!$this->walletRepository->registerTransfer($this->payer, $this->payee, $value)) {
            throw new NotRegisterTransferException();
        }

        return $this;
    }

    private function notifyPayee(): void
    {
        NotifyPayee::dispatchSync($this->payee);
    }
}
