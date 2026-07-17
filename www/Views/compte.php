<?php
$accounts = isset($accounts) ? json_decode($accounts, true) : [];
$errors = json_decode($errors ?? '[]', true);
$account = isset($account) ? json_decode($account, true) : null;

$autoOpen = $account !== null || !empty($errors);

$typeLabels = [
        'livret_a'       => 'Livret A',
        'compte_courant' => 'Compte courant',
];

function getTypeLabel(array $typeLabels, array $compte): string {
    // Si le type n'est pas dans les types connus, on affiche directement sa valeur
    return htmlspecialchars($typeLabels[$compte['type']] ?? $compte['type']);
}

function getBadgeClass(array $compte): string {
    return match($compte['type']) {
        'livret_a'       => 'badge--green',
        'compte_courant' => 'badge--blue',
        default          => 'badge--grey',
    };
}

function formatDateFr(?string $date): string {
    if (!$date) return '—';
    $d = DateTime::createFromFormat('Y-m-d', substr($date, 0, 10));
    return $d ? htmlspecialchars($d->format('d/m/Y')) : htmlspecialchars($date);
}
?>

<div class="page-heading">
    <div class="page-heading__text">
        <h1 class="page-heading__title">Comptes</h1>
        <p class="page-heading__subtitle">Gérez vos comptes bancaires et livrets</p>
    </div>
    <button class="button button--primary" data-modal-open="modal-compte" data-modal-mode="create">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Nouveau compte
    </button>
</div>

<div class="card card--table">
    <?php if (empty($accounts)): ?>
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5m0 0h-6m6 0a2 2 0 01-2 2h-4"/>
            </svg>
            <div class="empty-state__title">Aucun compte</div>
            <div class="empty-state__desc">Ajoutez votre premier compte pour commencer.</div>
            <button class="button button--primary" data-modal-open="modal-compte" data-modal-mode="create">
                Créer un compte
            </button>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th class="col--nowrap">Taux rémun.</th>
                    <th class="col--nowrap">Taux impos.</th>
                    <th class="col--nowrap">Créé le</th>
                    <th class="col--shrink">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($accounts as $compte): ?>
                    <tr>
                        <td style="font-weight:600;"><?= htmlspecialchars($compte['nom'] ?? '') ?></td>
                        <td>
                            <span class="badge <?= getBadgeClass($compte) ?>">
                                <?= getTypeLabel($typeLabels, $compte) ?>
                            </span>
                        </td>
                        <td class="col--muted"><?= htmlspecialchars($compte['description'] ?? '—') ?></td>
                        <td class="col--nowrap"><?= $compte['taux_remuneration'] !== null ? number_format($compte['taux_remuneration'], 2) . ' %' : '—' ?></td>
                        <td class="col--nowrap"><?= $compte['taux_imposition'] !== null ? number_format($compte['taux_imposition'], 2) . ' %' : '—' ?></td>
                        <td class="col--nowrap"><?= formatDateFr($compte['date_creation'] ?? null) ?></td>
                        <td class="col--shrink" style="white-space:nowrap;">
                            <button class="button button--ghost button--sm"
                                    data-modal-open="modal-compte"
                                    data-modal-mode="edit"
                                    data-id="<?= $compte['id'] ?>"
                                    data-nom="<?= htmlspecialchars($compte['nom'] ?? '', ENT_QUOTES) ?>"
                                    data-description="<?= htmlspecialchars($compte['description'] ?? '', ENT_QUOTES) ?>"
                                    data-type="<?= htmlspecialchars($compte['type'], ENT_QUOTES) ?>"
                                    data-is-autre="<?= $compte['is_autre'] ? '1' : '0' ?>"
                                    data-type-autre="<?= htmlspecialchars($compte['type_autre'] ?? '', ENT_QUOTES) ?>"
                                    data-taux-remuneration="<?= htmlspecialchars($compte['taux_remuneration'] ?? '', ENT_QUOTES) ?>"
                                    data-taux-imposition="<?= htmlspecialchars($compte['taux_imposition'] ?? '', ENT_QUOTES) ?>">
                                Modifier
                            </button>
                            <form method="POST" action="/accounts/delete?id=<?= $compte['id'] ?>" style="display:inline"
                                  onsubmit="return confirm('Supprimer ce compte ?')">
                                <button class="button button--danger button--sm" type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<dialog id="modal-compte" <?= $autoOpen ? 'data-modal-auto' : '' ?>>
    <div class="modal">

        <div class="modal__header">
            <div>
                <div class="modal__title" id="modal-compte-title">
                    <?= $account ? 'Modifier le compte' : 'Nouveau compte' ?>
                </div>
                <div class="modal__subtitle" id="modal-compte-subtitle">
                    <?= $account ? 'Mettez à jour les informations' : 'Renseignez les informations du compte' ?>
                </div>
            </div>
            <button class="modal__close" data-modal-close aria-label="Fermer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="modal-compte-form"
              method="POST"
              action="<?= $account ? '/accounts/edit?id=' . $account['id'] : '/accounts/create' ?>">

            <div class="modal__body">

                <?php if (!empty($errors)): ?>
                    <?php foreach ($errors as $err): ?>
                        <div class="alert alert--error"><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-label" for="compte-nom">Nom du compte *</label>
                    <input class="form-control" type="text" name="nom" id="compte-nom" required
                           placeholder="Ex : Compte principal"
                           value="<?= htmlspecialchars($account['nom'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="compte-description">Description</label>
                    <textarea class="form-control" name="description" id="compte-description"
                              placeholder="Description optionnelle..."><?= htmlspecialchars($account['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="compte-type">Type de compte *</label>
                    <select class="form-control" name="type" id="compte-type" required>
                        <option value="compte_courant" <?= ($account['type'] ?? '') === 'compte_courant' ? 'selected' : '' ?>>
                            Compte courant
                        </option>
                        <option value="livret_a" <?= ($account['type'] ?? '') === 'livret_a' ? 'selected' : '' ?>>
                            Livret A
                        </option>
                        <option value="autre" <?= ($account['is_autre'] ?? false) ? 'selected' : '' ?>>
                            Autre
                        </option>
                    </select>
                </div>

                <div class="form-group" id="groupe-type-autre">
                    <label class="form-label" for="compte-type-autre">Précisez le type *</label>
                    <input class="form-control" type="text" name="type_autre" id="compte-type-autre"
                           placeholder="Ex : PEA, PEL, Assurance vie..."
                           value="<?= htmlspecialchars($account['type_autre'] ?? '') ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="compte-taux-remun">Taux de rémunération (%)</label>
                        <input class="form-control" type="number" step="0.01" name="taux_remuneration"
                               id="compte-taux-remun" placeholder="0.00" min="0" max="100"
                               value="<?= htmlspecialchars($account['taux_remuneration'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="compte-taux-impos">Taux d'imposition (%)</label>
                        <input class="form-control" type="number" step="0.01" name="taux_imposition"
                               id="compte-taux-impos" placeholder="0.00" min="0" max="100"
                               value="<?= htmlspecialchars($account['taux_imposition'] ?? '') ?>">
                    </div>
                </div>

            </div>

            <div class="modal__footer">
                <button type="button" class="button button--ghost" data-modal-close>Annuler</button>
                <button type="submit" class="button button--primary" id="modal-compte-submit">
                    <?= $account ? 'Enregistrer' : 'Créer le compte' ?>
                </button>
            </div>

        </form>
    </div>
</dialog>

<script>
    (function () {
        var form        = document.getElementById('modal-compte-form');
        var title       = document.getElementById('modal-compte-title');
        var subtitle    = document.getElementById('modal-compte-subtitle');
        var submit      = document.getElementById('modal-compte-submit');
        var selectType  = document.getElementById('compte-type');
        var groupAutre  = document.getElementById('groupe-type-autre');
        var inputAutre  = document.getElementById('compte-type-autre');

        var fields = {
            nom:              document.getElementById('compte-nom'),
            description:      document.getElementById('compte-description'),
            type:             selectType,
            typeAutre:        inputAutre,
            tauxRemuneration: document.getElementById('compte-taux-remun'),
            tauxImposition:   document.getElementById('compte-taux-impos'),
        };

        // Affiche/masque le champ "type autre" et gère le required
        function toggleAutre(isAutre) {
            groupAutre.style.display = isAutre ? '' : 'none';
            inputAutre.required      = isAutre;
        }

        // Initialisation immédiate au chargement comme dans depense.php
        toggleAutre(selectType.value === 'autre');

        // Réaction au changement du select
        selectType.addEventListener('change', function () {
            toggleAutre(this.value === 'autre');
        });

        function resetToCreate() {
            form.action = '/accounts/create';
            title.textContent    = 'Nouveau compte';
            subtitle.textContent = 'Renseignez les informations du compte';
            submit.textContent   = 'Créer le compte';
            fields.nom.value              = '';
            fields.description.value      = '';
            fields.type.value             = 'compte_courant';
            fields.typeAutre.value        = '';
            fields.tauxRemuneration.value = '';
            fields.tauxImposition.value   = '';
            toggleAutre(false);
        }

        function fillForEdit(data) {
            form.action = '/accounts/edit?id=' + data.id;
            title.textContent    = 'Modifier le compte';
            subtitle.textContent = 'Mettez à jour les informations';
            submit.textContent   = 'Enregistrer';
            fields.nom.value              = data.nom || '';
            fields.description.value      = data.description || '';
            fields.tauxRemuneration.value = data.tauxRemuneration || '';
            fields.tauxImposition.value   = data.tauxImposition || '';

            if (data.isAutre === '1') {
                fields.type.value      = 'autre';
                // data.type contient directement la valeur saisie (ex: "PEA")
                fields.typeAutre.value = data.type || '';
                toggleAutre(true);
            } else {
                fields.type.value      = data.type;
                fields.typeAutre.value = '';
                toggleAutre(false);
            }
        }

        // Bouton "Nouveau compte"
        document.querySelectorAll('[data-modal-open="modal-compte"][data-modal-mode="create"]').forEach(function (btn) {
            btn.addEventListener('click', resetToCreate);
        });

        // Boutons "Modifier"
        document.querySelectorAll('[data-modal-open="modal-compte"][data-modal-mode="edit"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                fillForEdit({
                    id:               btn.dataset.id,
                    nom:              btn.dataset.nom,
                    description:      btn.dataset.description,
                    type:             btn.dataset.type,
                    isAutre:          btn.dataset.isAutre,
                    typeAutre:        btn.dataset.typeAutre,
                    tauxRemuneration: btn.dataset.tauxRemuneration,
                    tauxImposition:   btn.dataset.tauxImposition,
                });
            });
        });

    }());
</script>

