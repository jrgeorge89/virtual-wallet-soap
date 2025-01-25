<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoapController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta para WSDL y servidor SOAP general
Route::get('/soap/wsdl', [SoapController::class, 'wsdlAction'])->name('soap-wsdl');
Route::post('/soap/server', [SoapController::class, 'serverAction'])->name('soap-server');
