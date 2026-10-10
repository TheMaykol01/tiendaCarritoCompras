<?php

namespace App\Http\Controllers;

use App\Catalog\Catalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    /**
     * Show the store landing page.
     */
    public function home(): View
    {
        return view('storefront.home');
    }

    /**
     * Show the product catalog.
     */
    public function products(): View
    {
        return view('storefront.products', [
            'products' => Catalog::products(),
            'categories' => Catalog::categories(),
        ]);
    }

    /**
     * Show the store locations.
     */
    public function stores(): View
    {
        return view('storefront.stores', [
            'stores' => Catalog::stores(),
        ]);
    }

    /**
     * Show the about page.
     */
    public function about(): View
    {
        return view('storefront.about');
    }

    /**
     * Show the contact page.
     */
    public function contact(): View
    {
        return view('storefront.contact');
    }

    /**
     * Store a contact message.
     */
    public function storeContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'email', 'max:255'],
            'mensaje' => ['required', 'string', 'max:2000'],
        ]);

        // El envío real de correo se conectará aquí con el servicio de notifications.
        logger()->info('Mensaje de contacto recibido', $validated);

        return back()->with('status', 'Recibimos tu mensaje. Te respondemos el mismo día hábil.');
    }
}
