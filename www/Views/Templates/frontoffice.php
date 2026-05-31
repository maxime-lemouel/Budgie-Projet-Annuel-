<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budgie</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="/dist/css/app.css">
</head>
<body>

<?php
$currentUri = $_SERVER['REQUEST_URI'] ?? '/';
$pseudo = $_SESSION['pseudo'] ?? '';
$email = $_SESSION['email'] ?? '';
$roleId = $_SESSION['user_role_id'] ?? 2;
$initiale = strtoupper(mb_substr($pseudo ?: $email, 0, 1));

$navItems = [
        ['href' => '/', 'label' => 'Tableau de bord', 'match' => '/', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>'],
        ['href' => '/accounts', 'label' => 'Comptes', 'match' => '/accounts', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5m0 0h-6m6 0a2 2 0 01-2 2h-4"/></svg>'],
        ['href' => '/revenues', 'label' => 'revenues', 'match' => '/revenues', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5V4.5m0 0l-5 5m5-5l5 5"/></svg>'],
        ['href' => '/expenses', 'label' => 'Dépenses', 'match' => '/expenses', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>'],
        ['href' => '/contact', 'label' => 'Contact', 'match' => '/contact', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'],
];

if (($roleId ?? 2) == 1) {
    $navItems[] = ['href' => '/admin', 'label' => 'Administration', 'match' => '/admin', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'];
}
?>

<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="app-shell">

    <aside class="sidebar" id="sidebar">

        <a href="/" class="sidebar__brand">
            <div class="sidebar__brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5m0 0h-6m6 0a2 2 0 01-2 2h-4"/>
                </svg>
            </div>
            <span class="sidebar__brand-name">Budgie</span>
        </a>

        <nav class="sidebar__nav" aria-label="Navigation principale">
            <span class="nav-section-label">Menu</span>
            <?php foreach ($navItems as $item):
                $isActive = ($currentUri === $item['match'])
                        || ($item['match'] !== '/' && str_starts_with($currentUri, $item['match']));
                ?>
                <a href="<?= htmlspecialchars($item['href']) ?>"
                   class="nav-item<?= $isActive ? ' nav-item--active' : '' ?>">
                    <?= $item['icon'] ?>
                    <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar__footer">
            <div class="sidebar__user">
                <div class="sidebar__avatar"><?= htmlspecialchars($initiale) ?></div>
                <div class="sidebar__user-info">
                    <div class="sidebar__user-name"><?= htmlspecialchars($pseudo ?: 'Utilisateur') ?></div>
                    <div class="sidebar__user-email"><?= htmlspecialchars($email) ?></div>
                </div>
            </div>
            <a href="/logout" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                </svg>
                Déconnexion
            </a>
        </div>

    </aside>

    <div class="main-area">

        <header class="main-header">
            <button class="hamburger" onclick="openSidebar()" aria-label="Ouvrir le menu">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                </svg>
            </button>
            <div></div>
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