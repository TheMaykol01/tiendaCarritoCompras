<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStockRequest;
use App\Http\Requests\StockAdjustmentRequest;
use App\Models\Batch;
use App\Models\InventoryLog;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class InventoryController extends Controller
{
    /**
     * Show the barcode scanner and the latest movements.
     */
    public function index(): View
    {
        return view('inventory.index', [
            'logs' => InventoryLog::with('batch.product')->latest()->limit(10)->get(),
        ]);
    }

    /**
     * Apply a stock movement for the batch matching the scanned barcode.
     */
    public function adjust(StockAdjustmentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $batch = Batch::where('barcode', $data['barcode'])->firstOrFail();

        if ($data['type'] === Batch::TYPE_OUT && ! $batch->hasStockFor($data['quantity'])) {
            return response()->json([
                'message' => "El lote {$batch->batch_number} solo tiene {$batch->quantity} unidades.",
            ], 422);
        }

        $batch->adjustStock($data['type'], $data['quantity']);

        return response()->json([
            'message' => 'Inventario actualizado correctamente.',
            'batch' => $batch->batch_number,
            'quantity' => $batch->quantity,
        ]);
    }

    /**
     * Apply a stock movement made straight from a product card.
     */
    public function adjustProduct(ProductStockRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        $batch = $product->stockBatch();

        if ($data['type'] === Batch::TYPE_OUT && ! $batch->hasStockFor($data['quantity'])) {
            return back()->withErrors([
                'quantity' => "Solo hay {$batch->quantity} unidades disponibles de {$product->name}.",
            ]);
        }

        $batch->adjustStock($data['type'], $data['quantity']);

        return back()->with('status', "Inventario de {$product->name} actualizado.");
    }
}
