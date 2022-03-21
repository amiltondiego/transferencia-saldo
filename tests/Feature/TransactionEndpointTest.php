<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Exceptions\NotAuthorizedTransferException;
use App\Exceptions\NotCantPayerException;
use App\Exceptions\NotFoundPayeeException;
use App\Exceptions\NotRegisterTransferException;
use App\Exceptions\WithoutBalanceException;
use App\Jobs\NotifyPayee;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * @internal
 */
class TransactionEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function testTransactionWithoutAuthentication()
    {
        $response = $this->post('/api/transaction');

        $response->assertStatus(JsonResponse::HTTP_FOUND);
    }

    public function testTransactionNotFoundPayee()
    {
        $this->mockHTTP();

        $this->seed();

        Sanctum::actingAs(
            User::where('email', 'user-comum@gmail.com')->first(),
            ['*']
        );

        $response = $this->post('/api/transaction', [
            'value' => 20000,
            'payee' => 'not-found@gmail.com',
        ]);

        $exception = new NotFoundPayeeException();
        $response->assertStatus($exception->status());
        $response->assertJsonFragment($exception->contentMessage());
    }

    public function testTransactionWithoutBalance()
    {
        $this->mockHTTP();

        $this->seed();

        Sanctum::actingAs(
            User::where('email', 'user-comum@gmail.com')->first(),
            ['*']
        );

        $response = $this->post('/api/transaction', [
            'value' => 20000,
            'payee' => 'lojista@gmail.com',
        ]);

        $exception = new WithoutBalanceException();
        $response->assertStatus($exception->status());
        $response->assertJsonFragment($exception->contentMessage());
    }

    public function testTransactionNotCantPayer()
    {
        $this->mockHTTP();

        $this->seed();

        Sanctum::actingAs(
            User::where('email', 'lojista@gmail.com')->first(),
            ['*']
        );

        $response = $this->post('/api/transaction', [
            'value' => 10,
            'payee' => 'user-comum@gmail.com',
        ]);

        $exception = new NotCantPayerException();
        $response->assertStatus($exception->status());
        $response->assertJsonFragment($exception->contentMessage());
    }

    public function testTransactionWithAuthorization()
    {
        $this->mockHTTP();

        $this->seed();

        Sanctum::actingAs(
            User::where('email', 'user-comum@gmail.com')->first(),
            ['*']
        );

        $response = $this->post('/api/transaction', [
            'value' => 100,
            'payee' => 'lojista@gmail.com',
        ]);

        $response->assertStatus(JsonResponse::HTTP_CREATED);
    }

    public function testTransactionWithoutAuthorization()
    {
        $this->mockHTTP(false);

        $this->seed();

        Sanctum::actingAs(
            User::where('email', 'user-comum@gmail.com')->first(),
            ['*']
        );

        $response = $this->post('/api/transaction', [
            'value' => 100,
            'payee' => 'lojista@gmail.com',
        ]);

        $exception = new NotAuthorizedTransferException();
        $response->assertStatus($exception->status());
        $response->assertJsonFragment($exception->contentMessage());
    }

    public function testTransactionWithFailRegister()
    {
        $this->mockHTTP();

        $this->seed();

        Wallet::saving(fn () => false);

        Sanctum::actingAs(
            User::where('email', 'user-comum@gmail.com')->first(),
            ['*']
        );

        $response = $this->post('/api/transaction', [
            'value' => 100,
            'payee' => 'lojista@gmail.com',
        ]);

        $exception = new NotRegisterTransferException();
        $response->assertStatus($exception->status());
        $response->assertJsonFragment($exception->contentMessage());
    }

    public function testTransactionSuccess()
    {
        Queue::fake();

        $this->mockHTTP();

        $this->seed();

        Sanctum::actingAs(
            User::where('email', 'user-comum@gmail.com')->first(),
            ['*']
        );

        $response = $this->post('/api/transaction', [
            'value' => 100,
            'payee' => 'lojista@gmail.com',
        ]);

        Queue::assertPushed(NotifyPayee::class, 1);

        $response->assertStatus(JsonResponse::HTTP_CREATED);
    }

    private function mockHTTP($mockHttpAuthorizationOK = true)
    {
        Http::fake([
            'run.mocky.io/*' => Http::response($mockHttpAuthorizationOK ? [
                'message' => 'Autorizado',
            ] : [
                'message' => 'Não Autorizado',
            ]),
            'o4d9z.mocklab.io/*' => Http::response([
                'message' => 'Success',
            ]),
        ]);
    }
}
