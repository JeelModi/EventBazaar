<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 80px auto; padding: 20px; }
        input { width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 13px; }
        .success { color: green; font-size: 13px; }
    </style>
</head>
<body>
    <h2>Create Account</h2>

    {{-- Show success message --}}
    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    {{-- Show validation errors --}}
    @if($errors->any())
        @foreach($errors->all() as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
    @endif

    {{-- POST form --}}
    <form method="POST" action="{{ route('register') }}">
        @csrf {{-- Laravel security token --}}
        <input type="text"     name="name"                 placeholder="Full Name"         value="{{ old('name') }}"  required>
        <input type="email"    name="email"                placeholder="Email"             value="{{ old('email') }}" required>
        <input type="password" name="password"             placeholder="Password"                                     required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password"                            required>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
</body>
</html>