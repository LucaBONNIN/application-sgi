<?php

use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (): void {
    Route::get('/orders/{order}/quotation', [QuotationController::class, 'download'])
        ->name('orders.quotation.download');
    Route::get('/orders/{order}/quotation/preview', [QuotationController::class, 'preview'])
        ->name('orders.quotation.preview');
});
