<?php
$revenus  = isset($revenus)  ? json_decode($revenus,  true) : [];
$errors   = json_decode($errors ?? '[]', true);
$accounts = isset($accounts) ? json_decode($accounts, true) : [];

$isCreate = str_starts_with($_SERVER['REQUEST_URI'], '/revenues/create');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Revenus</title>
</head>
<body>

<h1>Gestion des revenus</h1>

<?php if (!empty($errors)) : ?>
    <?php foreach ($errors as $error) : ?>
        <div style="color:red;"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($isCreate) : ?>

    <h2>Nouveau revenu</h2>
    <form method="POST" action="/revenues/create">

        <div style="display:flex; gap:16px;">
            <div style="flex:1;">
                <label for="nom">Nom court *</label><br>
                <input type="text"
                       name="nom"
                       id="nom"
                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                       placeholder="Ex: Salaire"
                       required>
            </div>
            <div style="flex:1;">
                <label for="montant">Montant (€) *</label><br>
                <input type="number"
                       step="0.01"
                       min="0.01"
                       name="montant"
                       id="montant"
                       value="<?= htmlspecialchars($_POST['montant'] ?? '0') ?>"
                       required>
            </div>
        </div>
        <br>

        <div>
            <label for="description">description</label><br>
            <textarea name="description"
                      id="description"
                      rows="4"
                      style="width:100%;"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>
        <br>

        <div>
            <label for="compte_id">Compte *</label><br>
            <select name="compte_id" id="compte_id" required>
                <option value="">Sélectionner</option>
                <?php foreach ($accounts as $account) : ?>
                    <option value="<?= $account['id'] ?>"
                        <?= (isset($_POST['compte_id']) && (int)$_POST['compte_id'] === $account['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($account['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>

        <div style="display:flex; gap:16px;">
            <div style="flex:1;">
                <label for="date_debut">Date de début </label><br>
                <input type="date"
                       name="date_debut"
                       id="date_debut"
                       value="<?= htmlspecialchars($_POST['date_debut'] ?? date('Y-m-d')) ?>"
                       required>
            </div>
            <div style="flex:1;">
                <label for="date_fin">Date de fin</label><br>
                <input type="date"
                       name="date_fin"
                       id="date_fin"
                       value="<?= htmlspecialchars($_POST['date_fin'] ?? '') ?>">
            </div>
        </div>
        <br>

        <!-- Boutons -->
        <div style="display:flex; justify-content:flex-end; gap:8px;">
            <a href="/revenues"><button type="button">Annuler</button></a>
            <button type="submit">Créer</button>
        </div>

    </form>

<?php else : ?>

    <a href="/revenues/create"><button>créer un revenu</button></a>
    <hr>

    <?php if (empty($revenus)) : ?>
        <p>aucun revenu enregistré.</p>
    <?php else : ?>
        <table>
            <tr>
                <th>Compte</th>
                <th>Nom</th>
                <th>Montant</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($revenus as $rev) : ?>
                <tr>
                    <td><?= htmlspecialchars($rev['compte_nom'] ?? '') ?></td>
                    <td><?= htmlspecialchars($rev['nom'] ?? '') ?></td>
                    <td><?= number_format($rev['montant'], 2) ?> €</td>
                    <td><?= htmlspecialchars(substr($rev['date_debut'], 0, 10)) ?></td>
                    <td><?= $rev['date_fin'] ? htmlspecialchars(substr($rev['date_fin'], 0, 10)) : '-' ?></td>
                    <td>
                        <form method="POST" action="/revenues/delete?id=<?= $rev['id'] ?>" style="display:inline">
                            <button onclick="return confirm('Supprimer ce revenu ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>