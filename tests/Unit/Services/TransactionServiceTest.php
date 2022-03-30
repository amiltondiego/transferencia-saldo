<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Exceptions\NotAuthorizedTransferException;
use App\Exceptions\NotCantPayerException;
use App\Exceptions\NotFoundPayeeException;
use App\Exceptions\NotFoundPayerException;
use App\Exceptions\NotRegisterTransferException;
use App\Exceptions\WithoutBalanceException;
use App\Interfaces\ExternalAuthorizationInterface;
use App\Interfaces\UserInterface;
use App\Jobs\NotifyPayee;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\ExternalAuthorizationService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @internal
 */
class TransactionServiceTest extends TestCase
{
    public function testTransferBalanceNotFoundPayee()
    {
        /** @var User $user */
        $user = $this->mock(User::class);

        /** @var UserInterface $payee */
        $payee = $this->mock(UserInterface::class);

        /** @var UserInterface $payer */
        $payer = $this->mock(UserInterface::class);

        /** @var UserRepository $userRepository */
        $userRepository = $this->mock(UserRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('findByEmail')->once();
        });

        /** @var WalletRepository $walletRepository */
        $walletRepository = $this->mock(WalletRepository::class);

        /** @var ExternalAuthorizationService $externalService */
        $externalService = $this->mock(ExternalAuthorizationService::class);

        $service = new TransactionService(
            $payee, 
            $payer, 
            $userRepository, 
            $walletRepository, 
            $externalService
        );

        $this->expectException(NotFoundPayeeException::class);
        $service->transferBalance(100, $user, 'not-found-payee@gmail.com');
    }

    public function testTransferBalanceNotFoundPayer()
    {
        /** @var User $user */
        $user = $this->mock(User::class, function (MockInterface $mock){
            $mock->shouldReceive('getAttribute')->andReturn('found-user@gmail.com');
        });

        /** @var UserInterface $payee */
        $payee = $this->mock(UserInterface::class);

        /** @var UserInterface $payer */
        $payer = $this->mock(UserInterface::class);

        /** @var UserRepository $userRepository */
        $userRepository = $this->mock(UserRepository::class, function (MockInterface $mock) use($payee) {
            $mock->shouldReceive('findByEmail')->once()->andReturn($payee);
            $mock->shouldReceive('findByEmail')->once();
        });

        /** @var WalletRepository $walletRepository */
        $walletRepository = $this->mock(WalletRepository::class);

        /** @var ExternalAuthorizationService $externalService */
        $externalService = $this->mock(ExternalAuthorizationService::class);

        $service = new TransactionService(
            $payee, 
            $payer, 
            $userRepository, 
            $walletRepository, 
            $externalService
        );

        $this->expectException(NotFoundPayerException::class);
        $service->transferBalance(100, $user, 'found-payer@gmail.com');
    }

    public function testTransferBalanceWithoutBalance()
    {
        /** @var User $user */
        $user = $this->mock(User::class, function (MockInterface $mock){
            $mock->shouldReceive('getAttribute')->andReturn('found-user@gmail.com');
        });

        /** @var UserInterface $payee */
        $payee = $this->mock(UserInterface::class);

        /** @var UserInterface $payer */
        $payer = $this->mock(UserInterface::class);

        /** @var UserRepository $userRepository */
        $userRepository = $this->mock(UserRepository::class, function (MockInterface $mock) use($payee, $payer) {
            $mock->shouldReceive('findByEmail')->once()->andReturn($payee);
            $mock->shouldReceive('findByEmail')->once()->andReturn($payer);
        });

        /** @var WalletRepository $walletRepository */
        $walletRepository = $this->mock(WalletRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalance')->once()->andReturn(0);
        });

        /** @var ExternalAuthorizationService $externalService */
        $externalService = $this->mock(ExternalAuthorizationService::class);

        $service = new TransactionService(
            $payee, 
            $payer, 
            $userRepository, 
            $walletRepository, 
            $externalService
        );

        $this->expectException(WithoutBalanceException::class);
        $service->transferBalance(100, $user, 'found-payer@gmail.com');
    }

    public function testTransferBalanceNotCantPayer()
    {
        /** @var User $user */
        $user = $this->mock(User::class, function (MockInterface $mock){
            $mock->shouldReceive('getAttribute')->andReturn('found-user@gmail.com');
        });

        /** @var UserInterface $payee */
        $payee = $this->mock(UserInterface::class);

        /** @var UserInterface $payer */
        $payer = $this->mock(UserInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('canPayer')->once()->andReturnFalse();
        });

        /** @var UserRepository $userRepository */
        $userRepository = $this->mock(UserRepository::class, function (MockInterface $mock) use($payee, $payer) {
            $mock->shouldReceive('findByEmail')->once()->andReturn($payee);
            $mock->shouldReceive('findByEmail')->once()->andReturn($payer);
        });

        /** @var WalletRepository $walletRepository */
        $walletRepository = $this->mock(WalletRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalance')->once()->andReturn(100);
        });

        /** @var ExternalAuthorizationService $externalService */
        $externalService = $this->mock(ExternalAuthorizationService::class);

        $service = new TransactionService(
            $payee, 
            $payer, 
            $userRepository, 
            $walletRepository, 
            $externalService
        );

        $this->expectException(NotCantPayerException::class);
        $service->transferBalance(100, $user, 'found-payer@gmail.com');
    }

    public function testTransferBalanceNotAuthorizedTransfer()
    {
        /** @var User $user */
        $user = $this->mock(User::class, function (MockInterface $mock){
            $mock->shouldReceive('getAttribute')->andReturn('found-user@gmail.com');
        });

        /** @var UserInterface $payee */
        $payee = $this->mock(UserInterface::class);

        /** @var UserInterface $payer */
        $payer = $this->mock(UserInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('canPayer')->once()->andReturnTrue();
        });

        /** @var UserRepository $userRepository */
        $userRepository = $this->mock(UserRepository::class, function (MockInterface $mock) use($payee, $payer) {
            $mock->shouldReceive('findByEmail')->once()->andReturn($payee);
            $mock->shouldReceive('findByEmail')->once()->andReturn($payer);
        });

        /** @var WalletRepository $walletRepository */
        $walletRepository = $this->mock(WalletRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalance')->once()->andReturn(100);
        });

        /** @var ExternalAuthorizationService $externalService */
        $externalService = $this->mock(ExternalAuthorizationService::class, function (MockInterface $mock) {
            $mock->shouldReceive('hasAuthorization')->once()->andReturnFalse();
        });

        $service = new TransactionService(
            $payee, 
            $payer, 
            $userRepository, 
            $walletRepository, 
            $externalService
        );

        $this->expectException(NotAuthorizedTransferException::class);
        $service->transferBalance(100, $user, 'found-payer@gmail.com');
    }

    public function testTransferBalanceNotRegisterTransfer()
    {
        /** @var User $user */
        $user = $this->mock(User::class, function (MockInterface $mock){
            $mock->shouldReceive('getAttribute')->andReturn('found-user@gmail.com');
        });

        /** @var UserInterface $payee */
        $payee = $this->mock(UserInterface::class);

        /** @var UserInterface $payer */
        $payer = $this->mock(UserInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('canPayer')->once()->andReturnTrue();
        });

        /** @var UserRepository $userRepository */
        $userRepository = $this->mock(UserRepository::class, function (MockInterface $mock) use($payee, $payer) {
            $mock->shouldReceive('findByEmail')->once()->andReturn($payee);
            $mock->shouldReceive('findByEmail')->once()->andReturn($payer);
        });

        /** @var WalletRepository $walletRepository */
        $walletRepository = $this->mock(WalletRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalance')->once()->andReturn(100);
            $mock->shouldReceive('registerTransfer')->once()->andReturnFalse();
        });

        /** @var ExternalAuthorizationService $externalService */
        $externalService = $this->mock(ExternalAuthorizationService::class, function (MockInterface $mock) {
            $mock->shouldReceive('hasAuthorization')->once()->andReturnTrue();
        });

        $service = new TransactionService(
            $payee, 
            $payer, 
            $userRepository, 
            $walletRepository, 
            $externalService
        );

        $this->expectException(NotRegisterTransferException::class);
        $service->transferBalance(100, $user, 'found-payer@gmail.com');
    }

    public function testTransferBalanceSuccess()
    {
        Queue::fake();

        /** @var User $user */
        $user = $this->mock(User::class, function (MockInterface $mock){
            $mock->shouldReceive('getAttribute')->andReturn('found-user@gmail.com');
        });

        /** @var UserInterface $payee */
        $payee = $this->mock(UserInterface::class);

        /** @var UserInterface $payer */
        $payer = $this->mock(UserInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('canPayer')->once()->andReturnTrue();
        });

        /** @var UserRepository $userRepository */
        $userRepository = $this->mock(UserRepository::class, function (MockInterface $mock) use($payee, $payer) {
            $mock->shouldReceive('findByEmail')->once()->andReturn($payee);
            $mock->shouldReceive('findByEmail')->once()->andReturn($payer);
        });

        /** @var WalletRepository $walletRepository */
        $walletRepository = $this->mock(WalletRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalance')->once()->andReturn(100);
            $mock->shouldReceive('registerTransfer')->once()->andReturnTrue();
        });

        /** @var ExternalAuthorizationService $externalService */
        $externalService = $this->mock(ExternalAuthorizationService::class, function (MockInterface $mock) {
            $mock->shouldReceive('hasAuthorization')->once()->andReturnTrue();
        });

        $service = new TransactionService(
            $payee, 
            $payer, 
            $userRepository, 
            $walletRepository, 
            $externalService
        );

        $service->transferBalance(100, $user, 'found-payer@gmail.com');

        Queue::assertPushed(NotifyPayee::class, 1);
    }

}
