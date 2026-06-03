<!DOCTYPE html>
<html>
<head>
    <title>Login</title> 
    <style>
        body { font-family: Arial; max-width: 400px; margin: 80px auto; padding: 20px; }
        input { width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 13px; }
        .success { color: green; font-size: 13px; }
    </style>
</head>
<body>
    <h2>Login</h2>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email"    name="email"    placeholder="Email"    value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Password"                            required>
        <button type="submit">Login</button>
    </form>

    <p>No account? <a href="{{ route('register') }}">Register</a></p>
</body>
</html>