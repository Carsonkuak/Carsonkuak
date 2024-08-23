<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 15px 0;
            width: 100%;
            text-align: center;
            position: sticky;
            top: 0;
        }

        header h1 {
            margin: 0;
            font-size: 2.5em;
        }

        .header-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .header-buttons a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1em;
            margin: 0 10px;
        }

        .header-buttons form {
            display: inline;
        }

        .header-buttons button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .container {
            width: 80%;
            max-width: 800px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
        }

        .container h2 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .container img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .container p {
            margin: 10px 0;
            font-size: 1.2em;
            color: #333;
        }

        .input-group {
            margin: 20px 0;
        }

        .input-group label {
            display: block;
            font-size: 1.1em;
            margin-bottom: 5px;
            color: #333;
        }

        .input-group input {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .input-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        .container button {
            padding: 10px 20px;
            font-size: 1.1em;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-buttons">
            @auth
                <a href="{{ route('profile') }}" class="profile">{{ auth()->user()->username }}</a>
                <a href="{{ route('view_cart') }}" class="cart-button" title="Cart">
                    <span id="cartCount">{{ $cartCount }}</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="profile">Login</a>
            @endauth
        </div>
        <h1>Product Details</h1>
    </header>

    <div class="container">
        <h2>{{ $product->p_name }}</h2>
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->p_name }}">
        <p>{{ $product->details }}</p>
        <p>Mass: {{ $product->mass }} kg</p>
        <p>Price: ${{ $product->price }} per kg</p>
        <p>Registered on: {{ $product->created_at }}</p>
        <p>Last updated: {{ $product->updated_at }}</p>

        <form id="cartForm">
            @csrf
            <div class="input-group">
                <label for="massInput">Enter the mass:</label>
                <input type="number" id="massInput" name="massInput" value="1" min="1" required>
            </div>
            <p>Total Price: $<span id="totalPrice">{{ $product->price }}</span></p>
            <button type="button" onclick="addToCart({{ $product->id }})">Add to Cart</button>
        </form>
<button><a href="{{ route('home') }}">Back Home</a></button>
    </div>

    <script>
        const massInput = document.getElementById('massInput');
        const totalPriceElement = document.getElementById('totalPrice');
        const pricePerKg = {{ $product->price }};
        const productId = {{ $product->id }};

        massInput.addEventListener('input', updateTotalPrice);

        function updateTotalPrice() {
            const mass = massInput.value;
            const totalPrice = mass * pricePerKg;
            totalPriceElement.textContent = totalPrice.toFixed(2);
        }

        function addToCart(productId) {
            const mass = massInput.value;

            fetch(`{{ route('addcart', ':productId') }}`.replace(':productId', productId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ mass: mass })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart successfully!');
                } else {
                    alert('Failed to add product to cart. Please try again.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
