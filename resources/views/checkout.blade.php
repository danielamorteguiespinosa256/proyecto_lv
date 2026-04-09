<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .page {
            max-width: 560px;
            margin: 40px auto;
            padding: 0 1rem;
        }

        h1 {
            font-size: 22px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 1.5rem;
        }

        .alerta-exito {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 1.5rem;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alerta-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 1.5rem;
            font-size: 14px;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 1.25rem;
        }

        .section-label {
            font-size: 12px;
            color: #888;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .producto-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .producto-row:last-of-type {
            border-bottom: none;
        }

        .prod-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .prod-badge {
            background: #f0f0f0;
            border-radius: 6px;
            padding: 2px 8px;
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }

        .prod-name {
            font-size: 14px;
            color: #1a1a1a;
        }

        .prod-price {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        hr {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 12px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .total-row span:first-child {
            font-size: 13px;
            color: #888;
        }

        .total-row span:last-child {
            font-size: 13px;
            color: #1a1a1a;
        }

        .total-final span:first-child {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .total-final span:last-child {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .envio-gratis {
            color: #198754 !important;
        }

        .metodo {
            display: flex;
            gap: 10px;
            margin: 1.5rem 0 1rem;
        }

        .metodo-btn {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            font-size: 13px;
            color: #555;
            cursor: default;
            text-align: center;
        }

        .metodo-btn.activo {
            border: 2px solid #0d6efd;
            background: #e7f1ff;
            color: #0d6efd;
            font-weight: 600;
        }

        .btn-confirmar {
            width: 100%;
            padding: 14px;
            background: #1a1a1a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
        }

        .btn-confirmar:hover {
            background: #4a10e9;
        }
    </style>
</head>

<body>
    <div class="page">
        <h1>Resumen de tu orden</h1>

        @if(session('success'))
        <div class="alerta-exito">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alerta-error">
            ❌ {{ session('error') }}
        </div>
        @endif

        <div class="card">
            <p class="section-label">Productos</p>
            <div id="products"></div>
            <hr>
            <div class="total-row" style="margin-top:4px;">
                <span>Envío</span>
                <span class="envio-gratis">Gratis</span>
            </div>
            <hr>
            <div class="total-row total-final">
                <span>Total</span>
                <span id="total">$0.00</span>
            </div>
        </div>

        <form action="/checkout" method="post">
            @csrf
            <p class="section-label" style="margin-top:1.5rem; margin-bottom:10px;">Método de pago</p>
            <div class="metodo">
                <div class="metodo-btn activo">Tarjeta</div>
                <div class="metodo-btn">Efectivo</div>
                <div class="metodo-btn">Transferencia</div>
            </div>

            <input type="hidden" name="carrito" id="carritoInput">
            <button type="submit" class="btn-confirmar">Confirmar orden</button>
        </form>
    </div>

    <script>
        let carrito = JSON.parse(localStorage.getItem('carrito')) || []
        let divProducts = document.getElementById('products')
        let total = 0

        if (carrito.length === 0) {
            divProducts.innerHTML = '<p style="color:#999; font-style:italic; padding:8px 0;">Tu carrito está vacío.</p>'
        } else {
            carrito.forEach(p => {
                let subtotal = p.cantidad * p.price
                total += subtotal
                divProducts.innerHTML += `
                <div class="producto-row">
                    <div class="prod-info">
                        <span class="prod-badge">${p.cantidad}x</span>
                        <span class="prod-name">${p.name}</span>
                    </div>
                    <span class="prod-price">$${subtotal.toFixed(2)}</span>
                </div>`
            })
            document.getElementById('total').textContent = '$' + total.toFixed(2)
        }

        document.getElementById('carritoInput').value = JSON.stringify(carrito)
    </script>
</body>

</html>