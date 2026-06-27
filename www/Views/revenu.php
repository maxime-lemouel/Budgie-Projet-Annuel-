<?php
$revenus  = isset($revenus)  ? json_decode($revenus,  true) : [];
$errors   = json_decode($errors ?? '[]', true);
$accounts = isset($accounts) ? json_decode($accounts, true) : [];
$revenu   = isset($revenu)   ? json_decode($revenu,   true) : null;

$autoOpen = $revenu !== null || !empty($errors);
?>

<div class="page-heading">
    <div class="page-heading__text">
        <h1 class="page-heading__title">Revenus</h1>
        <p class="page-heading__subtitle">Suivez et gérez vos revenus récurrents</p>
    </div>
    <button class="button button--primary" data-modal-open="modal-revenu" data-modal-mode="create">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Nouveau revenu
    </button>
</div>

<div class="card card--table">

    <?php if (empty($revenus)): ?>

        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5V4.5m0 0l-5 5m5-5l5 5"/>
            </svg>
            <div class="empty-state__title">Aucun revenu</div>
            <div class="empty-state__desc">Commencez par créer votre premier revenu.</div>
            <button class="button button--primary" data-modal-open="modal-revenu" data-modal-mode="create" style="margin-top:8px;">
                Créer un revenu
            </button>
        </div>

    <?php else: ?>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th>Compte</th>
                    <th>Nom</th>
                    <th class="col--nowrap">Montant</th>
                    <th>Fréquence</th>
                    <th class="col--nowrap">Date début</th>
                    <th class="col--nowrap">Date fin</th>
                    <th class="col--shrink">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($revenus as $rev): ?>
                    <tr>
                        <td><?= htmlspecialchars($rev['compte_nom'] ?? '') ?></td>
                        <td style="font-weight:500;"><?= htmlspecialchars($rev['nom'] ?? '') ?></td>
                        <td class="col--nowrap">
                            <strong><?= number_format((float)$rev['montant'], 2) ?> €</strong>
                        </td>
                        <td>
                            <?php if ($rev['ponctuelle']): ?>
                                <span class="badge badge--grey">Ponctuelle</span>
                            <?php else: ?>
                                <span class="badge badge--blue">/ <?= (int)$rev['iteration'] ?> mois</span>
                            <?php endif; ?>
                        </td>
                        <td class="col--nowrap"><?= htmlspecialchars(substr($rev['date_debut'] ?? '', 0, 10)) ?></td>
                        <td class="col--nowrap"><?= $rev['date_fin'] ? htmlspecialchars(substr($rev['date_fin'], 0, 10)) : '—' ?></td>
                        <td class="col--shrink" style="white-space:nowrap;">
                            <button class="button button--ghost button--sm"
                                    data-modal-open="modal-revenu"
                                    data-modal-mode="edit"
                                    data-id="<?= $rev['id'] ?>"
                                    data-nom="<?= htmlspecialchars($rev['nom'] ?? '', ENT_QUOTES) ?>"
                                    data-description="<?= htmlspecialchars($rev['description'] ?? '', ENT_QUOTES) ?>"
                                    data-compte-id="<?= $rev['compte_id'] ?>"
                                    data-montant="<?= htmlspecialchars($rev['montant'] ?? '', ENT_QUOTES) ?>"
                                    data-frequence="<?= $rev['ponctuelle'] ? 'ponctuelle' : 'mois' ?>"
                                    data-iteration="<?= htmlspecialchars($rev['iteration'] ?? '', ENT_QUOTES) ?>"
                                    data-date-debut="<?= htmlspecialchars(substr($rev['date_debut'] ?? '', 0, 10), ENT_QUOTES) ?>"
                                    data-date-fin="<?= htmlspecialchars(substr($rev['date_fin'] ?? '', 0, 10), ENT_QUOTES) ?>">
                                Modifier
                            </button>
                            <form method="POST" action="/revenues/delete?id=<?= $rev['id'] ?>"
                                  onsubmit="return confirm('Supprimer ce revenu ?')">
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


<!-- ── Modal création / modification ──────────────────────── -->
<dialog id="modal-revenu" <?= $autoOpen ? 'data-modal-auto' : '' ?>>
    <div class="modal">

        <div class="modal__header">
            <div>
                <div class="modal__title" id="modal-revenu-title">
                    <?= $revenu ? 'Modifier le revenu' : 'Nouveau revenu' ?>
                </div>
                <div class="modal__subtitle" id="modal-revenu-subtitle">
                    <?= $revenu ? 'Mettez à jour les informations' : 'Renseignez les informations du revenu' ?>
                </div>
            </div>
            <button class="modal__close" data-modal-close aria-label="Fermer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="modal-revenu-form"
              method="POST"
              action="<?= $revenu ? '/revenues/edit?id=' . $revenu['id'] : '/revenues/create' ?>">

            <div class="modal__body">

                <?php if (!empty($errors)): ?>
                    <?php foreach ($errors as $err): ?>
                        <div class="alert alert--error"><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="rev-nom">Nom court *</label>
                        <input class="form-control" type="text" name="nom" id="rev-nom"
                               placeholder="Ex : Salaire"
                               value="<?= htmlspecialchars($revenu['nom'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="rev-montant">Montant (€) *</label>
                        <input class="form-control" type="number" step="0.01" min="0.01"
                               name="montant" id="rev-montant"
                               value="<?= htmlspecialchars($revenu['montant'] ?? '') ?>"
                               placeholder="0.00" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="rev-description">Description</label>
                    <textarea class="form-control" name="description" id="rev-description"
                              placeholder="Description optionnelle..."><?= htmlspecialchars($revenu['description'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="rev-compte-id">Compte *</label>
                        <select class="form-control" name="compte_id" id="rev-compte-id" required>
                            <option value="">Sélectionner un compte</option>
                            <?php foreach ($accounts as $account): ?>
                                <option value="<?= $account['id'] ?>"
                                        <?= isset($revenu['compte_id']) && (int)$revenu['compte_id'] === (int)$account['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($account['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="rev-frequence">Fréquence *</label>
                        <select class="form-control" name="frequence" id="rev-frequence">
                            <option value="mois"       <?= isset($revenu['ponctuelle']) && !$revenu['ponctuelle'] ? 'selected' : '' ?>>Tous les N jour</option>
                            <option value="ponctuelle" <?= isset($revenu['ponctuelle']) &&  $revenu['ponctuelle'] ? 'selected' : '' ?>>Ponctuelle</option>
                        </select>
                    </div>
                </div>

                <div id="rev-iteration-block">
                    <div class="form-group">
                        <label class="form-label" for="rev-iteration">Tous les N jour</label>
                        <input class="form-control" type="number" name="iteration" id="rev-iteration"
                               min="1" value="<?= htmlspecialchars((string)($revenu['iteration'] ?? '1')) ?>"
                               style="max-width:120px;">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="rev-date-debut">Date de début *</label>
                        <input class="form-control" type="date" name="date_debut" id="rev-date-debut"
                               value="<?= htmlspecialchars(substr($revenu['date_debut'] ?? date('Y-m-d'), 0, 10)) ?>" required>
                    </div>
                    <div class="form-group" id="rev-date-fin-group">
                        <label class="form-label" for="rev-date-fin">Date de fin</label>
                        <input class="form-control" type="date" name="date_fin" id="rev-date-fin"
                               value="<?= htmlspecialchars(substr($revenu['date_fin'] ?? '', 0, 10)) ?>">
                        <p class="modal__subtitle">Sans date de fin, le revenu sera répété indéfiniment.</p>
                    </div>
                </div>

            </div>

            <div class="modal__footer">
                <button type="button" class="button button--ghost" data-modal-close>Annuler</button>
                <button type="submit" class="button button--primary" id="modal-revenu-submit">
                    <?= $revenu ? 'Enregistrer' : 'Créer le revenu' ?>
                </button>
            </div>

        </form>
    </div>
</dialog>

<script>
    (function () {
        var form     = document.getElementById('modal-revenu-form');
        var title    = document.getElementById('modal-revenu-title');
        var subtitle = document.getElementById('modal-revenu-subtitle');
        var submit   = document.getElementById('modal-revenu-submit');

        var fields = {
            nom:         document.getElementById('rev-nom'),
            description: document.getElementById('rev-description'),
            compteId:    document.getElementById('rev-compte-id'),
            montant:     document.getElementById('rev-montant'),
            frequence:   document.getElementById('rev-frequence'),
            iteration:   document.getElementById('rev-iteration'),
            dateDebut:   document.getElementById('rev-date-debut'),
            dateFin:     document.getElementById('rev-date-fin'),
        };

        var iterBlock  = document.getElementById('rev-iteration-block');
        var dateFinGrp = document.getElementById('rev-date-fin-group');

        function toggleFrequence() {
            var isMois = fields.frequence.value === 'mois';

            // Bloc "N jour" : visible uniquement en mode récurrent
            iterBlock.style.display = isMois ? '' : 'none';
            isMois ? fields.iteration.setAttribute('required', '') : fields.iteration.removeAttribute('required');

            // Bloc "date de fin" : masqué en mode ponctuel
            dateFinGrp.style.display = isMois ? '' : 'none';
            if (!isMois) {
                fields.dateFin.value = '';
            }
        }

        fields.frequence.addEventListener('change', toggleFrequence);
        toggleFrequence();

        function resetToCreate() {
            form.action          = '/revenues/create';
            title.textContent    = 'Nouveau revenu';
            subtitle.textContent = 'Renseignez les informations du revenu';
            submit.textContent   = 'Créer le revenu';
            fields.nom.value         = '';
            fields.description.value = '';
            fields.compteId.value    = '';
            fields.montant.value     = '';
            fields.frequence.value   = 'mois';
            fields.iteration.value   = '1';
            fields.dateDebut.value   = '';
            fields.dateFin.value     = '';
            toggleFrequence();
        }

        function fillForEdit(data) {
            form.action          = '/revenues/edit?id=' + data.id;
            title.textContent    = 'Modifier le revenu';
            subtitle.textContent = 'Mettez à jour les informations';
            submit.textContent   = 'Enregistrer';
            fields.nom.value         = data.nom         || '';
            fields.description.value = data.description || '';
            fields.compteId.value    = data.compteId    || '';
            fields.montant.value     = data.montant      || '';
            fields.frequence.value   = data.frequence   || 'mois';
            fields.iteration.value   = data.iteration   || '1';
            fields.dateDebut.value   = data.dateDebut   || '';
            fields.dateFin.value     = data.dateFin     || '';
            toggleFrequence();
        }

        document.querySelectorAll('[data-modal-open="modal-revenu"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (btn.dataset.modalMode === 'edit') {
                    fillForEdit({
                        id:          btn.dataset.id,
                        nom:         btn.dataset.nom,
                        description: btn.dataset.description,
                        compteId:    btn.dataset.compteId,
                        montant:     btn.dataset.montant,
                        frequence:   btn.dataset.frequence,
                        iteration:   btn.dataset.iteration,
                        dateDebut:   btn.dataset.dateDebut,
                        dateFin:     btn.dataset.dateFin,
                    });
                } else {
                    <?php if (!$revenu && empty($errors)): ?>
                    resetToCreate();
                    <?php endif; ?>
                }
            });
        });

    }());
</script>