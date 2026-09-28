<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tindakan — Papan Pemantauan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" data-sidebar>
        <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-label="Sembunyikan panel navigasi" aria-expanded="true">‹</button>
        <span class="sidebar-resizer" data-sidebar-resizer aria-hidden="true"></span>
        <a class="brand" href="{{ route('dashboard') }}" aria-label="Dashboard"><span class="brand-mark">M</span></a>
        <nav class="side-nav" aria-label="Navigasi utama">
            <a class="nav-link" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span><span>Dashboard</span></a>
            <a class="nav-link is-active" href="{{ route('tindakan.index') }}"><span class="nav-icon">✓</span><span>Tindakan</span></a>
        </nav>
        <div class="sidebar-foot"><span class="status-dot"></span><span>Data disegerakkan</span></div>
    </aside>
    <main class="main-content">
        <header class="topbar">
            <h1 class="topbar-title">Papan Pemantauan</h1>
            <div class="topbar-meta"><span class="live-dot"></span><time datetime="{{ now()->toDateString() }}">{{ now()->format('d F Y') }}</time><span class="topbar-divider"></span><span class="avatar">{{ str(auth()->user()->name)->substr(0, 2)->upper() }}</span><span class="user-name">{{ auth()->user()->name }}</span></div>
        </header>
        <div class="page-wrap">
            <div class="page-heading">
                <div><p class="eyebrow">REKOD TERPERINCI</p><h1>Tindakan</h1><p class="heading-copy">Tambah, kemas kini dan padam rekod tindakan daripada satu tempat.</p></div>
                <a class="primary-action" href="{{ route('tindakan.create') }}"><span>+</span> Tambah tindakan</a>
            </div>
            @if(session('success'))<div class="flash-success">{{ session('success') }}</div>@endif
            <section class="panel table-panel">
                <form class="table-toolbar" method="GET">
                    <div class="search-box"><span>⌕</span><input name="search" type="search" value="{{ request('search') }}" placeholder="Cari ID, tema, pemilik atau catatan..." aria-label="Cari tindakan"></div>
                    <div class="filter-row">
                        <select name="status" aria-label="Tapis mengikut status"><option value="">Semua status</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
                        <select name="priority" aria-label="Tapis mengikut keutamaan"><option value="">Semua keutamaan</option>@foreach($priorities as $priority)<option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>@endforeach</select>
                        <select name="sort" aria-label="Susun rekod"><option value="action_code" @selected(request('sort', 'action_code') === 'action_code')>Susun: ID</option><option value="theme" @selected(request('sort') === 'theme')>Susun: Tema</option><option value="status" @selected(request('sort') === 'status')>Susun: Status</option><option value="priority" @selected(request('sort') === 'priority')>Susun: Keutamaan</option><option value="updated_on" @selected(request('sort') === 'updated_on')>Susun: Tarikh kemas kini</option></select>
                        <button class="reset-button" type="submit">Tapis</button>
                        <a class="reset-button reset-link" href="{{ route('tindakan.index') }}">Reset</a>
                    </div>
                </form>
                <div class="table-meta"><span>{{ $actions->total() }} rekod tindakan</span><span class="table-hint">Gunakan Edit atau Padam untuk mengurus rekod</span></div>
                <div class="table-scroll">
                    <table>
                        <thead><tr><th>No</th><th>ID</th><th>Rujukan</th><th>Tema</th><th>Tindakan diperlukan</th><th>Pemilik tindakan</th><th>Sasaran / tempoh</th><th>Status</th><th>Keutamaan</th><th>Asas tindakan</th><th>Kemajuan terkini</th><th>Bukti / keputusan</th><th>Tarikh kemas kini</th><th>Catatan sumber</th><th>Urus</th></tr></thead>
                        <tbody>
                        @forelse($actions as $action)
                            <tr>
                                <td>{{ $actions->firstItem() + $loop->index }}</td><td><strong class="table-id">{{ $action->action_code }}</strong></td><td>{{ $action->reference }}</td><td><strong>{{ $action->theme }}</strong></td><td>{{ $action->required_action }}</td><td>{{ $action->owner }}</td><td>{{ $action->target ?? '—' }}</td><td class="deadline-cell {{ $action->status !== 'Selesai' && $action->due_date?->isPast() ? 'deadline-overdue' : '' }}">{{ $action->due_date?->format('d M Y') ?? '—' }}</td><td><span class="status-pill status-{{ str($action->status)->slug() }}">{{ $action->status }}</span></td><td class="priority-{{ str($action->priority)->slug() }}">{{ $action->priority }}</td><td>{{ $action->basis }}</td><td>{{ $action->progress ?? '—' }}</td><td>{{ $action->evidence ?? '—' }}</td><td>{{ $action->updated_on?->format('d M Y') ?? '—' }}</td><td>{{ $action->source_note ?? '—' }}</td><td><div class="row-actions"><a class="small-action" href="{{ route('tindakan.edit', $action) }}">Edit</a><form method="POST" action="{{ route('tindakan.destroy', $action) }}" onsubmit="return confirm('Padam tindakan ini?')">@csrf @method('DELETE')<button class="small-action danger-action" type="submit">Padam</button></form></div></td>
                            </tr>
                        @empty
                            <tr><td colspan="16" class="empty-row">Tiada rekod tindakan.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="table-footer"><span>{{ $actions->firstItem() ?? 0 }} hingga {{ $actions->lastItem() ?? 0 }}</span>{{ $actions->links() }}</div>
            </section>
        </div>
    </main>
</div>
</body>
</html>
