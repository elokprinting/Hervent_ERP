<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - HERVENT ERP</title>
</head>
<body>
    <main>
        <h1>Masuk ke HERVENT ERP</h1>

        @if ($errors->any())
            <div role="alert">
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Kata sandi</label>
            <input id="password" name="password" type="password" required>

            <label>
                <input name="remember" type="checkbox" value="1">
                Ingat saya
            </label>

            <button type="submit">Masuk</button>
        </form>
    </main>
</body>
</html>
