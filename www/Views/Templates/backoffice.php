<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — Budgie</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="/dist/css/admin.css">
</head>
<body>

<?php
$currentUri = $_SERVER['REQUEST_URI'] ?? '/admin';
$pseudo     = $_SESSION['pseudo'] ?? '';
$email      = $_SESSION['email'] ?? '';
$initiale   = strtoupper(mb_substr($pseudo ?: $email, 0, 1));

$navItems = [
        ['href' => '/admin',         'label' => 'Dashboard',    'match' => '/admin',         'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>'],
        ['href' => '/admin/users',   'label' => 'Utilisateurs', 'match' => '/admin/users',   'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>'],
];
?>

<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="app-shell">

    <aside class="sidebar" id="sidebar">

        <a href="/admin" class="sidebar__brand">
            <div class="sidebar__brand-icon" style="background: var(--red);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="sidebar__brand-name">Administration</div>
                <div style="font-size:11px; color:var(--grey-500); font-weight:400;">Budgie</div>
            </div>
        </a>

        <nav class="sidebar__nav">
            <span class="nav-section-label">Gestion</span>
            <?php foreach ($navItems as $item):
                $isActive = ($currentUri === $item['match'])
                        || ($item['match'] !== '/admin' && str_starts_with($currentUri, $item['match']));
                ?>
                <a href="<?= htmlspecialchars($item['href']) ?>"
                   class="nav-item<?= $isActive ? ' nav-item--active' : '' ?>">
                    <?= $item['icon'] ?>
                    <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php endforeach; ?>

            <span class="nav-section-label" style="margin-top:8px;">Navigation</span>
            <a href="/" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Retour au site
            </a>
        </nav>

        <div class="sidebar__footer">
            <div class="sidebar__user">
                <div class="sidebar__avatar"><?= htmlspecialchars($initiale) ?></div>
                <div class="sidebar__user-info">
                    <div class="sidebar__user-name"><?= htmlspecialchars($pseudo ?: 'Admin') ?></div>
                    <div class="sidebar__user-email"><?= htmlspecialchars($email) ?></div>
                </div>
            </div>
            <a href="/logout" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                </svg>
                Déconnexion
            </a>
        </div>

    </aside>

    <div class="main-area">

        <header class="main-header">
            <button class="hamburger" onclick="openSidebar()" aria-label="Ouvrir le menu">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                </svg>
            </button>
            <div class="admin-badge">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
                Zone administrateur
            </div>
        </header>

        <main class="main-content">
            <?php include $this->viewPath; ?>
        </main>

    </div>

</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('sidebar--open');
        document.getElementById('sidebar-overlay').classList.add('sidebar-overlay--visible');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('sidebar--open');
        document.getElementById('sidebar-overlay').classList.remove('sidebar-overlay--visible');
    }
</script>

<script src="/public/js/modal.js"></script>
</body>
</html>