<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/**
 


 * @OA\Post(
 *     path="/register",
 *     summary="Register a new user",
 *     description="Creates a new user in the application",
 *     tags={"Authentication"},
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully"
 *     )
 * )
 */
Route::post('register', [AuthController::class, 'register']);

/**
 * @OA\Post(
 *     path="/login",
 *     summary="User login",
 *     description="Authenticate an existing user in the application",
 *     tags={"Authentication"},
 *     @OA\Response(
 *         response=200,
 *         description="User authenticated successfully"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="User logout",
     *     description="Disconnects the authenticated user from the application",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully"
     *     )
     * )
     */
    Route::post('logout', [AuthController::class, 'logout']);

    /**
     * @OA\Post(
     *     path="/refresh",
     *     summary="Token refresh",
     *     description="Refreshes the access token of the authenticated user",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed successfully"
     *     )
     * )
     */
    Route::post('refresh', [AuthController::class, 'refresh']);

    /**
     * @OA\Get(
     *     path="/user",
     *     summary="Authenticated user information",
     *     description="Returns information about the authenticated user",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="User information retrieved successfully"
     *     )
     * )
     */
    Route::get('user', [AuthController::class, 'user']);

    /**
     * @OA\Resource(
     *     path="/posts",
     *     summary="Post resources",
     *     description="Endpoints for managing posts",
     *     tags={"Posts"}
     * )
     */
    Route::resource('posts', PostController::class)
        ->except(['create', 'edit']);
});