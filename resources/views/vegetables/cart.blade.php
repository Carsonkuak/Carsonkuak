<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .total {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .mass-input {
            width: 60px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Your Cart</h1>
    </header>

    <div class="container">
        @if ($cartItems->isEmpty())
            <p>Your cart is empty.</p>
        @else
            <form action="{{ route('updateCart') }}" method="POST">
                @csrf
                <table>
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Mass (kg)</th>
                            <th>Price per kg</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" class="product-checkbox" data-price="{{ $item->product->price }}" data-mass="{{ $item->mass }}" value="{{ $item->id }}">
                                </td>
                                <td>
                                    <img src="{{ asset('images/' . $item->product->image_url) }}" alt="{{ $item->product->p_name }}">
                                </td>
                                <td>{{ $item->product->p_name }}</td>
                                <td>
                                    <input type="number" name="mass[{{ $item->id }}]" class="mass-input" value="{{ $item->mass }}" min="0" step="0.01">
                                </td>
                                <td>${{ number_format($item->product->price, 2) }}</td>
                                <td class="item-total">${{ number_format($item->product->price * $item->mass, 2) }}</td>
                                <td>
                                    <button type="submit" formaction="{{ route('editCartItem', ['id' => $item->id]) }}" class="btn">Edit</button>
                                    <form action="{{ route('removeFromCart') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                                        <button type="submit" class="btn btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="total">
                    <strong>Total Cart Price: $<span id="totalPrice">{{ number_format($totalPrice, 2) }}</span></strong>
                </div>
                <button><a href="{{ route('home') }}" class="btn">Back Home</a></button>
                {{-- <a href="{{ route('checkout') }}" class="btn">Proceed to Checkout</a> --}}
            </form>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const totalPriceElement = document.getElementById('totalPrice');
            const massInputs = document.querySelectorAll('.mass-input');

            function updateTotalPrice() {
                let total = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const price = parseFloat(checkbox.getAttribute('data-price'));
                        const mass = parseFloat(checkbox.getAttribute('data-mass'));
                        total += price * mass;
                    }
                });
                totalPriceElement.textContent = total.toFixed(2);
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotalPrice);
            });

            massInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const row = input.closest('tr');
                    const price = parseFloat(row.querySelector('td:nth-child(5)').textContent.replace('$', '').replace(",",""));
                    console.log(price);
                    const  mass = parseFloat(input.value);
                    const totalCell = row.querySelector('.item-total');
                    const total = price * mass;
                    totalCell.textContent = `$${total.toFixed(2)}`;

                    const checkbox = row.querySelector('.product-checkbox');
                    checkbox.setAttribute('data-mass', mass);

                    updateTotalPrice();
                });
            });

            updateTotalPrice();
        });
    </script>
</body>
</html>
