<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forget Email</title>
</head>
<body>

<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    @error('email')
        {{$message}}
    @enderror
    <button type="submit">Send OTP</button>
</form>

</body>
</html>
