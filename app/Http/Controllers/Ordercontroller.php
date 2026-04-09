<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Leer el carrito enviado desde el formulario (viene como JSON)
        $carrito = json_decode($request->input('carrito'), true);

        if (empty($carrito)) {
            return redirect()->back()->with('error', 'El carrito está vacío');
        }

        // 2. Crear la orden
        $orden = new Order();
        $orden->user_id = 1;
        $orden->metodo_pago = 'Tarjeta';
        $orden->save();

        // 3. Guardar cada producto en la tabla order_product
        foreach ($carrito as $item) {
            $orden->products()->attach($item['id'], [
                'price'    => $item['price'],
                'cantidad' => $item['cantidad'],
            ]);
        }

        return redirect('/checkout')->with('success', '¡Orden creada exitosamente!');
    }
}