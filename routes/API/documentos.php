<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\DocumentoExternoController;



Route::group([
	'middleware'  => 'api',
  'prefix'      => 'documento'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(DocumentoController::class)->group(function () {
      Route::post('/enviar', 'store');
      Route::post('/crear-temporal', 'storeTemporal');
      Route::get('/{id}', 'show');
      Route::post('/actualizar/{id}', 'update');
      Route::post('/eliminar-anexo/{id}', 'destroyAnexo');
      Route::post('/agregar-anexo', 'addAnexo');
      Route::post('/asignar', 'assignDoc');
      Route::get('/descargar-anexo/{id}', 'downloadAnexo');
      Route::get('/generar-documento/{id}', 'genareteDocument');
      Route::delete('/eliminar-documento/{id}', 'destroyDocument');
    });
  });
});

Route::group([
	'middleware'  => 'api',
  'prefix'      => 'documento-externo'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(DocumentoExternoController::class)->group(function () {
      Route::post('/registrar', 'store');
      Route::get('/generar-documento/{id}', 'genareteDocument');
      Route::get('/{id}', 'show');
    });
  });
});
