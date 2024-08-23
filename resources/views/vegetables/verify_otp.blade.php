<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
            margin: 20px;
        }
        div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }
        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div>
        <h1>Verify OTP</h1>
        <form id="otpForm" action="{{ route('verify.otp') }}" method="POST">
            @csrf
            <div>
                <label for="otp">Enter OTP:</label>
                <input type="text" name="otp" required>
            </div>
            @error("otp")
                <p>{{$message}}</p>
            @enderror
            <button type="submit">Verify</button>
        </form>
        <a href="{{ route('vege_register') }}" class="back-link" id="backLink">Back to Register</a>
    </div>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif


    {{-- <script>
        function handleFormSubmit() {
            const otpVerified = false;

            if (!otpVerified) {
                window.location.href = "{{ route('vege_register') }}";
                return false;
            } else {
                alert('Registration successful!');
                window.location.href = "{{ route('home') }}";
                return false;
            }
        }
        document.getElementById('otpForm').onsubmit = handleFormSubmit;
    </script> --}}
</body>
</html>
