<!DOCTYPE html>
<html lang="ms">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'Papan Pemantauan') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="app-shell">
            <aside class="sidebar">
                <a class="brand" href="#dashboard" data-view-link="dashboard" aria-label="Dashboard"><span class="brand-mark">M</span><span class="brand-copy"><strong>MTIC</strong><small>monitoring desk</small></span></a>
                <nav class="side-nav" aria-label="Navigasi utama">
                    <a class="nav-link is-active" href="#dashboard" data-view-link="dashboard"><span class="nav-icon">⌂</span><span>Dashboard</span></a>
                    <a class="nav-link" href="#tindakan" data-view-link="tindakan"><span class="nav-icon">✓</span><span>Tindakan</span><span class="nav-count" id="navCount">0</span></a>
                </nav>
                
            </aside>

            <main class="main-content">
                <header class="topbar"><div class="mobile-brand"><span class="brand-mark">M</span><span>MTIC / MONITORING DESK</span></div><div class="topbar-meta"><span class="live-dot"></span><span>16 September 2026</span><span class="topbar-divider"></span><span class="avatar">PN</span><span class="user-name">Pentadbir</span><button class="icon-button" type="button" aria-label="Buka menu pengguna">⌄</button></div></header>
                <div class="page-wrap">
                    <section class="page-view is-visible" id="dashboardView" data-view="dashboard">
                        <div class="page-heading reveal"><div><p class="eyebrow">PUSAT KAWALAN TINDAKAN</p><h1>Papan Pemantauan</h1><p class="heading-copy">Gambaran pantas terhadap komitmen, kemajuan dan perkara yang memerlukan perhatian.</p></div><a class="primary-action" href="#tindakan" data-view-link="tindakan"><span>+</span> Tindakan baharu</a></div>
                        <div class="kpi-grid reveal-stagger">
                            <button class="kpi-card" type="button" data-kpi-filter=""><span class="kpi-icon navy">◌</span><span><small>JUMLAH TINDAKAN</small><strong id="totalKpi">0</strong><em>Semua rekod aktif</em></span><span class="kpi-arrow">↗</span></button>
                            <button class="kpi-card" type="button" data-kpi-filter="Belum disahkan"><span class="kpi-icon coral">!</span><span><small>BELUM DISAHKAN</small><strong id="unverifiedKpi">0</strong><em id="unverifiedNote">0% daripada jumlah</em></span><span class="kpi-arrow">↗</span></button>
                            <button class="kpi-card" type="button" data-kpi-filter="Tinggi"><span class="kpi-icon amber">↑</span><span><small>KEUTAMAAN TINGGI</small><strong id="highKpi">0</strong><em id="highNote">0% daripada jumlah</em></span><span class="kpi-arrow">↗</span></button>
                            <button class="kpi-card" type="button" data-kpi-filter="Dalam tindakan"><span class="kpi-icon teal">◒</span><span><small>DALAM TINDAKAN</small><strong id="actionKpi">0</strong><em id="actionNote">0% sedang dilaksanakan</em></span><span class="kpi-arrow">↗</span></button>
                        </div>
                        <div class="dashboard-grid reveal-stagger">
                            <section class="panel status-panel"><div class="panel-heading"><div><p class="eyebrow">KEMAJUAN</p><h2>Taburan status</h2></div><button class="more-button" type="button" aria-label="Lihat semua status">•••</button></div><div class="status-content"><div class="donut" id="statusDonut"><div><strong id="donutTotal">0</strong><span>JUMLAH</span></div></div><div class="legend" id="statusLegend"></div></div></section>
                            <section class="panel chart-panel"><div class="panel-heading"><div><p class="eyebrow">FOKUS</p><h2>Keutamaan tindakan</h2></div><span class="period-label">SEMUA REKOD</span></div><div class="bar-chart" id="priorityChart"></div></section>
                            <section class="panel insight-panel"><p class="eyebrow">PERHATIAN UTAMA</p><h2>Tema dengan tindakan terbuka</h2><p class="panel-description">Bidang yang memerlukan susulan paling dekat.</p><div class="open-list" id="openThemes"></div><a class="text-link" href="#tindakan" data-view-link="tindakan">Lihat semua tindakan <span>→</span></a></section>
                        </div>
                        <div class="lower-grid reveal"><section class="panel theme-panel"><div class="panel-heading"><div><p class="eyebrow">GAMBARAN BIDANG</p><h2>Tema teratas dengan tindakan terbuka</h2></div><a class="text-link" href="#tindakan" data-view-link="tindakan">Buka senarai <span>→</span></a></div><div class="theme-bars" id="themeChart"></div></section><section class="panel timeline-panel"><div class="panel-heading"><div><p class="eyebrow">AKTIVITI MESYUARAT</p><h2>Tindakan mengikut tarikh mesyuarat</h2></div><span class="period-label">TERKINI</span></div><div class="timeline-chart" id="timelineChart"></div></section></div>
                    </section>
                    <section class="page-view" id="tindakanView" data-view="tindakan">
                        <div class="page-heading"><div><p class="eyebrow">REKOD TERPERINCI</p><h1>Tindakan</h1><p class="heading-copy">Cari, tapis dan semak setiap tindakan daripada rekod mesyuarat.</p></div><button class="primary-action" type="button" id="exportButton"><span>↓</span> Eksport CSV</button></div>
                        <section class="panel table-panel"><div class="table-toolbar"><div class="search-box"><span>⌕</span><input id="searchInput" type="search" placeholder="Cari ID, tema, pemilik atau catatan..." aria-label="Cari tindakan"></div><div class="filter-row"><select id="statusFilter" aria-label="Tapis mengikut status"><option value="">Semua status</option></select><select id="priorityFilter" aria-label="Tapis mengikut keutamaan"><option value="">Semua keutamaan</option></select><select id="themeFilter" aria-label="Tapis mengikut tema"><option value="">Semua tema</option></select><button class="reset-button" type="button" id="resetFilters">Reset</button></div></div><div class="table-meta"><span id="tableInfo">0 rekod</span><span class="table-hint">Klik mana-mana baris untuk melihat butiran</span></div><div class="table-scroll"><table><thead><tr><th>ID</th><th>Rujukan</th><th>Tema</th><th>Tindakan Diperlukan</th><th>Pemilik Tindakan</th><th>Sasaran / Tempoh</th><th>Status</th><th>Keutamaan</th><th>Asas Tindakan</th><th>Kemajuan Terkini</th><th>Bukti / Keputusan</th><th>Tarikh Kemas Kini</th><th>Catatan Sumber</th></tr></thead><tbody id="tableBody"></tbody></table></div><div class="table-footer"><span>Menunjukkan rekod ditapis</span><div class="pagination" id="pagination"></div></div></section>
                    </section>
                </div>
            </main>
        </div>
        <div class="toast" id="toast" role="status"></div>
    </body>
</html>
