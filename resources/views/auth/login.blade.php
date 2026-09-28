<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log masuk — Papan Pemantauan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
    <main class="auth-card">
        <a class="brand auth-brand" href="{{ route('login') }}" aria-label="Papan Pemantauan"><span class="brand-mark">M</span></a>
        <p class="eyebrow">PAPAN PEMANTAUAN</p>
        <h1>Log masuk</h1>
        <p class="auth-description">Gunakan akaun pentadbir anda untuk mengurus rekod tindakan.</p>
        @if ($errors->any())
            <div class="flash-error">{{ $errors->first() }}</div>
        @endif
        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf
            <label>E-mel<input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus></label>
            <label>Kata laluan<input name="password" type="password" autocomplete="current-password" required></label>
            <label class="remember-field"><input name="remember" type="checkbox" value="1"> Ingat saya</label>
            <button class="primary-action auth-submit" type="submit">Log masuk</button>
        </form>
        <p class="auth-switch">Belum ada akaun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
    </main>
</body>
</html>
