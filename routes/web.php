<?php

use App\Http\Controllers\QuotationController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/demo-reset-token', function () {
    try {
        return response()->json(['token' => Order::orderByDesc('id')->value('created_at')?->timestamp]);
    } catch (Throwable) {
        return response()->json(['token' => null]);
    }
});

Route::middleware('auth')->group(function (): void {
    Route::get('/orders/{order}/quotation', [QuotationController::class, 'download'])
        ->name('orders.quotation.download');
    Route::get('/orders/{order}/quotation/preview', [QuotationController::class, 'preview'])
        ->name('orders.quotation.preview');
});
