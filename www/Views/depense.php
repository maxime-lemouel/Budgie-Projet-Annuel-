<?php
$depenses = isset($depenses) ? json_decode($depenses, true) : [];
$errors   = json_decode($errors ?? '[]', true);
$accounts = isset($accounts) ? json_decode($accounts, true) : [];
$depense  = isset($depense)  ? json_decode($depense,  true) : null;

$autoOpen = $depense !== null || !empty($errors);
?>

<div class="page-heading">
    <div class="page-heading__text">
        <h1 class="page-heading__title">Dépenses</h1>
        <p class="page-heading__subtitle">Suivez et gérez vos dépenses récurrentes</p>
    </div>
    <button class="button button--primary" data-modal-open="modal-depense" data-modal-mode="create">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Nouvelle dépense
    </button>
</div>

<div class="card card--table">

    <?php if (empty($depenses)): ?>

        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
            </svg>
            <div class="empty-state__title">Aucune dépense</div>
            <div class="empty-state__desc">Commencez par créer votre première dépense.</div>
            <button class="button button--primary" data-modal-open="modal-depense" data-modal-mode="create" style="margin-top:8px;">
                Créer une dépense
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
                <?php foreach ($depenses as $dep): ?>
                    <tr>
                        <td><?= htmlspecialchars($dep['compte_nom'] ?? '') ?></td>
                        <td style="font-weight:500;"><?= htmlspecialchars($dep['nom'] ?? '') ?></td>
                        <td class="col--nowrap">
                            <strong><?= number_format((float)$dep['montant'], 2) ?> €</strong>
                        </td>
                        <td>
                            <?php if ($dep['ponctuelle']): ?>
                                <span class="badge badge--grey">Ponctuelle</span>
                            <?php else: ?>
                                <span class="badge badge--blue">/ <?= (int)$dep['iteration'] ?> mois</span>
                            <?php endif; ?>
                        </td>
                        <td class="col--nowrap"><?= htmlspecialchars(substr($dep['date_debut'] ?? '', 0, 10)) ?></td>
                        <td class="col--nowrap"><?= $dep['date_fin'] ? htmlspecialchars(substr($dep['date_fin'], 0, 10)) : '—' ?></td>
                        <td class="col--shrink" style="white-space:nowrap;">
                            <button class="button button--ghost button--sm"
                                    data-modal-open="modal-depense"
                                    data-modal-mode="edit"
                                    data-id="<?= $dep['id'] ?>"
                                    data-nom="<?= htmlspecialchars($dep['nom'] ?? '', ENT_QUOTES) ?>"
                                    data-description="<?= htmlspecialchars($dep['description'] ?? '', ENT_QUOTES) ?>"
                                    data-compte-id="<?= $dep['compte_id'] ?>"
                                    data-montant="<?= htmlspecialchars($dep['montant'] ?? '', ENT_QUOTES) ?>"
                                    data-frequence="<?= $dep['ponctuelle'] ? 'ponctuelle' : 'mois' ?>"
                                    data-iteration="<?= htmlspecialchars($dep['iteration'] ?? '', ENT_QUOTES) ?>"
                                    data-date-debut="<?= htmlspecialchars(substr($dep['date_debut'] ?? '', 0, 10), ENT_QUOTES) ?>"
                                    data-date-fin="<?= htmlspecialchars(substr($dep['date_fin'] ?? '', 0, 10), ENT_QUOTES) ?>">
                                Modifier
                            </button>
                            <form method="POST" action="/expenses/delete?id=<?= $dep['id'] ?>"
                                  onsubmit="return confirm('Supprimer cette dépense ?')">
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
<dialog id="modal-depense" <?= $autoOpen ? 'data-modal-auto' : '' ?>>
    <div class="modal">

        <div class="modal__header">
            <div>
                <div class="modal__title" id="modal-depense-title">
                    <?= $depense ? 'Modifier la dépense' : 'Nouvelle dépense' ?>
                </div>
                <div class="modal__subtitle" id="modal-depense-subtitle">
                    <?= $depense ? 'Mettez à jour les informations' : 'Renseignez les informations de la dépense' ?>
                </div>
            </div>
            <button class="modal__close" data-modal-close aria-label="Fermer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="modal-depense-form"
              method="POST"
              action="<?= $depense ? '/expenses/edit?id=' . $depense['id'] : '/expenses/create' ?>">

            <div class="modal__body">

                <?php if (!empty($errors)): ?>
                    <?php foreach ($errors as $err): ?>
                        <div class="alert alert--error"><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="dep-nom">Nom court *</label>
                        <input class="form-control" type="text" name="nom" id="dep-nom"
                               placeholder="Ex : Crédit Auto"
                               value="<?= htmlspecialchars($depense['nom'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="dep-montant">Montant (€) *</label>
                        <input class="form-control" type="number" step="0.01" min="0.01"
                               name="montant" id="dep-montant"
                               value="<?= htmlspecialchars($depense['montant'] ?? '') ?>"
                               placeholder="0.00" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="dep-description">Description</label>
                    <textarea class="form-control" name="description" id="dep-description"
                              placeholder="Description optionnelle..."><?= htmlspecialchars($depense['description'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="dep-compte-id">Compte *</label>
                        <select class="form-control" name="compte_id" id="dep-compte-id" required>
                            <option value="">Sélectionner un compte</option>
                            <?php foreach ($accounts as $account): ?>
                                <option value="<?= $account['id'] ?>"
                                        <?= isset($depense['compte_id']) && (int)$depense['compte_id'] === (int)$account['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($account['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="dep-frequence">Fréquence *</label>
                        <select class="form-control" name="frequence" id="dep-frequence">
                            <option value="mois"       <?= isset($depense['ponctuelle']) && !$depense['ponctuelle'] ? 'selected' : '' ?>>Tous les N mois</option>
                            <option value="ponctuelle" <?= isset($depense['ponctuelle']) &&  $depense['ponctuelle'] ? 'selected' : '' ?>>Ponctuelle</option>
                        </select>
                    </div>
                </div>

                <div id="dep-iteration-block">
                    <div class="form-group">
                        <label class="form-label" for="dep-iteration">Tous les N mois</label>
                        <input class="form-control" type="number" name="iteration" id="dep-iteration"
                               min="1" value="<?= htmlspecialchars((string)($depense['iteration'] ?? '1')) ?>"
                               style="max-width:120px;">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="dep-date-debut">Date de début *</label>
                        <input class="form-control" type="date" name="date_debut" id="dep-date-debut"
                               value="<?= htmlspecialchars(substr($depense['date_debut'] ?? date('Y-m-d'), 0, 10)) ?>" required>
                    </div>
                    <div class="form-group" id="dep-date-fin-group">
                        <label class="form-label" for="dep-date-fin">Date de fin</label>
                        <input class="form-control" type="date" name="date_fin" id="dep-date-fin"
                               value="<?= htmlspecialchars(substr($depense['date_fin'] ?? '', 0, 10)) ?>">
                        <p class="modal__subtitle">Sans date de fin, la dépense sera répétée indéfiniment.</p>
                    </div>
                </div>

            </div>

            <div class="modal__footer">
                <button type="button" class="button button--ghost" data-modal-close>Annuler</button>
                <button type="submit" class="button button--primary" id="modal-depense-submit">
                    <?= $depense ? 'Enregistrer' : 'Créer la dépense' ?>
                </button>
            </div>

        </form>
    </div>
</dialog>

<script>
    (function () {
        var form     = document.getElementById('modal-depense-form');
        var title    = document.getElementById('modal-depense-title');
        var subtitle = document.getElementById('modal-depense-subtitle');
        var submit   = document.getElementById('modal-depense-submit');

        var fields = {
            nom:         document.getElementById('dep-nom'),
            description: document.getElementById('dep-description'),
            compteId:    document.getElementById('dep-compte-id'),
            montant:     document.getElementById('dep-montant'),
            frequence:   document.getElementById('dep-frequence'),
            iteration:   document.getElementById('dep-iteration'),
            dateDebut:   document.getElementById('dep-date-debut'),
            dateFin:     document.getElementById('dep-date-fin'),
        };

        var iterBlock   = document.getElementById('dep-iteration-block');
        var dateFinGrp  = document.getElementById('dep-date-fin-group');


        function syncMinDate() {
            fields.dateFin.min = fields.dateDebut.value;
            if (fields.dateFin.value && fields.dateFin.value < fields.dateDebut.value) {
                fields.dateFin.value = '';
            }
        }
        fields.dateDebut.addEventListener('change', syncMinDate);
        syncMinDate();

        // Masque/affiche les blocs selon la fréquence choisie
        function toggleFrequence() {
            var isMois = fields.frequence.value === 'mois';

            // Bloc "N mois" : visible uniquement en mode récurrent
            iterBlock.style.display = isMois ? '' : 'none';
            isMois ? fields.iteration.setAttribute('required', '') : fields.iteration.removeAttribute('required');

            // Bloc "date de fin" : masqué en mode ponctuel (n'a aucun sens)
            dateFinGrp.style.display = isMois ? '' : 'none';
            if (!isMois) {
                fields.dateFin.value = ''; // on vide pour ne pas envoyer de valeur parasite
            }
        }

        fields.frequence.addEventListener('change', toggleFrequence);
        toggleFrequence();

        function resetToCreate() {
            form.action          = '/expenses/create';
            title.textContent    = 'Nouvelle dépense';
            subtitle.textContent = 'Renseignez les informations de la dépense';
            submit.textContent   = 'Créer la dépense';
            fields.nom.value         = '';
            fields.description.value = '';
            fields.compteId.value    = '';
            fields.montant.value     = '';
            fields.frequence.value   = 'mois';
            fields.iteration.value   = '1';
            fields.dateDebut.value   = '';
            fields.dateFin.value     = '';
            toggleFrequence();
            syncMinDate();
        }

        function fillForEdit(data) {
            form.action          = '/expenses/edit?id=' + data.id;
            title.textContent    = 'Modifier la dépense';
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
            syncMinDate();
        }

        document.querySelectorAll('[data-modal-open="modal-depense"]').forEach(function (btn) {
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
                    <?php if (!$depense && empty($errors)): ?>
                    resetToCreate();
                    <?php endif; ?>
                }
            });
        });

    }());
</script>