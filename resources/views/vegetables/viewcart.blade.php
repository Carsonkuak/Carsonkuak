<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 15px 0;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .cart-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .cart-item img {
            width: 100px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .cart-item-info {
            flex: 1;
            margin-left: 20px;
        }
        .cart-item-info p {
            margin: 5px 0;
            font-size: 1.1em;
            color: #333;
        }
        .cart-item-total {
            font-size: 1.2em;
            color: #333;
            font-weight: bold;
        }
        .cart-summary {
            text-align: right;
            font-size: 1.3em;
            margin-top: 20px;
        }
        table img {
            width: 80px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        function calculateTotal() {
            let totalPrice = 0;
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    const price = parseFloat(checkbox.dataset.price);
                    const mass = parseFloat(checkbox.dataset.mass);
                    totalPrice += price * mass;
                }
            });
            document.getElementById('totalPrice').innerText = '$' + totalPrice.toFixed(2);
        }
    </script>
</head>
<body>
    <header>
        <div>
            <h1>Your Cart</h1>
        </div>
    </header>
    @extends('layouts.app')

    @section('content')
        <div class="container">
            <h1>Your Cart</h1>

            @if($cartItems->isEmpty())
                <p>Your cart is empty.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Mass (kg)</th>
                            <th>Price (per kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" class="product-checkbox" data-price="{{ $item->product->price }}" data-mass="{{ $item->mass }}" onclick="calculateTotal()">
                                </td>
                                <td>{{ $item->product->p_name }}</td>
                                <td><img src="{{ $item->product->image_url }}" alt="{{ $item->product->p_name }}"></td>
                                <td>{{ $item->mass }} kg</td>
                                <td>${{ $item->product->price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3>Total Price: <span id="totalPrice">$0.00</span></h3>
            @endif
        </div>
    @endsection

</body>
</html>
