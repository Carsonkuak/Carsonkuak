<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: grid;
            justify-content: center;
            grid-template-columns: 1fr;
            align-items: center;
            height: 100vh;
            width:100%;
        }

        .container{
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h1, h2 {
            color: #333;
            margin: 0 0 15px;
        }

        form {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .address-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .address-card {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1 1 calc(50% - 10px);
            box-sizing: border-box;
        }

        .address-card form {
            margin-top: 10px;
        }

        .address-card .delete-button {
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            margin-top: 10px;
            display: block;
            width: 100%;
        }

        .address-card .delete-button:hover {
            background-color: #e53935;
        }

        .back-button {
            text-align: center;
            margin-top: 20px;
        }

        .back-button a {
            text-decoration: none;
            color: #fff;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 4px;
            display: inline-block;
        }

        .back-button a:hover {
            background-color: #45a049;
        }

        .flash-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }

        .flash-message-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Profile</h1>

        @if(session('success'))
            <div class="flash-message">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flash-message-error">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('profile.update.username') }}" method="POST">
            @csrf
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required>
            @error('username')
                <div class="flash-message-error">
                    {{$message}}
                </div>
            @enderror
            <button type="submit">Update Username</button>
        </form>

        <form action="{{ route('profile.update.email') }}" method="POST" id="email-form">
            @csrf
            <label for="email">New Email:</label>
            <input type="email" id="email" name="new_email" value="{{ old('new_email') }}" required>
            @error('email')
                <div class="flash-message-error">
                    {{$message}}
                </div>
            @enderror
            <button type="submit">Update Email</button>
        </form>

        <form action="{{ route('profile.update.password') }}" method="POST" id="password-form">
            @csrf
            <label for="password">New Password:</label>
            <input type="password" id="password" name="new_password" required>
            <button type="submit">Update Password</button>
        </form>

        <div id="otp-section" style="display: none;">
            <h2>OTP Verification</h2>
            <form action="{{ route('profile.verify.otp') }}" method="POST">
                @csrf
                <label for="otp">Enter OTP:</label>
                <input type="text" id="otp" name="otp" required>
                @error('otp')
                    <div class="flash-message-error">
                        {{$message}}
                    </div>
                @enderror
                <button type="submit">Verify OTP</button>
            </form>
        </div>

        <div class="back-button">
            <a href="{{ route('home') }}">Back Home</a>
        </div>

        <h2>Your Addresses</h2>
        <div class="address-grid">
            @foreach ($addresses as $address)
                <div class="address-card">
                    <p>{{ $address->address_line1 }}</p>
                    <p>{{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}</p>
                    <p>{{ $address->country }}</p>

                    <form action="{{ route('address.delete', $address->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-button">Delete Address</button>
                    </form>
                </div>
            @endforeach
        </div>

        <h2>Add New Address</h2>
        <form action="{{ route('address.store') }}" method="POST">
            @csrf
            <label for="address_line1">Address Line 1:</label>
            <input type="text" id="address_line1" name="address_line1" required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" required>

            <label for="zip_code">Zip Code:</label>
            <input type="text" id="zip_code" name="zip_code" required>

            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>

            <button type="submit">Add Address</button>
        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailForm = document.getElementById('email-form');
            const passwordForm = document.getElementById('password-form');
            const otpSection = document.getElementById('otp-section');

            emailForm.addEventListener('submit', function(event) {
                otpSection.style.display = 'block';
                event.();
            });

            passwordForm.addEventListener('submit', function(event) {
                otpSection.style.display = 'block';
                event.preventDefault();
            });
        });
    </script>
</body>
</html>
