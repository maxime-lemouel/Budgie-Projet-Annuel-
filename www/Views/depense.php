<?php
$depenses = isset($depenses) ? json_decode($depenses, true) : [];
$errors   = json_decode($errors   ?? '[]', true);
$accounts = isset($accounts) ? json_decode($accounts, true) : [];

$isCreate = str_starts_with($_SERVER['REQUEST_URI'], '/expenses/create');

$selectedFrequence = $_POST['frequence'] ?? 'mois';

$depense  = isset($depense)  ? json_decode($depense,  true) : null;
$uri      = $_SERVER['REQUEST_URI'];
$isEdit   = str_starts_with($uri, '/expenses/edit');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dépenses</title>
</head>
<body>

<h1>Gestion des dépenses</h1>

<?php if (!empty($errors)) : ?>
    <?php foreach ($errors as $error) : ?>
        <div style="color:red;"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($isCreate) : ?>

    <h2>Nouvelle dépense</h2>
    <form method="POST" action="/expenses/create">

        <!-- Ligne 1 : Nom court / Montant -->
        <div style="display:flex; gap:16px;">
            <div style="flex:1;">
                <label for="nom">Nom court *</label><br>
                <input type="text"
                       name="nom"
                       id="nom"
                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                       placeholder="Ex: Crédit Auto"
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

        <!-- Ligne 2 : Description -->
        <div>
            <label for="description">Description</label><br>
            <textarea name="description"
                      id="description"
                      rows="4"
                      style="width:100%;"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>
        <br>

        <!-- Ligne 3 : Compte / Fréquence -->
        <div style="display:flex; gap:16px;">
            <div style="flex:1;">
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
            <div style="flex:1;">
                <label for="frequence">Fréquence *</label><br>
                <select name="frequence" id="frequence" required>
                    <option value="mois" <?= $selectedFrequence === 'mois' ? 'selected' : '' ?>>
                        Tous les N mois
                    </option>
                    <option value="ponctuelle" <?= $selectedFrequence === 'ponctuelle' ? 'selected' : '' ?>>
                        Ponctuelle (une seule fois)
                    </option>
                </select>
            </div>
        </div>
        <br>

        <!-- Ligne 4 : Champ N — visible uniquement si fréquence = mois -->
        <div id="iteration_block">
            <label for="iteration">Tous les N mois</label><br>
            <input type="number"
                   name="iteration"
                   id="iteration"
                   min="1"
                   value="<?= htmlspecialchars($_POST['iteration'] ?? '1') ?>"
                   required>
        </div>
        <br>

        <!-- Ligne 5 : Date de début / Date de fin -->
        <div style="display:flex; gap:16px;">
            <div style="flex:1;">
                <label for="date_debut">Date de début *</label><br>
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
            <a href="/expenses"><button type="button">Annuler</button></a>
            <button type="submit">Créer</button>
        </div>

    </form>

    <script>
        const selectFrequence = document.getElementById('frequence');
        const iterationBlock  = document.getElementById('iteration_block');
        const iterationInput  = document.getElementById('iteration');

        function toggleIteration() {
            const isMois = selectFrequence.value === 'mois';
            iterationBlock.style.display = isMois ? 'block' : 'none';
            if (isMois) {
                iterationInput.setAttribute('required', 'required');
            } else {
                iterationInput.removeAttribute('required');
            }
        }

        selectFrequence.addEventListener('change', toggleIteration);
        toggleIteration();
    </script>

<?php // Ajouté : bloc édition
elseif ($isEdit && $depense) : ?>

    <h2>Modifier la dépense</h2>
    <form method="POST" action="/expenses/edit?id=<?= (int)$depense['id'] ?>">

        <div style="display:flex; gap:16px;">
            <div style="flex:1;">
                <label for="nom">Nom court *</label><br>
                <input type="text"
                       name="nom"
                       id="nom"
                       value="<?= htmlspecialchars($depense['nom'] ?? '') ?>"
                       placeholder="Ex: Crédit Auto"
                       required>
            </div>
            <div style="flex:1;">
                <label for="montant">Montant (€) *</label><br>
                <input type="number"
                       step="0.01"
                       min="0.01"
                       name="montant"
                       id="montant"
                       value="<?= htmlspecialchars($depense['montant'] ?? '0') ?>"
                       required>
            </div>
        </div>
        <br>

        <div>
            <label for="description">Description</label><br>
            <textarea name="description"
                      id="description"
                      rows="4"
                      style="width:100%;"><?= htmlspecialchars($depense['description'] ?? '') ?></textarea>
        </div>
        <br>

        <div style="display:flex; gap:16px;">
            <div style="flex:1;">
                <label for="compte_id">Compte *</label><br>
                <select name="compte_id" id="compte_id" required>
                    <option value="">Sélectionner</option>
                    <?php foreach ($accounts as $account) : ?>
                        <option value="<?= $account['id'] ?>"
                            <?= (int)($depense['compte_id'] ?? 0) === $account['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($account['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex:1;">
                <?php $freq = ($depense['ponctuelle'] ?? false) ? 'ponctuelle' : 'mois'; ?>
                <label for="frequence">Fréquence *</label><br>
                <select name="frequence" id="frequence" required>
                    <option value="mois"       <?= $freq === 'mois'       ? 'selected' : '' ?>>Tous les N mois</option>
                    <option value="ponctuelle" <?= $freq === 'ponctuelle' ? 'selected' : '' ?>>Ponctuelle (une seule fois)</option>
                </select>
            </div>
        </div>
        <br>

        <div id="iteration_block">
            <label for="iteration">Tous les N mois</label><br>
            <input type="number"
                   name="iteration"
                   id="iteration"
                   min="1"
                   value="<?= htmlspecialchars($depense['iteration'] ?? '1') ?>">
        </div>
        <br>

        <div style="display:flex; gap:16px;">
            <div style="flex:1;">
                <label for="date_debut">Date de début *</label><br>
                <input type="date"
                       name="date_debut"
                       id="date_debut"
                       value="<?= htmlspecialchars(substr($depense['date_debut'] ?? '', 0, 10)) ?>"
                       required>
            </div>
            <div style="flex:1;">
                <label for="date_fin">Date de fin</label><br>
                <input type="date"
                       name="date_fin"
                       id="date_fin"
                       value="<?= htmlspecialchars(substr($depense['date_fin'] ?? '', 0, 10)) ?>">
            </div>
        </div>
        <br>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
            <a href="/expenses"><button type="button">Annuler</button></a>
            <button type="submit">Enregistrer</button>
        </div>

    </form>

    <script>
        const selectFrequence = document.getElementById('frequence');
        const iterationBlock  = document.getElementById('iteration_block');
        const iterationInput  = document.getElementById('iteration');

        function toggleIteration() {
            const isMois = selectFrequence.value === 'mois';
            iterationBlock.style.display = isMois ? 'block' : 'none';
            if (isMois) {
                iterationInput.setAttribute('required', 'required');
            } else {
                iterationInput.removeAttribute('required');
            }
        }

        selectFrequence.addEventListener('change', toggleIteration);
        toggleIteration();
    </script>

<?php else : ?>

    <a href="/expenses/create"><button>+ Créer une dépense</button></a>
    <hr>

    <?php if (empty($depenses)) : ?>
        <p>Aucune dépense enregistrée.</p>
    <?php else : ?>
        <table>
            <tr>
                <th>Compte</th>
                <th>Nom</th>
                <th>Montant</th>
                <th>Fréquence</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($depenses as $dep) : ?>
                <tr>
                    <td><?= htmlspecialchars($dep['compte_nom'] ?? '') ?></td>
                    <td><?= htmlspecialchars($dep['nom'] ?? '') ?></td>
                    <td><?= number_format($dep['montant'], 2) ?> €</td>
                    <td>
                        <?= $dep['ponctuelle']
                            ? 'Ponctuelle'
                            : 'Tous les ' . (int)$dep['iteration'] . ' mois' ?>
                    </td>
                    <td><?= htmlspecialchars(substr($dep['date_debut'], 0, 10)) ?></td>
                    <td><?= $dep['date_fin'] ? htmlspecialchars(substr($dep['date_fin'], 0, 10)) : '-' ?></td>
                    <td>
                        <a href="/expenses/edit?id=<?= (int)$dep['id'] ?>">
                            <button type="button">Modifier</button>
                        </a>
                        <form method="POST" action="/expenses/delete?id=<?= $dep['id'] ?>" style="display:inline">
                            <button onclick="return confirm('Supprimer cette dépense ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>
