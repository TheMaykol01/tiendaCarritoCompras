<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockAdjustmentRequest;
use App\Models\Batch;
use App\Models\InventoryLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

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

        if ($data['type'] === Batch::TYPE_OUT && $data['quantity'] > $batch->quantity) {
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
}
