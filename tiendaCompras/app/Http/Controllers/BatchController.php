<?php

namespace App\Http\Controllers;

use App\Http\Requests\BatchRequest;
use App\Models\Batch;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class BatchController extends Controller
{
    /**
     * Display the lots that make up the inventory.
     */
    public function index(): View
    {
        return view('batches.index', [
            'batches' => Batch::with('product')->latest()->paginate(12),
        ]);
    }

    /**
     * Show the form for registering a new batch.
     */
    public function create(): View
    {
        return view('batches.create', [
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created batch.
     */
    public function store(BatchRequest $request): RedirectResponse
    {
        Batch::create($request->validated());

        return redirect()
            ->route('batches.index')
            ->with('status', 'Lote registrado correctamente.');
    }

    /**
     * Render the barcode image for the given batch.
     */
    public function barcode(Batch $batch): Response
    {
        $png = DNS1D::getBarcodePNG($batch->barcode, 'C128', 2, 60, [0, 0, 0], false, [255, 255, 255]);

        return response(base64_decode($png), 200, [
            'Content-Type' => 'image/png',
        ]);
    }

    /**
     * Remove the given batch from the inventory.
     */
    public function destroy(Batch $batch): RedirectResponse
    {
        $batch->delete();

        return redirect()
            ->route('batches.index')
            ->with('status', 'Lote eliminado del inventario.');
    }
}
