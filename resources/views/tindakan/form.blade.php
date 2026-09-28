<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $formTitle }} — Papan Pemantauan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="Dashboard"><span class="brand-mark">M</span></a>
        <nav class="side-nav" aria-label="Navigasi utama">
            <a class="nav-link" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span><span>Dashboard</span></a>
            <a class="nav-link is-active" href="{{ route('tindakan.index') }}"><span class="nav-icon">✓</span><span>Tindakan</span></a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="topbar">
            <h1 class="topbar-title">Papan Pemantauan</h1>
            <div class="topbar-meta"><span class="avatar">{{ str(auth()->user()->name)->substr(0, 2)->upper() }}</span><span class="user-name">{{ auth()->user()->name }}</span></div>
        </header>
        <div class="page-wrap">
            <div class="page-heading">
                <div><p class="eyebrow">PENGURUSAN REKOD</p><h1>{{ $formTitle }}</h1><p class="heading-copy">Lengkapkan maklumat tindakan di bawah.</p></div>
                <a class="text-link" href="{{ route('tindakan.index') }}">← Kembali ke tindakan</a>
            </div>
            @if($errors->any())<div class="flash-error">Sila semak medan yang ditanda.</div>@endif
            <section class="panel form-panel">
                <form method="POST" action="{{ $action->exists ? route('tindakan.update', $action) : route('tindakan.store') }}">
                    @csrf
                    @if($action->exists) @method('PUT') @endif
                    <div class="form-grid">
                        <label>ID tindakan<input name="action_code" value="{{ old('action_code', $action->action_code) }}" required>@error('action_code')<small>{{ $message }}</small>@enderror</label>
                        <label>Rujukan<input name="reference" value="{{ old('reference', $action->reference) }}" required>@error('reference')<small>{{ $message }}</small>@enderror</label>
                        <label>Tema<input name="theme" value="{{ old('theme', $action->theme) }}" required>@error('theme')<small>{{ $message }}</small>@enderror</label>
                        <label>Pemilik tindakan<input name="owner" value="{{ old('owner', $action->owner) }}" required>@error('owner')<small>{{ $message }}</small>@enderror</label>
                        <label>Sasaran / tempoh<input name="target" value="{{ old('target', $action->target) }}"></label>
                        <label>Status<select name="status" required>@foreach(['Belum disahkan', 'Dalam tindakan', 'Pemantauan berterusan', 'Menunggu pihak luar', 'Selesai'] as $status)<option value="{{ $status }}" @selected(old('status', $action->status) === $status)>{{ $status }}</option>@endforeach</select>@error('status')<small>{{ $message }}</small>@enderror</label>
                        <label>Keutamaan<select name="priority" required>@foreach(['Tinggi', 'Sederhana', 'Rendah'] as $priority)<option value="{{ $priority }}" @selected(old('priority', $action->priority) === $priority)>{{ $priority }}</option>@endforeach</select>@error('priority')<small>{{ $message }}</small>@enderror</label>
                        <label>Asas tindakan<input name="basis" value="{{ old('basis', $action->basis) }}" required>@error('basis')<small>{{ $message }}</small>@enderror</label>
                        <label>Tarikh mesyuarat<input name="meeting_date" type="date" value="{{ old('meeting_date', $action->meeting_date?->format('Y-m-d')) }}">@error('meeting_date')<small>{{ $message }}</small>@enderror</label>
                        <label class="span-two">Tindakan diperlukan<textarea name="required_action" rows="4" required>{{ old('required_action', $action->required_action) }}</textarea>@error('required_action')<small>{{ $message }}</small>@enderror</label>
                        <label class="span-two">Kemajuan terkini<textarea name="progress" rows="3">{{ old('progress', $action->progress) }}</textarea></label>
                        <label class="span-two">Bukti / keputusan<textarea name="evidence" rows="3">{{ old('evidence', $action->evidence) }}</textarea></label>
                        <label class="span-two">Catatan sumber<textarea name="source_note" rows="3">{{ old('source_note', $action->source_note) }}</textarea></label>
                        <label>Tarikh kemas kini<input name="updated_on" type="date" value="{{ old('updated_on', $action->updated_on?->format('Y-m-d')) }}">@error('updated_on')<small>{{ $message }}</small>@enderror</label>
                    </div>
                    <div class="form-actions"><a class="reset-button reset-link" href="{{ route('tindakan.index') }}">Batal</a><button class="primary-action" type="submit">{{ $action->exists ? 'Simpan perubahan' : 'Simpan tindakan' }}</button></div>
                </form>
            </section>
        </div>
    </main>
</div>
</body>
</html>
