<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NivelController;

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
	'middleware' => 'api'
], function () {
   Route::group([
      'prefix'=>'auth'],function(){
         Route::post('login/{conexion}',[AuthController::class, 'login']);
         Route::post('/reset-password',[AuthController::class, 'sendResetPasswordEmail']);
         Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::get('/logout', [AuthController::class, 'logout']);
            // Route::get('/revoketoken', [AuthController::class, 'RevokeToken']);
            Route::post('/changepassword', [AuthController::class, 'changePassword']);
         });
   });
});

Route::group([
	'middleware'  => 'api',
  'prefix'      => 'nivel'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(NivelController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::post('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
  });
});
