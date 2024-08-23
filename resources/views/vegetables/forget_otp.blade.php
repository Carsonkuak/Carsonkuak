<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forget Password Otp Verify</title>
</head>
<body>
<form action="{{ route('password.otp') }}" method="POST">
    @csrf
    <input type="hidden" name="email" value="{{ session('email') }}">
    <label for="otp">OTP:</label>
    <input type="text" id="otp" name="otp" required>
    @error('otp')
        {{$message}}
    @enderror
    <button type="submit">Verify OTP</button>
</form>

</body>
</html>
