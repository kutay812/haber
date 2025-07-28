<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hoş Geldiniz</title>
</head>
<body>
    <h1>Hoş geldiniz, {{ auth()->user()->name }}</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Çıkış Yap</button>
    </form>
</body>
</html>
