<?php $users = json_decode($users ?? '[]', true); ?>

<div class="page-heading">
    <div class="page-heading__text">
        <h1 class="page-heading__title">Utilisateurs</h1>
        <p class="page-heading__subtitle"><?= count($users) ?> compte<?= count($users) > 1 ? 's' : '' ?> enregistré<?= count($users) > 1 ? 's' : '' ?></p>
    </div>
</div>

<div class="card card--table">
    <?php if (empty($users)): ?>
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
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
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Créé le</th>
                    <th class="col--shrink">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td style="font-weight:600;"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
                        <td class="col--mono col--muted"><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?php if ($user['role_id'] == 1): ?>
                                <span class="badge badge--red">Admin</span>
                            <?php else: ?>
                                <span class="badge badge--grey">Utilisateur</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['is_active']): ?>
                                <span class="badge badge--green">Actif</span>
                            <?php else: ?>
                                <span class="badge badge--grey">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td class="col--nowrap"><?= htmlspecialchars($user['date_created'] ?? '') ?></td>
                        <td class="col--shrink" style="white-space:nowrap;">
                            <a href="/admin/users/edit?id=<?= $user['id'] ?>" class="button button--sm btn--ghost">Éditer</a>
                            <?php if ($user['id'] != ($_SESSION['user_id'] ?? 0)): ?>
                                <a href="/admin/users/delete?id=<?= $user['id'] ?>"
                                   class="button button--sm btn--danger"
                                   onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>