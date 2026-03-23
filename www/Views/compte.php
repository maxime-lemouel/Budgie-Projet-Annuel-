<?php
$accounts = isset($accounts) ? json_decode($accounts, true) : [];
$errors   = json_decode($errors ?? '[]', true);
$account  = isset($account)  ? json_decode($account, true)  : null;

$typeLabels = [
    'livret_a' => 'Livret A',
    'compte_courant' => 'Compte courant'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Comptes</title>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>

<h1>Gestion des comptes</h1>
<?php if (!empty($errors)) : ?>
    <?php foreach ($errors as $error) : ?>
        <div style="color:red;"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($_SERVER['REQUEST_URI'] === '/accounts/create'): ?>

    <h2>Nouveau compte</h2>
    <form method="POST" action="/accounts/create">
        <label>Nom du compte</label><br>
        <input type="text" name="nom"><br><br>

        <label>Description</label><br>
        <textarea name="description"></textarea><br><br>

        <label>Type de compte</label><br>
        <select name="type">
            <option value="livret_a">Livret A</option>
            <option value="compte_courant">Compte courant</option>
        </select><br><br>

        <label>Taux de rémunération (%)</label><br>
        <input type="number" step="0.01" name="taux_remuneration"><br><br>

        <label>Taux d'imposition (%)</label><br>
        <input type="number" step="0.01" name="taux_imposition"><br><br>

        <button type="submit">Créer</button>
        <a href="/accounts">Annuler</a>
    </form>

<?php elseif ($account !== null): ?>

    <h2>Modifier le compte</h2>
    <form method="POST" action="/accounts/edit?id=<?= $account['id'] ?>">
        <label>Nom du compte</label><br>
        <input type="text" name="nom" value="<?= htmlspecialchars($account['nom'] ?? '') ?>"><br><br>

        <label>Description</label><br>
        <textarea name="description"><?= htmlspecialchars($account['description'] ?? '') ?></textarea><br><br>

        <label>Type de compte</label><br>
        <select name="type">
            <option value="livret_a" <?= ($account['type'] ?? '') === 'livret_a' ? 'selected' : '' ?>>Livret A</option>
            <option value="compte_courant" <?= ($account['type'] ?? '') === 'compte_courant' ? 'selected' : '' ?>>Compte courant</option>
        </select><br><br>

        <label>Taux de rémunération (%)</label><br>
        <input type="number" step="0.01" name="taux_remuneration" value="<?= htmlspecialchars($account['taux_remuneration'] ?? '') ?>"><br><br>

        <label>Taux d'imposition (%)</label><br>
        <input type="number" step="0.01" name="taux_imposition" value="<?= htmlspecialchars($account['taux_imposition'] ?? '') ?>"><br><br>

        <button type="submit">Mettre à jour</button>
        <a href="/accounts">Annuler</a>
    </form>

<?php else: ?>
    <a href="/accounts/create"><button>+ Créer un compte</button></a>
    <hr>

    <?php if (empty($accounts)): ?>
        <p>Aucun compte.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Description</th>
                <th>Taux rémunération (%)</th>
                <th>Taux imposition (%)</th>
                <th>Date création</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($accounts as $acc): ?>
                <tr>
                    <td><?= htmlspecialchars($acc['nom'] ?? '') ?></td>
                    <td>
                        <?= htmlspecialchars($typeLabels[$acc['type']] ?? $acc['type']) ?>
                    </td>

                    <td><?= htmlspecialchars($acc['description'] ?? '') ?></td>
                    <td>
                        <?= $acc['taux_remuneration'] !== null
                            ? number_format($acc['taux_remuneration'], 2) . ' %'
                            : '-' ?>
                    </td>

                    <td>
                        <?= $acc['taux_imposition'] !== null
                            ? number_format($acc['taux_imposition'], 2) . ' %'
                            : '-' ?>
                    </td>

                    <td><?= htmlspecialchars($acc['date_creation'] ?? '') ?></td>

                    <td>
                        <a href="/accounts/edit?id=<?= $acc['id'] ?>">Modifier</a>
                        &nbsp;
                        <form method="POST" action="/accounts/delete?id=<?= $acc['id'] ?>" style="display:inline">
                            <button onclick="return confirm('Supprimer ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>