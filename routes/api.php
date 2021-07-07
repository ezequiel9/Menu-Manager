<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user', [UserController::class, 'current']);

    Route::patch('settings/profile', [ProfileController::class, 'update']);
    Route::patch('settings/password', [PasswordController::class, 'update']);
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});

Route::get('users', [\App\Http\Controllers\Auth\UserController::class, 'getUsers']);
Route::post('add-user', [\App\Http\Controllers\Auth\UserController::class, 'addUser']);

Route::post('search', [\App\Http\Controllers\MenuController::class, 'search']);
Route::get('dates', [\App\Http\Controllers\MenuController::class, 'getDates']);
Route::post('menu/add-variation', [\App\Http\Controllers\MenuController::class, 'addVariation']);
Route::get('menu-types', [\App\Http\Controllers\MenuController::class, 'getMenuTypes']);
Route::post('menu', [\App\Http\Controllers\MenuController::class, 'addMenu']);
Route::delete('menu/{menu}', [\App\Http\Controllers\MenuController::class, 'deleteMenu']);
Route::delete('menu/{menu}/variation/{menu_variation}', [\App\Http\Controllers\MenuController::class, 'deleteVariation']);


Route::post('orders', [\App\Http\Controllers\OrderController::class, 'storeOrder']);
Route::get('order-types', [\App\Http\Controllers\OrderController::class, 'getOrderTypes']);
Route::delete('orders/{order}', [\App\Http\Controllers\OrderController::class, 'deleteOrder']);
Route::get('orders/user/{user_id}/week_number/{week_number}', [\App\Http\Controllers\OrderController::class, 'getOrders']);
