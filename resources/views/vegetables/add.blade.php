<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="file"] {
            padding: 3px;
        }
        .form-group .text-danger {
            color: #dc3545;
            font-size: 0.875em;
        }
        .btn {
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #6c757d;
            margin-top: 15px;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .alert-success {
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            color: #155724;
            margin-bottom: 20px;
        }
        .alert-success .text-success {
            color: #155724;
        }
        a {
            color: #ffffff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Product</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ url('products') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="p_id">Product ID</label>
                <input type="text" id="p_id" name="p_id" class="form-control" value="{{ old('p_id') }}" required>
                @error('p_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" class="form-control">
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="p_name">Product Name</label>
                <input type="text" id="p_name" name="p_name" class="form-control" value="{{ old('p_name') }}" required>
                @error('p_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="details">Details</label>
                <textarea id="details" name="details" class="form-control">{{ old('details') }}</textarea>
                @error('details')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="mass">Mass</label>
                <input type="number" id="mass" name="mass" class="form-control" value="{{ old('mass') }}" step="0.01" required>
                @error('mass')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" class="form-control" value="{{ old('price') }}" step="0.01" required>
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn">Add Product</button>
        </form>
        <a href="{{ url('/vege_home') }}" class="btn btn-back">Back to Home</a>
    </div>
</body>
</html>
