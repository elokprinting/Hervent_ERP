<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Sistem - HERVENT ERP</title>
</head>
<body>
    <main>
        <h1>Admin Sistem</h1>
        <p>Akses administrator sistem aktif untuk {{ auth()->user()->name }}.</p>
        <p>Konfirmasi kata sandi diperlukan kembali setelah masa konfirmasi berakhir.</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Keluar</button>
        </form>
    </main>
</body>
</html>
