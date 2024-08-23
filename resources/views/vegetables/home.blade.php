<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Main Page</title>
    <!-- Font Awesome for cart icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            position: relative;
        }
        header h1 {
            margin: 0;
            font-size: 2em;
        }
        .header-buttons {
            position: absolute;
            top: 10px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-buttons a,
        .header-buttons button {
            color: white;
            background-color: #007bff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .header-buttons a:hover,
        .header-buttons button:hover {
            background-color: #0056b3;
        }
        .cart-button {
            position: relative;
            font-size: 24px;
            color: white;
            background-color: transparent;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .cart-button .fas {
            font-size: 24px;
        }
        .cart-button span {
            position: absolute;
            top: -8px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            font-weight: bold;
            line-height: 1;
            text-align: center;
        }
        .header-buttons .profile {
            background-color: #28a745;
        }
        .header-buttons .profile:hover {
            background-color: #218838;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            overflow: hidden;
            padding: 20px;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .product {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 10px;
            width: calc(33% - 20px);
            text-align: center;
        }
        .product img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product p {
            margin: 10px 0;
            color: #333;
        }
        .product a {
            text-decoration: none;
            color: #333;
        }
        .product a:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-buttons">
            @auth
            <a href="{{ route('profile') }}" class="profile">
                {{ auth()->user()->username }}
            </a>
            @else
                <a href="{{ route('login') }}" class="profile">Login</a>
            @endauth
            <a href="{{ route('view_cart') }}" class="cart-button" title="Cart">
                <i class="fas fa-shopping-cart"></i>
                <span id="cartCount">{{ $cartCount }}</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
        <div>
            <h1>Welcome</h1>
        </div>
    </header>

    <div class="container">
        <h2>Products</h2>
        <div class="products">
            @foreach ($products as $product)
                <div class="product">
                    <a href="{{ route('product.details', $product->id) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->p_name }}">
                        <h3>{{ $product->p_name }}</h3>
                    </a>
                    <p>${{ $product->price }} per {{ $product->mass }} kg</p>
                    <button> <a href="{{ route('product.details', $product->id) }}" class="btn ">Add To Cart</a></button>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        function fetchCartCount() {
            fetch("{{ route('cart.count') }}")
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cartCount').innerText = data.count;
                })
                .catch(error => console.error('Error fetching cart count:', error));
        }

        setInterval(fetchCartCount, 5000); // Fetch every 5 seconds
    </script>
</body>
</html>
