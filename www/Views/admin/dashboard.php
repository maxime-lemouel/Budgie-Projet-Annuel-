<?php
$stats = json_decode($stats ?? '{}', true);
$latest_users = json_decode($latest_users ?? '[]', true);
?>

<div class="page-heading">
    <div class="page-heading__text">
        <h1 class="page-heading__title">Dashboard</h1>
        <p class="page-heading__subtitle">Vue d'ensemble de l'application</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--blue">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
        </div>
        <div class="stat-card__label">Utilisateurs totaux</div>
        <div class="stat-card__value"><?= $stats['total_users'] ?? 0 ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--green">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-card__label">Utilisateurs actifs</div>
        <div class="stat-card__value"><?= $stats['active_users'] ?? 0 ?></div>
    </div>
</div>

<div class="card" style="padding:0; overflow:hidden;">
    <div class="card__header" style="padding:16px 20px;">
        <div>
            <div class="card__title">Derniers utilisateurs inscrits</div>
            <div class="card__subtitle">Les comptes les plus récents</div>
        </div>
        <a href="/admin/users" class="button button--ghost btn--sm">Voir tous</a>
    </div>

    <?php if (empty($latest_users)): ?>
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
            </svg>
            <div class="empty-state__title">Aucun utilisateur</div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Inscrit le</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($latest_users as $user): ?>
                    <tr>
                        <td style="font-weight:600;"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
                        <td style="color:var(--grey-500);"><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['date_created'] ?? '') ?></td>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>