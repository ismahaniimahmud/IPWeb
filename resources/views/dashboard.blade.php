<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- header -->
    <title>Papan Pemantauan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" data-sidebar>
        <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-label="Sembunyikan panel navigasi" aria-expanded="true">‹</button>
        <span class="sidebar-resizer" data-sidebar-resizer aria-hidden="true"></span>
        <a class="brand" href="{{ route('dashboard') }}" aria-label="Dashboard"><span class="brand-mark">M</span></a>
        <nav class="side-nav" aria-label="Navigasi utama">
            <a class="nav-link is-active" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span><span>Dashboard</span></a>
            <a class="nav-link" href="{{ route('tindakan.index') }}"><span class="nav-icon">✓</span><span>Tindakan</span></a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="topbar"><h1 class="topbar-title">Papan Pemantauan</h1><div class="topbar-meta"><span class="live-dot"></span><time datetime="{{ now()->toDateString() }}">{{ now()->format('d F Y') }}</time><span class="topbar-divider"></span><div class="account-menu" data-account-menu><button class="account-button" type="button" data-account-toggle aria-expanded="false"><span class="avatar">{{ str(auth()->user()->name)->substr(0, 2)->upper() }}</span><span class="user-name">{{ auth()->user()->name }}</span><span class="account-chevron">⌄</span></button><div class="account-dropdown" data-account-dropdown hidden><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Log keluar</button></form></div></div></div></header>
        <div class="page-wrap">
            <div class="kpi-grid">
                <div class="kpi-card"><span class="kpi-icon navy">◌</span><span><small>JUMLAH TINDAKAN</small><strong>{{ $actions->count() }}</strong><em>Semua rekod senarai tindakan</em></span></div>
                <div class="kpi-card"><span class="kpi-icon coral">!</span><span><small>BELUM DISAHKAN</small><strong>{{ $statusCounts['Belum disahkan'] ?? 0 }}</strong><em>{{ $actions->count() ? round(($statusCounts['Belum disahkan'] ?? 0) / $actions->count() * 100) : 0 }}% daripada jumlah tindakan</em></span><span class="kpi-badge coral-badge">Aktif</span></div>
                <div class="kpi-card"><span class="kpi-icon amber">↗</span><span><small>KEUTAMAAN TINGGI</small><strong>{{ $priorityCounts['Tinggi'] ?? 0 }}</strong><em>{{ $actions->count() ? round(($priorityCounts['Tinggi'] ?? 0) / $actions->count() * 100) : 0 }}% daripada jumlah tindakan</em></span><span class="kpi-badge amber-badge">Aktif</span></div>
                <div class="kpi-card"><span class="kpi-icon teal">♧</span><span><small>DALAM TINDAKAN</small><strong>{{ $statusCounts['Dalam tindakan'] ?? 0 }}</strong><em>{{ $actions->count() ? round(($statusCounts['Dalam tindakan'] ?? 0) / $actions->count() * 100) : 0 }}% sedang dilaksanakan</em></span><span class="kpi-badge teal-badge">Aktif</span></div>
            </div>
            <div class="dashboard-grid">
                <section class="panel status-panel"><div class="panel-heading"><div><h2>Taburan status</h2><p class="panel-description">Gambaran keseluruhan kemajuan tindakan</p></div></div><div class="status-content"><div class="donut" style="background: conic-gradient(#e26162 0 {{ ($statusCounts['Belum disahkan'] ?? 0) / max($actions->count(), 1) * 360 }}deg, #f19a18 {{ ($statusCounts['Belum disahkan'] ?? 0) / max($actions->count(), 1) * 360 }}deg {{ (($statusCounts['Belum disahkan'] ?? 0) + ($statusCounts['Dalam tindakan'] ?? 0)) / max($actions->count(), 1) * 360 }}deg, #7a6bb1 {{ (($statusCounts['Belum disahkan'] ?? 0) + ($statusCounts['Dalam tindakan'] ?? 0)) / max($actions->count(), 1) * 360 }}deg {{ (($statusCounts['Belum disahkan'] ?? 0) + ($statusCounts['Dalam tindakan'] ?? 0) + ($statusCounts['Pemantauan berterusan'] ?? 0)) / max($actions->count(), 1) * 360 }}deg, #2d70a8 {{ (($statusCounts['Belum disahkan'] ?? 0) + ($statusCounts['Dalam tindakan'] ?? 0) + ($statusCounts['Pemantauan berterusan'] ?? 0)) / max($actions->count(), 1) * 360 }}deg 360deg)"><div><strong>{{ $actions->count() }}</strong><span>JUMLAH</span></div></div><div class="legend">@foreach($statusCounts->sortDesc() as $status => $count)<div class="legend-row"><span class="legend-dot" style="background: {{ ['Belum disahkan' => '#e26162', 'Dalam tindakan' => '#f19a18', 'Pemantauan berterusan' => '#7a6bb1', 'Menunggu pihak luar' => '#2d70a8', 'Selesai' => '#168f91'][$status] ?? '#9aaabd' }}"></span><span>{{ $status }} ({{ $count }})</span></div>@endforeach</div></div></section>
                <section class="panel chart-panel"><div class="panel-heading"><div><h2>Taburan keutamaan</h2><p class="panel-description">Jumlah tindakan mengikut tahap keutamaan</p></div></div><div class="bar-chart">@foreach(['Tinggi', 'Sederhana', 'Rendah'] as $priority)<div class="bar-column"><strong>{{ $priorityCounts[$priority] ?? 0 }}</strong><div class="bar" style="height: {{ max(4, (($priorityCounts[$priority] ?? 0) / max($priorityCounts->max() ?: 1, 1)) * 145) }}px; background: {{ $priority === 'Tinggi' ? '#e04f49' : ($priority === 'Sederhana' ? '#f19a18' : '#299b71') }}"></div><span class="bar-label">{{ $priority }}</span></div>@endforeach</div></section>
                @php
                    $themeColors = ['#2d70a8', '#e26162', '#e7a334', '#168f91', '#7a6bb1', '#9aaabd'];
                    $themeSegments = [];
                    $themeTotal = $themeCounts->sum();

                    foreach ($themeCounts as $theme => $count) {
                        $themeSegments[] = [
                            'theme' => $theme,
                            'count' => $count,
                            'color' => $themeColors[count($themeSegments) % count($themeColors)],
                        ];
                    }

                    $themeGradient = '';
                    $start = 0;
                    foreach ($themeSegments as $index => $segment) {
                        $end = $themeTotal > 0 ? $start + ($segment['count'] / $themeTotal) * 360 : 0;
                        $themeGradient .= sprintf('%s %sdeg %sdeg%s', $segment['color'], $start, $end, $index === array_key_last($themeSegments) ? '' : ', ');
                        $start = $end;
                    }
                @endphp
            </div>
            <!-- Template for the lower grid section with tema teratas dengan tindakan terbuka dan tema paling banyak tindakan -->
            <div class="lower-grid"><section class="panel theme-panel"><div class="panel-heading"><div><h2>Tema teratas dengan tindakan terbuka</h2><p class="panel-description">Bidang yang mempunyai tindakan belum selesai</p></div></div><div class="theme-bars">@foreach($openThemeCounts as $theme => $count)<div class="theme-bar-column"><span class="theme-bar-label">{{ $theme }}</span><div class="theme-bar-track"><div class="theme-bar-fill" style="width: {{ $count / max($openThemeCounts->max() ?: 1, 1) * 100 }}%"></div></div><strong class="theme-bar-value">{{ $count }}</strong></div>@endforeach</div></section><section class="panel chart-panel"><div class="panel-heading"><div><h2>Tema paling banyak tindakan</h2><p class="panel-description">Bidang yang paling memerlukan perhatian</p></div></div><div class="status-content"><div class="donut" style="background: conic-gradient({{ $themeGradient }});"><div><strong>{{ $themeCounts->max() ?? 0 }}</strong><span>TERATAS</span></div></div><div class="legend">@foreach($themeSegments as $segment)<div class="legend-row"><span class="legend-dot" style="background: {{ $segment['color'] }}"></span><span>{{ $segment['theme'] }}</span><strong>{{ $segment['count'] }}</strong></div>@endforeach</div></div></section></div>
            <section class="panel dashboard-table"><div class="table-head"><div><h2>Senarai tindakan</h2><p class="panel-description">Rekod terkini daripada senarai tindakan</p></div><a class="text-link" href="{{ route('tindakan.index') }}">Lihat semua tindakan <span>→</span></a></div><div class="table-scroll"><table><thead><tr><th>No</th><th>ID</th><th>Rujukan</th><th>Tema</th><th>Pemilik tindakan</th><th>Status</th><th>Keutamaan</th><th>Tindakan diperlukan</th><th>Kemajuan terkini</th><th>Bukti / keputusan</th><th>Catatan sumber</th><th>Tarikh kemas kini</th></tr></thead><tbody>@foreach($recentActions as $action)<tr class="is-clickable" tabindex="0" role="button" aria-label="Lihat butiran tindakan {{ $action->action_code }}"><td>{{ $loop->iteration }}</td><td><a class="table-id" href="{{ route('tindakan.edit', $action) }}">{{ $action->action_code }}</a></td><td>{{ $action->reference }}</td><td><strong>{{ $action->theme }}</strong></td><td>{{ $action->owner }}</td><td><span class="status-pill status-{{ str($action->status)->slug() }}">{{ $action->status }}</span></td><td class="priority-{{ str($action->priority)->slug() }}">{{ $action->priority }}</td><td>{{ $action->required_action }}</td><td>{{ $action->progress ?? '—' }}</td><td>{{ $action->evidence ?? '—' }}</td><td>{{ $action->source_note ?? '—' }}</td><td>{{ $action->updated_on?->format('d M Y') ?? '—' }}</td></tr>@endforeach</tbody></table></div><div class="dashboard-table-footer"><span>Menunjukkan {{ $recentActions->count() }} rekod terbaharu</span><a class="primary-action" href="{{ route('tindakan.create') }}"><span>+</span> Tambah tindakan</a></div></section>
        </div>
    </main>
</div>
</body>
</html>
