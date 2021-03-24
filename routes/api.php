<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CategoryController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// auth
Route::group(['middleware' => ['auth:sanctum'],], function () {
    Route::post('user', [ProfileController::class, 'update']);
    Route::post('user/password', [ProfileController::class, 'password']);

    Route::post('verify', [VerificationController::class, 'verify']);
    Route::post('resend', [VerificationController::class, 'resend']);
});

// guest
Route::group(['middleware' => ['guest:sanctum'],], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('password/request', [ForgotPasswordController::class, 'sendResetCode']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
});


Route::get("categories", [CategoryController::class, 'index']);
