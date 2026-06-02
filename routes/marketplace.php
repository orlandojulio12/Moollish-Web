<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Marketplace\MarketplaceController;
use App\Http\Controllers\Marketplace\VendedorController;
use App\Http\Controllers\Marketplace\CarritoController;

// ── Públicas ────────────────────────────────────────────────────────────────
Route::get('/marketplace',              [MarketplaceController::class, 'home'])->name('marketplace.home');
Route::get('/marketplace/catalogo',     [MarketplaceController::class, 'catalogo'])->name('marketplace.catalogo');
Route::get('/marketplace/ganado/{id}',  [MarketplaceController::class, 'detalleAnimal'])->name('marketplace.detalle-animal');
Route::get('/marketplace/lote/{id}',    [MarketplaceController::class, 'detalleLote'])->name('marketplace.detalle-lote');
Route::get('/marketplace/insumo/{id}',  [MarketplaceController::class, 'detalleInsumo'])->name('marketplace.detalle-insumo');
Route::get('/marketplace/vendedor/{id}',[MarketplaceController::class, 'perfilVendedor'])->name('marketplace.perfil-vendedor');

// ── API del carrito (auth) ───────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('marketplace/api/carrito')->name('marketplace.api.carrito.')->group(function () {
    Route::get('/',                [CarritoController::class, 'index'])->name('index');
    Route::get('/count',           [CarritoController::class, 'count'])->name('count');
    Route::post('/agregar',        [CarritoController::class, 'agregar'])->name('agregar');
    Route::delete('/vaciar',       [CarritoController::class, 'vaciar'])->name('vaciar');
    Route::post('/fusionar',       [CarritoController::class, 'fusionar'])->name('fusionar');
    Route::delete('/{id}',         [CarritoController::class, 'quitar'])->name('quitar');
    Route::patch('/{id}/cantidad', [CarritoController::class, 'actualizarCantidad'])->name('cantidad');
});

// ── Requieren login — cualquier rol del marketplace puede entrar ─────────────
Route::middleware(['auth', 'marketplace'])->group(function () {
    Route::get('/marketplace/carrito',     [MarketplaceController::class, 'carrito'])->name('marketplace.carrito');
    Route::get('/marketplace/checkout',    [MarketplaceController::class, 'checkout'])->name('marketplace.checkout');
    Route::post('/marketplace/checkout',   [MarketplaceController::class, 'procesarCheckout'])->name('marketplace.checkout.procesar');
    Route::get('/marketplace/pedido/{id}/confirmacion', [MarketplaceController::class, 'confirmacionPedido'])->name('marketplace.pedido.confirmacion');
    Route::get('/marketplace/mis-pedidos', [MarketplaceController::class, 'misPedidos'])->name('marketplace.mis-pedidos');
});

// ── Solo pueden vender: proveedor(6), propietario(3), admin(1) ───────────────
Route::middleware(['auth', 'puede.vender'])->group(function () {
    Route::get('/marketplace/panel',              [VendedorController::class, 'dashboard'])->name('marketplace.panel');
    Route::get('/marketplace/panel/publicar',     [VendedorController::class, 'crearPublicacion'])->name('marketplace.panel.publicar');
    Route::get('/marketplace/panel/publicaciones',[VendedorController::class, 'misPublicaciones'])->name('marketplace.panel.publicaciones');
    Route::get('/marketplace/panel/ventas',       [VendedorController::class, 'misVentas'])->name('marketplace.panel.ventas');
    Route::get('/marketplace/panel/perfil',       [VendedorController::class, 'miPerfil'])->name('marketplace.panel.perfil');

    Route::post('/marketplace/panel/publicar/animal', [VendedorController::class, 'storeAnimal'])->name('marketplace.panel.publicar.animal');
    Route::post('/marketplace/panel/publicar/lote',   [VendedorController::class, 'storeLote'])->name('marketplace.panel.publicar.lote');
    Route::post('/marketplace/panel/publicar/insumo', [VendedorController::class, 'storeInsumo'])->name('marketplace.panel.publicar.insumo');

    Route::get('/marketplace/panel/lote-interno/{id}/datos', [VendedorController::class, 'datosLoteInterno'])->name('marketplace.panel.lote-interno.datos');

    Route::post('/marketplace/panel/perfil', [VendedorController::class, 'actualizarPerfil'])->name('marketplace.panel.perfil.update');
});

// ── Solicitud de upgrade: comprador → proveedor ──────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/marketplace/upgrade',  [MarketplaceController::class, 'solicitarUpgrade'])->name('marketplace.solicitar.upgrade');
    Route::post('/marketplace/upgrade', [MarketplaceController::class, 'guardarUpgrade'])->name('marketplace.guardar.upgrade');
});
