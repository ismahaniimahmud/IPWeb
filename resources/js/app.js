document.querySelectorAll('[data-confirm]').forEach((element) => {
    element.addEventListener('click', (event) => {
        if (!window.confirm(element.dataset.confirm)) {
            event.preventDefault();
        }
    });
});

document.querySelectorAll('[data-sidebar]').forEach((sidebar) => {
    const toggle = sidebar.querySelector('[data-sidebar-toggle]');
    const resizer = sidebar.querySelector('[data-sidebar-resizer]');

    toggle?.addEventListener('click', () => {
        sidebar.classList.toggle('is-collapsed');
        toggle.setAttribute('aria-expanded', String(!sidebar.classList.contains('is-collapsed')));
        toggle.setAttribute('aria-label', sidebar.classList.contains('is-collapsed') ? 'Tunjukkan panel navigasi' : 'Sembunyikan panel navigasi');
    });

    resizer?.addEventListener('pointerdown', (event) => {
        if (sidebar.classList.contains('is-collapsed')) {
            sidebar.classList.remove('is-collapsed');
        }

        const startWidth = sidebar.getBoundingClientRect().width;
        const startX = event.clientX;
        const resize = (moveEvent) => {
            const width = Math.min(360, Math.max(170, startWidth + moveEvent.clientX - startX));
            sidebar.style.flexBasis = `${width}px`;
            sidebar.style.width = `${width}px`;
        };
        const stop = () => {
            document.body.classList.remove('is-resizing-sidebar');
            document.removeEventListener('pointermove', resize);
            document.removeEventListener('pointerup', stop);
        };

        document.body.classList.add('is-resizing-sidebar');
        document.addEventListener('pointermove', resize);
        document.addEventListener('pointerup', stop, { once: true });
    });
});

document.querySelectorAll('[data-account-menu]').forEach((menu) => {
    const toggle = menu.querySelector('[data-account-toggle]');
    const dropdown = menu.querySelector('[data-account-dropdown]');
    const name = menu.querySelector('[data-user-name]');

    toggle?.addEventListener('click', () => {
        const isOpen = dropdown.hidden;
        dropdown.hidden = !isOpen;
        toggle.setAttribute('aria-expanded', String(isOpen));
    });

    menu.querySelectorAll('[data-account-action]').forEach((action) => {
        action.addEventListener('click', () => {
            const isLogin = action.dataset.accountAction === 'login';
            name.textContent = isLogin ? 'Pentadbir' : 'Tetamu';
            menu.querySelector('[data-account-action="logout"]').hidden = isLogin;
            menu.querySelector('[data-account-action="login"]').hidden = !isLogin;
            dropdown.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
        });
    });
});

document.querySelectorAll('.topbar-meta').forEach((meta) => {
    if (meta.querySelector('[data-account-menu]')) {
        return;
    }

    const avatar = meta.querySelector('.avatar');
    const userName = meta.querySelector('.user-name');

    if (!avatar || !userName) {
        return;
    }

    const menu = document.createElement('div');
    menu.className = 'account-menu';
    menu.innerHTML = '<button class="account-button" type="button" data-account-toggle aria-expanded="false"><span class="avatar"></span><span class="user-name"></span><span class="account-chevron">⌄</span></button><div class="account-dropdown" data-account-dropdown hidden><button type="button" data-real-logout>Log keluar</button></div>';
    menu.querySelector('.avatar').textContent = avatar.textContent;
    menu.querySelector('.user-name').textContent = userName.textContent;
    avatar.remove();
    userName.remove();
    meta.append(menu);

    const toggle = menu.querySelector('[data-account-toggle]');
    const dropdown = menu.querySelector('[data-account-dropdown]');
    toggle.addEventListener('click', () => {
        const isOpen = dropdown.hidden;
        dropdown.hidden = !isOpen;
        toggle.setAttribute('aria-expanded', String(isOpen));
    });

    menu.querySelector('[data-real-logout]').addEventListener('click', async () => {
        await fetch('/logout', {
            method: 'POST',
            headers: {
                Accept: 'text/html',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        window.location.assign('/login');
    });
});

const tableColumnOrder = [
    'ID', 'Rujukan', 'Tema', 'Pemilik tindakan', 'Sasaran / tempoh',
    'Status', 'Keutamaan', 'Asas tindakan', 'Catatan sumber',
    'Tindakan diperlukan', 'Kemajuan terkini', 'Bukti / keputusan',
    'Tarikh kemas kini', 'Urus',
];

document.querySelectorAll('table').forEach((table) => {
    const headerCells = [...table.querySelectorAll('thead th')];
    const order = tableColumnOrder
        .map((label) => headerCells.findIndex((cell) => cell.textContent.trim() === label))
        .filter((index) => index >= 0);

    if (order.length !== headerCells.length) {
        return;
    }

    table.querySelectorAll('tr').forEach((row) => {
        const cells = [...row.children];
        row.replaceChildren(...order.map((index) => cells[index]));
    });
});

document.querySelectorAll('.topbar-meta > span').forEach((dateElement) => {
    if (/^\d{1,2} [A-Za-z]+ \d{4}$/.test(dateElement.textContent.trim())) {
        dateElement.textContent = new Intl.DateTimeFormat('ms-MY', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
        }).format(new Date());
    }
});

const dashboardRows = document.querySelectorAll('.dashboard-table tbody tr.is-clickable');

if (dashboardRows.length) {
    const detailDialog = document.createElement('dialog');
    detailDialog.className = 'action-detail-dialog';
    detailDialog.innerHTML = '<div class="action-detail-card"><div class="action-detail-heading"><div><p class="eyebrow">BUTIRAN TINDAKAN</p><h2 data-action-detail-title></h2></div><button class="dialog-close" type="button" data-action-detail-close aria-label="Tutup">×</button></div><dl class="action-detail-list" data-action-detail-list></dl></div>';
    document.body.append(detailDialog);

    const title = detailDialog.querySelector('[data-action-detail-title]');
    const detailList = detailDialog.querySelector('[data-action-detail-list]');
    const closeButton = detailDialog.querySelector('[data-action-detail-close]');
    const headers = [...dashboardRows[0].closest('table').querySelectorAll('thead th')];

    const openDetails = (row) => {
        const cells = [...row.children];
        const actionCode = cells[1]?.textContent.trim() || 'Tindakan';
        title.textContent = actionCode;
        detailList.replaceChildren();

        headers.forEach((header, index) => {
            if (index === 0 || !cells[index]) {
                return;
            }

            const label = document.createElement('dt');
            label.textContent = header.textContent.trim();
            const value = document.createElement('dd');
            value.textContent = cells[index].textContent.trim() || '—';
            detailList.append(label, value);
        });

        detailDialog.showModal();
    };

    dashboardRows.forEach((row) => {
        row.addEventListener('click', (event) => {
            if (event.target.closest('a, button, form')) {
                return;
            }

            openDetails(row);
        });

        row.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openDetails(row);
            }
        });
    });

    closeButton.addEventListener('click', () => detailDialog.close());
    detailDialog.addEventListener('click', (event) => {
        if (event.target === detailDialog) {
            detailDialog.close();
        }
    });
}

