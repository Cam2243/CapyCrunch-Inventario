<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;

// Página principal — inventario del día
Route::get('/', [InventarioController::class, 'index'])->name('inicio');

// Apertura del día: ingresar stock inicial
Route::post('/apertura', [InventarioController::class, 'apertura'])->name('apertura');

// Agregar nuevo producto
Route::post('/producto/agregar', [InventarioController::class, 'agregarProducto'])->name('producto.agregar');

// Registrar una venta en tiempo real
Route::post('/venta', [InventarioController::class, 'registrarVenta'])->name('venta.registrar');

// Cierre de caja
Route::get('/cierre', [InventarioController::class, 'cierre'])->name('cierre');

// Reiniciar el día (nueva apertura)
Route::post('/reiniciar', [InventarioController::class, 'reiniciar'])->name('reiniciar');