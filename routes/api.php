<?php

declare(strict_types=1);

use App\Http\Controllers\TransactionController;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/create-token', function (Request $request) {
    /** @var ?User $user */
    $user = User::where('email', $request->get('email'))->first();
    /** @var ResponseFactory $response */
    $response = response();

    if (is_null($user)) {
        return $response->json([], JsonResponse::HTTP_UNAUTHORIZED);
    }
    $token = $user->createToken('auth_token')->plainTextToken;

    return $response->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
});

Route::middleware('auth:sanctum')->post('/transaction', [TransactionController::class, 'transaction']);
