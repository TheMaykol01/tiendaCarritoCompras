<?php

namespace App\Http\Controllers;

use App\Catalog\Catalog;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(): View
    {
        return view('livewire.products.index', [
            'products' => Product::query()
                ->withSum('batches as available_stock', 'quantity')
                ->latest()
                ->paginate(12),
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        return view('livewire.products.create');
    }

    /**
     * Store a newly created product.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        Product::create($this->withGeneratedFields($request));

        return redirect()
            ->route('products.index')
            ->with('status', 'Producto agregado al catálogo.');
    }

    /**
     * Show the form for editing the given product.
     */
    public function edit(Product $product): View
    {
        return view('livewire.products.edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the given product.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($this->withGeneratedFields($request));

        return redirect()
            ->route('products.index')
            ->with('status', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the given product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('status', 'Producto eliminado del catálogo.');
    }

    /**
     * Merge the validated data with the generated slug and icon.
     *
     * @return array<string, mixed>
     */
    private function withGeneratedFields(ProductRequest $request): array
    {
        return array_merge($request->validated(), [
            'slug' => $this->uniqueSlug($request->string('name')),
            'icon' => $this->pickIcon($request->string('category')),
        ]);
    }

    /**
     * Build a slug that is not already taken by another product.
     */
    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    /**
     * Pick the SVG icon that matches the given category.
     */
    private function pickIcon(string $category): string
    {
        $icons = Catalog::ICONS;

        $byCategory = match (mb_strtolower($category)) {
            'computación', 'computacion' => 'laptop',
            'audio' => 'headphones',
            'wearables' => 'watch',
            'móviles', 'moviles' => 'phone',
            'fotografía', 'fotografia' => 'camera',
            default => 'phone',
        };

        return array_key_exists($byCategory, $icons) ? $byCategory : 'phone';
    }
}
