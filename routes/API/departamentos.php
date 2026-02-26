<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\NucleoController;



Route::group([
	'middleware'  => 'api',
  'prefix'      => 'departamentos'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(DepartamentoController::class)->group(function () {
      Route::get('/', 'index');
      Route::get('/list', 'list');
      Route::get('/sub-departamentos', 'subDepartaments');
      Route::post('/', 'store');
    //   Route::get('/{id}', 'show');
    Route::post('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
    Route::get('/list/redactar', 'departamentsWrite');
    });
  });
});

Route::group([
	'middleware'  => 'api',
  'prefix'      => 'nucleo'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(DepartamentoController::class)->group(function () {
      Route::get('/', 'allNucleos');
      Route::get('/byDepartamentos', 'departamentsByNucleo');
    });
    Route::controller(NucleoController::class)->group(function () {
      Route::post('/store', 'store');
      Route::post('/update/{id}', 'update');
      Route::delete('/delete/{id}', 'destroy');
    });
  });
});
