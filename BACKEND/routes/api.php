<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ShopController;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/change-pass', [AuthController::class, 'changePassWord']);
    //shop
    Route::post('/register-shop', [ShopController::class, 'register']);
    Route::post('/update-shop', [ShopController::class, 'UpdateShop']);

    //reset
    Route::post('sendPasswordResetLink',[ResetPasswordController::class,'sendEmail']);
    Route::post('resetPassword',[ChangePasswordController::class,'process']);
});
Route::get('/confirm-shop/{token}/{email?}', [ShopController::class, 'Confirm_shop'])->name('confim_mail');

