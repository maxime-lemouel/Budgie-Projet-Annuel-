<?php
$user = json_decode($user ?? '{}', true);
$errors = json_decode($errors ?? '[]', true);
$success = ($success ?? 'false') == 'true';
?>

<div class="page-heading">
    <div class="page-heading__text">
        <h1 class="page-heading__title">Éditer l'utilisateur</h1>
        <p class="page-heading__subtitle"><?= htmlspecialchars(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) ?></p>
    </div>
    <a href="/admin/users" class="button button--ghost">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"
             style="width:15px;height:15px">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Retour
    </a>
</div>

<?php if ($success): ?>
    <div class="alert alert--success">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Utilisateur mis à jour avec succès.
    </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $err): ?>
        <div class="alert alert--error"><?= htmlspecialchars(is_string($err) ? $err : json_encode($err)) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="card" style="max-width:540px;">
    <form method="POST" style="display:flex; flex-direction:column; gap:18px;">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="firstname">Prénom *</label>
                <input class="form-control" type="text" id="firstname" name="firstname" required
                       value="<?= htmlspecialchars($user['firstname'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="lastname">Nom *</label>
                <input class="form-control" type="text" id="lastname" name="lastname" required
                       value="<?= htmlspecialchars($user['lastname'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email *</label>
            <input class="form-control" type="email" id="email" name="email" required
                   value="<?= htmlspecialchars($user['email'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label class="form-label" for="role_id">Rôle</label>
            <select class="form-control" id="role_id" name="role_id">
                <option value="2" <?= ($user['role_id'] ?? 2) == 2 ? 'selected' : '' ?>>Utilisateur</option>
                <option value="1" <?= ($user['role_id'] ?? 2) == 1 ? 'selected' : '' ?>>Administrateur</option>
            </select>
        </div>

        <?php if (($user['role_id'] ?? 2) == 2): ?>
            <div style="display:flex; align-items:center; gap:10px;">
                <input type="checkbox" id="is_active" name="is_active" style="width:16px;height:16px;cursor:pointer;"
                        <?= ($user['is_active'] ?? false) ? 'checked' : '' ?>>
                <label for="is_active" class="form-label" style="margin:0; cursor:pointer;">Compte actif</label>
            </div>
        <?php endif; ?>

        <div style="display:flex; gap:8px; justify-content:flex-end;">
            <a href="/admin/users" class="button button--ghost">Annuler</a>
            <button type="submit" class="button button--primary">Enregistrer</button>
        </div>
    </form>
</div>