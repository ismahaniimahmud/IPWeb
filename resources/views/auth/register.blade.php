<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar akaun — Papan Pemantauan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
    <main class="auth-card">
        <a class="brand auth-brand" href="{{ route('login') }}" aria-label="Papan Pemantauan"><span class="brand-mark">M</span></a>
        <p class="eyebrow">PAPAN PEMANTAUAN</p>
        <h1>Daftar akaun</h1>
        <p class="auth-description">Cipta akaun untuk mengurus rekod tindakan.</p>
        @if ($errors->any())
            <div class="flash-error">{{ $errors->first() }}</div>
        @endif
        <form class="auth-form" method="POST" action="{{ route('register') }}">
            @csrf
            <label>Nama<input name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus></label>
            <label>E-mel<input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required></label>
            <label>Kata laluan<input name="password" type="password" autocomplete="new-password" required></label>
            <label>Sahkan kata laluan<input name="password_confirmation" type="password" autocomplete="new-password" required></label>
            <button class="primary-action auth-submit" type="submit">Daftar akaun</button>
        </form>
        <p class="auth-switch">Sudah ada akaun? <a href="{{ route('login') }}">Log masuk</a></p>
    </main>
</body>
</html>
