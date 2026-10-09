<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi kata sandi - HERVENT ERP</title>
</head>
<body>
    <main>
        <h1>Konfirmasi kata sandi</h1>
        <p>Masukkan kembali kata sandi Anda untuk melanjutkan ke area dengan hak akses tinggi.</p>

        @if ($errors->any())
            <div role="alert">
                <p>{{ $errors->first('password') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm.store') }}">
            @csrf
            <label for="password">Kata sandi</label>
            <input id="password" name="password" type="password" required autofocus>
            <button type="submit">Konfirmasi</button>
        </form>
    </main>
</body>
</html>
