<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Interfaces\HttpExceptionInterface;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function __construct(
        private ResponseFactory $response,
        private TransactionService $transactionService
    ) {
    }

    public function transaction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|numeric|min:1',
            'payee' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->response->json($validator->errors(), JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            /** @var User $payer */
            $payer = $request->user();
            $this->transactionService->transferBalance(floatval($request->get('value')), $payer, strval($request->get('payee')));

            return $this->response->json(['transaction registred.'], JsonResponse::HTTP_CREATED);
        } catch (HttpExceptionInterface $exception) {
            return $this->response->json($exception->contentMessage(), $exception->status());
        }
    }
}
