<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class QuotationController extends Controller
{
    public function download(Order $order): BinaryFileResponse
    {
        Gate::authorize('view', $order);

        abort_if(blank($order->quotation_path), 404);

        return response()->download(Storage::disk('local')->path($order->quotation_path));
    }

    public function preview(Order $order): BinaryFileResponse
    {
        Gate::authorize('view', $order);

        abort_if(blank($order->quotation_path), 404);

        return response()->file(Storage::disk('local')->path($order->quotation_path));
    }
}
