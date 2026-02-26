<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BandejaController;



Route::group([
	'middleware'  => 'api',
  'prefix'      => 'bandeja'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(BandejaController::class)->group(function () {
      Route::get('/enviados', 'enviados');
      Route::get('/recibidos', 'recibidos');
      Route::get('/borradores', 'borradores');
      Route::get('/por-corregir', 'corregir');
      Route::get('/count', 'bandeja');
      Route::get('/verificate', 'hasNewDocuments');
      Route::get('/externos', 'externos');
      Route::get('/externos-salida', 'externosSalida');
      Route::get('/externos-por-aprobar', 'externosPorAprobar');
    });
  });
});
